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
        $totalProviders = Provider::count();
        $activeProviders = Provider::where('active', true)->count();
        $totalCoupons = Coupon::count();
        $totalSubscribers = Subscriber::count();
        $totalClicks = ClickEvent::count();

        $recentClicks = ClickEvent::with(['provider', 'coupon'])
            ->latest()
            ->take(10)
            ->get();

        $topProviders = Provider::orderBy('clicks', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProviders',
            'activeProviders',
            'totalCoupons',
            'totalSubscribers',
            'totalClicks',
            'recentClicks',
            'topProviders'
        ));
    }
}
