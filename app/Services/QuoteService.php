<?php

namespace App\Services;

use App\Models\QuoteRequest;
use App\Models\SandikCalculationRequest;
use Illuminate\Support\Facades\DB;

class QuoteService
{
    /**
     * Create a product quote request (from product detail page "Teklif Al" button).
     */
    public function createProductQuote(array $data): QuoteRequest
    {
        return DB::transaction(function () use ($data) {
            $quote = QuoteRequest::create([
                'reference_number'   => QuoteRequest::generateReference(),
                'type'               => 'product',
                'status'             => 'yeni',
                'user_id'            => $data['user_id'] ?? null,
                'contact_name'       => $data['contact_name'],
                'contact_email'      => $data['contact_email'],
                'contact_phone'      => $data['contact_phone'] ?? null,
                'company_name'       => $data['company_name'] ?? null,
                'tax_number'         => $data['tax_number'] ?? null,
                'preferred_contact'  => $data['preferred_contact'] ?? 'email',
                'message'            => $data['message'] ?? null,
            ]);

            // Attach product items
            if (! empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    $quote->items()->create([
                        'product_id'   => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'quantity'     => $item['quantity'] ?? null,
                        'notes'        => $item['notes'] ?? null,
                    ]);
                }
            }

            return $quote;
        });
    }

    /**
     * Create a Sandık Hesaplama quote (6-step form → SandikCalculationRequest + QuoteRequest).
     */
    public function createSandikQuote(array $data): QuoteRequest
    {
        return DB::transaction(function () use ($data) {
            $quote = QuoteRequest::create([
                'reference_number'  => QuoteRequest::generateReference(),
                'type'              => 'sandik',
                'status'            => 'yeni',
                'user_id'           => $data['user_id'] ?? null,
                'contact_name'      => $data['contact_name'],
                'contact_email'     => $data['contact_email'],
                'contact_phone'     => $data['contact_phone'] ?? null,
                'company_name'      => $data['company_name'] ?? null,
                'preferred_contact' => $data['preferred_contact'] ?? 'email',
                'message'           => $data['notes'] ?? null,
                'file_path'         => $data['file_path'] ?? null,
                'file_name'         => $data['file_name'] ?? null,
            ]);

            // 1:1 sandik calculation data
            SandikCalculationRequest::create([
                'quote_request_id'    => $quote->id,
                'length_cm'           => $data['length_cm'],
                'width_cm'            => $data['width_cm'],
                'height_cm'           => $data['height_cm'],
                'weight_kg'           => $data['weight_kg'],
                'crate_type'          => $data['crate_type'],
                'requires_ispm15'     => $data['requires_ispm15'] ?? false,
                'requires_forklift'   => $data['requires_forklift'] ?? false,
                'requires_crane'      => $data['requires_crane'] ?? false,
                'shipping_type'       => $data['shipping_type'] ?? 'ihracat',
                'material'            => $data['material'] ?? null,
                'quantity'            => $data['quantity'],
                'destination_city'    => $data['destination_city'] ?? null,
                'destination_country' => $data['destination_country'] ?? 'Türkiye',
                'notes'               => $data['notes'] ?? null,
            ]);

            return $quote;
        });
    }

    /**
     * Create a general / project quote request.
     */
    public function createGeneralQuote(array $data, string $type = 'general'): QuoteRequest
    {
        return QuoteRequest::create([
            'reference_number'  => QuoteRequest::generateReference(),
            'type'              => $type,
            'status'            => 'yeni',
            'user_id'           => $data['user_id'] ?? null,
            'contact_name'      => $data['contact_name'],
            'contact_email'     => $data['contact_email'],
            'contact_phone'     => $data['contact_phone'] ?? null,
            'company_name'      => $data['company_name'] ?? null,
            'preferred_contact' => $data['preferred_contact'] ?? 'email',
            'message'           => $data['message'] ?? null,
            'file_path'         => $data['file_path'] ?? null,
            'file_name'         => $data['file_name'] ?? null,
        ]);
    }

    /**
     * Assign a quote to a sales team member.
     */
    public function assignQuote(QuoteRequest $quote, int $adminUserId): void
    {
        $quote->update([
            'assigned_to' => $adminUserId,
            'status'      => 'inceleniyor',
        ]);
    }

    /**
     * Mark a quote as won or lost.
     */
    public function closeQuote(QuoteRequest $quote, bool $won): void
    {
        $quote->update([
            'status' => $won ? 'kazanildi' : 'kaybedildi',
        ]);
    }
}
