#!/bin/bash
# ==============================================================================
# Script de Despliegue y Optimización para aaPanel (Apache + PHP 8.2/8.3)
# DebateHosting
# ==============================================================================

echo "🚀 Iniciando despliegue y optimización en aaPanel..."

# 1. Ajustar permisos recomendados para Apache (usuario www en aaPanel)
echo "📁 Configurando permisos para Apache y aaPanel..."
chown -R www:www .
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod -R 775 storage bootstrap/cache
chmod -R 775 database 2>/dev/null || true

# 2. Enlace simbólico de almacenamiento público
echo "🔗 Verificando enlace simbólico de almacenamiento..."
php artisan storage:link 2>/dev/null || true

# 3. Limpieza de cachés anteriores
echo "🧹 Limpiando cachés antiguas..."
php artisan optimize:clear

# 4. Migraciones de base de datos
echo "💾 Ejecutando migraciones de base de datos..."
php artisan migrate --force

# 5. Compilación y optimización de cachés de producción
echo "⚡ Minificando y compilando cachés de Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ ¡Optimización completada con éxito en aaPanel!"
echo "ℹ️ Asegúrate de que en aaPanel > Sitio > Site Directory esté apuntando a /public"
