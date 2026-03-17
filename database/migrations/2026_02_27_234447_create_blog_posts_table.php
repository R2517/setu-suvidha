<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('blog_category_id')->nullable()->constrained()->nullOnDelete();

            $table->string('title');
            $table->string('title_mr')->nullable();
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->text('excerpt_mr')->nullable();
            $table->json('content_json');

            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable();
            $table->string('featured_image_caption')->nullable();

            $table->string('meta_title')->nullable();
            $table->string('meta_title_mr')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_description_mr')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots')->default('index, follow');
            $table->string('og_image')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->string('focus_keyword_mr')->nullable();

            $table->string('language')->default('en');
            $table->string('hreflang_en_slug')->nullable();
            $table->string('hreflang_mr_slug')->nullable();

            $table->enum('status', ['draft', 'published', 'scheduled', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();

            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedInteger('reading_time_minutes')->default(1);

            $table->json('previous_content_json')->nullable();
            $table->unsignedInteger('version')->default(1);

            $table->string('old_slug')->nullable();
            
            $table->index('slug');
            $table->index('status');
            $table->index(['status', 'published_at']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
