<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Laravel 13 already created the users table.
// This migration adds CNRWOOD-specific fields only.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->enum('type', ['individual', 'company'])->default('individual')->after('phone');
            $table->string('locale', 5)->default('tr')->after('type');
            $table->softDeletes()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'type', 'locale', 'deleted_at']);
        });
    }
};
