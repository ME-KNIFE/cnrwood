<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Iyzipay\Model\Address as IyzicoAddress;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Currency;
use Iyzipay\Model\Locale;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Model\PaymentChannel;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Model\ThreedsInitialize;
use Iyzipay\Model\ThreedsPayment;
use Iyzipay\Options;
use Iyzipay\Request\CreateThreedsPaymentRequest;
use Illuminate\Support\Facades\Log;

/**
 * Phase 11C — Iyzico 3DS Payment Integration.
 *
 * Flow:
 *   1. initialize3DPayment()  → returns HTML form (auto-submits to bank)
 *   2. Bank authenticates user via 3DS
 *   3. Iyzico POSTs to our callback URL with paymentId + conversationData
 *   4. confirm3DPayment()     → completes charge, returns success/failure
 *
 * Card data is NEVER stored — passed directly to Iyzico API in one request.
 */
class IyzicoService
{
    private Options $options;

    public function __construct()
    {
        $this->options = new Options();
        $this->options->setApiKey(config('iyzico.api_key'));
        $this->options->setSecretKey(config('iyzico.secret_key'));
        $this->options->setBaseUrl(config('iyzico.base_url'));
    }

    // ── Public API ────────────────────────────────────────────────────────────

    /**
     * Initialize 3DS payment. Returns the HTML content Iyzico sends back —
     * render it as a full page and it auto-submits to the bank's 3DS form.
     *
     * @param  Order  $order      The order to charge
     * @param  array  $cardData   Keys: holder, number, expire_month, expire_year, cvc
     * @return string             HTML content to render as a full page
     *
     * @throws \RuntimeException  on Iyzico API error
     */
    public function initialize3DPayment(Order $order, array $cardData): string
    {
        $request = new CreateThreedsPaymentRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId((string) $order->id);
        $request->setPrice($this->formatAmount($order->total));
        $request->setPaidPrice($this->formatAmount($order->total));
        $request->setCurrency(Currency::TL);
        $request->setInstallment(1);
        $request->setPaymentChannel(PaymentChannel::WEB);
        $request->setPaymentGroup(PaymentGroup::PRODUCT);
        $request->setCallbackUrl(route('payment.callback'));

        // Card — never stored, passed directly to Iyzico
        $paymentCard = new PaymentCard();
        $paymentCard->setCardHolderName($cardData['holder']);
        $paymentCard->setCardNumber(preg_replace('/\s+/', '', $cardData['number']));
        $paymentCard->setExpireMonth($cardData['expire_month']);
        $paymentCard->setExpireYear($cardData['expire_year']);
        $paymentCard->setCvc($cardData['cvc']);
        $paymentCard->setRegisterCard(0);
        $request->setPaymentCard($paymentCard);

        // Buyer
        $buyer = new Buyer();
        $buyer->setId((string) ($order->user_id ?? 'guest-' . $order->id));
        $buyer->setName($this->splitName($order->customer_name)[0]);
        $buyer->setSurname($this->splitName($order->customer_name)[1]);
        $buyer->setEmail($order->customer_email);
        $buyer->setIdentityNumber('11111111111'); // required by Iyzico; use real TC if collected
        $buyer->setRegistrationAddress($this->formatAddress($order->billing_address ?? $order->shipping_address));
        $buyer->setCity($order->billing_address['city'] ?? $order->shipping_address['city'] ?? 'İstanbul');
        $buyer->setCountry('Turkey');
        $buyer->setPhone($order->customer_phone ?? '+905000000000');
        $buyer->setIp(request()->ip());
        $request->setBuyer($buyer);

        // Shipping address
        $shippingAddr = new IyzicoAddress();
        $shippingAddr->setContactName($order->shipping_address['full_name'] ?? $order->customer_name);
        $shippingAddr->setCity($order->shipping_address['city'] ?? 'İstanbul');
        $shippingAddr->setCountry('Turkey');
        $shippingAddr->setAddress($this->formatAddress($order->shipping_address));
        $shippingAddr->setZipCode($order->shipping_address['postal_code'] ?? '');
        $request->setShippingAddress($shippingAddr);

        // Billing address
        $billingAddr = new IyzicoAddress();
        $billingArr = $order->billing_address ?? $order->shipping_address;
        $billingAddr->setContactName($billingArr['full_name'] ?? $order->customer_name);
        $billingAddr->setCity($billingArr['city'] ?? 'İstanbul');
        $billingAddr->setCountry('Turkey');
        $billingAddr->setAddress($this->formatAddress($billingArr));
        $billingAddr->setZipCode($billingArr['postal_code'] ?? '');
        $request->setBillingAddress($billingAddr);

        // Basket items
        $basketItems = [];
        foreach ($order->items as $item) {
            $basketItem = new BasketItem();
            $basketItem->setId((string) $item->id);
            $basketItem->setName(mb_substr($item->product_name, 0, 100));
            $basketItem->setCategory1('Ahşap Ürünleri');
            $basketItem->setItemType(BasketItemType::PHYSICAL);
            $basketItem->setPrice($this->formatAmount($item->total_price));
            $basketItems[] = $basketItem;
        }

        // Adjust for discount/shipping so basket total = paidPrice
        if ((float) $order->discount_amount > 0) {
            $discountItem = new BasketItem();
            $discountItem->setId('discount');
            $discountItem->setName('İndirim');
            $discountItem->setCategory1('İndirim');
            $discountItem->setItemType(BasketItemType::VIRTUAL);
            $discountItem->setPrice('-' . $this->formatAmount($order->discount_amount));
            $basketItems[] = $discountItem;
        }

        if ((float) $order->shipping_cost > 0) {
            $shippingItem = new BasketItem();
            $shippingItem->setId('shipping');
            $shippingItem->setName('Kargo');
            $shippingItem->setCategory1('Kargo');
            $shippingItem->setItemType(BasketItemType::VIRTUAL);
            $shippingItem->setPrice($this->formatAmount($order->shipping_cost));
            $basketItems[] = $shippingItem;
        }

        $request->setBasketItems($basketItems);

        $result = ThreedsInitialize::create($request, $this->options);

        if ($result->getStatus() !== 'success') {
            Log::error('Iyzico 3DS init failed', [
                'order_id'      => $order->id,
                'error_code'    => $result->getErrorCode(),
                'error_message' => $result->getErrorMessage(),
            ]);

            throw new \RuntimeException(
                'Ödeme başlatılamadı: ' . ($result->getErrorMessage() ?? 'Bilinmeyen hata')
            );
        }

        // htmlContent is base64 encoded
        $html = base64_decode($result->getHtmlContent());

        // Record a pending payment row for tracking
        Payment::create([
            'order_id'  => $order->id,
            'method'    => 'kredi_karti',
            'status'    => 'pending',
            'amount'    => $order->total,
            'provider'  => 'iyzico',
            'notes'     => 'Iyzico 3DS başlatıldı',
        ]);

        return $html;
    }

    /**
     * Confirm 3DS payment after bank redirect.
     * Called from PaymentController::callback() with Iyzico's POST data.
     *
     * @param  string  $paymentId          from Iyzico callback
     * @param  string  $conversationData   from Iyzico callback
     * @param  string  $conversationId     order ID passed originally
     * @return array{success: bool, providerRef: string, message: string}
     */
    public function confirm3DPayment(
        string $paymentId,
        string $conversationData,
        string $conversationId
    ): array {
        $request = new CreateThreedsPaymentRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($conversationId);
        $request->setPaymentId($paymentId);
        $request->setConversationData($conversationData);

        $result = ThreedsPayment::create($request, $this->options);

        $success = $result->getStatus() === 'success';

        if (! $success) {
            Log::warning('Iyzico 3DS confirm failed', [
                'conversation_id' => $conversationId,
                'error_code'      => $result->getErrorCode(),
                'error_message'   => $result->getErrorMessage(),
            ]);
        }

        return [
            'success'     => $success,
            'providerRef' => $result->getPaymentId() ?? $paymentId,
            'message'     => $success ? 'Ödeme başarılı.' : ($result->getErrorMessage() ?? 'Ödeme başarısız.'),
        ];
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /** Format decimal for Iyzico (always 2 decimal places as string, no thousands separator). */
    private function formatAmount(mixed $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }

    /** Split full name into [first, last]. Falls back gracefully for single-word names. */
    private function splitName(string $fullName): array
    {
        $parts = explode(' ', trim($fullName), 2);

        return [$parts[0], $parts[1] ?? $parts[0]];
    }

    /** Combine address array into a single string for Iyzico. */
    private function formatAddress(array $address): string
    {
        return trim(
            ($address['address_line1'] ?? '') . ' ' .
            ($address['address_line2'] ?? '') . ' ' .
            ($address['district'] ?? '') . ' ' .
            ($address['city'] ?? '')
        );
    }
}
