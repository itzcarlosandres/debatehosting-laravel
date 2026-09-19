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
        // 1. Categorías
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 2. Badges
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('label');
            $table->string('color')->default('green');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 3. Proveedores
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('logo_url')->nullable();
            $table->json('categories')->nullable();
            $table->string('plan')->nullable();
            $table->decimal('price_from', 8, 2)->default(0);
            $table->decimal('price_before', 8, 2)->default(0);
            $table->string('period')->default('mes');
            $table->decimal('score_precio', 3, 1)->default(0);
            $table->decimal('score_rendimiento', 3, 1)->default(0);
            $table->decimal('score_soporte', 3, 1)->default(0);
            $table->decimal('score_facilidad', 3, 1)->default(0);
            $table->decimal('uptime', 5, 2)->default(99.9);
            $table->text('affiliate_url')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('clicks')->default(0);
            $table->string('badge')->nullable();
            $table->string('badge_color')->nullable();
            $table->text('description')->nullable();
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->text('verdict')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        // 4. Cupones
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('discount');
            $table->text('condition')->nullable();
            $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade');
            $table->timestamp('expires_at')->nullable();
            $table->boolean('verified')->default(true);
            $table->integer('clicks')->default(0);
            $table->timestamps();
        });

        // 5. El Podio (Picks destacados)
        Schema::create('picks', function (Blueprint $table) {
            $table->id();
            $table->integer('position');
            $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade');
            $table->string('tag');
            $table->string('titulo');
            $table->text('veredicto');
            $table->timestamps();
        });

        // 6. Ticker de novedades
        Schema::create('ticker_items', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->boolean('hot')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 7. Configuración global dinámica
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        // 8. Suscriptores boletín
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamps();
        });

        // 9. Métricas y analítica de clics
        Schema::create('click_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->nullable()->constrained('providers')->onDelete('set null');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
            $table->string('type'); // affiliate_link | coupon_copy
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('click_events');
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('ticker_items');
        Schema::dropIfExists('picks');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('providers');
        Schema::dropIfExists('badges');
        Schema::dropIfExists('categories');
    }
};
