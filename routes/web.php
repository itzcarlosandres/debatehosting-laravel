<?php

use App\Http\Controllers\Admin\AdminBadgeController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminProviderController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminSectionsController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AiGeneratorController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Públicas de DebateHosting
|--------------------------------------------------------------------------
*/

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/proveedores', [ProviderController::class, 'index'])->name('providers.index');
Route::get('/proveedores/{slug}', [ProviderController::class, 'show'])->name('providers.show');

// Reseñas y Análisis Editoriales
Route::get('/resenas', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/resenas/{slug}', [ReviewController::class, 'show'])->name('reviews.show');

Route::get('/cupones', [CouponController::class, 'index'])->name('coupons.index');
Route::get('/ofertas', [HomeController::class, 'ofertas'])->name('ofertas');
Route::get('/balanza', [ComparisonController::class, 'index'])->name('balanza');

Route::get('/auditor', [AuditorController::class, 'index'])->name('auditor');
Route::post('/auditor/inspect', [AuditorController::class, 'inspect'])->name('auditor.inspect');

// Redirección de afiliados y contador de clics
Route::get('/go/{slug}', [RedirectController::class, 'redirect'])->name('go');

// APIs públicas para tracking y newsletter
Route::post('/api/subscribe', [SubscriberController::class, 'store'])->name('api.subscribe');
Route::post('/api/track', [RedirectController::class, 'trackCoupon'])->name('api.track');

// Páginas informativas editoriales
Route::view('/metodo', 'pages.legal.metodo')->name('metodo');
Route::view('/afiliados', 'pages.legal.afiliados')->name('afiliados');
Route::view('/privacidad', 'pages.legal.privacidad')->name('privacidad');
Route::view('/terminos', 'pages.legal.terminos')->name('terminos');

/*
|--------------------------------------------------------------------------
| Rutas de Administración (/admin)
|--------------------------------------------------------------------------
*/

Route::redirect('/login', '/admin/login')->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    // Autenticación
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Panel protegido
    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // CRUD Proveedores
        Route::resource('providers', AdminProviderController::class);

        // CRUD Reseñas y Análisis Editoriales
        Route::resource('reviews', AdminReviewController::class);
        Route::post('reviews/{review}/toggle-publish', [AdminReviewController::class, 'togglePublish'])->name('reviews.toggle-publish');

        // CRUD Categorías
        Route::resource('categories', AdminCategoryController::class);

        // CRUD Badges / Distintivos
        Route::resource('badges', AdminBadgeController::class);

        // CRUD Cupones
        Route::resource('coupons', AdminCouponController::class);

        // Portada & Secciones (Edición de Textos del Home)
        Route::get('/sections', [AdminSectionsController::class, 'index'])->name('sections.index');
        Route::post('/sections', [AdminSectionsController::class, 'update'])->name('sections.update');

        // Portada & Secciones (Edición de Textos del Home)
        Route::get('/sections', [AdminSectionsController::class, 'index'])->name('sections.index');
        Route::post('/sections', [AdminSectionsController::class, 'update'])->name('sections.update');

        // Portada & Secciones (Edición de Textos del Home)
        Route::get('/sections', [AdminSectionsController::class, 'index'])->name('sections.index');
        Route::post('/sections', [AdminSectionsController::class, 'update'])->name('sections.update');

        // Portada & Secciones (Edición de Textos del Home)
        Route::get('/sections', [AdminSectionsController::class, 'index'])->name('sections.index');
        Route::post('/sections', [AdminSectionsController::class, 'update'])->name('sections.update');

        // Configuraciones de Portada y Sitio
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/clear-cache', [AdminSettingsController::class, 'clearCache'])->name('settings.clear-cache');
        Route::post('/settings/reset-stats', [AdminSettingsController::class, 'resetStats'])->name('settings.reset-stats');

        Route::post('/settings/clear-cache', [AdminSettingsController::class, 'clearCache'])->name('settings.clear-cache');
        Route::post('/settings/reset-stats', [AdminSettingsController::class, 'resetStats'])->name('settings.reset-stats');

        Route::post('/settings/clear-cache', [AdminSettingsController::class, 'clearCache'])->name('settings.clear-cache');
        Route::post('/settings/reset-stats', [AdminSettingsController::class, 'resetStats'])->name('settings.reset-stats');

        Route::post('/settings/clear-cache', [AdminSettingsController::class, 'clearCache'])->name('settings.clear-cache');
        Route::post('/settings/reset-stats', [AdminSettingsController::class, 'resetStats'])->name('settings.reset-stats');

        // Asistente IA para Generación de Proveedores y Reseñas
        Route::post('/ai/generate-provider', [AiGeneratorController::class, 'generateProvider'])->name('ai.generate-provider');
        Route::post('/ai/generate-review', [AiGeneratorController::class, 'generateReview'])->name('ai.generate-review');
        Route::post('/ai/test-gemini', [AiGeneratorController::class, 'testGemini'])->name('ai.test-gemini');
        Route::post('/ai/generate-products', [AiGeneratorController::class, 'generateProducts'])->name('ai.generate-products');
        Route::post('/ai/generate-products', [AiGeneratorController::class, 'generateProducts'])->name('ai.generate-products');
        Route::post('/ai/generate-products', [AiGeneratorController::class, 'generateProducts'])->name('ai.generate-products');
        Route::post('/ai/generate-products', [AiGeneratorController::class, 'generateProducts'])->name('ai.generate-products');
    });
});
