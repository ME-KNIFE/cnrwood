<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title_tr')->nullable();
            $table->string('title_en')->nullable();
            $table->text('excerpt_tr')->nullable();
            $table->text('excerpt_en')->nullable();
            $table->longText('content_tr')->nullable();
            $table->longText('content_en')->nullable();
            $table->string('hero_image_path')->nullable();
            $table->string('image_alt_tr')->nullable();
            $table->string('image_alt_en')->nullable();
            $table->string('meta_title_tr')->nullable();
            $table->string('meta_title_en')->nullable();
            $table->text('meta_description_tr')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_pages');
    }
};
