<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pick;
use App\Models\Provider;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSectionsController extends Controller
{
    public function index()
    {
        $hero = Setting::get('hero', [
            'kickerText' => 'OBSERVATORIO DE HOSTING Y NUBE',
            'titleBefore' => 'El observatorio técnico de',
            'titleHighlight' => 'hosting y servidores',
            'titleAfter' => '.',
            'description' => 'Comparamos proveedores en vivo con pruebas empíricas de latencia TTFB, tiempos de respuesta y estrés de CPU. Sin sesgos comerciales ni puestos comprados.',
            'primaryBtnText' => 'Pesar en La Balanza',
            'primaryBtnUrl' => '/balanza',
            'secondaryBtnText' => 'Ver Todos los Proveedores',
            'secondaryBtnUrl' => '/proveedores',
            'counter1Label' => 'Proveedores auditados',
            'counter2Label' => 'Cupones verificados',
            'counter3Label' => 'Metodología abierta',
            'counter4Label' => 'Patrocinios encubiertos',
            'previewTitle' => 'Duelo Editorial en Vivo',
            'fighter1Name' => 'Hostinger',
            'fighter1Price' => '$2.49/mes',
            'fighter2Name' => 'SiteGround',
            'fighter2Price' => '$2.99/mes',
            'metric1Label' => 'Velocidad TTFB & Carga',
            'metric1Scores' => '9.4 vs 8.9',
            'metric2Label' => 'Soporte Técnico 24/7',
            'metric2Scores' => '7.8 vs 9.5',
            'previewVerdict' => 'Si buscas máxima economía y discos NVMe veloces, Hostinger lidera. Si tu prioridad es soporte instantáneo en español y WordPress de élite, SiteGround gana el duelo.',
        ]);

        $sectionHeaders = Setting::get('sectionHeaders', []);

        if (empty($sectionHeaders['tabla'])) {
            $sectionHeaders['tabla'] = [
                'kicker' => 'AUDITORÍA DIRECTA • ORDENADOS POR INCORPORACIÓN RECIENTE',
                'titleBefore' => 'Tabla Comparativa de Rendimiento',
                'subtitle' => 'Datos consolidados de proveedores ordenados por los últimos añadidos a nuestro observatorio técnico, con hardware y latencias probadas.',
            ];
        }

        if (empty($sectionHeaders['podio'])) {
            $sectionHeaders['podio'] = [
                'kicker' => 'SELECCIÓN DIRECTA DE LA REDACCIÓN',
                'titleBefore' => 'El Podio Editorial',
                'subtitle' => 'Los tres mejores servicios clasificados por categoría tras cientos de horas de auditoría técnica.',
            ];
        }

        if (empty($sectionHeaders['metodo'])) {
            $sectionHeaders['metodo'] = [
                'kicker' => 'INDEPENDENCIA Y RIGOR',
                'titleBefore' => 'Nuestra Metodología de Auditoría',
                'subtitle' => 'Cuatro pilares técnicos en los que basamos cada puntuación y veredicto del observatorio.',
            ];
        }

        if (empty($sectionHeaders['news'])) {
            $sectionHeaders['news'] = [
                'titleBefore' => 'Suscríbete al Boletín Técnico de Hosting',
                'subtitle' => 'Recibe semanalmente alertas de caídas masivas de servidores, auditorías de nuevos proveedores y cupones exclusivos probados.',
            ];
        }

        $providers = Provider::where('active', true)->orderBy('name')->get();
        $picks = Pick::with('provider')->orderBy('position')->get();

        return view('admin.sections.index', compact('hero', 'sectionHeaders', 'picks', 'providers'));
    }

    public function update(Request $request)
    {
        // 1. Guardar Textos del Hero Principal
        if ($request->has('hero')) {
            $currentHero = Setting::get('hero', []);
            $mergedHero = array_merge($currentHero, $request->input('hero', []));
            Setting::set('hero', $mergedHero);
        }

        // 2. Guardar Encabezados de Secciones
        if ($request->has('sectionHeaders')) {
            $currentSections = Setting::get('sectionHeaders', []);
            $mergedSections = array_merge($currentSections, $request->input('sectionHeaders', []));
            Setting::set('sectionHeaders', $mergedSections);
        }

        // 3. Guardar Los Elegidos (Picks del Podio)
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

        return redirect()->route('admin.sections.index')->with('success', '¡Textos y secciones de la Portada actualizados correctamente!');
    }
}
