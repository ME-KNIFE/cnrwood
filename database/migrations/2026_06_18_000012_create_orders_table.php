<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();    // SIP-2026-XXXXX

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Customer snapshot — preserved even if user is deleted
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20)->nullable();

            // Order lifecycle status
            $table->enum('status', [
                'beklemede',        // Pending
                'odeme_bekleniyor', // Awaiting EFT confirmation
                'islemde',          // Processing
                'kargoya_verildi',  // Shipped
                'teslim_edildi',    // Delivered
                'iptal_edildi',     // Cancelled
                'iade_edildi',      // Refunded
            ])->default('beklemede');

            // Payment (denormalized quick-check; full transaction log in payments table)
            $table->enum('payment_method', ['havale_eft', 'kredi_karti']);
            $table->enum('payment_status', [
                'beklemede',            // pending
                'odeme_bekleniyor',     // awaiting_bank_transfer
                'odendi',               // paid
                'basarisiz',            // failed
                'iptal_edildi',         // cancelled
                'iade_edildi',          // refunded
            ])->default('beklemede');

            // Amounts
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('coupon_code')->nullable();

            // Addresses as JSON snapshots (immutable order history)
            $table->json('shipping_address');
            $table->json('billing_address')->nullable();

            // Shipping denormalized (canonical data in shipments table)
            $table->string('cargo_company')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // EFT-specific — admin_users.id stored without FK (admin_users migration ordering)
            $table->string('eft_iban')->nullable();
            $table->timestamp('eft_confirmed_at')->nullable();
            $table->unsignedBigInteger('eft_confirmed_by')->nullable();

            $table->text('admin_notes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
