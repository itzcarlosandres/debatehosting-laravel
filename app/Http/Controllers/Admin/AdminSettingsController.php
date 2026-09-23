<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Pick;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminSettingsController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::all()->pluck('value', 'key');

        $activeTab = $request->query('tab', 'brand');

        // Valores por defecto alineados con el diseño original
        $data = [
            'siteName' => $settings['siteName'] ?? 'Debatehosting',
            'siteUrl' => $settings['siteUrl'] ?? 'https://debatehosting.com',
            'siteTagline' => $settings['siteTagline'] ?? 'El Gran Observatorio de Hosting, VPS y Cupones (Edición Verificada)',
            'contactEmail' => $settings['contactEmail'] ?? 'redaccion@debatehosting.com',
            'currency' => $settings['currency'] ?? '$',
            'independenceCommitment' => $settings['independenceCommitment'] ?? 'Medición real de latencia TTFB, uptime y relación calidad-precio sin tapujos.',
            'disclosureNotice' => $settings['disclosureNotice'] ?? 'Debatehosting se financia mediante enlaces de afiliación regulados. Al contratar a través de nuestros enlaces, podemos recibir una comisión sin coste adicional para ti. Esto nunca afecta a la objetividad de nuestros análisis ni a las posiciones del ranking.',

            // Formato y presentación del Logo
            'logoType' => $settings['logoType'] ?? 'icon_text',
            'logoIcon' => $settings['logoIcon'] ?? 'server',
            'logoTextPrefix' => $settings['logoTextPrefix'] ?? 'Debate',
            'logoTextHighlight' => $settings['logoTextHighlight'] ?? 'hosting',
            'logoColor' => $settings['logoColor'] ?? '#0E6B41',
            'logoUrl' => $settings['logoUrl'] ?? '/logo.png',

            // Assets gráficos
            'faviconUrl' => $settings['faviconUrl'] ?? '/favicon.png',
            'ogImageUrl' => $settings['ogImageUrl'] ?? '/og-image.png',

            // Barra superior de noticiero (Top Bar Ticker)
            'showTopBar' => isset($settings['showTopBar']) ? (bool) $settings['showTopBar'] : true,
            'topBarBadge' => $settings['topBarBadge'] ?? 'RADAR ACTIVO',
            'topBarText' => $settings['topBarText'] ?? '14 Proveedores de Hosting bajo auditoría continua. Pruebas de velocidad en tiempo real.',
            'topBarRightBadge' => $settings['topBarRightBadge'] ?? '100% INDEPENDIENTE',
            'topBarRightText' => $settings['topBarRightText'] ?? 'EDICIÓN 2026',

            // Cinta de noticias en marquesina (Ticker)
            'showTicker' => isset($settings['showTicker']) ? (bool) $settings['showTicker'] : false,

            // SEO & Indexación
            'defaultMetaTitle' => $settings['defaultMetaTitle'] ?? 'Debatehosting — El Gran Observatorio de Hosting, VPS y Cupones',
            'defaultMetaDescription' => $settings['defaultMetaDescription'] ?? 'Medio editorial y comparador técnico independiente de hosting web, servidores VPS, cloud y cupones verificados. Medición real de latencia TTFB, uptime y relación calidad-precio sin tapujos.',
            'defaultKeywords' => $settings['defaultKeywords'] ?? 'hosting web, mejor hosting espana, comparativa hosting, vps baratos, cupones hosting, hosting wordpress, test ttfb',
            'googleAnalyticsId' => $settings['googleAnalyticsId'] ?? '',
            'googleSearchConsoleCode' => $settings['googleSearchConsoleCode'] ?? '',
            'customHeadCode' => $settings['customHeadCode'] ?? '',
            'customBodyCode' => $settings['customBodyCode'] ?? '',

            // Afiliación & Ética
            'affiliateRel' => $settings['affiliateRel'] ?? 'sponsored noopener noreferrer',

            // Inteligencia Artificial (Google Gemini)
            'geminiApiKey' => $settings['geminiApiKey'] ?? env('GEMINI_API_KEY', ''),
            'geminiModel' => $settings['geminiModel'] ?? env('GEMINI_MODEL', 'gemini-2.5-flash'),
            'geminiTemperature' => $settings['geminiTemperature'] ?? '0.5',
        ];

        // Diagnóstico del sistema para pestaña Motor
        $systemInfo = [
            'phpVersion' => PHP_VERSION,
            'laravelVersion' => app()->version(),
            'dbDriver' => config('database.default'),
            'providersCount' => Provider::count(),
            'categoriesCount' => Category::count(),
            'badgesCount' => Badge::count(),
            'couponsCount' => Coupon::count(),
            'subscribersCount' => Subscriber::count(),
        ];

        $providers = Provider::where('active', true)->orderBy('name')->get();
        $picks = Pick::with('provider')->orderBy('position')->get();
        $hero = Setting::get('hero', []);

        return view('admin.settings.index', compact(
            'data',
            'activeTab',
            'systemInfo',
            'providers',
            'picks',
            'hero'
        ));
    }

    public function update(Request $request)
    {
        $currentTab = $request->input('current_tab', 'brand');

        // Subida de Favicon
        if ($request->hasFile('favicon_file')) {
            $file = $request->file('favicon_file');
            $uploadDir = public_path('uploads/branding');
            if (! File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $filename = 'favicon_'.time().'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            Setting::set('faviconUrl', '/uploads/branding/'.$filename);
        } elseif ($request->filled('faviconUrl')) {
            Setting::set('faviconUrl', $request->faviconUrl);
        }

        // Subida de Imagen OG
        if ($request->hasFile('og_image_file')) {
            $file = $request->file('og_image_file');
            $uploadDir = public_path('uploads/branding');
            if (! File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $filename = 'og_'.time().'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            Setting::set('ogImageUrl', '/uploads/branding/'.$filename);
        } elseif ($request->filled('ogImageUrl')) {
            Setting::set('ogImageUrl', $request->ogImageUrl);
        }

        // Subida de Logo en Imagen
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $uploadDir = public_path('uploads/branding');
            if (! File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $filename = 'logo_'.time().'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            Setting::set('logoUrl', '/uploads/branding/'.$filename);
        } elseif ($request->filled('logoUrl')) {
            Setting::set('logoUrl', $request->logoUrl);
        }

        // Campos directos de texto / selección
        $directKeys = [
            'siteName',
            'siteUrl',
            'siteTagline',
            'contactEmail',
            'currency',
            'independenceCommitment',
            'disclosureNotice',
            'logoType',
            'logoIcon',
            'logoTextPrefix',
            'logoTextHighlight',
            'logoColor',
            'topBarBadge',
            'topBarText',
            'topBarRightBadge',
            'topBarRightText',
            'defaultMetaTitle',
            'defaultMetaDescription',
            'defaultKeywords',
            'googleAnalyticsId',
            'googleSearchConsoleCode',
            'customHeadCode',
            'customBodyCode',
            'affiliateRel',
            'geminiApiKey',
            'geminiModel',
            'geminiTemperature',
        ];

        foreach ($directKeys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // Toggles booleanos
        if ($request->has('has_topbar_toggle')) {
            Setting::set('showTopBar', $request->boolean('showTopBar'));
        }

        if ($request->has('has_ticker_toggle')) {
            Setting::set('showTicker', $request->boolean('showTicker'));
        }

        // Actualización de cuenta si se solicita
        if ($request->boolean('update_account')) {
            $user = Auth::user();
            if ($user) {
                if ($request->filled('name')) {
                    $user->name = $request->name;
                }
                if ($request->filled('email')) {
                    $user->email = $request->email;
                }
                if ($request->filled('password')) {
                    $request->validate([
                        'password' => 'min:6|confirmed',
                    ]);
                    $user->password = Hash::make($request->password);
                }
                $user->save();
            }
        }

        // Podio (Picks) si está en la petición
        if ($request->has('picks') && is_array($request->picks)) {
            foreach ($request->picks as $pos => $pData) {
                if (! empty($pData['provider_id'])) {
                    Pick::updateOrCreate(
                        ['position' => $pos],
                        [
                            'provider_id' => $pData['provider_id'],
                            'tag' => $pData['tag'] ?? 'DESTACADO',
                            'titulo' => $pData['titulo'] ?? '',
                            'veredicto' => $pData['veredicto'] ?? '',
                        ]
                    );
                }
            }
        }

        return redirect()->route('admin.settings', ['tab' => $currentTab])
            ->with('success', 'Configuración guardada exitosamente.');
    }

    public function clearCache()
    {
        Artisan::call('optimize:clear');

        return back()->with('success', '¡Caché del sistema y plantillas optimizadas con éxito!');
    }
}
