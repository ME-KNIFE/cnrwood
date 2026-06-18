<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();   // TKL-2026-XXXX

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            // assigned_to = admin_users.id — no FK constraint due to migration ordering.
            // admin_users was created in 000002; FK would work, but using unsignedBigInteger
            // avoids coupling migrations across unrelated domains.
            $table->unsignedBigInteger('assigned_to')->nullable();

            $table->enum('type', ['product', 'general', 'project', 'sandik']);
            $table->enum('status', [
                'yeni',
                'inceleniyor',
                'teklif_gonderildi',
                'kazanildi',
                'kaybedildi',
            ])->default('yeni');

            // Contact info snapshot (used even for guest requests)
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone', 20)->nullable();
            $table->string('company_name')->nullable();
            $table->string('tax_number', 20)->nullable();
            $table->enum('preferred_contact', ['phone', 'email', 'whatsapp'])->default('email');

            $table->text('message')->nullable();
            $table->string('file_path')->nullable();    // R2 path for uploaded file
            $table->string('file_name')->nullable();

            $table->text('admin_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
