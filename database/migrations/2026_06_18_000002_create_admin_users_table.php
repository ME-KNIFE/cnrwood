<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Completely separate from the front-end users table.
// admin_users log in at /admin or /magaza-panel only.
// Placed early (000002) so orders + quote_requests can reference admin_users.id.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', [
                'super_admin',      // full access to /admin
                'sales_manager',    // orders, quotes, customers, messages
                'editor',           // blog, projects, fairs, media
                'product_manager',  // products, categories
                'support',          // orders read+status, messages, quotes read
                'store_manager',    // /magaza-panel ONLY — blocked from /admin
            ]);
            $table->string('locale', 5)->default('tr');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_users');
    }
};
