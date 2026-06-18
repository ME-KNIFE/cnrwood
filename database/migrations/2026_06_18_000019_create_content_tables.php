<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('slug')->unique();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->json('title');
            $table->json('excerpt')->nullable();
            $table->json('body');
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();

            $table->string('featured_image_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->date('completed_at')->nullable();
            $table->integer('sort_order')->default(0);

            $table->json('title');
            $table->json('description')->nullable();
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();

            // Gallery images via Spatie MediaLibrary (collection: 'project_gallery')
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fairs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('city')->nullable();
            $table->string('venue')->nullable();
            $table->integer('sort_order')->default(0);

            $table->json('name');
            $table->json('description')->nullable();

            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('fairs');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('blog_posts');
    }
};
