<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pick;
use App\Models\Provider;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $hero = Setting::get('hero', []);
        $siteName = Setting::get('siteName', 'Debatehosting');
        $siteTagline = Setting::get('siteTagline', '');
        $contactEmail = Setting::get('contactEmail', 'contacto@debatehosting.com');
        $currency = Setting::get('currency', '$');
        $disclosureNotice = Setting::get('disclosureNotice', '');

        $providers = Provider::where('active', true)->orderBy('name')->get();
        $picks = Pick::with('provider')->orderBy('position')->get();

        return view('admin.settings.index', compact(
            'hero',
            'siteName',
            'siteTagline',
            'contactEmail',
            'currency',
            'disclosureNotice',
            'providers',
            'picks'
        ));
    }

    public function update(Request $request)
    {
        if ($request->has('siteName')) {
            Setting::set('siteName', $request->siteName);
        }
        if ($request->has('siteTagline')) {
            Setting::set('siteTagline', $request->siteTagline);
        }
        if ($request->has('contactEmail')) {
            Setting::set('contactEmail', $request->contactEmail);
        }
        if ($request->has('currency')) {
            Setting::set('currency', $request->currency);
        }
        if ($request->has('disclosureNotice')) {
            Setting::set('disclosureNotice', $request->disclosureNotice);
        }

        if ($request->has('hero')) {
            $hero = Setting::get('hero', []);
            $mergedHero = array_merge($hero, $request->input('hero', []));
            Setting::set('hero', $mergedHero);
        }

        // Actualizar Podio (Picks)
        if ($request->has('picks') && is_array($request->picks)) {
            foreach ($request->picks as $pos => $pData) {
                if (!empty($pData['provider_id'])) {
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

        return back()->with('success', 'Configuraciones guardadas correctamente.');
    }
}
