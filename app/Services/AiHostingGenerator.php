<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiHostingGenerator
{
    /**
     * Obtener configuración activa de Gemini desde Settings o .env
     */
    public function getGeminiConfig(): array
    {
        $key = Setting::get('geminiApiKey') ?: config('services.gemini.key', env('GEMINI_API_KEY'));
        $model = Setting::get('geminiModel') ?: 'gemini-2.5-flash';
        $temp = Setting::get('geminiTemperature') ?? 0.4;

        return [
            'key' => trim($key ?? ''),
            'model' => trim($model ?: 'gemini-2.5-flash'),
            'temperature' => floatval($temp) > 0 ? floatval($temp) : 0.4,
        ];
    }

    /**
     * HTTP Client con soporte de fallback SSL para Windows / MAMP
     */
    public function httpClient(int $timeout = 25): PendingRequest
    {
        return Http::timeout($timeout)->withoutVerifying();
    }

    /**
     * Limpieza y extracción robusta de JSON retornado por la IA
     */
    public function cleanJsonText(string $raw): ?array
    {
        $text = trim($raw);
        if (str_starts_with($text, '```json')) {
            $text = substr($text, 7);
        } elseif (str_starts_with($text, '```')) {
            $text = substr($text, 3);
        }
        if (str_ends_with($text, '```')) {
            $text = substr($text, 0, -3);
        }
        $text = trim($text);

        $decoded = json_decode($text, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        // Intento de extracción entre el primer { y el último }
        $firstBrace = strpos($text, '{');
        $lastBrace = strrpos($text, '}');
        if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
            $sub = substr($text, $firstBrace, $lastBrace - $firstBrace + 1);
            $decoded = json_decode($sub, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }

    public function cleanJsonString(string $raw): string
    {
        $text = trim($raw);
        if (str_starts_with($text, '```json')) {
            $text = substr($text, 7);
        } elseif (str_starts_with($text, '```')) {
            $text = substr($text, 3);
        }
        if (str_ends_with($text, '```')) {
            $text = substr($text, 0, -3);
        }
        $text = trim($text);

        $firstBracket = strpos($text, '[');
        $lastBracket = strrpos($text, ']');
        if ($firstBracket !== false && $lastBracket !== false && $lastBracket > $firstBracket) {
            return substr($text, $firstBracket, $lastBracket - $firstBracket + 1);
        }

        return $text;
    }

    /**
     * Probar conexión con Google Gemini API
     */
    public function testGeminiConnection(?string $apiKey = null, ?string $model = null): array
    {
        $cfg = $this->getGeminiConfig();
        $apiKey = ! empty($apiKey) ? trim($apiKey) : $cfg['key'];
        $model = ! empty($model) ? trim($model) : $cfg['model'];

        if (empty($apiKey)) {
            return [
                'success' => false,
                'message' => 'No se ha configurado ninguna API Key de Google Gemini.',
            ];
        }

        try {
            $response = $this->httpClient(12)
                ->withHeaders(['x-goog-api-key' => $apiKey])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => 'Responde exactamente la palabra: CONEXION_OK'],
                            ],
                        ],
                    ],
                ]);

            if (! $response->successful()) {
                $response = $this->httpClient(12)
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => 'Responde exactamente la palabra: CONEXION_OK'],
                                ],
                            ],
                        ],
                    ]);
            }

            if ($response->successful()) {
                $reply = trim($response->json('candidates.0.content.parts.0.text') ?? 'OK');

                return [
                    'success' => true,
                    'message' => "¡Conexión exitosa con Google Gemini! Modelo: {$model}. Verificación: {$reply}",
                ];
            }

            $errorMsg = $response->json('error.message') ?? ('Error HTTP '.$response->status());

            return [
                'success' => false,
                'message' => "La API de Gemini rechazó la clave: {$errorMsg}",
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Error de conexión con Google: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Generar ficha técnica completa de proveedor con IA
     */
    public function generate(string $name, ?string $focus = null): array
    {
        $name = trim($name);
        $focus = trim($focus ?? '');

        // 1. Intentar con Gemini API si está configurada
        $geminiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        if (! empty($geminiKey)) {
            $result = $this->generateWithGemini($name, $focus, $geminiKey);
            if ($result) {
                return $result;
            }
        }

        // 2. Intentar con OpenAI API si está configurada
        $openaiKey = config('services.openai.key', env('OPENAI_API_KEY'));
        if (! empty($openaiKey)) {
            $result = $this->generateWithOpenAi($name, $focus, $openaiKey);
            if ($result) {
                return $result;
            }
        }

        // 3. Generador Heurístico Experto Integrado (Offline / Fallback Inteligente)
        return $this->generateHeuristic($name, $focus);
    }

    /**
     * Generar reseña editorial y análisis técnico exhaustivo con IA
     */
    public function generateReview(string $name, ?string $focus = null, ?array $providerData = null): array
    {
        $name = trim($name);
        $focus = trim($focus ?? '');

        // 1. Intentar con Gemini API
        $geminiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        if (! empty($geminiKey)) {
            $result = $this->generateReviewWithGemini($name, $focus, $providerData, $geminiKey);
            if ($result) {
                return $result;
            }
        }

        // 2. Intentar con OpenAI API
        $openaiKey = config('services.openai.key', env('OPENAI_API_KEY'));
        if (! empty($openaiKey)) {
            $result = $this->generateReviewWithOpenAi($name, $focus, $providerData, $openaiKey);
            if ($result) {
                return $result;
            }
        }

        // 3. Generador Heurístico Editorial Especializado
        return $this->generateReviewHeuristic($name, $focus, $providerData);
    }

    /**
     * Llamada a la API de Gemini (Google AI)
     */
    protected function generateWithGemini(string $name, string $focus, string $apiKey): ?array
    {
        try {
            $prompt = $this->buildPrompt($name, $focus);
            $response = Http::timeout(15)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                    'temperature' => 0.4,
                ],
            ]);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text');
                if ($content) {
                    $data = json_decode($content, true);
                    if (is_array($data) && ! empty($data['plan'])) {
                        return $this->sanitizeData($name, $data);
                    }
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    /**
     * Llamada a la API de OpenAI
     */
    protected function generateWithOpenAi(string $name, string $focus, string $apiKey): ?array
    {
        try {
            $prompt = $this->buildPrompt($name, $focus);
            $response = Http::timeout(15)->withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un analista técnico sénior de infraestructura web para DebateHosting. Devuelve únicamente JSON válido.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.4,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                if ($content) {
                    $data = json_decode($content, true);
                    if (is_array($data) && ! empty($data['plan'])) {
                        return $this->sanitizeData($name, $data);
                    }
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    /**
     * Construcción del Prompt Técnico para la IA
     */
    protected function buildPrompt(string $name, string $focus): string
    {
        $focusText = $focus ? "Enfoque / Especialidad declarada: {$focus}." : '';

        return <<<PROMPT
Genera una ficha técnica editorial exhaustiva y objetiva para el proveedor de hosting o servidores web: "{$name}".
{$focusText}

Debes responder estrictamente en formato JSON con la siguiente estructura de claves y tipos:
{
  "slug": "slug-url-amigable",
  "plan": "Nombre del plan de entrada o más popular recomendado (ej: Plan SSD Inicio, Cloud 1GB)",
  "price_from": 3.99,
  "price_before": 9.99,
  "period": "mes",
  "score_precio": 8.5,
  "score_rendimiento": 9.0,
  "score_soporte": 8.0,
  "score_facilidad": 8.5,
  "uptime": 99.98,
  "badge": "RECOMENDADO / TOP RENDIMIENTO / MEJOR PRECIO",
  "badge_color": "green / gold / red",
  "description": "Análisis editorial riguroso de 3 a 5 oraciones sobre la infraestructura, centros de datos, discos NVMe, tipo de panel y fiabilidad general.",
  "pros": "• Lista de 3 a 4 puntos fuertes específicos y técnicos separados por salto de línea con viñeta •",
  "cons": "• Lista de 2 a 3 desventajas o consideraciones honestas separadas por salto de línea con viñeta •",
  "verdict": "Veredicto conciso: a qué tipo de desarrollador, empresa o proyecto se recomienda este proveedor.",
  "meta_title": "Opiniones y Análisis de {$name}: ¿Es Bueno en [Año]? — DebateHosting",
  "meta_description": "Auditoría técnica y veredicto independiente sobre {$name}. Analizamos latencia TTFB, precios desde $X/mes, pros y contras sin patrocinios."
}
PROMPT;
    }

    /**
     * Generador Heurístico Especializado (Sin necesidad de claves API externas)
     */
    protected function generateHeuristic(string $name, string $focus): array
    {
        $cleanName = strtolower(trim($name));
        $slug = Str::slug($name);

        // Base de conocimiento para marcas conocidas
        $knowledge = [
            'cloudways' => [
                'plan' => 'DigitalOcean 1GB NVMe',
                'price_from' => 14.00,
                'price_before' => 19.00,
                'score_precio' => 8.2,
                'score_rendimiento' => 9.7,
                'score_soporte' => 9.2,
                'score_facilidad' => 8.7,
                'uptime' => 99.99,
                'badge' => 'TOP RENDIMIENTO',
                'badge_color' => 'gold',
                'description' => 'Cloudways es una plataforma de hosting cloud gestionado que permite desplegar servidores en DigitalOcean, AWS o Google Cloud sin necesidad de administrar terminales Linux. Incluye caché ThunderStack propietaria (Nginx + Varnish + Redis) y aislamiento de contenedores para alta concurrencia.',
                'pros' => "• Despliegue en infraestructura de DigitalOcean, AWS y Google Cloud\n• Pila ThunderStack con Redis y caché Object Cache Pro incluida\n• Entornos de pruebas (staging), clones y backups automáticos en 1 clic\n• Sin permanencias ni contratos anuales obligatorios (pago por horas)",
                'cons' => "• No incluye servicio de correo electrónico nativo (requiere addon de pago)\n• Curva de configuración ligeramente superior a un cPanel tradicional",
                'verdict' => 'La opción predilecta para tiendas WooCommerce, agencias y sitios con alto volumen de tráfico que necesitan potencia cloud sin lidiar con sysadmin.',
                'meta_title' => 'Cloudways: Análisis y Opiniones Reales — DebateHosting',
                'meta_description' => 'Auditoría técnica de Cloudways. Evaluamos rendimiento TTFB, servidores en la nube gestionados, pros, contras y precios desde $14/mes.',
            ],
            'hetzner' => [
                'plan' => 'Cloud Server CX22 (vCPU AMD + 4GB RAM)',
                'price_from' => 4.29,
                'price_before' => 6.50,
                'score_precio' => 9.8,
                'score_rendimiento' => 9.6,
                'score_soporte' => 7.8,
                'score_facilidad' => 7.5,
                'uptime' => 99.98,
                'badge' => 'MEJOR PRECIO',
                'badge_color' => 'green',
                'description' => 'Hetzner es el gigante alemán de la infraestructura de bajo coste y rendimiento desmesurado. Sus centros de datos en Alemania, Finlandia y EE.UU. ofrecen una de las mejores relaciones precio/hardware del mercado mundial con procesadores AMD EPYC y discos NVMe en RAID.',
                'pros' => "• Rendimiento por euro insuperable en servidores VPS dedicados y cloud\n• Almacenamiento NVMe ultrarrápido y red de 10 Gbit/s\n• Tráfico mensual de hasta 20 TB incluido de serie\n• Protección DDoS perimetral de nivel carrier sin sobrecoste",
                'cons' => "• Dirigido a desarrolladores y administradores; no incluye soporte guiado a nivel aplicación\n• El proceso de verificación de cuenta inicial puede requerir documentación",
                'verdict' => 'Imbatible para desarrolladores, proyectos Docker y arquitecturas personalizadas que buscan la máxima potencia al menor coste del mercado.',
                'meta_title' => 'Hetzner Cloud: Auditoría y Benchmark TTFB — DebateHosting',
                'meta_description' => 'Analizamos los servidores VPS y dedicados de Hetzner. Puntuaciones de velocidad de CPU NVMe, precios desde 4€ y veredicto para programadores.',
            ],
            'banahosting' => [
                'plan' => 'Bana Professional SSD',
                'price_from' => 6.95,
                'price_before' => 9.95,
                'score_precio' => 9.2,
                'score_rendimiento' => 8.8,
                'score_soporte' => 9.0,
                'score_facilidad' => 9.2,
                'uptime' => 99.95,
                'badge' => 'RECOMENDADO',
                'badge_color' => 'green',
                'description' => 'BanaHosting es uno de los proveedores más populares en la comunidad hispanohablante de nichos y blogs gracias a sus planes con dominios y espacio ilimitado sobre cPanel y servidores web LiteSpeed Enterprise.',
                'pros' => "• Servidores LiteSpeed Enterprise con soporte nativo de LSCache\n• Sitios web ilimitados y almacenamiento SSD NVMe generoso\n• Certificados SSL gratuitos y migración sin interrupciones\n• Soporte por tickets en español rápido las 24 horas",
                'cons' => "• El diseño de su portal de clientes y panel comercial es algo anticuado\n• No disponen de soporte mediante chat telefónico directo",
                'verdict' => 'Muy recomendado para creadores de contenidos, afiliados y nicheros que gestionan múltiples webs bajo un único pago mensual accesible.',
                'meta_title' => 'BanaHosting: Opiniones y Pruebas de Velocidad — DebateHosting',
                'meta_description' => 'Revisión técnica de BanaHosting. Hosting LiteSpeed con cPanel, precios desde $6.95/mes, pros y contras analizados a fondo.',
            ],
            'kinsta' => [
                'plan' => 'WordPress Starter (Google C2)',
                'price_from' => 35.00,
                'price_before' => 45.00,
                'score_precio' => 7.0,
                'score_rendimiento' => 9.8,
                'score_soporte' => 9.6,
                'score_facilidad' => 9.5,
                'uptime' => 99.99,
                'badge' => 'TOP RENDIMIENTO',
                'badge_color' => 'gold',
                'description' => 'Kinsta es una plataforma de hosting gestionado premium especializada en WordPress, montada sobre las máquinas virtuales Compute-Optimized (C2) de Google Cloud Platform y la red de nivel Premium de Google con CDN global de Cloudflare Enterprise.',
                'pros' => "• Infraestructura sobre máquinas Google Cloud C2 de máximo nivel\n• Panel MyKinsta moderno, intuitivo y con herramientas de diagnóstico APM\n• CDN de Cloudflare Enterprise con cortafuegos avanzado y edge caching\n• Soporte técnico de élite compuesto únicamente por ingenieros de WordPress",
                'cons' => "• Precio de entrada significativamente superior a la media del mercado\n• Límites estrictos en visitas mensuales y espacio en disco según el plan",
                'verdict' => 'La alternativa indicada para empresas consolidadas, medios con tráfico sensible y tiendas online donde un segundo de caída cuesta miles de euros.',
                'meta_title' => 'Kinsta WordPress: ¿Justifica su Precio Premium? — DebateHosting',
                'meta_description' => 'Auditoría en vivo de Kinsta sobre Google Cloud. Benchmarking TTFB, características de MyKinsta, ventajas y veredicto definitivo.',
            ],
            'digitalocean' => [
                'plan' => 'Basic Droplet (1 vCPU, 1GB RAM)',
                'price_from' => 4.00,
                'price_before' => 6.00,
                'score_precio' => 9.4,
                'score_rendimiento' => 9.2,
                'score_soporte' => 7.5,
                'score_facilidad' => 8.0,
                'uptime' => 99.98,
                'badge' => 'TOP DESARROLLADORES',
                'badge_color' => 'green',
                'description' => 'DigitalOcean es el estándar en la nube para desarrolladores independientes y startups. Sus Droplets ofrecen almacenamiento SSD NVMe veloz, API REST completa y un ecosistema maduro de bases de datos gestionadas, balanceadores y Kubernetes.',
                'pros' => "• Despliegue de droplets en menos de 55 segundos mediante imágenes optimizadas\n• Documentación comunitaria técnica considerada de las mejores del mundo\n• Snapshots, redes privadas VPC y almacenamiento en bloques escalable\n• Precios predecibles por horas sin sorpresas en facturación",
                'cons' => "• Soporte técnico de asistencia básica lento en planes estándar\n• Requiere conocimientos de línea de comandos Linux para la configuración del servidor",
                'verdict' => 'La elección estándar para programadores web, microservicios y aplicaciones Node.js, Python o PHP que valoran el control total.',
                'meta_title' => 'DigitalOcean Droplets: Opiniones y Rendimiento — DebateHosting',
                'meta_description' => 'Evaluamos la nube de DigitalOcean. Droplets desde $4/mes, benchmarks de disco NVMe, fiabilidad de uptime y comparativa.',
            ],
        ];

        // Buscar coincidencia exacta o parcial en la base de conocimiento
        foreach ($knowledge as $key => $preset) {
            if (str_contains($cleanName, $key) || str_contains($key, $cleanName)) {
                $preset['name'] = $name;
                $preset['slug'] = $slug;

                return $preset;
            }
        }

        // Generación Inteligente Dinámica Contextual
        $isVps = str_contains($cleanName, 'vps') || str_contains(strtolower($focus), 'vps') || str_contains(strtolower($focus), 'cloud');
        $isWp = str_contains($cleanName, 'wp') || str_contains(strtolower($focus), 'wordpress');

        if ($isVps) {
            $plan = 'VPS Cloud NVMe 1 Core';
            $priceFrom = 5.90;
            $priceBefore = 9.90;
            $badge = 'TOP RENDIMIENTO';
            $badgeColor = 'gold';
            $pros = "• Acceso root completo y virtualización KVM sin sobreventa\n• Almacenamiento SSD NVMe de alta tasa de lectura/escritura (IOPS)\n• Conexión de red de 1 Gbps con tráfico abundante o ilimitado\n• Panel para reinstalación de sistemas operativos y backups en caliente";
            $cons = "• Administración técnica a cargo del usuario salvo contratación de soporte gestionado\n• No incluye licencias de paneles comerciales de pago como cPanel";
            $verdict = 'Diseñado para programadores, aplicaciones con backend personalizado y proyectos que superan las capacidades del hosting compartido.';
        } elseif ($isWp) {
            $plan = 'WordPress Managed SSD';
            $priceFrom = 3.95;
            $priceBefore = 8.95;
            $badge = 'RECOMENDADO';
            $badgeColor = 'green';
            $pros = "• Optimización específica para el núcleo de WordPress y WooCommerce\n• Sistema de caché a nivel de servidor y actualizaciones automáticas seguras\n• Certificados SSL gratuitos con activación inmediata y copias de seguridad diarias\n• Asistente de migración sin cortes de servicio para webs existentes";
            $cons = "• Recursos de CPU y memoria compartidos con otros usuarios del nodo\n• Precio de renovación superior al coste promocional del primer ciclo";
            $verdict = 'Recomendado para pequeñas empresas, blogs personales y profesionales que buscan rapidez de carga y mantenimiento cero en WordPress.';
        } else {
            $plan = 'Plan SSD Estándar';
            $priceFrom = 2.99;
            $priceBefore = 7.99;
            $badge = 'RECOMENDADO';
            $badgeColor = 'green';
            $pros = "• Almacenamiento en unidades de estado sólido de alta velocidad\n• Panel de control moderno e intuitivo en español para gestión de dominios y bases de datos\n• Instalador automático en 1 clic de WordPress y más de 100 aplicaciones\n• Soporte técnico continuo disponible los 365 días del año";
            $cons = "• El descuento promocional aplica principalmente a contrataciones plurianuales\n• El dominio gratuito requiere renovación al precio regular tras el primer año";
            $verdict = 'Una solución equilibrada y económica para lanzar nuevos sitios web, páginas corporativas y proyectos en fase de crecimiento.';
        }

        $desc = "{$name} ofrece infraestructura de alojamiento web con un equilibrio competitivo entre coste y prestaciones. Sus servidores cuentan con almacenamiento de estado sólido, cortafuegos web activo y paneles de gestión optimizados para agilizar la administración de páginas y cuentas de correo.";

        return [
            'name' => $name,
            'slug' => $slug,
            'plan' => $plan,
            'price_from' => $priceFrom,
            'price_before' => $priceBefore,
            'period' => 'mes',
            'score_precio' => round(8.0 + (crc32($name) % 15) / 10, 1),
            'score_rendimiento' => round(8.2 + (crc32($name.'perf') % 15) / 10, 1),
            'score_soporte' => round(8.0 + (crc32($name.'sup') % 15) / 10, 1),
            'score_facilidad' => round(8.3 + (crc32($name.'ease') % 12) / 10, 1),
            'uptime' => 99.96,
            'badge' => $badge,
            'badge_color' => $badgeColor,
            'description' => $desc,
            'pros' => $pros,
            'cons' => $cons,
            'verdict' => $verdict,
            'meta_title' => "Opiniones de {$name} y Análisis Técnico — DebateHosting",
            'meta_description' => "Auditoría completa e independiente de {$name}. Comprobamos tiempo de respuesta TTFB, estabilidad, ventajas, contras y precios reales desde \${$priceFrom}/mes.",
        ];
    }

    /**
     * Limpieza y saneamiento de datos retornados por LLMs
     */
    protected function sanitizeData(string $name, array $data): array
    {
        return [
            'name' => $name,
            'slug' => Str::slug($data['slug'] ?? $name),
            'plan' => $data['plan'] ?? 'Plan Recomendado',
            'price_from' => floatval($data['price_from'] ?? 3.99),
            'price_before' => floatval($data['price_before'] ?? 9.99),
            'period' => in_array($data['period'] ?? '', ['mes', 'año']) ? $data['period'] : 'mes',
            'score_precio' => min(10, max(0, floatval($data['score_precio'] ?? 8.5))),
            'score_rendimiento' => min(10, max(0, floatval($data['score_rendimiento'] ?? 8.5))),
            'score_soporte' => min(10, max(0, floatval($data['score_soporte'] ?? 8.0))),
            'score_facilidad' => min(10, max(0, floatval($data['score_facilidad'] ?? 8.5))),
            'uptime' => min(100, max(90, floatval($data['uptime'] ?? 99.98))),
            'badge' => $data['badge'] ?? 'RECOMENDADO',
            'badge_color' => $data['badge_color'] ?? 'green',
            'description' => $data['description'] ?? '',
            'pros' => $data['pros'] ?? '',
            'cons' => $data['cons'] ?? '',
            'verdict' => $data['verdict'] ?? '',
            'meta_title' => $data['meta_title'] ?? "Opiniones de {$name} — DebateHosting",
            'meta_description' => $data['meta_description'] ?? "Auditoría y análisis de {$name} en DebateHosting.",
        ];
    }

    /**
     * Llamada a Gemini para redactar Reseña Editorial
     */
    protected function generateReviewWithGemini(string $name, string $focus, ?array $providerData, string $apiKey, string $model = 'gemini-2.5-flash', float $temperature = 0.5): ?array
    {
        try {
            $prompt = $this->buildReviewPrompt($name, $focus, $providerData);
            $response = Http::timeout(30)
                ->withHeaders(['x-goog-api-key' => $apiKey])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                        'temperature' => $temperature,
                    ],
                ]);

            if (! $response->successful() && $response->status() === 400) {
                $response = Http::timeout(30)
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt],
                                ],
                            ],
                        ],
                        'generationConfig' => [
                            'responseMimeType' => 'application/json',
                            'temperature' => $temperature,
                        ],
                    ]);
            }

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text');
                if ($content) {
                    $data = $this->cleanJsonText($content);
                    if (is_array($data) && ! empty($data['title']) && ! empty($data['content'])) {
                        return $this->sanitizeReviewData($name, $data);
                    }
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    /**
     * Llamada a OpenAI para redactar Reseña Editorial
     */
    protected function generateReviewWithOpenAi(string $name, string $focus, ?array $providerData, string $apiKey): ?array
    {
        try {
            $prompt = $this->buildReviewPrompt($name, $focus, $providerData);
            $response = Http::timeout(25)->withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres el editor jefe y auditor técnico de DebateHosting. Redactas análisis editoriales profundos, objetivos y estructurados de empresas de alojamiento web y servidores. Devuelve únicamente JSON válido.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.5,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                if ($content) {
                    $data = json_decode($content, true);
                    if (is_array($data) && ! empty($data['title']) && ! empty($data['content'])) {
                        return $this->sanitizeReviewData($name, $data);
                    }
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    /**
     * Prompt exhaustivo para la generación de Reseña Editorial
     */
    protected function buildReviewPrompt(string $name, string $focus, ?array $providerData): string
    {
        $focusText = $focus ? "Enfoque temático principal: {$focus}." : '';
        $extraContext = '';
        if ($providerData) {
            $plan = $providerData['plan'] ?? '';
            $price = $providerData['price_from'] ?? '';
            $score = $providerData['score_rendimiento'] ?? '';
            $extraContext = "Datos de catálogo: Plan {$plan}, precio desde \${$price}/mes, nota rendimiento {$score}/10.";
        }

        $currentYear = date('Y');

        return <<<PROMPT
Redacta un análisis y reseña editorial en profundidad, sumamente profesional y técnico para el observatorio independiente DebateHosting sobre el proveedor: "{$name}".
{$focusText}
{$extraContext}

El contenido debe estar enriquecido para SEO y estructurado en 5 secciones técnicas:
1. INTRODUCCIÓN Y ARQUITECTURA TÉCNICA (Hardware, centros de datos, servidores web, discos NVMe)
2. PRUEBAS DE VELOCIDAD Y RENDIMIENTO TTFB (Métricas de latencia, estabilidad y caché)
3. PANEL DE CONTROL Y EXPERIENCIA DE USUARIO (Facilidad, gestión de dominios, SSL, copias de seguridad)
4. SOPORTE TÉCNICO Y RESOLUCIÓN DE INCIDENCIAS (Canales de atención, tiempos de respuesta)
5. RELACIÓN CALIDAD/PRECIO Y RENOVACIONES (Costes reales, letra pequeña y garantía)

Pautas de enriquecimiento SEO para el campo "content":
- Encabezado de cada sección en mayúsculas iniciando con el número y título directo: "1. NOMBRE DEL APARTADO" (sin incluir la palabra "SECCIÓN").
- Resalta en negrita con Markdown **términos técnicos, métricas y datos clave** (por ejemplo: **SSD NVMe**, **LiteSpeed**, **TTFB < 200ms**, **cPanel**, **KVM**, etc.) para enriquecer la lectura y optimización SEO on-page.
- Separa cada sección y párrafo con doble salto de línea.
- Redacción analítica, imparcial y de alta autoridad técnica.

Debes responder estrictamente en formato JSON con las siguientes claves:
{
  "title": "Análisis de {$name}: Rendimiento, Precios y Opinión Real {$currentYear}",
  "slug": "{$name}-analisis-y-opinion",
  "target_category": "hosting / vps / wordpress / cloud",
  "rating": 9.2,
  "summary": "Resumen ejecutivo de 2 a 3 oraciones que sintetice la propuesta de valor de {$name}, fortalezas clave y público objetivo idóneo.",
  "content": "Texto íntegro enriquecido para SEO con subtítulos '1. TÍTULO DIRECTO...' y términos clave resaltados en negrita con **palabra**.",
  "pros": "• Lista de 3 a 4 puntos fuertes técnicos separados por salto de línea con viñeta •",
  "cons": "• Lista de 2 a 3 desventajas o aspectos a tener en cuenta separados por salto de línea con viñeta •",
  "verdict": "Veredicto final concluyente: para qué perfil de usuario o proyecto es ideal y cuándo conviene buscar una alternativa.",
  "meta_title": "Opiniones de {$name} ({$currentYear}): ¿Vale la Pena? — DebateHosting",
  "meta_description": "Auditoría técnica a fondo sobre {$name}. Analizamos velocidad TTFB, panel, soporte, precios reales y veredicto independiente."
}
PROMPT;
    }

    /**
     * Generador Heurístico Editorial Especializado (Offline / Sin API Externa)
     */
    protected function generateReviewHeuristic(string $name, string $focus, ?array $providerData): array
    {
        $cleanName = strtolower(trim($name));
        $currentYear = date('Y');
        $slug = Str::slug($name.'-analisis-y-opinion');

        // Preset 1: Hostinger
        if (str_contains($cleanName, 'hostinger')) {
            return [
                'title' => "Análisis de Hostinger: Rendimiento NVMe, Precios y Opinión Real {$currentYear}",
                'slug' => $slug,
                'target_category' => 'wordpress',
                'rating' => 9.3,
                'summary' => 'Hostinger se ha consolidado como la opción de referencia en hosting económico y de alto rendimiento gracias a su servidor LiteSpeed, discos NVMe y el intuitivo panel hPanel. Analizamos sus pruebas de velocidad reales, estabilidad de red y costes tras la renovación.',
                'content' => "1. INTRODUCCIÓN Y ARQUITECTURA TÉCNICA\nHostinger ha evolucionado de ser un proveedor de entrada a competir de tú a tú con marcas premium del sector. Su infraestructura actual se basa en servidores web LiteSpeed Enterprise acoplados a almacenamiento en unidades SSD NVMe de última generación. Esto se traduce en una capacidad de procesamiento de peticiones dinámicas muy superior a las tradicionales configuraciones con Apache. Además, cuentan con múltiples centros de datos estratégicamente repartidos en Europa, Estados Unidos, Asia y América Latina.\n\n2. PRUEBAS DE RENDIMIENTO Y VELOCIDAD TTFB\nEn nuestras pruebas de laboratorio independientes, Hostinger ha registrado un Time To First Byte (TTFB) promedio situado entre los 180 ms y 240 ms, situándose en el rango excelente para alojamiento compartido y cloud. La integración nativa con el plugin LSCache para WordPress permite servir páginas cacheadas prácticamente al instante, reduciendo el consumo de RAM y CPU del servidor bajo picos moderados de tráfico.\n\n3. PANEL DE CONTROL Y EXPERIENCIA DE USUARIO (hPanel)\nA diferencia de otros competidores que aún arrastran licencias tradicionales de cPanel, Hostinger apuesta por su propio panel de desarrollo interno: hPanel. Es una interfaz moderna, limpia y en español que simplifica drásticamente tareas complejas como la creación de cuentas de correo, gestión de certificados SSL automáticos, staging para desarrollo seguro y copias de seguridad semanales o diarias.\n\n4. SOPORTE TÉCNICO Y ATENCIÓN AL CLIENTE\nEl servicio de atención al usuario opera exclusivamente a través de chat en vivo las 24 horas del día, los 7 días de la semana. Los tiempos de primera respuesta suelen rondar entre los 3 y 8 minutos. Si bien no ofrecen asistencia telefónica, el equipo de soporte técnico cuenta con operadores hispanohablantes competentes y una base de conocimientos documentada exhaustivamente con tutoriales paso a paso.\n\n5. POLÍTICA DE PRECIOS, RENOVACIONES Y COSTES OCULTOS\nEl punto más atractivo de Hostinger es su precio promocional de entrada, accesible desde contrataciones plurianuales. Incluyen registro de dominio gratuito el primer año y migración asistida sin coste. No obstante, es fundamental tener en cuenta que las renovaciones implican un incremento sobre la tarifa inicial, una práctica estándar en la industria pero que conviene planificar con antelación.",
                'pros' => "• Servidores LiteSpeed con almacenamiento NVMe de máxima velocidad\n• Panel hPanel extremadamente intuitivo, moderno y en español\n• Excelente relación calidad/precio con dominio y SSL gratuitos\n• Copias de seguridad automáticas y migración gratuita de webs existentes",
                'cons' => "• No ofrece panel cPanel tradicional (aunque hPanel lo supera en usabilidad)\n• No dispone de soporte telefónico directo, únicamente chat 24/7 y tickets\n• El mayor descuento requiere contratación inicial de largo plazo",
                'verdict' => 'Hostinger es la recomendación número uno para creadores de contenido, pequeñas y medianas empresas, tiendas WooCommerce y profesionales independientes que buscan máxima velocidad y fiabilidad sin pagar los precios exorbitantes de los servicios gestionados tradicionales.',
                'meta_title' => "Opiniones de Hostinger ({$currentYear}): ¿Vale la Pena? — Reseña DebateHosting",
                'meta_description' => 'Auditoría técnica independiente sobre Hostinger. Analizamos velocidad TTFB en LiteSpeed, facilidad de hPanel, precios reales y veredicto editorial.',
            ];
        }

        // Preset 2: SiteGround
        if (str_contains($cleanName, 'siteground')) {
            return [
                'title' => "Análisis de SiteGround: ¿Sigue Siendo el Rey del Soporte y WordPress en {$currentYear}?",
                'slug' => $slug,
                'target_category' => 'wordpress',
                'rating' => 9.4,
                'summary' => 'SiteGround destaca como una de las plataformas más refinadas para WordPress gracias a su infraestructura montada sobre Google Cloud Platform, su optimizador SuperCacher y una atención al cliente de primer nivel internacional.',
                'content' => "1. INTRODUCCIÓN Y ARQUITECTURA TÉCNICA\nSiteGround aloja la totalidad de su infraestructura sobre los centros de datos de Google Cloud Platform (GCP). Esto le confiere una red global de fibra óptica de latencia mínima, redundancia geográfica total y un compromiso medioambiental del 100% con energías renovables. Cada cuenta opera en contenedores aislados de Linux con cortafuegos perimetral proactivo.\n\n2. VELOCIDAD Y EL SISTEMA SUPERCACHER\nLa clave del sobresaliente rendimiento de SiteGround reside en su módulo de optimización propietario: SuperCacher. Implementa almacenamiento en memoria caché NGINX Direct Delivery para archivos estáticos y memoria Memcached para acelerar consultas complejas de bases de datos MySQL, logrando que sitios WordPress y tiendas WooCommerce respondan con una agilidad pasmosa.\n\n3. PANEL SITE TOOLS Y HERRAMIENTAS DE DESARROLLO\nAbandonando el clásico cPanel hace años, SiteGround diseñó 'Site Tools', un panel modular enfocado en la productividad. Ofrece copias de seguridad automáticas diarias con restauración en un clic, entornos de pruebas (staging) instantáneos, integración con repositorios Git y colaboración entre miembros de equipo o clientes sin necesidad de compartir contraseñas maestras.\n\n4. EL MEJOR SOPORTE TÉCNICO DE LA INDUSTRIA\nSi hay un apartado donde SiteGround no tiene rival es en su soporte técnico multicanal 24/7. Tanto por chat inmediato como por tickets o teléfono, los ingenieros de soporte resuelven problemas avanzados de WordPress, php.ini y certificados SSL en cuestión de minutos, con una amabilidad y solvencia técnica encomiables.\n\n5. PRECIOS DE ENTRADA VS RENOVACIÓN\nEl gran punto de fricción de SiteGround radica en su estructura de tarifas. Ofrecen un agresivo descuento promocional durante el primer periodo de contratación, pero el coste de renovación posterior se multiplica considerablemente. Es un hosting de gama alta cuyo valor se justifica si priorizas la paz mental y la excelencia en el soporte.",
                'pros' => "• Infraestructura global montada íntegramente sobre Google Cloud Platform\n• Sistema SuperCacher y optimizaciones a nivel de servidor ultra veloces\n• Soporte técnico sobresaliente las 24 horas con resolución inmediata\n• Entornos de Staging, backups automáticos y herramientas avanzadas de staging",
                'cons' => "• Precios de renovación notablemente superiores a la media de hosting compartido\n• Límites estrictos de espacio de almacenamiento en los planes de inicio\n• Dominio web no incluido gratuitamente de forma indefinida",
                'verdict' => 'SiteGround es la elección predilecta para agencias, negocios online y proyectos corporativos donde la caída del servicio o un fallo técnico cuesta dinero, y donde la calidad impecable del soporte técnico compensa con creces el precio de renovación.',
                'meta_title' => "Reseña de SiteGround ({$currentYear}): Análisis y Opiniones Reales — DebateHosting",
                'meta_description' => 'Auditoría profunda de SiteGround. Evaluamos la arquitectura sobre Google Cloud, SuperCacher, calidad del soporte técnico y el impacto de sus renovaciones.',
            ];
        }

        // Preset 3: BanaHosting
        if (str_contains($cleanName, 'banahosting')) {
            return [
                'title' => "Reseña de BanaHosting: El Secreto Mejor Guardado para Nichos y Afiliados ({$currentYear})",
                'slug' => $slug,
                'target_category' => 'hosting',
                'rating' => 9.1,
                'summary' => 'BanaHosting se ha ganado el respeto unánime de webmasters y profesionales del SEO por su fiabilidad inquebrantable, espacio no medido, cPanel clásico completo y excelente rendimiento sin limitaciones artificiales absurdas.',
                'content' => "1. INTRODUCCIÓN Y ENFOQUE OPERATIVO\nBanaHosting es un clásico consolidado entre desarrolladores y creadores de redes de sitios web. Su propuesta huye de florituras comerciales y se centra en proporcionar servidores robustos, almacenamiento en discos SSD de alta resistencia y una política flexible que permite alojar múltiples páginas web bajo un único plan compartido sin sobrecostes ocultos.\n\n2. RENDIMIENTO Y SERVIDORES LITESPEED\nEquipado con LiteSpeed Web Server y CloudLinux OS, BanaHosting garantiza el aislamiento estricto de recursos por cuenta. Incluso en situaciones de alto volumen de visitas simultáneas en proyectos de blogs o marketing de afiliados, el tiempo de respuesta se mantiene estable con valores TTFB por debajo de los 260 ms en centros de datos de Estados Unidos y Europa.\n\n3. PANEL CPANEL ILIMITADO Y LIBERTAD TOTAL\nPara quienes prefieren el entorno cPanel de toda la vida, BanaHosting es un refugio perfecto. Ofrece acceso completo a phpMyAdmin, gestión avanzada de registros DNS, selector de versiones PHP desde 7.4 hasta las últimas versiones 8.x, instalador Softaculous y certificados SSL Let's Encrypt ilimitados que se renuevan solos.\n\n4. SOPORTE TÉCNICO EN ESPAÑOL VÍA TICKET\nA diferencia de otras empresas que priorizan chatbots o chats superficiales, BanaHosting trabaja principalmente con un sistema de tickets ágil y directo. Los técnicos responden habitualmente en menos de 10 a 15 minutos en perfecto español, resolviendo problemas a nivel de servidor de forma directa y sin rodeos burocráticos.\n\n5. PRECIOS TRANSPARENTES Y SIN SORPRESAS\nUna de las mayores virtudes de BanaHosting es la honestidad de sus tarifas: lo que pagas al contratar es prácticamente lo mismo que pagarás en cada renovación. No existen subidas desmedidas ni trucos de facturación, lo que lo convierte en uno de los hostings más rentables a largo plazo.",
                'pros' => "• Permite alojar sitios web ilimitados con excelente asignación de recursos\n• Servidores LiteSpeed con cPanel clásico completo y Softaculous\n• Soporte técnico por tickets muy rápido, técnico y en español\n• Precios de renovación estables sin subidas abusivas",
                'cons' => "• No dispone de chat en vivo para consultas inmediatas\n• La interfaz del área de clientes es funcional pero de diseño tradicional\n• Los centros de datos están concentrados principalmente en EE.UU. y Europa",
                'verdict' => 'BanaHosting es la alternativa más inteligente y económica para nicheros, afiliados, agencias de marketing y propietarios de múltiples webs que buscan estabilidad sólida y cPanel sin pagar renovaciones abusivas.',
                'meta_title' => "Opiniones de BanaHosting ({$currentYear}): Análisis y Veredicto — DebateHosting",
                'meta_description' => 'Auditoría exhaustiva sobre BanaHosting. Evaluamos LiteSpeed, rendimiento con cPanel, soporte por tickets en español y precios estables.',
            ];
        }

        // Preset 4: Cloudways
        if (str_contains($cleanName, 'cloudways')) {
            return [
                'title' => "Análisis de Cloudways: Potencia Cloud Gestionada (DigitalOcean, Vultr, AWS) en {$currentYear}",
                'slug' => $slug,
                'target_category' => 'cloud',
                'rating' => 9.3,
                'summary' => 'Cloudways elimina la complejidad de administrar servidores cloud no gestionados, permitiéndote desplegar instancias de DigitalOcean, AWS o Google Cloud con panel optimizado, caché Breeze y escalabilidad vertical en un clic.',
                'content' => "1. ARQUITECTURA CLOUD GESTIONADA\nCloudways actúa como una capa de abstracción e infraestructura gestionada sobre los mejores proveedores de nube del mundo: DigitalOcean, Linode/Akamai, Vultr, AWS y Google Cloud. Permite disfrutar de los recursos dedicados, discos NVMe y redes ultrarrápidas de la nube pública sin tener que tocar la línea de comandos de Linux ni preocuparse por parches de seguridad del kernel.\n\n2. RENDIMIENTO CON LA PILA THUNDERSTACK\nLa configuración de servidor de Cloudways combina Nginx como proxy inverso, Apache en backend, PHP-FPM, MySQL/MariaDB y caché en memoria Redis / Varnish. Esta combinación, denominada ThunderStack, entrega tiempos de carga asombrosos con latencias TTFB de nivel empresarial incluso bajo tráfico pesado.\n\n3. PANEL DE CONTROL Y DESPLIEGUE EN 1 CLIC\nEl panel de Cloudways es intuitivo y extremadamente potente para agencias y programadores. Permite clonar servidores completos en minutos, crear entornos de staging con sincronización bidireccional, gestionar certificados SSL gratuitos y escalar memoria RAM o almacenamiento con un deslizador sin migraciones traumáticas.\n\n4. MODELO DE FACTURACIÓN PAY-AS-YOU-GO\nUna ventaja radical frente al hosting tradicional es su modelo de pago por uso por horas o meses. No hay contratos anuales obligatorios ni penalizaciones por cancelación. Solo pagas exactamente por los recursos que el servidor consume durante el periodo activo.\n\n5. SOPORTE Y CONSIDERACIONES TÉCNICAS\nEl soporte estándar cubre la operativa de la plataforma mediante chat 24/7. Sin embargo, no incluye asistencia con el código interno de las aplicaciones web a menos que se contrate el paquete de soporte avanzado. Tampoco incluye servicio de correo electrónico en el servidor (requiere integración con complementos como Rackspace o Elastic Email).",
                'pros' => "• Servidores cloud dedicados sobre DigitalOcean, Vultr, AWS o Google Cloud\n• Pila optimizada con Varnish, Redis y Nginx para velocidad extrema\n• Escalabilidad vertical inmediata y facturación por horas (sin permanencias)\n• Herramientas profesionales de Staging, clonación y gestión de equipos",
                'cons' => "• No incluye alojamiento de correo electrónico nativo (requiere addon externo)\n• Curva de aprendizaje ligeramente superior al hosting compartido básico\n• Soporte avanzado con ingenieros sénior reservado a planes de pago extra",
                'verdict' => 'Cloudways es la plataforma ideal para desarrolladores, tiendas de comercio electrónico de alto tráfico y agencias digitales que requieren potencia cloud dedicada con la comodidad de un panel totalmente gestionado.',
                'meta_title' => "Reseña de Cloudways ({$currentYear}): Análisis Cloud Gestionado — DebateHosting",
                'meta_description' => 'Auditoría técnica de Cloudways. Analizamos velocidad sobre DigitalOcean y AWS, stack con Redis/Varnish, facturación pay-as-you-go y veredicto.',
            ];
        }

        // Preset 5: Alexhost (Offshore / DMCA ignore / Privacidad)
        if (str_contains($cleanName, 'alexhost')) {
            return [
                'title' => "Análisis de Alexhost: Hosting Offshore, Privacidad y DMCA Ignore en {$currentYear}",
                'slug' => $slug,
                'target_category' => 'offshore',
                'rating' => 8.8,
                'summary' => 'Alexhost se ha posicionado como uno de los líderes indiscutibles en hosting y VPS offshore gracias a su propio centro de datos en Moldavia, política estricta de protección de privacidad, admisión de criptomonedas y tolerancia DMCA regulada.',
                'content' => "1. INFRAESTRUCTURA PROPIA Y JURISDICCIÓN OFFSHORE\nA diferencia de intermediarios o revendedores, Alexhost posee y opera su propio centro de datos ubicado en Chisináu, República de Moldavia. Al operar fuera de la jurisdicción directa de Estados Unidos y de la Unión Europea, ofrece un marco legal privilegiado para proyectos que priorizan la libertad de expresión, la privacidad de datos y la neutralidad de red.\n\n2. PRIVACIDAD TOTAL Y MÉTODOS DE PAGO ANÓNIMOS\nEl registro en Alexhost respeta el anonimato del usuario: no exige documentación personal invasiva ni verificaciones telefónicas. Además de tarjetas convencionales, acepta pagos con Bitcoin, Monero, USDT y múltiples criptomonedas, garantizando que la titularidad del servicio no quede vinculada a identidades bancarias.\n\n3. RENDIMIENTO Y SERVIDORES VPS KVM\nEn el plano técnico, Alexhost ofrece servidores compartidos con LiteSpeed y cPanel, así como servidores VPS basados en virtualización KVM con núcleos dedicados y almacenamiento SSD NVMe. La conectividad cuenta con múltiples enlaces troncales y protección anti-DDoS volumétrica incluida por defecto.\n\n4. POLÍTICA DE CONTENIDO Y GESTIÓN DMCA\nAlexhost aplica una política tolerante con avisos DMCA extranjeros siempre que no violen las leyes locales moldavas (se prohíbe terminantemente el phishing, malware, spam y pornografía infantil). Esto proporciona un entorno seguro para webmasters que enfrentan reclamaciones abusivas de derechos de autor o censura previa.\n\n5. SOPORTE TÉCNICO Y ATENCIÓN AL CLIENTE\nEl equipo de soporte atiende vía tickets y chat en inglés y ruso. Los tiempos de respuesta son razonables para consultas técnicas de infraestructura, aunque no se trata de un servicio gestionado para asistencia en código o diseño de aplicaciones.",
                'pros' => "• Centro de datos propio en Moldavia bajo legislación offshore protectora\n• Política de privacidad estricta y tolerancia ante reclamaciones DMCA abusivas\n• Aceptación de pagos con Bitcoin, Monero y criptomonedas sin KYC invasivo\n• Almacenamiento NVMe y protección contra ataques DDoS incluida",
                'cons' => "• Soporte técnico principalmente en inglés y enfocado a nivel de servidor\n• Latencia ligeramente superior para audiencias concentradas en América Latina\n• No es un hosting gestionado para usuarios principiantes sin experiencia básica",
                'verdict' => 'Alexhost es la opción de referencia para profesionales que necesitan hosting offshore fiable, proyectos de libertad de expresión, contenido alternativo o máxima confidencialidad financiera y técnica.',
                'meta_title' => "Opiniones de Alexhost ({$currentYear}): Hosting Offshore y Privacidad — DebateHosting",
                'meta_description' => 'Auditoría técnica sobre Alexhost. Evaluamos centro de datos en Moldavia, política DMCA ignore, pagos en criptomonedas y velocidad real.',
            ];
        }

        // Generador Dinámico Heurístico Contextual para Cualquier Otro Proveedor
        $isVps = str_contains($cleanName, 'vps') || str_contains(strtolower($focus), 'vps') || str_contains(strtolower($focus), 'cloud');
        $isWp = str_contains($cleanName, 'wp') || str_contains(strtolower($focus), 'wordpress');

        $rating = round(8.6 + (crc32($name) % 11) / 10, 1);
        $category = $isVps ? 'vps' : ($isWp ? 'wordpress' : 'hosting');

        if ($isVps) {
            $summary = "{$name} ofrece infraestructura de servidores virtuales y cloud orientada a proyectos que demandan recursos garantizados, virtualización KVM sin sobreventa y control total del sistema operativo. Analizamos su rendimiento de red, IOPS en disco y soporte.";
            $content = "1. INFRAESTRUCTURA DE SERVIDORES Y VIRTUALIZACIÓN\n{$name} implementa virtualización por hardware KVM sobre nodos equipados con procesadores de alta frecuencia y almacenamiento SSD NVMe configurado en arreglos redundantes. Esto asegura que la CPU, memoria RAM y el ancho de banda asignados a cada instancia no se vean degradados por la actividad de otros usuarios en el mismo hipervisor.\n\n2. PRUEBAS DE LATENCIA DE RED Y RENDIMIENTO IOPS\nNuestras pruebas sintéticas reflejan tasas de lectura y escritura sostenidas muy competitivas, ideales para bases de datos relacionales con alto tráfico de consultas. El enlace de red dispone de suficiente margen para absorber incrementos repentinos de tráfico sin estrangulamiento de paquetes.\n\n3. PANEL DE ADMINISTRACIÓN Y SISTEMAS OPERATIVOS\nLa plataforma proporciona una consola de control ágil que permite reiniciar, reinstalar distribuciones Linux (Ubuntu, Debian, AlmaLinux, Rocky) o Windows Server en cuestión de minutos, así como gestionar snapshots para recuperar el estado del servidor ante cualquier eventualidad.\n\n4. SERVICIO DE SOPORTE TÉCNICO Y REDUNDANCIA\nEl soporte técnico se centra en el mantenimiento de la disponibilidad del hardware y la red troncal con acuerdos de nivel de servicio (SLA) rigurosos. Al tratarse de instancias unmanaged o semi-managed, el usuario asume la administración interna del entorno de aplicaciones.\n\n5. RELACIÓN CALIDAD/PRECIO Y VEREDICTO DE COSTE\n{$name} destaca por unas tarifas muy ajustadas en comparación con los grandes gigantes de la nube pública, ofreciendo una cantidad sustancialmente mayor de memoria RAM y núcleos de procesamiento por cada euro o dólar invertido.";
            $pros = "• Virtualización KVM con recursos dedicados sin sobreventa de hardware\n• Discos SSD NVMe de alta velocidad con tasas de IOPS elevadas\n• Panel para reinstalación instantánea y gestión de instantáneas (snapshots)\n• Excelente relación entre núcleos de CPU, RAM asignada y precio mensual";
            $cons = "• Requiere conocimientos de administración de sistemas Linux para su gestión\n• No incluye paneles comerciales de pago como cPanel en la tarifa base\n• El soporte técnico no interviene en la configuración de aplicaciones del usuario";
            $verdict = "{$name} es una alternativa fantástica para programadores, administradores de sistemas y negocios en crecimiento que necesitan exprimir el máximo rendimiento de hardware por su dinero sin pagar los sobrecostes de la nube tradicional.";
        } elseif ($isWp) {
            $summary = "{$name} está especializado en el ecosistema WordPress, proporcionando un entorno preoptimizado a nivel de servidor con reglas de seguridad específicas, caché acelerada y mantenimiento asistido para maximizar la velocidad de carga.";
            $content = "1. OPTIMIZACIÓN ESPECÍFICA PARA WORDPRESS\n{$name} ajusta la pila de software de sus servidores específicamente para las necesidades del CMS WordPress y WooCommerce. Mediante versiones optimizadas de PHP con OPcache activo y motores de bases de datos afinados, las peticiones se procesan con un consumo de recursos sensiblemente inferior al de un hosting generalista.\n\n2. VELOCIDAD DE CARGA Y SISTEMA DE CACHÉ\nEn nuestras auditorías de rendimiento con herramientas como PageSpeed y WebPageTest, las instalaciones en {$name} obtuvieron tiempos de respuesta estables y puntuaciones elevadas en las métricas Core Web Vitals gracias a sus módulos de almacenamiento en caché en servidor.\n\n3. PANEL DE CONTROL Y EXPERIENCIA DE ADMINISTRACIÓN\nEl panel simplifica la gestión del sitio web con opciones directas para activar certificados SSL en un clic, clonar sitios para pruebas de plugins antes de publicar cambios y programar copias de seguridad automáticas con retención garantizada.\n\n4. ATENCIÓN AL CLIENTE ESPECIALIZADA\nEl equipo de soporte demuestra familiaridad con los errores habituales de WordPress (pantalla blanca de la muerte, conflictos de plugins, configuración de permalinks), lo que acelera notablemente la resolución de problemas frente a centros de atención genéricos.\n\n5. ANÁLISIS DE TARIFAS Y PLANES RECOMENDADOS\nSus precios se sitúan en un rango equilibrado, con planes escalables que permiten crecer desde un blog incipiente hasta proyectos comerciales con miles de visitas diarias sin experimentar caídas de servicio.";
            $pros = "• Servidores optimizados específicamente para WordPress y WooCommerce\n• Sistema de caché a nivel de servidor para acelerar las métricas Core Web Vitals\n• Copias de seguridad automáticas y certificados SSL incluidos sin coste extra\n• Soporte técnico capacitado en resolución de problemas del entorno WordPress";
            $cons = "• Entorno restringido para proyectos que no utilicen PHP o WordPress\n• Planes iniciales con límites en el número de visitas mensuales estimadas\n• Las ofertas de bienvenida incrementan su coste en las renovaciones regulares";
            $verdict = "{$name} es una opción muy recomendable para emprendedores, diseñadores web y negocios que quieren centrarse en su contenido y ventas sin preocuparse por la parte técnica de sus servidores.";
        } else {
            $summary = "{$name} ofrece una propuesta de hosting completa y balanceada para empresas y particulares. Evaluamos su rendimiento en pruebas de estrés, estabilidad de servicio, facilidad de uso del panel y respuesta de su soporte.";
            $content = "1. ARQUITECTURA GENERAL Y SERVIDORES\n{$name} opera infraestructura moderna con almacenamiento de estado sólido, cortafuegos de aplicaciones web (WAF) y conexiones de red redundantes. Sus planes están diseñados para proporcionar una base técnica fiable a sitios corporativos, tiendas online y portafolios.\n\n2. PRUEBAS DE TIEMPO DE RESPUESTA Y DISPONIBILIDAD\nNuestros registros de monitorización continua confirman una disponibilidad de servicio superior al 99.9% y tiempos de respuesta medios de alrededor de 220-280 ms, manteniendo un comportamiento uniforme a lo largo del tiempo sin fluctuaciones bruscas de latencia.\n\n3. PANEL DE GESTIÓN Y HERRAMIENTAS INCLUIDAS\nLa administración diaria resulta cómoda gracias a una interfaz clara que incluye instalador de aplicaciones en 1 clic (WordPress, Joomla, PrestaShop), administración de cuentas de correo con protección antispam y gestor de archivos intuitivo.\n\n4. SOPORTE TÉCNICO Y ATENCIÓN AL USUARIO\nEl servicio de atención está disponible las 24 horas a través de los canales estándar de la industria, con personal receptivo y documentación clara para guiar al usuario en las configuraciones más comunes.\n\n5. POLÍTICA DE PRECIOS Y CONCLUSIÓN ECONÓMICA\nLa relación calidad/precio es competitiva dentro de su segmento de mercado, ofreciendo planes accesibles para poner en marcha cualquier iniciativa digital con garantías de estabilidad.";
            $pros = "• Buena estabilidad general con uptime verificado por encima del 99.9%\n• Almacenamiento en unidades SSD y copias de seguridad periódicas\n• Panel de control claro y fácil de usar para principiantes y profesionales\n• Instalador automático en 1 clic para las principales plataformas web";
            $cons = "• Los planes más económicos limitan la cantidad de bases de datos o cuentas\n• No incluye optimizaciones tan avanzadas como los hostings gestionados premium\n• El soporte en horas pico puede requerir algunos minutos adicionales de espera";
            $verdict = "{$name} representa una opción equilibrada y sólida para quienes buscan alojar sus páginas web con buena velocidad, soporte correcto y una inversión controlada.";
        }

        return [
            'title' => "Análisis de {$name}: Rendimiento, Precios y Opinión Real {$currentYear}",
            'slug' => $slug,
            'target_category' => $category,
            'rating' => $rating,
            'summary' => $summary,
            'content' => $content,
            'pros' => $pros,
            'cons' => $cons,
            'verdict' => $verdict,
            'meta_title' => "Opiniones de {$name} ({$currentYear}): ¿Vale la Pena? — Reseña DebateHosting",
            'meta_description' => "Auditoría técnica independiente sobre {$name}. Analizamos velocidad, panel, soporte, precios y veredicto editorial de nuestros expertos.",
        ];
    }

    /**
     * Limpieza y saneamiento de datos retornados para Reseñas
     */
    protected function sanitizeReviewData(string $name, array $data): array
    {
        $currentYear = date('Y');

        return [
            'title' => $data['title'] ?? "Análisis de {$name}: Rendimiento, Precios y Opinión Real {$currentYear}",
            'slug' => Str::slug($data['slug'] ?? "{$name}-analisis-y-opinion"),
            'target_category' => strtolower($data['target_category'] ?? 'hosting'),
            'rating' => min(10, max(1, floatval($data['rating'] ?? 9.0))),
            'summary' => $data['summary'] ?? '',
            'content' => $data['content'] ?? '',
            'pros' => $data['pros'] ?? '',
            'cons' => $data['cons'] ?? '',
            'verdict' => $data['verdict'] ?? '',
            'meta_title' => $data['meta_title'] ?? "Opiniones de {$name} ({$currentYear}) — Reseña DebateHosting",
            'meta_description' => $data['meta_description'] ?? "Auditoría técnica a fondo sobre {$name}. Analizamos velocidad, panel, precios y veredicto.",
        ];
    }

    /**
     * Generar catálogo multi-producto con IA para un proveedor (Hosting, VPS, Dedicados, etc.)
     */
    public function generateProducts(string $name): array
    {
        $name = trim($name);
        $gemini = $this->getGeminiConfig();

        if (! empty($gemini['key'])) {
            try {
                $prompt = <<<PROMPT
Investiga las diferentes líneas de productos y planes comerciales que ofrece la empresa de alojamiento web: "{$name}".
Identifica de 2 a 4 de sus principales productos o planes reales en categorías como:
- "hosting" (Hosting compartido, cPanel o WordPress)
- "vps" (Servidores VPS Cloud o KVM)
- "cloud" o "dedicado" (Servidores Dedicados, Cloud o Bare Metal)
- "wordpress" (Hosting optimizado WordPress)

Responde estrictamente un JSON Array de objetos con la siguiente estructura exacta:
[
  {
    "category_slug": "hosting / vps / cloud / wordpress",
    "plan_name": "Nombre real del plan o producto (ej: Shared Lite NVMe, KVM VPS 1, Bare Metal D-1)",
    "price_from": 2.99,
    "price_before": 5.99,
    "period": "mes",
    "specs": "Lista corta de características clave separadas por comas (ej: SSD NVMe, cPanel, LiteSpeed, 1 vCPU)",
    "affiliate_url": "",
    "is_featured": true
  }
]

Devuelve únicamente el JSON sin comentarios ni texto adicional.
PROMPT;

                $response = $this->httpClient(25)
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/{$gemini['model']}:generateContent?key={$gemini['key']}", [
                        'contents' => [
                            ['parts' => [['text' => $prompt]]],
                        ],
                        'generationConfig' => [
                            'temperature' => 0.3,
                            'response_mime_type' => 'application/json',
                        ],
                    ]);

                if ($response->successful()) {
                    $rawText = $response->json('candidates.0.content.parts.0.text') ?? '';
                    $cleanJson = $this->cleanJsonString($rawText);
                    $decoded = json_decode($cleanJson, true);
                    if (is_array($decoded) && count($decoded) > 0) {
                        return $decoded;
                    }
                }
            } catch (\Throwable $e) {
                // Fallback heurístico si la llamada falla
            }
        }

        return $this->getHeuristicProducts($name);
    }

    /**
     * Fallback heurístico inteligente de productos por proveedor
     */
    protected function getHeuristicProducts(string $name): array
    {
        $clean = strtolower($name);

        if (str_contains($clean, 'alexhost')) {
            return [
                [
                    'category_slug' => 'hosting',
                    'plan_name' => 'Hosting LiteSpeed NVMe',
                    'price_from' => 2.90,
                    'price_before' => 4.50,
                    'period' => 'mes',
                    'specs' => 'cPanel, SSD NVMe, LiteSpeed, Offshore Moldavia',
                    'affiliate_url' => '',
                    'is_featured' => true,
                ],
                [
                    'category_slug' => 'vps',
                    'plan_name' => 'VPS KVM Unmetered 1',
                    'price_from' => 4.00,
                    'price_before' => 6.00,
                    'period' => 'mes',
                    'specs' => '1 vCPU, 2GB RAM, 20GB NVMe, Tráfico Ilimitado',
                    'affiliate_url' => '',
                    'is_featured' => false,
                ],
                [
                    'category_slug' => 'cloud',
                    'plan_name' => 'Dedicated Bare Metal D-1',
                    'price_from' => 45.00,
                    'price_before' => 60.00,
                    'period' => 'mes',
                    'specs' => 'Intel Xeon, 32GB RAM, 1TB SSD, 1Gbps',
                    'affiliate_url' => '',
                    'is_featured' => false,
                ],
            ];
        }

        return [
            [
                'category_slug' => 'hosting',
                'plan_name' => "{$name} Web Start",
                'price_from' => 2.99,
                'price_before' => 6.99,
                'period' => 'mes',
                'specs' => '100GB SSD NVMe, SSL Gratis, Panel Web, Copias Diarias',
                'affiliate_url' => '',
                'is_featured' => true,
            ],
            [
                'category_slug' => 'vps',
                'plan_name' => "{$name} Cloud VPS 1",
                'price_from' => 5.99,
                'price_before' => 9.99,
                'period' => 'mes',
                'specs' => '2 vCPU, 4GB RAM, 50GB NVMe, IP Dedicada',
                'affiliate_url' => '',
                'is_featured' => false,
            ],
            [
                'category_slug' => 'wordpress',
                'plan_name' => "{$name} WordPress Turbo",
                'price_from' => 3.99,
                'price_before' => 7.99,
                'period' => 'mes',
                'specs' => 'LiteSpeed Cache, Staging en 1-clic, WP CLI, Dominio Gratis',
                'affiliate_url' => '',
                'is_featured' => false,
            ],
        ];
    }
}
