<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 1:1 with quote_requests where type = 'sandik'.
// Created by the 6-step Sandık Hesaplama form.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sandik_calculation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_request_id')->unique()->constrained()->cascadeOnDelete();

            // Step 1 — Dimensions
            $table->decimal('length_cm', 8, 2);
            $table->decimal('width_cm', 8, 2);
            $table->decimal('height_cm', 8, 2);
            $table->decimal('weight_kg', 8, 2);

            // Step 2 — Crate type
            $table->enum('crate_type', [
                'ahsap',
                'osb',
                'izgara',
                'vinc_aparatli',
                'endcap',
                'taban_izgara',
                'bilmiyorum',
            ]);

            // Step 3 — Technical requirements
            $table->boolean('requires_ispm15')->default(false);
            $table->boolean('requires_forklift')->default(false);
            $table->boolean('requires_crane')->default(false);
            $table->enum('shipping_type', ['ic', 'ihracat'])->default('ihracat');

            // Step 4 — Quantity & destination
            $table->string('material')->nullable();
            $table->integer('quantity');
            $table->string('destination_city')->nullable();
            $table->string('destination_country')->nullable()->default('Türkiye');

            // Step 5/6 — Notes (file is on quote_requests.file_path)
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sandik_calculation_requests');
    }
};
