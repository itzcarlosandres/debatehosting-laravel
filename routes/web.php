<?php

use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminProviderController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Públicas de DebateHosting
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/proveedores', [ProviderController::class, 'index'])->name('providers.index');
Route::get('/proveedores/{slug}', [ProviderController::class, 'show'])->name('providers.show');

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

        // CRUD Cupones
        Route::resource('coupons', AdminCouponController::class);

        // Configuraciones de Portada y Sitio
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    });
});
