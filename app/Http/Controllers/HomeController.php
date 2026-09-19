<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Pick;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\TickerItem;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $providers = Provider::where('active', true)->orderBy('score_precio', 'desc')->get();
        $categories = Category::orderBy('order')->get();
        $picks = Pick::with('provider')->orderBy('position')->get();
        $coupons = Coupon::with('provider')->where('verified', true)->get();
        $tickerItems = TickerItem::orderBy('order')->get();

        // Configuraciones dinámicas de la portada
        $hero = Setting::get('hero', []);
        $sectionHeaders = Setting::get('sectionHeaders', []);
        $mejores = Setting::get('mejores', []);
        $branding = [
            'siteName' => Setting::get('siteName', 'Debatehosting'),
            'siteTagline' => Setting::get('siteTagline', 'El Gran Observatorio de Hosting'),
            'logoType' => Setting::get('logoType', 'icon_text'),
            'logoIcon' => Setting::get('logoIcon', 'server'),
            'logoTextPrefix' => Setting::get('logoTextPrefix', 'Debate'),
            'logoTextHighlight' => Setting::get('logoTextHighlight', 'hosting'),
            'logoUrl' => Setting::get('logoUrl', '/logo.png'),
        ];

        return view('pages.home', compact(
            'providers',
            'categories',
            'picks',
            'coupons',
            'tickerItems',
            'hero',
            'sectionHeaders',
            'mejores',
            'branding'
        ));
    }

    public function ofertas(Request $request)
    {
        $query = Provider::where('active', true);

        if ($request->filled('categoria') && $request->categoria !== 'todas') {
            $cat = $request->categoria;
            $query->whereJsonContains('categories', $cat);
        }

        $providers = $query->orderBy('price_from', 'asc')->get();
        $categories = Category::orderBy('order')->get();
        $sectionHeaders = Setting::get('sectionHeaders', []);

        return view('pages.ofertas', compact('providers', 'categories', 'sectionHeaders'));
    }
}
