<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClickEvent;
use App\Models\Coupon;
use App\Models\Provider;
use App\Models\Subscriber;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Métricas de Tiempo
        $todayClicks = ClickEvent::where('created_at', '>=', now()->startOfDay())->count();
        $clicks7Days = ClickEvent::where('created_at', '>=', now()->subDays(7))->count();
        $clicks30Days = ClickEvent::where('created_at', '>=', now()->subDays(30))->count();
        $historicalClicks = ClickEvent::count();

        // 2. Canales y Entidades
        $affiliateClicks = ClickEvent::where('type', 'affiliate_link')->count();
        $couponCopies = ClickEvent::where('type', 'coupon_copy')->count();
        $totalSubscribers = Subscriber::count();
        $verifiedCoupons = Coupon::where('verified', true)->count();
        if ($verifiedCoupons === 0) {
            $verifiedCoupons = Coupon::count();
        }

        // 3. Actividad Diaria de Clics (Últimos 7 Días)
        $dayNames = [
            0 => 'DOM',
            1 => 'LUN',
            2 => 'MAR',
            3 => 'MIÉ',
            4 => 'JUE',
            5 => 'VIE',
            6 => 'SÁB',
        ];

        $dailyActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $targetDate = now()->subDays($i);
            $dateString = $targetDate->format('Y-m-d');
            $dayOfWeek = (int) $targetDate->format('w');
            $count = ClickEvent::whereDate('created_at', $dateString)->count();

            $dailyActivity[] = [
                'date' => $dateString,
                'dayLabel' => $dayNames[$dayOfWeek],
                'count' => $count,
            ];
        }

        $maxDailyClicks = max(array_column($dailyActivity, 'count')) ?: 1;
        $totalDailyClicks7 = array_sum(array_column($dailyActivity, 'count'));

        // 4. Top Proveedores por Tráfico de Afiliados
        $topProviders = Provider::where('active', true)->orderBy('clicks', 'desc')->take(10)->get();
        $totalProviderClicks = Provider::sum('clicks') ?: 1;

        // 5. Últimos Clics y Conversiones (Telemetría en Vivo)
        $recentClicks = ClickEvent::with(['provider', 'coupon'])
            ->latest()
            ->take(15)
            ->get();

        return view('admin.dashboard', compact(
            'todayClicks',
            'clicks7Days',
            'clicks30Days',
            'historicalClicks',
            'affiliateClicks',
            'couponCopies',
            'totalSubscribers',
            'verifiedCoupons',
            'dailyActivity',
            'maxDailyClicks',
            'totalDailyClicks7',
            'topProviders',
            'totalProviderClicks',
            'recentClicks'
        ));
    }
}
