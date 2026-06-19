<?php

namespace App\Http\Controllers;

use App\Models\QuoteRequest;
use App\Models\SandikCalculationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Phase 9A/9B — Public Sandık Hesaplama form controller.
 *
 * Strict scope:
 *   - Writes to quote_requests (type='sandik') + sandik_calculation_requests only.
 *   - Optional file attachment stored on local (private) disk under sandik-attachments/.
 *   - NO email sending, NO PDF, NO customer login, NO payment.
 *   - Reuses QuoteRequest::generateReference() and the existing thank-you route.
 */
class SandikController extends Controller
{
    // Valid crate_type values (mirrors the DB enum exactly)
    private const CRATE_TYPES = [
        'ahsap',
        'osb',
        'izgara',
        'vinc_aparatli',
        'endcap',
        'taban_izgara',
        'bilmiyorum',
    ];

    // ── GET /sandik-hesaplama ─────────────────────────────────────────────────
    public function create()
    {
        return view('public.sandik');
    }

    // ── POST /sandik-hesaplama ────────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $this->validateInput($request);

        // Upload file (if present) BEFORE the DB transaction.
        // If the transaction later fails, we clean up the orphaned file.
        [$storedPath, $originalName] = $this->handleUpload($request);

        try {
            $quote = $this->persist($data, $storedPath, $originalName);
        } catch (\Throwable $e) {
            // Roll back the uploaded file so nothing is orphaned.
            if ($storedPath !== null) {
                Storage::disk('local')->delete($storedPath);
            }
            throw $e;
        }

        return redirect()->route('public.quote.thanks', ['ref' => $quote->reference_number]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Internals
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Store the uploaded attachment on the private local disk.
     * Returns [storedPath, originalName] or [null, null] if no file was uploaded.
     *
     * @return array{string|null, string|null}
     */
    private function handleUpload(Request $request): array
    {
        if (! $request->hasFile('attachment') || ! $request->file('attachment')->isValid()) {
            return [null, null];
        }

        /** @var UploadedFile $file */
        $file = $request->file('attachment');

        $ext        = strtolower($file->getClientOriginalExtension());
        $uuid       = (string) Str::uuid();
        $storedPath = $file->storeAs('sandik-attachments', $uuid . '.' . $ext, 'local');

        // storeAs returns false on failure
        if ($storedPath === false) {
            throw new \RuntimeException('Dosya yüklenirken bir hata oluştu.');
        }

        // Keep original name only for display in admin; trim to safe length.
        $originalName = mb_substr(trim($file->getClientOriginalName()), 0, 255);

        return [$storedPath, $originalName];
    }

    /** @return array<string,mixed> */
    private function validateInput(Request $request): array
    {
        return $request->validate([
            // ── Contact fields ────────────────────────────────────────────────
            'contact_name'      => ['required', 'string', 'max:120'],
            'contact_email'     => ['required', 'string', 'email:rfc', 'max:160'],
            'contact_phone'     => ['required', 'string', 'max:20'],
            'company_name'      => ['nullable', 'string', 'max:160'],
            'preferred_contact' => ['nullable', Rule::in(['phone', 'email', 'whatsapp'])],

            // ── Sandık dimensions (all required) ─────────────────────────────
            'length_cm'  => ['required', 'numeric', 'min:1', 'max:99999.99'],
            'width_cm'   => ['required', 'numeric', 'min:1', 'max:99999.99'],
            'height_cm'  => ['required', 'numeric', 'min:1', 'max:99999.99'],
            'weight_kg'  => ['required', 'numeric', 'min:0.01', 'max:99999.99'],

            // ── Crate type (required) ─────────────────────────────────────────
            'crate_type' => ['required', Rule::in(self::CRATE_TYPES)],

            // ── Technical requirements (checkboxes — absent = false) ───────────
            'requires_ispm15'  => ['nullable', 'boolean'],
            'requires_forklift' => ['nullable', 'boolean'],
            'requires_crane'   => ['nullable', 'boolean'],

            // ── Shipping & destination ────────────────────────────────────────
            'shipping_type'      => ['required', Rule::in(['ic', 'ihracat'])],
            'quantity'           => ['required', 'integer', 'min:1', 'max:999999'],
            'destination_city'   => ['nullable', 'string', 'max:120'],
            'destination_country' => ['nullable', 'string', 'max:120'],
            'material'           => ['nullable', 'string', 'max:120'],

            // ── Notes ─────────────────────────────────────────────────────────
            'notes'   => ['nullable', 'string', 'max:4000'],

            // ── Optional file attachment ──────────────────────────────────────
            // Stored on private local disk (not public).
            // Executables, scripts, and SVG rejected by mimes list.
            'attachment' => [
                'nullable',
                'file',
                'max:10240',                                          // 10 MB
                'mimes:pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx,zip', // safe types only
            ],

            // ── Honeypot — must stay empty ────────────────────────────────────
            'website' => ['nullable', 'size:0'],
        ], [
            'contact_name.required'   => 'Ad Soyad alanı zorunludur.',
            'contact_email.required'  => 'E-posta alanı zorunludur.',
            'contact_email.email'     => 'Geçerli bir e-posta adresi giriniz.',
            'contact_phone.required'  => 'Telefon alanı zorunludur.',
            'length_cm.required'      => 'Uzunluk (cm) zorunludur.',
            'length_cm.min'           => 'Uzunluk en az 1 cm olmalıdır.',
            'width_cm.required'       => 'Genişlik (cm) zorunludur.',
            'width_cm.min'            => 'Genişlik en az 1 cm olmalıdır.',
            'height_cm.required'      => 'Yükseklik (cm) zorunludur.',
            'height_cm.min'           => 'Yükseklik en az 1 cm olmalıdır.',
            'weight_kg.required'      => 'Ağırlık (kg) zorunludur.',
            'weight_kg.min'           => 'Ağırlık 0\'dan büyük olmalıdır.',
            'crate_type.required'     => 'Sandık tipi seçimi zorunludur.',
            'crate_type.in'           => 'Geçersiz sandık tipi.',
            'shipping_type.required'  => 'Sevkiyat tipi seçimi zorunludur.',
            'shipping_type.in'        => 'Geçersiz sevkiyat tipi.',
            'quantity.required'       => 'Adet zorunludur.',
            'quantity.integer'        => 'Adet tam sayı olmalıdır.',
            'quantity.min'            => 'Adet en az 1 olmalıdır.',
            'notes.max'               => 'Ek bilgi alanı en fazla 4000 karakter olabilir.',
            'website.size'            => 'Spam kontrolü başarısız.',
            'attachment.file'         => 'Lütfen geçerli bir dosya seçiniz.',
            'attachment.max'          => 'Dosya boyutu en fazla 10 MB olabilir.',
            'attachment.mimes'        => 'Yalnızca PDF, görsel (JPG, PNG, WEBP), Word, Excel veya ZIP dosyası yükleyebilirsiniz.',
        ]);
    }

    private function persist(array $data, ?string $storedPath, ?string $originalName): QuoteRequest
    {
        return DB::transaction(function () use ($data, $storedPath, $originalName) {
            // Reference collision is extremely unlikely but we retry up to 3×.
            $quote = null;
            for ($attempt = 0; $attempt < 3; $attempt++) {
                try {
                    $quote = QuoteRequest::create([
                        'reference_number'  => QuoteRequest::generateReference(),
                        'type'              => 'sandik',
                        'status'            => 'yeni',
                        'contact_name'      => $data['contact_name'],
                        'contact_email'     => $data['contact_email'],
                        'contact_phone'     => $data['contact_phone'],
                        'company_name'      => $data['company_name']      ?? null,
                        'preferred_contact' => $data['preferred_contact'] ?? 'email',
                        'message'           => $data['notes']             ?? null,
                        // File path on private local disk (null if no file uploaded)
                        'file_path'         => $storedPath,
                        'file_name'         => $originalName,
                    ]);
                    break;
                } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                    if ($attempt === 2) {
                        throw $e;
                    }
                }
            }

            SandikCalculationRequest::create([
                'quote_request_id'  => $quote->id,
                'length_cm'         => $data['length_cm'],
                'width_cm'          => $data['width_cm'],
                'height_cm'         => $data['height_cm'],
                'weight_kg'         => $data['weight_kg'],
                'crate_type'        => $data['crate_type'],
                'requires_ispm15'   => (bool) ($data['requires_ispm15']  ?? false),
                'requires_forklift' => (bool) ($data['requires_forklift'] ?? false),
                'requires_crane'    => (bool) ($data['requires_crane']   ?? false),
                'shipping_type'     => $data['shipping_type'],
                'quantity'          => (int) $data['quantity'],
                'destination_city'  => $data['destination_city']   ?? null,
                'destination_country' => $data['destination_country'] ?? 'Türkiye',
                'material'          => $data['material']            ?? null,
                'notes'             => $data['notes']               ?? null,
            ]);

            return $quote;
        });
    }
}
