<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fairs', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('sort_order');
            $table->boolean('is_featured')->default(false)->after('is_published');
            $table->string('cover_image_path')->nullable()->after('is_featured');
            $table->string('image_alt_tr')->nullable()->after('cover_image_path');
            $table->string('image_alt_en')->nullable()->after('image_alt_tr');
        });
    }

    public function down(): void
    {
        Schema::table('fairs', function (Blueprint $table) {
            $table->dropColumn([
                'is_published', 'is_featured',
                'cover_image_path', 'image_alt_tr', 'image_alt_en',
            ]);
        });
    }
};
