<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Key-value settings store. Access via Setting::get('key') / Setting::set('key', value).
// Cached in Redis with Cache::rememberForever; cleared on write.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50)->index();   // general | ecommerce | notifications | seo
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type', 20)->default('string');  // string | boolean | json | integer
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
