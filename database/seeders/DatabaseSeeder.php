<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Pick;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\TickerItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Administrador por defecto
        User::updateOrCreate(
            ['email' => 'admin@debatehosting.com'],
            [
                'name' => 'Administrador DebateHosting',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Categorías oficiales
        $categories = [
            ['slug' => 'hosting', 'name' => 'Hosting web', 'icon' => 'server', 'order' => 1],
            ['slug' => 'vps', 'name' => 'VPS', 'icon' => 'cpu', 'order' => 2],
            ['slug' => 'wordpress', 'name' => 'WordPress', 'icon' => 'globe', 'order' => 3],
            ['slug' => 'cloud', 'name' => 'Cloud', 'icon' => 'cloud', 'order' => 4],
            ['slug' => 'dominios', 'name' => 'Dominios', 'icon' => 'at-sign', 'order' => 5],
        ];
        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 3. Badges oficiales
        $badges = [
            ['slug' => 'mejor-precio', 'label' => 'MEJOR PRECIO', 'color' => 'green', 'order' => 1],
            ['slug' => 'top-rendimiento', 'label' => 'TOP RENDIMIENTO', 'color' => 'gold', 'order' => 2],
            ['slug' => 'recomendado', 'label' => 'RECOMENDADO', 'color' => 'green', 'order' => 3],
            ['slug' => 'vps-calidad-precio', 'label' => 'VPS CALIDAD/PRECIO', 'color' => 'green', 'order' => 4],
            ['slug' => 'ultra-rapido', 'label' => 'ULTRA RÁPIDO', 'color' => 'red', 'order' => 5],
        ];
        foreach ($badges as $b) {
            Badge::updateOrCreate(['slug' => $b['slug']], $b);
        }

        // 4. Proveedores completos
        $providers = [
            [
                'name' => 'Hostinger',
                'slug' => 'hostinger',
                'logo_url' => '/uploads/logos/hostinger.svg',
                'categories' => ['hosting', 'wordpress', 'cloud'],
                'plan' => 'Premium Web Hosting',
                'price_from' => 2.49,
                'price_before' => 9.99,
                'period' => 'mes',
                'score_precio' => 9.4,
                'score_rendimiento' => 8.8,
                'score_soporte' => 8.5,
                'score_facilidad' => 9.2,
                'uptime' => 99.98,
                'affiliate_url' => 'https://www.hostinger.es?ref=debatehosting',
                'badge' => 'MEJOR PRECIO',
                'badge_color' => 'green',
                'active' => true,
                'description' => 'Hostinger destaca por ofrecer la mejor relación calidad-precio del mercado, con servidores ultrarrápidos basados en LiteSpeed Web Server y panel hPanel extremadamente intuitivo.',
                'pros' => "• Servidores LiteSpeed con caché LSCache nativo\n• Migración web gratuita y automática\n• Soporte 24/7 en español muy reactivo",
                'cons' => "• Precio de renovación superior al precio promocional\n• No incluye IP dedicada en planes compartidos básicos",
                'verdict' => 'La opción más balanceada para proyectos personales, blogs en WordPress y tiendas medianas que buscan máxima velocidad sin gastar de más.',
                'coupons' => [
                    [
                        'code' => 'DEBATE10',
                        'discount' => '−10% extra',
                        'condition' => 'En planes Premium y Business de 12 a 48 meses',
                        'verified' => true,
                    ]
                ]
            ],
            [
                'name' => 'SiteGround',
                'slug' => 'siteground',
                'logo_url' => '/uploads/logos/siteground.svg',
                'categories' => ['hosting', 'wordpress', 'cloud'],
                'plan' => 'GrowBig',
                'price_from' => 3.99,
                'price_before' => 14.99,
                'period' => 'mes',
                'score_precio' => 7.2,
                'score_rendimiento' => 9.7,
                'score_soporte' => 9.9,
                'score_facilidad' => 9.5,
                'uptime' => 99.99,
                'affiliate_url' => 'https://www.siteground.es?ref=debatehosting',
                'badge' => 'TOP RENDIMIENTO',
                'badge_color' => 'gold',
                'active' => true,
                'description' => 'SiteGround es el estándar de oro en soporte técnico y arquitectura optimizada para WordPress sobre Google Cloud Platform con Ultrafast PHP.',
                'pros' => "• Soporte técnico por chat inmediato e hiperespecializado\n• Infraestructura construida 100% sobre Google Cloud\n• Copias de seguridad automáticas y staging en 1 clic",
                'cons' => "• Límite estricto de espacio en disco (20 GB en GrowBig)\n• Renovaciones a precio elevado",
                'verdict' => 'Recomendado para agencias, e-commerce exigentes y negocios que no pueden permitirse 1 minuto de inactividad.',
                'coupons' => [
                    [
                        'code' => 'SGDEBATE',
                        'discount' => '−76% OFF',
                        'condition' => 'Descuento de bienvenida para nuevos registros anuales',
                        'verified' => true,
                    ]
                ]
            ],
            [
                'name' => 'BanaHosting',
                'slug' => 'banahosting',
                'logo_url' => '/uploads/logos/banahosting.svg',
                'categories' => ['hosting', 'wordpress'],
                'plan' => 'Bana Starter SSD',
                'price_from' => 4.95,
                'price_before' => 6.95,
                'period' => 'mes',
                'score_precio' => 9.2,
                'score_rendimiento' => 8.9,
                'score_soporte' => 8.7,
                'score_facilidad' => 8.4,
                'uptime' => 99.96,
                'affiliate_url' => 'https://www.banahosting.com?ref=debatehosting',
                'badge' => 'RECOMENDADO',
                'badge_color' => 'green',
                'active' => true,
                'description' => 'Un clásico preferido por webmasters y nicheros por su estabilidad legendaria, uso de cPanel clásico y recursos no medidos en almacenamiento.',
                'pros' => "• Almacenamiento NVMe ultrarrápido sin límites estrictos\n• cPanel tradicional completo sin restricciones\n• Excelente rendimiento bajo tráfico simultáneo elevado",
                'cons' => "• Interfaz de área de clientes tradicional\n• Soporte principalmente por tickets (muy rápido pero sin chat directo)",
                'verdict' => 'La mejor elección para nicheros, redes de blogs y webmasters experimentados que adoran cPanel.',
                'coupons' => [
                    [
                        'code' => 'BH10OFF',
                        'discount' => '−10% primer mes',
                        'condition' => 'Válido en cualquier plan compartido SSD',
                        'verified' => true,
                    ]
                ]
            ],
            [
                'name' => 'Contabo',
                'slug' => 'contabo',
                'logo_url' => '/uploads/logos/contabo.svg',
                'categories' => ['vps', 'cloud'],
                'plan' => 'Cloud VPS 1',
                'price_from' => 4.50,
                'price_before' => 6.00,
                'period' => 'mes',
                'score_precio' => 9.8,
                'score_rendimiento' => 8.1,
                'score_soporte' => 7.0,
                'score_facilidad' => 7.2,
                'uptime' => 99.95,
                'affiliate_url' => 'https://contabo.com?ref=debatehosting',
                'badge' => 'VPS CALIDAD/PRECIO',
                'badge_color' => 'green',
                'active' => true,
                'description' => 'El proveedor alemán que reventó el mercado de los servidores virtuales privados ofreciendo más RAM y núcleos por euro que ningún competidor.',
                'pros' => "• 4 vCPU y 8 GB de RAM por solo 4.50€/mes\n• Tráfico ilimitado de 32 TB\n• Protección DDoS básica incluida",
                'cons' => "• Cobro adicional por coste de instalación en pagos mensuales\n• Rendimiento I/O de disco promedio en horas punta",
                'verdict' => 'Insuperable si necesitas potencia bruta de cómputo y memoria para laboratorios, VPNs o bots.',
                'coupons' => []
            ],
            [
                'name' => 'IONOS',
                'slug' => 'ionos',
                'logo_url' => '/uploads/logos/ionos.svg',
                'categories' => ['hosting', 'vps', 'dominios'],
                'plan' => 'Hosting Plus',
                'price_from' => 1.00,
                'price_before' => 12.00,
                'period' => 'mes',
                'score_precio' => 8.5,
                'score_rendimiento' => 8.0,
                'score_soporte' => 8.2,
                'score_facilidad' => 8.6,
                'uptime' => 99.97,
                'affiliate_url' => 'https://www.ionos.es?ref=debatehosting',
                'badge' => 'OFERTA 1€',
                'badge_color' => 'green',
                'active' => true,
                'description' => 'Gigante europeo de la infraestructura digital con centros de datos propios y asesor personal directo incluido en todos los planes.',
                'pros' => "• Oferta de bienvenida a 1€ durante el primer año\n• Asesor personal dedicado asignado con teléfono directo\n• Dominio gratis incluido el primer año",
                'cons' => "• Panel de control propietario algo denso\n• Cláusulas de renovación con aviso previo",
                'verdict' => 'Gran alternativa para pymes y profesionales que requieren factura europea y asesoría telefónica.',
                'coupons' => []
            ],
            [
                'name' => 'Cloudways',
                'slug' => 'cloudways',
                'logo_url' => '/uploads/logos/cloudways.svg',
                'categories' => ['cloud', 'vps', 'wordpress'],
                'plan' => 'DigitalOcean 1GB',
                'price_from' => 14.00,
                'price_before' => 14.00,
                'period' => 'mes',
                'score_precio' => 8.0,
                'score_rendimiento' => 9.6,
                'score_soporte' => 9.0,
                'score_facilidad' => 8.8,
                'uptime' => 99.99,
                'affiliate_url' => 'https://www.cloudways.com?ref=debatehosting',
                'badge' => 'CLOUD GESTIONADO',
                'badge_color' => 'gold',
                'active' => true,
                'description' => 'Plataforma de nube gestionada que permite desplegar servidores de DigitalOcean, AWS o Google Cloud con entorno optimizado para PHP y WordPress.',
                'pros' => "• Pila tecnológica ThunderStack ultrarrápida\n• Copias de seguridad automáticas y clonación de servidores\n• Sin necesidad de gestionar la consola de Linux",
                'cons' => "• No incluye servicio de correo electrónico por defecto (vía add-on)\n• Precio más alto que contratar directamente con el proveedor de nube",
                'verdict' => 'La mejor opción para desarrolladores y tiendas WooCommerce con mucho tráfico.',
                'coupons' => [
                    [
                        'code' => 'CLOUDWAYS20',
                        'discount' => '−20% OFF 3 meses',
                        'condition' => 'Descuento para nuevas cuentas en cualquier servidor cloud',
                        'verified' => true,
                    ]
                ]
            ],
            [
                'name' => 'Namecheap',
                'slug' => 'namecheap',
                'logo_url' => '/uploads/logos/namecheap.svg',
                'categories' => ['dominios', 'hosting', 'wordpress'],
                'plan' => 'Stellar',
                'price_from' => 1.98,
                'price_before' => 4.48,
                'period' => 'mes',
                'score_precio' => 9.3,
                'score_rendimiento' => 7.5,
                'score_soporte' => 8.1,
                'score_facilidad' => 8.9,
                'uptime' => 99.94,
                'affiliate_url' => 'https://www.namecheap.com?ref=debatehosting',
                'badge' => 'DOMINIO ECONÓMICO',
                'badge_color' => 'green',
                'active' => true,
                'description' => 'Líder indiscutible en registro de dominios que además ofrece planes de hosting compartido sumamente asequibles con cPanel.',
                'pros' => "• Protección de privacidad WhoisGuard gratuita de por vida\n• Registro de dominios a precios competitivos sin trucos\n• Soporte técnico activo por chat 24/7",
                'cons' => "• Velocidad de servidor modesta en planes económicos compartidos\n• Centros de datos principalmente en EE.UU. y Reino Unido",
                'verdict' => 'Recomendado número uno para comprar dominios baratos y para webs de bajo tráfico.',
                'coupons' => []
            ],
            [
                'name' => 'HostGator',
                'slug' => 'hostgator',
                'logo_url' => '/uploads/logos/hostgator.svg',
                'categories' => ['hosting', 'wordpress'],
                'plan' => 'Plan Hatchling',
                'price_from' => 3.75,
                'price_before' => 8.95,
                'period' => 'mes',
                'score_precio' => 8.2,
                'score_rendimiento' => 7.6,
                'score_soporte' => 7.8,
                'score_facilidad' => 8.8,
                'uptime' => 99.95,
                'affiliate_url' => 'https://www.hostgator.com?ref=debatehosting',
                'badge' => 'CLÁSICO',
                'badge_color' => 'dark',
                'active' => true,
                'description' => 'Uno de los proveedores más reconocidos a nivel global, con ancho de banda no medido y cPanel fácil de usar.',
                'pros' => "• Ancho de banda y almacenamiento generosos\n• Instalador de WordPress en 1 clic\n• Garantía de reembolso de 30 días",
                'cons' => "• Tiempos de carga promedio comparado con LiteSpeed\n• Ofertas agresivas de venta cruzada en el checkout",
                'verdict' => 'Aceptable para proyectos sencillos que buscan una marca con décadas de trayectoria.',
                'coupons' => []
            ],
            [
                'name' => 'OVHcloud',
                'slug' => 'ovhcloud',
                'logo_url' => '/uploads/logos/ovhcloud.svg',
                'categories' => ['vps', 'cloud', 'dominios'],
                'plan' => 'VPS Starter',
                'price_from' => 3.50,
                'price_before' => 4.50,
                'period' => 'mes',
                'score_precio' => 9.4,
                'score_rendimiento' => 8.3,
                'score_soporte' => 7.0,
                'score_facilidad' => 7.4,
                'uptime' => 99.97,
                'affiliate_url' => 'https://www.ovhcloud.com?ref=debatehosting',
                'badge' => 'EUROPEO',
                'badge_color' => 'dark',
                'active' => true,
                'description' => 'El mayor proveedor de nube de Europa, con centros de datos en Francia, Alemania y España, y cumplimiento RGPD estricto.',
                'pros' => "• Soberanía de datos 100% europea y cumplimiento de privacidad\n• Excelente red de fibra con protección Anti-DDoS líder mundial\n• Precios de VPS y dedicados muy competitivos",
                'cons' => "• Panel de cliente con curva de aprendizaje pronunciada\n• Soporte gratuito por tickets con tiempos de espera variables",
                'verdict' => 'Ideal para empresas europeas y administradores de sistemas que priorizan la privacidad.',
                'coupons' => []
            ],
            [
                'name' => 'Porkbun',
                'slug' => 'porkbun',
                'logo_url' => '/uploads/logos/porkbun.svg',
                'categories' => ['dominios'],
                'plan' => 'Dominio .com',
                'price_from' => 10.37,
                'price_before' => 12.50,
                'period' => 'año',
                'score_precio' => 9.9,
                'score_rendimiento' => 9.1,
                'score_soporte' => 8.9,
                'score_facilidad' => 9.4,
                'uptime' => 99.99,
                'affiliate_url' => 'https://porkbun.com?ref=debatehosting',
                'badge' => 'TOP DOMINIOS',
                'badge_color' => 'gold',
                'active' => true,
                'description' => 'El registrador independiente más querido por la comunidad tech por su total transparencia y precios al costo.',
                'pros' => "• Precios de renovación más bajos de toda la industria\n• Certificados SSL y privacidad Whois gratis para siempre\n• Interfaz sin publicidad ni upsells engañosos",
                'cons' => "• Interfaz solo disponible en inglés\n• Servicios de hosting complementarios muy básicos",
                'verdict' => 'El mejor registrador de dominios del mundo actualmente.',
                'coupons' => []
            ],
            [
                'name' => 'DigitalOcean',
                'slug' => 'digitalocean',
                'logo_url' => '/uploads/logos/digitalocean.svg',
                'categories' => ['cloud', 'vps'],
                'plan' => 'Basic Droplet',
                'price_from' => 4.00,
                'price_before' => 4.00,
                'period' => 'mes',
                'score_precio' => 8.9,
                'score_rendimiento' => 9.3,
                'score_soporte' => 8.4,
                'score_facilidad' => 9.5,
                'uptime' => 99.99,
                'affiliate_url' => 'https://www.digitalocean.com?ref=debatehosting',
                'badge' => 'DESARROLLADORES',
                'badge_color' => 'dark',
                'active' => true,
                'description' => 'La plataforma cloud favorita de desarrolladores y startups por su API impecable y documentación legendaria.',
                'pros' => "• Despliegue de Droplets en menos de 55 segundos\n• Comunidad masiva con tutoriales para cualquier tecnología\n• Facturación por horas exacta sin permanencias",
                'cons' => "• Requiere conocimientos de administración de sistemas Linux\n• Copias de seguridad automáticas con coste extra (+20%)",
                'verdict' => 'Indispensable para programadores y proyectos en NodeJS, Laravel, Python y Docker.',
                'coupons' => []
            ],
            [
                'name' => 'Kinsta',
                'slug' => 'kinsta',
                'logo_url' => '/uploads/logos/kinsta.svg',
                'categories' => ['wordpress', 'cloud'],
                'plan' => 'Starter Plan',
                'price_from' => 35.00,
                'price_before' => 35.00,
                'period' => 'mes',
                'score_precio' => 6.8,
                'score_rendimiento' => 9.9,
                'score_soporte' => 9.9,
                'score_facilidad' => 9.6,
                'uptime' => 99.99,
                'affiliate_url' => 'https://kinsta.com?ref=debatehosting',
                'badge' => 'PREMIUM WP',
                'badge_color' => 'gold',
                'active' => true,
                'description' => 'Hosting administrado para WordPress de gama alta sobre la red Premium de Google Cloud con contenedores C2 aislados.',
                'pros' => "• Velocidad y tiempos de respuesta TTFB imbatibles\n• Soporte por ingenieros senior de WordPress 24/7\n• Herramienta APM integrada para detectar cuellos de botella",
                'cons' => "• Precio inicial elevado (a partir de $35/mes)\n• No admite proyectos que no sean WordPress o aplicaciones cloud específicas",
                'verdict' => 'La opción premium definitiva para empresas y tiendas que no escatiman en presupuesto.',
                'coupons' => []
            ],
            [
                'name' => 'Alexhost',
                'slug' => 'alexhost',
                'logo_url' => '/uploads/logos/logo_1788984975075_alexhost.jpg',
                'categories' => ['vps', 'hosting'],
                'plan' => 'Offshore Lite',
                'price_from' => 4.00,
                'price_before' => 6.00,
                'period' => 'mes',
                'score_precio' => 8.8,
                'score_rendimiento' => 8.0,
                'score_soporte' => 7.5,
                'score_facilidad' => 7.8,
                'uptime' => 99.90,
                'affiliate_url' => 'https://alexhost.com?ref=debatehosting',
                'badge' => 'PRIVACIDAD',
                'badge_color' => 'dark',
                'active' => true,
                'description' => 'Proveedor especializado en servidores con centro de datos propio en Moldavia para máxima privacidad.',
                'pros' => "• Gran política de privacidad y libertad de contenidos\n• Admite pagos anónimos con criptomonedas\n• Centro de datos propio",
                'cons' => "• Latencia hacia Latinoamérica superior a proveedores con CDNs locales\n• Soporte en inglés",
                'verdict' => 'Para usuarios que buscan privacidad y pago en cripto.',
                'coupons' => []
            ],
        ];

        foreach ($providers as $provData) {
            $coupons = $provData['coupons'] ?? [];
            unset($provData['coupons']);

            $provider = Provider::updateOrCreate(
                ['slug' => $provData['slug']],
                $provData
            );

            foreach ($coupons as $coup) {
                Coupon::updateOrCreate(
                    ['code' => $coup['code']],
                    array_merge($coup, ['provider_id' => $provider->id])
                );
            }
        }

        // 5. El Podio (Picks de portada)
        $hostinger = Provider::where('slug', 'hostinger')->first();
        $siteground = Provider::where('slug', 'siteground')->first();
        $banahosting = Provider::where('slug', 'banahosting')->first();

        if ($hostinger && $siteground && $banahosting) {
            Pick::truncate();
            Pick::create([
                'position' => 1,
                'provider_id' => $hostinger->id,
                'tag' => 'ORO EDITORIAL · MEJOR CALIDAD / PRECIO',
                'titulo' => 'El Campeón del Hosting Equilibrado',
                'veredicto' => 'Por velocidad LiteSpeed, facilidad del hPanel y precio imbatible, es la opción recomendada para el 85% de los proyectos.',
            ]);
            Pick::create([
                'position' => 2,
                'provider_id' => $siteground->id,
                'tag' => 'PLATA EDITORIAL · TOP RENDIMIENTO WP',
                'titulo' => 'La Fortaleza Técnica de Alta Gama',
                'veredicto' => 'Sobre Google Cloud con soporte en vivo inmediato. Si tu web factura dinero a diario, este es tu hogar.',
            ]);
            Pick::create([
                'position' => 3,
                'provider_id' => $banahosting->id,
                'tag' => 'BRONCE EDITORIAL · EL REY DE CPANEL',
                'titulo' => 'La Elección del Webmaster Tradicional',
                'veredicto' => 'Almacenamiento NVMe sin límites engañosos y cPanel completo para quienes gestionan decenas de dominios.',
            ]);
        }

        // 6. Ticker de novedades
        $tickerItems = [
            ['text' => '🔥 Cupones actualizados hoy: Hostinger −10% extra con DEBATE10', 'hot' => true, 'order' => 1],
            ['text' => '⚡ Auditoría TTFB: SiteGround registra 142ms de media en Europa', 'hot' => false, 'order' => 2],
            ['text' => '🏆 La Balanza 2026: Ajusta tus prioridades y descubre tu proveedor ideal', 'hot' => false, 'order' => 3],
            ['text' => '🛡️ Verificación independiente sin patrocinios encubiertos', 'hot' => false, 'order' => 4],
        ];
        TickerItem::truncate();
        foreach ($tickerItems as $item) {
            TickerItem::create($item);
        }

        // 7. Cargar configuraciones de settings.json
        $settingsFile = database_path('data/settings.json');
        if (file_exists($settingsFile)) {
            $json = json_decode(file_get_contents($settingsFile), true);
            if (is_array($json)) {
                foreach ($json as $key => $val) {
                    Setting::updateOrCreate(['key' => $key], ['value' => $val]);
                }
            }
        }
    }
}
