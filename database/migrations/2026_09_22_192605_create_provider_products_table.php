<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade');
            $table->string('category_slug')->nullable()->index();
            $table->string('plan_name');
            $table->decimal('price_from', 8, 2)->default(0);
            $table->decimal('price_before', 8, 2)->default(0);
            $table->string('period')->default('mes');
            $table->json('specs')->nullable();
            $table->text('affiliate_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Migrar automáticamente los planes existentes en 'providers' hacia 'provider_products'
        if (Schema::hasTable('providers')) {
            $existingProviders = DB::table('providers')->get();
            foreach ($existingProviders as $provider) {
                if (! empty($provider->plan) || $provider->price_from > 0) {
                    $categories = json_decode($provider->categories ?? '[]', true);
                    $firstCategory = is_array($categories) && count($categories) > 0 ? $categories[0] : null;

                    DB::table('provider_products')->insert([
                        'provider_id' => $provider->id,
                        'category_slug' => $firstCategory,
                        'plan_name' => $provider->plan ?: 'Plan Estándar',
                        'price_from' => $provider->price_from ?: 0,
                        'price_before' => $provider->price_before ?: 0,
                        'period' => $provider->period ?: 'mes',
                        'affiliate_url' => $provider->affiliate_url,
                        'is_featured' => true,
                        'order' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_products');
    }
};
