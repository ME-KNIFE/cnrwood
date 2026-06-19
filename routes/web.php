<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\QuoteRequestController;
use Illuminate\Support\Facades\Route;

// ─── Public website (Phase 7A) ─────────────────────────────────────────────
// Read-only Blade pages. No checkout, no DB writes from these actions.
// Filament admin panels (/admin, /magaza-panel) are registered separately by
// their own ServiceProviders and are NOT affected by these routes.
Route::get('/',                [PublicController::class, 'home'])->name('home');
Route::get('/urunler',         [PublicController::class, 'products'])->name('public.products');
Route::get('/kategori/{slug}', [PublicController::class, 'category'])->name('public.category');
Route::get('/urun/{slug}',     [PublicController::class, 'product'])->name('public.product');

// ─── Phase 7B — Public quote request flow ──────────────────────────────────
// CSRF is enforced by Laravel's `web` middleware group.
// POSTs are throttled to 5 requests / minute / IP to mitigate spam abuse.
Route::get('/teklif-al',                [QuoteRequestController::class, 'createGeneral'])->name('public.quote.create');
Route::get('/urun/{slug}/teklif-al',    [QuoteRequestController::class, 'createForProduct'])->name('public.quote.product.create');
Route::get('/teklif-al/tesekkurler',    [QuoteRequestController::class, 'thankYou'])->name('public.quote.thanks');

Route::middleware('throttle:5,1')->group(function () {
    Route::post('/teklif-al',             [QuoteRequestController::class, 'storeGeneral'])->name('public.quote.store');
    Route::post('/urun/{slug}/teklif-al', [QuoteRequestController::class, 'storeForProduct'])->name('public.quote.product.store');
});

// ─── Phase 7C — Public contact form ────────────────────────────────────────
Route::get('/iletisim',  [ContactController::class, 'create'])->name('public.contact');
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/iletisim', [ContactController::class, 'store'])->name('public.contact.store');
});
