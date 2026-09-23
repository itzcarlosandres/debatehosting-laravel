<?php

use App\Models\ClickEvent;
use App\Models\Coupon;
use App\Models\Provider;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('stats:reset', function () {
    \App\Models\ClickEvent::query()->delete();
    \App\Models\Provider::query()->update(['clicks' => 0]);
    \App\Models\Coupon::query()->update(['clicks' => 0]);
    $this->info('Estadísticas y telemetría restablecidas a 0 exitosamente para producción.');
})->purpose('Restablecer todas las estadísticas y telemetría a cero para producción');


Artisan::command('stats:reset', function () {
    ClickEvent::query()->delete();
    Provider::query()->update(['clicks' => 0]);
    Coupon::query()->update(['clicks' => 0]);
    $this->info('Estadísticas y telemetría restablecidas a 0 exitosamente para producción.');
})->purpose('Restablecer todas las estadísticas y telemetría a cero para producción');

Artisan::command('stats:reset', function () {
    ClickEvent::query()->delete();
    Provider::query()->update(['clicks' => 0]);
    Coupon::query()->update(['clicks' => 0]);
    $this->info('Estadísticas y telemetría restablecidas a 0 exitosamente para producción.');
})->purpose('Restablecer todas las estadísticas y telemetría a cero para producción');

Artisan::command('stats:reset', function () {
    ClickEvent::query()->delete();
    Provider::query()->update(['clicks' => 0]);
    Coupon::query()->update(['clicks' => 0]);
    $this->info('Estadísticas y telemetría restablecidas a 0 exitosamente para producción.');
})->purpose('Restablecer todas las estadísticas y telemetría a cero para producción');
