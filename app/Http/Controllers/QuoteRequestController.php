<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\QuoteRequest;
use App\Models\QuoteRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Phase 7B — Public quote request controller.
 *
 * Strict scope:
 *   - Reads existing tables only (quote_requests, quote_request_items).
 *   - NO email sending, NO PDF, NO file upload, NO customer login.
 *   - Product ID is taken ONLY from the route slug — never trusted from user input.
 *   - All status/type values constrained to the existing enum.
 */
class QuoteRequestController extends Controller
{
    // ── GET /teklif-al ───────────────────────────────────────────────────────
    public function createGeneral()
    {
        return view('public.quote-create', [
            'product' => null,
        ]);
    }

    // ── POST /teklif-al ──────────────────────────────────────────────────────
    public function storeGeneral(Request $request)
    {
        $data = $this->validateInput($request, isProduct: false);

        $quote = $this->persistQuote(
            data:    $data,
            type:    'general',
            product: null,
            qty:     null,
        );

        return redirect()
            ->route('public.quote.thanks', ['ref' => $quote->reference_number]);
    }

    // ── GET /urun/{slug}/teklif-al ───────────────────────────────────────────
    public function createForProduct(string $slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['category', 'images'])
            ->firstOrFail();

        return view('public.quote-create', [
            'product' => $product,
        ]);
    }

    // ── POST /urun/{slug}/teklif-al ──────────────────────────────────────────
    public function storeForProduct(Request $request, string $slug)
    {
        // Product source of truth: the URL slug. We NEVER read product_id from input.
        $product = Product::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $this->validateInput($request, isProduct: true);

        $quote = $this->persistQuote(
            data:    $data,
            type:    'product',
            product: $product,
            qty:     (int) ($data['quantity'] ?? 1),
        );

        return redirect()
            ->route('public.quote.thanks', ['ref' => $quote->reference_number]);
    }

    // ── GET /teklif-al/tesekkurler?ref=... ───────────────────────────────────
    public function thankYou(Request $request)
    {
        $ref = (string) $request->query('ref', '');

        // Display the reference only if it's safely formatted.
        // We do NOT look up the row — avoids leaking existence info & extra DB hit.
        $safeRef = preg_match('/^TKL-\d{4}-\d{1,8}$/', $ref) ? $ref : null;

        return view('public.quote-thank-you', [
            'reference' => $safeRef,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Internals
    // ─────────────────────────────────────────────────────────────────────────

    /** @return array<string,mixed> */
    private function validateInput(Request $request, bool $isProduct): array
    {
        $rules = [
            'contact_name'      => ['required', 'string', 'max:120'],
            'contact_email'     => ['required', 'string', 'email:rfc', 'max:160'],
            'contact_phone'     => ['required', 'string', 'max:20'],
            'company_name'      => ['nullable', 'string', 'max:160'],
            'tax_number'        => ['nullable', 'string', 'max:20'],
            'preferred_contact' => ['nullable', Rule::in(['phone', 'email', 'whatsapp'])],
            'message'           => ['nullable', 'string', 'max:4000'],
            // Honeypot — must be empty. Spam bots fill every input.
            'website'           => ['nullable', 'size:0'],
        ];

        if ($isProduct) {
            $rules['quantity'] = ['nullable', 'integer', 'min:1', 'max:999999'];
        }

        return $request->validate($rules, [
            'contact_email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'website.size'        => 'Spam kontrolü başarısız.',
        ]);
    }

    private function persistQuote(array $data, string $type, ?Product $product, ?int $qty): QuoteRequest
    {
        return DB::transaction(function () use ($data, $type, $product, $qty) {
            // Retry once on the (extremely unlikely) reference collision.
            $quote = null;
            for ($attempt = 0; $attempt < 3; $attempt++) {
                try {
                    $quote = QuoteRequest::create([
                        'reference_number'  => QuoteRequest::generateReference(),
                        'type'              => $type,
                        'status'            => 'yeni',
                        'contact_name'      => $data['contact_name'],
                        'contact_email'     => $data['contact_email'],
                        'contact_phone'     => $data['contact_phone'],
                        'company_name'      => $data['company_name']      ?? null,
                        'tax_number'        => $data['tax_number']        ?? null,
                        'preferred_contact' => $data['preferred_contact'] ?? 'email',
                        'message'           => $data['message']           ?? null,
                    ]);
                    break;
                } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                    if ($attempt === 2) {
                        throw $e;
                    }
                }
            }

            if ($product !== null && $quote !== null) {
                QuoteRequestItem::create([
                    'quote_request_id' => $quote->id,
                    'product_id'       => $product->id,
                    'product_name'     => $product->getTranslation('name', app()->getLocale()) ?? ('Ürün #' . $product->id),
                    'quantity'         => $qty,
                    'notes'            => null,
                ]);
            }

            return $quote;
        });
    }
}
