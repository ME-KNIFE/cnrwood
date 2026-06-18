<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Normalized payment transaction log.
// Each payment attempt gets one row.
// orders.payment_status is the denormalized quick-check field.
// This table is the canonical record for iyzico/PayTR webhooks, EFT confirmations.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->enum('method', ['havale_eft', 'kredi_karti']);
            $table->enum('status', [
                'pending',                  // created, not yet confirmed
                'awaiting_bank_transfer',   // EFT: waiting for customer to send wire
                'paid',                     // confirmed
                'failed',                   // payment attempt failed
                'cancelled',                // cancelled before completion
                'refunded',                 // refunded after payment
            ])->default('pending');

            $table->decimal('amount', 10, 2);

            // Provider info (iyzico, paytry, manual for EFT)
            $table->string('provider')->nullable();         // iyzico | paytry | manual
            $table->string('provider_ref')->nullable();     // provider transaction ID
            $table->json('provider_response')->nullable();  // full webhook payload

            // EFT-specific
            $table->string('bank_sender_name')->nullable();
            $table->string('bank_sender_iban')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
