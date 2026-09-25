<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::with('provider')
            ->where('verified', true)
            ->whereHas('provider', function ($q) {
                $q->where('active', true);
            });

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('discount', 'like', "%{$search}%")
                    ->orWhere('condition', 'like', "%{$search}%")
                    ->orWhereHas('provider', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $coupons = $query->orderBy('created_at', 'desc')->get();
        $sectionHeaders = Setting::get('sectionHeaders', []);

        return view('pages.coupons', compact('coupons', 'sectionHeaders'));
    }
}
