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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->nullable()->constrained('providers')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('provider_name')->nullable();
            $table->string('target_category')->nullable();
            $table->decimal('rating', 3, 1)->default(9.0);
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->text('verdict')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('published')->default(true);
            $table->boolean('featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['published', 'published_at']);
            $table->index(['provider_id', 'published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
