<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Phase 9B — Secure admin download controller for Sandık attachments.
 *
 * Security guarantees:
 *   - Route is protected by auth:admin middleware (AdminUser guard).
 *   - File path is read ONLY from the DB record — never from request input.
 *   - Path is validated to start with 'sandik-attachments/' before serving.
 *   - File is served from the private local disk (storage/app/private/).
 *   - Response is forced-download (Content-Disposition: attachment).
 *   - No inline rendering possible.
 */
class SandikAttachmentController extends Controller
{
    private const ALLOWED_PREFIX = 'sandik-attachments/';

    public function download(Request $request, QuoteRequest $quoteRequest): StreamedResponse
    {
        // 1. Must be a sandık quote
        abort_unless($quoteRequest->type === 'sandik', 404);

        // 2. Must have an attachment
        $filePath = $quoteRequest->file_path;
        abort_if($filePath === null || $filePath === '', 404);

        // 3. Path traversal guard — stored path must start with our prefix.
        //    (Defensive: we control generation, but belt-and-suspenders.)
        if (! str_starts_with($filePath, self::ALLOWED_PREFIX)) {
            abort(403, 'Geçersiz dosya yolu.');
        }

        // 4. File must exist on disk
        if (! Storage::disk('local')->exists($filePath)) {
            abort(404, 'Dosya bulunamadı.');
        }

        // 5. Display name: stored original filename → fallback to basename
        $displayName = $quoteRequest->file_name
            ? basename($quoteRequest->file_name)
            : basename($filePath);

        // 6. Force-download from private disk — never publicly accessible
        return Storage::disk('local')->download($filePath, $displayName);
    }
}
