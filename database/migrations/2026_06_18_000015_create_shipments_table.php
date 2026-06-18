<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Normalized shipment tracking.
// One order can have multiple shipments (partial fulfillment).
// orders.cargo_company + orders.tracking_number are denormalized for quick display.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->string('cargo_company')->nullable();        // "Aras", "Yurtiçi", etc.
            $table->string('tracking_number')->nullable();
            $table->string('tracking_url')->nullable();

            $table->enum('status', [
                'hazirlanıyor',     // Preparing
                'kargoya_verildi',  // Handed to carrier
                'teslim_edildi',    // Delivered
                'iade',             // Return
            ])->default('hazirlanıyor');

            $table->timestamp('shipped_at')->nullable();
            $table->date('estimated_delivery')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
