<?php

use App\Http\Controllers\Admin\SandikAttachmentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\QuoteRequestController;
use App\Http\Controllers\SandikController;
use App\Http\Controllers\SitemapController;
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

// ─── Phase 7D — Public SEO ─────────────────────────────────────────────────
// robots.txt is served as a static file from public/robots.txt.
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('public.sitemap');

// ─── Phase 7E — Public content pages (read-only, no DB writes) ─────────────
Route::get('/kurumsal',         [PublicController::class, 'corporate'])->name('public.corporate');
Route::get('/hakkimizda',       [PublicController::class, 'about'])->name('public.about');
Route::get('/hizmetler',        [PublicController::class, 'services'])->name('public.services');
// ─── Phase 9B — Secure admin file download (auth:admin required) ───────────
// Protected by the 'admin' guard — only authenticated AdminUsers may access.
// File path comes from DB (QuoteRequest model), never from request input.
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin-dl/sandik/{quoteRequest}', [SandikAttachmentController::class, 'download'])
         ->name('admin.sandik.attachment.download');
});

// ─── Phase 9A — Sandık Hesaplama real form ─────────────────────────────────
Route::get('/sandik-hesaplama', [SandikController::class, 'create'])->name('public.sandik');
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/sandik-hesaplama', [SandikController::class, 'store'])->name('public.sandik.store');
});

// ─── Phase 8B — Checkout / Order creation (guest + authenticated) ───────────
// Payment method is always havale_eft — never accepted from request.
// Order creation is wrapped in OrderService::createFromCart (DB::transaction).
// Success page uses session key checkout_order_id — no order id in URL.
Route::prefix('siparis')->name('checkout.')->group(function () {
    Route::get('/olustur',    [CheckoutController::class, 'index'])->name('index');
    Route::get('/tesekkurler', [CheckoutController::class, 'success'])->name('success');
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/olustur', [CheckoutController::class, 'store'])->name('store');
    });
});

// ─── Phase 8A — Public shopping cart (guest + authenticated) ────────────────
// Static segments (ekle, temizle) are declared before the {item} parameter
// so they are never captured as a route model binding value.
Route::prefix('sepet')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::middleware('throttle:30,1')->group(function () {
        Route::post('/ekle',               [CartController::class, 'add'])->name('add');
        Route::post('/temizle',            [CartController::class, 'clear'])->name('clear');
        Route::post('/{item}/guncelle',    [CartController::class, 'update'])->name('update');
        Route::post('/{item}/sil',         [CartController::class, 'remove'])->name('remove');
    });
});
