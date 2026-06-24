<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('status');
            $table->boolean('is_featured')->default(false)->after('is_published');
            $table->string('category', 100)->nullable()->after('sort_order');
            $table->string('client_name')->nullable()->after('category');
            $table->string('location')->nullable()->after('client_name');
            $table->string('cover_image_path')->nullable()->after('location');
            $table->string('image_alt_tr')->nullable()->after('cover_image_path');
            $table->string('image_alt_en')->nullable()->after('image_alt_tr');
            $table->text('excerpt_tr')->nullable()->after('image_alt_en');
            $table->text('excerpt_en')->nullable()->after('excerpt_tr');
            $table->text('content_tr')->nullable()->after('excerpt_en');
            $table->text('content_en')->nullable()->after('content_tr');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'is_published', 'is_featured', 'category', 'client_name', 'location',
                'cover_image_path', 'image_alt_tr', 'image_alt_en',
                'excerpt_tr', 'excerpt_en', 'content_tr', 'content_en',
            ]);
        });
    }
};
