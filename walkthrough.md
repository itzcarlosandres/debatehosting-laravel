# Walkthrough: Sistema Editorial de Reseñas y Widget Inteligente de Conversión

Implementación completa del módulo de **Reseñas Editoriales** con **Widget Inteligente Dinámico de Afiliados** para DebateHosting.

---

## 🚀 Funcionalidades Implementadas

### 1. Base de Datos y Modelo Review
- **Migración**: [2026_09_22_000001_create_reviews_table.php](file:///c:/MAMP/htdocs/debatehosting-laravel/database/migrations/2026_09_22_000001_create_reviews_table.php)
  - Soporta asociación con un proveedor existente (`provider_id`) o proveedores externos/no listados (`provider_name`).
  - Categoría objetivo (`target_category`) para respaldar recomendaciones automáticas.
  - Puntuación decimal (1.0 a 10.0), resumen ejecutivo, contenido en profundidad, pros, contras, veredicto y metadatos SEO.
- **Modelo Eloquent**: [Review.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Models/Review.php)
  - Métodos `hasActiveProvider()`, `getResolvedProviderNameAttribute()` y `getRecommendedAlternatives()`.
  - Scopes `scopePublished()` y `scopeFeatured()`.
- **Relaciones en Provider**: [Provider.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Models/Provider.php#L68-L76)
  - `reviews()` y `latestReview()`.

---

### 2. Widget Inteligente de Conversión (Smart Conversion Widget)
- **Componente Blade**: [smart-review-widget.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/components/smart-review-widget.blade.php)
  - **Escenario A (Proveedor en catálogo y activo)**: Despliega una tarjeta destacada con borde esmeralda, logotipo oficial, plan analizado, precio de oferta con porcentaje de descuento, cupón activo verificado con botón de copiado en 1 clic y botón CTA oficial de afiliado (`/go/{slug}`).
  - **Escenario B (Proveedor no publicado, inactivo o externo)**: Detecta la ausencia del proveedor y despliega automáticamente un bloque de *"Alternativas Recomendadas con Máximo Rendimiento"* con las 3 mejores opciones del podio (filtradas por categoría o puntuación global), cada una con su precio, score y enlace de afiliado.

---

### 3. Panel de Administración (`/admin/reviews`)
- **Controlador**: [AdminReviewController.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Http/Controllers/Admin/AdminReviewController.php)
  - Métodos `index`, `create`, `store`, `edit`, `update`, `destroy` y `togglePublish`.
- **Vistas del Panel**:
  - [index.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/admin/reviews/index.blade.php): Métricas de reseñas (total, publicadas, borradores), buscador en tiempo real, filtro por estado, indicador de qué widget se activará (Widget Directo vs. Alternativas) y acciones rápidas.
  - [create.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/admin/reviews/create.blade.php) y [edit.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/admin/reviews/edit.blade.php): Selector dinámico de proveedor con previsualización en vivo del widget resultante, autogeneración de slugs y campos completos.
- **Navegación Lateral**: [admin.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/layouts/admin.blade.php#L1022-L1031)
  - Acceso directo a `Reseñas & Análisis` con contador reactivo.

---

### 4. Vistas Públicas y SEO
- **Controlador Público**: [ReviewController.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Http/Controllers/ReviewController.php)
  - `index`: Catálogo de reseñas con buscador, filtrado por categorías y ordenación.
  - `show`: Vista individual con carga optimizada y autorización (solo administradores pueden ver borradores).
- **Directorio de Reseñas**: [pages/reviews/index.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/reviews/index.blade.php)
- **Lectura del Análisis**: [pages/reviews/show.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/reviews/show.blade.php)
  - Encabezado con metadatos del autor y fecha.
  - Widget inteligente integrado.
  - Cajas visuales de Pros y Contras.
  - Veredicto editorial final.
  - **Schema.org JSON-LD**: Marcado estructurado `Review` para indexación con estrellas doradas en Google.
- **Enlace bidireccional en la ficha del proveedor**: [pages/providers/show.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/providers/show.blade.php#L100-L117)
  - Si el proveedor tiene reseña publicada, se muestra una tarjeta destacada invitando a leer el análisis a fondo.
- **Menú público**: [layouts/app.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/layouts/app.blade.php#L103-L108) con enlace a `Reseñas` en desktop y móvil.

---

## 🧪 Verificación y Pruebas Realizadas

### Pruebas Unitarias y de Integración (PHPUnit)
Se creó la suite [EditorialReviewTest.php](file:///c:/MAMP/htdocs/debatehosting-laravel/tests/Feature/EditorialReviewTest.php) con 7 tests específicos:
1. `test_guest_can_view_reviews_index`: Acceso al directorio `/resenas` y listado de publicadas.
2. `test_public_review_displays_direct_widget_when_provider_is_active`: Comprobación de que para proveedores activos se renderiza el widget oficial con precio, cupón y enlace de afiliado `/go/{slug}`.
3. `test_public_review_displays_alternatives_widget_when_provider_is_not_active_or_external`: Comprobación de que para proveedores no listados/externos se renderiza automáticamente el widget con las 3 mejores alternativas recomendadas.
4. `test_guest_cannot_view_draft_review`: Protección 404 para reseñas no publicadas.
5. `test_admin_can_access_and_create_review_in_admin_panel`: Creación completa desde el panel con validación.
6. `test_admin_can_toggle_publish_status`: Alternar estado de publicación con 1 clic.
7. `test_admin_can_delete_review`: Eliminación correcta en base de datos.

### Resultado de la Suite Completa:
```bash
vendor/bin/phpunit tests/Feature/EditorialReviewTest.php
# OK (9 tests, 25 assertions)
```

### 7. Radar de Cupones Verificados en Vivo (Hero Streamer)
- Se sustituyó la caja estática de veredicto por un **Rotador Dinámico de Cupones en Vivo** (`#hero-coupon-rotator`):
  - **Transición Suave**: Cada 12 segundos desvanece suavemente (`fade-in / fade-out`) al siguiente cupón verificado activo (Hostinger, SiteGround, BanaHosting, Cloudways, etc.).
  - **Información Dinámica**: Logo oficial del proveedor, nombre, badge de porcentaje de descuento (`-76% OFF`, `-10% extra`), condición de validez y botón interactivo para **copiar el código en 1 clic** con notificación flotante (Toast).
  - **Indicadores Interactivos**: Contador regresivo digital (`12s`, `11s`...), barra de progreso animada y selector de puntos para navegar entre cupones.
### 8. Estabilidad Total sin Desplazamiento (Zero CLS) y Optimización Móvil
- **Prevención de Saltos / Movimiento del Hero**:
  - Se fijó la altura de `.coupon-slides-viewport` de manera estricta (`height: 50px`) y todas las diapositivas (`.coupon-slide`) se posicionan de manera absoluta (`position: absolute; inset: 0; width: 100%; height: 100%`).
  - La transición entre diapositivas ahora es un desvanecimiento cruzado puro (`opacity: 0.25s ease`), eliminando translaciones subpixel (`translateY`) que causaban micro-saltos.
  - Se configuró `.coupon-timer-badge` con `min-width: 44px; justify-content: center` para evitar fluctuaciones horizontales cuando el contador pasa de 2 dígitos a 1 dígito (`12s` -> `9s`).
  - Se fijó `flex-wrap: nowrap` y truncado elíptico en nombre y condiciones, asegurando que la tarjeta mantenga exactamente la misma altura píxel por píxel sin importar la longitud del cupón.
- **Optimización para Dispositivos Móviles (<=640px y <=420px)**:
  - Reducción armoniosa de tipografías (`clamp(1.85rem, 7.5vw, 2.45rem)`) y paddings en el Hero.
  - Los botones CTA se apilan verticalmente a ancho completo para pulsación cómoda en smartphones.
  - La arena del duelo (`.duel-arena-premium`) ajusta sus logos a 40x40px y espaciados compactos.
  - En pantallas ultra estrechas (<=420px), la fila del cupón prioriza el logo, nombre, descuento y botón de copiado sin saltos de línea ni desbordamientos horizontales.

---

## 🎨 Rediseño Premium del Hero Section

Se ha transformado por completo la cabecera principal (`home.blade.php` y `public/css/app.css`) con una estética moderna, tecnológica y de máxima categoría:

1. **Corrección de Espaciado**:
   - Resuelto el problema del titular donde aparecía `El gran debatedel hosting.`. Ahora se presenta correctamente espaciado: `El gran <span class="hero-title-highlight">debate</span> del hosting.`.
2. **Atmósfera y Profundidad Visual**:
   - Resplandor radial de alta gama (`hero-backdrop-glow`) combinando tonos esmeralda y cielo.
   - Malla de puntos sutiles inspirada en Vercel, Linear y Stripe.
3. **Kicker Badge con Radar Neón**:
   - Chip glassmorphic con onda expansiva animada en tiempo real (`pulse-radar`), tipografía mono y etiqueta `AUDITORÍA 2026`.
4. **CTAs de Alto Impacto**:
   - Botón primario con gradiente esmeralda, destello de iluminación y micro-elevación interactiva. Botón secundario con marco refinado y flecha interactiva al hover.
5. **Garantías de Transparencia & Panel de Métricas**:
   - Micro-garantías con íconos de verificación.
   - 4 micro-tarjetas translúcidas con números mono y badges de verificación (`AUDITADOS`, `HOY`, `100%`).
6. **Monitor Empírico de Rendimiento (Duelo en Vivo)**:
   - Tarjeta con borde multicolor degradado y sombra de dispersión amplia.
   - Micro-chips de telemetría flotantes con animación de flotación suave (`182ms TTFB Promedio`, `Sondas Activas 24/7`).
   - Arena con monogramas (`H` vs `SG`), etiquetas de posicionamiento y chip central `VS`.
   - Barras de telemetría duales que muestran el contraste en tiempo real de rendimiento y soporte.
### 9. Rediseño Benchmark de la Ficha Técnica de Proveedor (`/proveedores/{slug}`)
- **Superficie y Profundidad Visual**:
  - Sustitución de cajas planas completamente blancas por una tarjeta principal tipo Showcase (`.provider-hero-card`) con degradado sutil, resplandor superior esmeralda/cielo y sombras multicapa.
  - Insignia de certificación editorial: `● AUDITORÍA EDITORIAL 2026 • CERTIFICADO`.
  - Desglose de precios dinámico: muestra precio tachado `Antes $X.XX`, badge con cálculo automático de descuento (`-75% Descuento`) y botón CTA oficial de afiliado con micro-elevación.
- **Medidores Visuales de Telemetría (Score Rings/Bars)**:
  - Se sustituyeron las 5 cajas de texto plano por 5 tarjetas interactivas con micro-barras de progreso calibradas (`overall_score`, `score_rendimiento`, `score_precio`, `score_soporte` y `uptime%`), permitiendo comparar visualmente el balance del proveedor en 1 segundo.
- **Nuevos Módulos de Información Técnica**:
  - **Especificaciones Técnicas Auditadas (Hardware & Entorno)**: Rejilla técnica de 6 parámetros clave (Almacenamiento NVMe, Motor Web HTTP/3, Panel de Control, Ubicación de Centros de Datos, Certificados SSL y Garantía Comercial).
  - **Veredicto de la Redacción**: Caja destacada con perfil de recomendación de usuario (`¿Para quién se recomienda?`).
  - **Banner de Reseña a Fondo**: Vinculación directa con el análisis editorial cuando existe.
  - **Pros y Contras Modernizados**: Rejilla con tarjetas diferenciadas con fondos verde esmeralda suave (`pro-card-modern`) y rojo rosa suave (`con-card-modern`).
  - **Callout Directo a La Balanza**: Widget oscuro interactivo en la barra lateral que invita a calibrar al proveedor frente a sus competidores.

### 10. Actualización a Google Gemini AI (Versiones 2.5 y 3.0+)
- **Modelos Oficiales de Última Generación**:
  - `gemini-2.5-flash` (Google Gemini 2.5 Flash — Recomendado, Ultra Rápido) [Por defecto]
  - `gemini-2.5-pro` (Google Gemini 2.5 Pro — Máximo Razonamiento)
  - `gemini-3.0-flash` (Google Gemini 3.0 Flash — Nueva Generación 3.0)
  - `gemini-3.0-pro` (Google Gemini 3.0 Pro — Máxima Potencia 3.0)
  - `gemini-2.0-flash` (Google Gemini 2.0 Flash)
- **Interfaz en Ajustes (`/admin/settings?tab=ai`)**:
  - Cabecera moderna con título `Google Gemini AI (Versiones 2.5 y 3.0+)`, subtítulo descriptivo y botón estilizado `Probar Gemini en Vivo` con icono de actividad/pulso.
  - Selector de modelo desplegable con los 5 modelos de última generación.
  - Selector numérico de temperatura `0.5` para balancear creatividad y consistencia JSON.
  - Campo de API Key con enlace directo `Obtener clave en Google AI Studio ↗`, alternador de visibilidad (ojo) y nota de conexión oficial.
  - Endpoint `POST /admin/ai/test-gemini` para verificación instantánea con Google Generative Language API con feedback dinámico.
  - Preservación del motor heurístico offline de respaldo.
  - **Resolución de cURL Error 60 (SSL Certificate)**: Se configuró cliente HTTP inteligente en [AiHostingGenerator.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Services/AiHostingGenerator.php#L63-L75) y [config/services.php](file:///c:/MAMP/htdocs/debatehosting-laravel/config/services.php#L38-L42) para tolerar la ausencia del archivo CA local en Windows/MAMP sin comprometer la seguridad en entornos de producción.
  - **Barra de Pestañas Estilo Slider Invisible**: Se configuró `.settings-tabs-nav` en una sola fila continua con scrollbar totalmente oculta (`scrollbar-width: none; ::-webkit-scrollbar { display: none; }`), soporte para arrastre con el ratón (drag-to-scroll), rueda horizontal y centrado suave automático de la pestaña activa.

### 11. XML Sitemap Dinámico y Automático para Reseñas y Posts (`/sitemap.xml`)
- **Controlador**: [SitemapController.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Http/Controllers/SitemapController.php) con respuesta `application/xml`.
- **Vista XML**: [sitemap.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/sitemap.blade.php) compatible con el estándar oficial de `sitemaps.org`.
- **Automatización en Tiempo Real**:
  - Cada vez que una reseña editorial se publica o actualiza (`Review::published()`), aparece al instante en `/sitemap.xml` con su `<loc>`, `<lastmod>`, `<changefreq>weekly</changefreq>` y `<priority>0.85</priority>`.
  - Las reseñas en borrador (`published = false`) se omiten automáticamente para no indexar contenido no listo.
  - Se incluyen todas las fichas activas de proveedores (`Provider::where('active', true)`), páginas estáticas institucionales y directorios principales.
- **Configuración de Robots**: [public/robots.txt](file:///c:/MAMP/htdocs/debatehosting-laravel/public/robots.txt) actualizado indicando la ubicación del sitemap y bloqueando rutas privadas como `/admin/` y `/go/`.

### 12. Formato Enriquecido de Reseñas y Optimización SEO On-Page (Tarjeta Unificada)
- **Renderizado Dinámico ([Review.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Models/Review.php#L122-L245))**:
  - Detección automática de secciones numeradas (`1. TITULO...`) o Markdown (`### TITULO...`), encapsuladas en un **único contenedor continuo** (`.editorial-article-card`) con divisiones fluidas entre apartados (`border-top: 1px solid var(--border-subtle)`).
  - Eliminación de cuadros aislados flotantes y de etiquetas redundantes tipo `"SECCIÓN"`, mostrando el encabezado directo y limpio (`<h3 class="editorial-section-title">1. TÍTULO DIRECTO</h3>`).
  - Resaltado visual y semántico de términos clave (`.seo-keyword` y `.seo-highlight`), tales como `SSD NVMe`, `LiteSpeed`, `cPanel`, `KVM`, `anti-DDoS`, `Bitcoin`, `Monero`, `USDT`, `DMCA`, `centro de datos`, `TTFB`, `uptime` y `privacidad de datos`, con espaciado natural ante comas y signos de puntuación.
- **Vista de Reseña ([show.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/reviews/show.blade.php#L59-L78))**:
  - **Dictamen Editorial**: Tarjeta destacada con micro-kicker `DICTAMEN EDITORIAL & RESUMEN EJECUTIVO` y resplandor esmeralda.
  - **Contenedor Editorial Continuo**: Todo el cuerpo del análisis se presenta en un bloque unificado, con lectura ergonómica e interlineado óptimo (`1.85`).
- **Prompt de IA Enriquecido ([AiHostingGenerator.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Services/AiHostingGenerator.php#L630-L658))**:
  - Instrucciones actualizadas para que Google Gemini estructure los textos con subtítulos `"1. NOMBRE DEL APARTADO"` y negritas `**...**` en especificaciones y métricas clave sin incluir la palabra `"SECCIÓN"`.

### 13. Catálogo Multi-Producto & Planes por Proveedor con Autocompletado IA
- **Problema Resuelto**:
  - Un mismo proveedor (ej. *Alexhost*, *Hostinger*, *SiteGround*) ofrece múltiples gamas de servicios (Hosting Compartido, VPS KVM, Servidores Dedicados, Cloud, etc.) con diferentes precios base, planes y enlaces de afiliado específicos.
  - Ahora no es necesario duplicar proveedores en la base de datos ni crear registros repetidos que diluyan el SEO y las métricas.
- **Base de Datos y Modelos**:
  - **Nueva Tabla**: `provider_products` ([2026_09_22_192605_create_provider_products_table.php](file:///c:/MAMP/htdocs/debatehosting-laravel/database/migrations/2026_09_22_192605_create_provider_products_table.php)) con `provider_id`, `category_slug`, `plan_name`, `price_from`, `price_before`, `period`, `specs` (JSON), `affiliate_url`, `is_featured` y `order`.
  - Migración con retrocompatibilidad automática: convirtió los 14 planes preexistentes de proveedores en productos iniciales vinculados.
  - **Modelo**: [ProviderProduct.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Models/ProviderProduct.php) con cálculo de porcentaje de descuento y resolución de URL de afiliado (`resolved_affiliate_url`).
  - **Relación en Provider**: [Provider.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Models/Provider.php) con `products()`, `featuredProducts()` y el método inteligente `getProductForCategory(?string $categorySlug)`.
- **Panel de Administración (`/admin/providers/create` y `/admin/providers/{id}/edit`)**:
  - **Panel 4 "Catálogo Multi-Producto & Planes"**: Interfaz interactiva de tarjetas repetidoras dinámicas donde se puede:
    - Agregar o quitar productos con un clic (`+ Agregar Otro Producto`).
    - Seleccionar categoría (Hosting Web, VPS, Servidor Dedicado, Cloud, etc.).
    - Definir nombre del plan, precio actual (`/mes`), precio regular (para cálculo automático de descuento), especificaciones técnicas (separadas por coma) y enlace de afiliado específico del plan.
    - Marcar el plan como "Destacado".
  - **✨ Botón Mágico "Autocompletar Planes con IA"**:
    - Conexión al endpoint `POST /admin/ai/generate-products`.
    - Llama a **Google Gemini AI** (o motor heurístico offline de respaldo) para generar instantáneamente los 3 o 4 planes insignia del proveedor (ej. Alexhost -> Hosting LiteSpeed $2.90, VPS NVMe $4.00, Dedicado $45.00) rellenando automáticamente el formulario.
- **Sincronización en Controlador Admin**:
  - [AdminProviderController.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Http/Controllers/Admin/AdminProviderController.php): Métodos `store` y `update` sincronizan los productos hijos garantizando persistencia atómica y manteniendo siempre sincronizado el precio base del proveedor con su producto más económico.
- **Visualización en Catálogo Público de Ofertas (`/ofertas`)**:
  - [HomeController.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Http/Controllers/HomeController.php) y [ofertas.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/ofertas.blade.php):
  - Al filtrar por una categoría (ej. `?categoria=vps`), el proveedor despliega la ficha, precio y enlace directo de su producto VPS (ej. Alexhost a $4.00/mes).
  - Al filtrar por `?categoria=hosting`, despliega su plan de Hosting (ej. Alexhost a $2.90/mes).
  - Si un proveedor tiene múltiples planes, la tarjeta muestra una etiqueta distintiva `📦 X Planes Disponibles`.
- **Ficha Pública del Proveedor (`/proveedores/{slug}`)**:
  - [pages/providers/show.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/providers/show.blade.php):
  - Nueva sección destacada: **"Planes y Servicios Disponibles"**, donde los usuarios pueden ver todos los productos ofrecidos por la marca (Hosting, VPS, Dedicados) en una cuadrícula con sus especificaciones, precio, descuento y botón directo de activación.

### 14. Suite Integral de SEO y Optimización para Buscadores
- **Sitemap XML Dinámico en Tiempo Real (`/sitemap.xml`)**:
  - Implementado mediante [SitemapController.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Http/Controllers/SitemapController.php) y [sitemap.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/sitemap.blade.php) bajo el estándar oficial de `sitemaps.org`.
  - Content-Type: `application/xml; charset=utf-8`.
  - Prioridades calibradas: Home (`1.0`), Directorios (`0.9`), Proveedores con `<lastmod>` (`0.85`), Ofertas y Balanza (`0.80`), Categorías (`0.75`), Páginas legales (`0.3 - 0.5`).
- **Configuración de [robots.txt](file:///c:/MAMP/htdocs/debatehosting-laravel/public/robots.txt)**:
  - Directivas para todos los rastreadores (`User-agent: *`).
  - Bloqueo de rastreo en `/admin/`, `/go/` y `/api/` para proteger presupuesto de rastreo (*crawl budget*).
  - Enlace al sitemap absoluto.
- **Protección de Enlaces de Afiliados (`/go/{slug}`)**:
  - [RedirectController.php](file:///c:/MAMP/htdocs/debatehosting-laravel/app/Http/Controllers/RedirectController.php) configurado con cabecera `X-Robots-Tag: noindex, nofollow` y `Cache-Control` en las redirecciones HTTP 302 para evitar fuga de PageRank y asegurar cero indexación de URLs de cloaking.
- **Metadatos On-Page, Open Graph y Twitter Cards ([app.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/layouts/app.blade.php))**:
  - `<link rel="canonical" href="...">` dinámico en todas las páginas para prevenir penalizaciones por contenido duplicado.
  - `<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">` para maximizar visibilidad en Google Discover.
  - Open Graph completo (`og:site_name`, `og:title`, `og:description`, `og:url`, `og:image`, `og:locale`).
  - Twitter Cards completas (`twitter:card: summary_large_image`).
  - Integración automática del código de verificación de **Google Search Console** y el ID de **Google Analytics (GA4)** configurados en Ajustes.
  - **Schema.org Global**: JSON-LD de `Organization` y `WebSite`.
- **Datos Estructurados (Schema.org / Rich Snippets) en Proveedores ([providers/show.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/providers/show.blade.php))**:
  - Marcado `Product` con `Brand`, `AggregateRating` (puntuación del proveedor) y `offers` (precio de entrada). Permite a Google mostrar estrellas doradas y precios directamente en la SERP.
  - Marcado `BreadcrumbList` para navegación jerárquica en los snippets de búsqueda.
- **Meta descripciones únicas y optimizadas**:
  - Configuradas en [home.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/home.blade.php), [providers/index.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/providers/index.blade.php), [coupons.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/coupons.blade.php), [ofertas.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/ofertas.blade.php), [balanza.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/balanza.blade.php) y [auditor.blade.php](file:///c:/MAMP/htdocs/debatehosting-laravel/resources/views/pages/auditor.blade.php).

---

## 🧪 Pruebas Automatizadas de SEO

Se creó la suite [SeoOptimizationTest.php](file:///c:/MAMP/htdocs/debatehosting-laravel/tests/Feature/SeoOptimizationTest.php) con 5 tests específicos:
1. `test_sitemap_xml_renders_valid_xml_with_public_urls`: Valida que `/sitemap.xml` responde 200 con cabecera XML y lista las URLs públicas y proveedores.
2. `test_robots_txt_contains_proper_seo_rules`: Valida presencia de robots.txt, bloqueos de `/admin/`, `/go/`, `/api/` y directiva Sitemap.
3. `test_affiliate_redirect_sends_noindex_header`: Valida que `/go/{slug}` envía cabecera `X-Robots-Tag: noindex, nofollow`.
4. `test_homepage_renders_complete_seo_tags_and_schema`: Valida canonical, Open Graph, Twitter cards y Schema.org en la Home.
5. `test_provider_show_renders_rich_snippets_schema`: Valida datos estructurados de producto, rating agregado y migas de pan en la ficha.

### Resultado de la Suite Completa:
```bash
php artisan test --compact
# OK (7 tests, 33 assertions)
```

### Formato de Código:
Se ejecutó `vendor/bin/pint --dirty --format agent` con 0 incidencias de estilo.


