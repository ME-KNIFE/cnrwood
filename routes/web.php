<?php

use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// ─── Public website (Phase 7A) ─────────────────────────────────────────────
// Read-only Blade pages. No checkout, no quote-form submission, no DB writes.
// Filament admin panels (/admin, /magaza-panel) are registered separately by
// their own ServiceProviders and are NOT affected by these routes.
Route::get('/',                [PublicController::class, 'home'])->name('home');
Route::get('/urunler',         [PublicController::class, 'products'])->name('public.products');
Route::get('/kategori/{slug}', [PublicController::class, 'category'])->name('public.category');
Route::get('/urun/{slug}',     [PublicController::class, 'product'])->name('public.product');
