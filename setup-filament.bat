@echo off
echo ========================================
echo    Configurando Filament Admin
echo ========================================
echo.

echo [1/6] Publicando assets de Filament...
php artisan filament:install --panels
php artisan vendor:publish --tag=filament-config
php artisan vendor:publish --tag=filament-translations

echo.
echo [2/6] Limpiando cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo.
echo [3/6] Optimizando aplicación...
php artisan optimize
php artisan config:cache
php artisan route:cache

echo.
echo [4/6] Instalando dependencias de Node.js...
npm install

echo.
echo [5/6] Compilando assets...
npm run build

echo.
echo [6/6] Verificando configuración...
php artisan route:list | findstr admin

echo.
echo ========================================
echo    Configuración Completada
echo ========================================
echo.
echo URL del panel: http://localhost:8000/admin
echo Email: admin@ecommerce.com
echo Password: password
echo.
echo Presiona cualquier tecla para continuar...
pause > nul 