<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ─────────────────────────────────────────────────────────────────────────────
// THE MOST CRITICAL TABLE IN THIS PROJECT.
//
// product_type is NOT NULL — enforced at DB level.
// The entire frontend branches on this single field:
//
//   buyable    → has price, has stock, Add to Cart, full checkout
//   quote_only → NO price (not even 0 TL), NO cart, "Teklif Al" only
//
// price and stock_quantity are nullable so quote_only rows never accidentally
// hold a price. App layer (OrderService, CartService) must also enforce this.
// ─────────────────────────────────────────────────────────────────────────────
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')
                ->nullable()
                ->constrained('product_categories')
                ->nullOnDelete();

            // ── CRITICAL FIELD ────────────────────────────────────────────────
            $table->enum('product_type', ['buyable', 'quote_only'])
                ->comment('buyable=price+cart | quote_only=no price, no cart, EVER');
            // ─────────────────────────────────────────────────────────────────

            $table->string('sku')->unique()->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);

            // Translatable JSON fields
            $table->json('name');
            $table->json('description')->nullable();
            $table->json('short_description')->nullable();
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();

            // ── buyable-only fields (always NULL for quote_only) ──────────────
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->nullable()->default(0);
            $table->integer('low_stock_threshold')->nullable()->default(5);
            $table->boolean('track_stock')->default(false);
            $table->decimal('weight_kg', 8, 3)->nullable();
            // ─────────────────────────────────────────────────────────────────

            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_type', 'is_active']);
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
