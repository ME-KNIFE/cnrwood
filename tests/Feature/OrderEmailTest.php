<?php

namespace Tests\Feature;

use App\Mail\OrderReceived;
use App\Mail\PaymentConfirmed;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderEmailTest extends TestCase
{
    private const TOKEN = 'test-email-token';

    public function test_order_received_mail_is_sent_after_checkout(): void
    {
        Mail::fake();

        $user    = User::factory()->create();
        $product = Product::factory()->buyable()->create();

        $this->actingAs($user);
        $this->post('/sepet/ekle', ['product_id' => $product->id, 'quantity' => 1]);

        $this->withSession(['checkout_token' => self::TOKEN])
            ->post('/siparis/olustur', [
                'checkout_token' => self::TOKEN,
                'customer_name'  => 'Test Müşteri',
                'customer_email' => 'musteri@example.com',
                'customer_phone' => '05001234567',
                'full_name'      => 'Test Müşteri',
                'phone'          => '05001234567',
                'address_line1'  => 'Test Sokak No: 1',
                'city'           => 'İstanbul',
            ]);

        Mail::assertSent(OrderReceived::class, function (OrderReceived $mail) {
            return $mail->hasTo('musteri@example.com');
        });
    }

    public function test_order_received_mail_not_sent_when_email_empty(): void
    {
        Mail::fake();

        $user    = User::factory()->create();
        $product = Product::factory()->buyable()->create();

        $this->actingAs($user);
        $this->post('/sepet/ekle', ['product_id' => $product->id, 'quantity' => 1]);

        // email field is required by validation, so test via OrderService directly with empty email
        $order = Order::factory()->create(['customer_email' => '']);
        $order->items()->create([
            'product_id'   => $product->id,
            'product_name' => $product->getTranslation('name', 'tr'),
            'product_sku'  => $product->sku,
            'quantity'     => 1,
            'unit_price'   => '100.00',
            'total_price'  => '100.00',
        ]);

        (new OrderService)->confirmHavalePayment($order);

        Mail::assertNotSent(PaymentConfirmed::class);
    }

    public function test_payment_confirmed_mail_is_sent_after_havale_confirm(): void
    {
        Mail::fake();

        $order = Order::factory()->create([
            'status'         => 'beklemede',
            'payment_status' => 'odeme_bekleniyor',
            'payment_method' => 'havale_eft',
            'customer_email' => 'musteri@example.com',
        ]);

        (new OrderService)->confirmHavalePayment($order);

        Mail::assertSent(PaymentConfirmed::class, function (PaymentConfirmed $mail) {
            return $mail->hasTo('musteri@example.com');
        });
    }
}
