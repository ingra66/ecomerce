# Instalación del Panel de Administración - Filament

## 🚀 Instalación Rápida

### 1. Instalar Dependencias
```bash
composer require filament/filament:"^3.0-stable"
```

### 2. Publicar Configuración
```bash
php artisan vendor:publish --tag=filament-config
php artisan vendor:publish --tag=filament-assets
```

### 3. Ejecutar Migraciones
```bash
php artisan migrate
```

### 4. Crear Usuario Administrador
```bash
php artisan make:filament-user
```

### 5. Limpiar Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 🔧 Configuración Adicional

### Variables de Entorno
Agregar al archivo `.env`:
```env
FILAMENT_PATH=admin
FILAMENT_BRAND="Ecommerce Admin"
FILAMENT_FILESYSTEM_DISK=public
```

### Configurar Almacenamiento
```bash
php artisan storage:link
```

## 📱 Acceso al Panel

### URL del Panel
```
http://tu-dominio.com/admin
```

### Credenciales
- Usar las credenciales del usuario creado con `make:filament-user`
- O usar cualquier usuario existente en la base de datos

## 🛠️ Solución de Problemas

### Error: Class not found
```bash
composer dump-autoload
```

### Error: Assets no encontrados
```bash
php artisan vendor:publish --tag=filament-assets --force
```

### Error: Configuración no encontrada
```bash
php artisan vendor:publish --tag=filament-config --force
```

### Error: Permisos de archivos
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## 📊 Verificar Instalación

### 1. Verificar Rutas
```bash
php artisan route:list | grep admin
```

### 2. Verificar Configuración
```bash
php artisan config:show filament
```

### 3. Verificar Base de Datos
```bash
php artisan migrate:status
```

## 🔒 Seguridad

### Configurar HTTPS (Producción)
```env
APP_URL=https://tu-dominio.com
ASSET_URL=https://tu-dominio.com
```

### Configurar Roles (Opcional)
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

## 📈 Próximos Pasos

1. **Personalizar Dashboard**
   - Agregar widgets personalizados
   - Configurar métricas específicas

2. **Configurar Notificaciones**
   - Email para nuevas órdenes
   - Alertas de stock bajo

3. **Implementar Reportes**
   - Reportes de ventas
   - Análisis de productos

4. **Optimizar Performance**
   - Configurar cache
   - Optimizar consultas

## 📞 Soporte

### Documentación Oficial
- [Filament Documentation](https://filamentphp.com/docs)
- [Laravel Documentation](https://laravel.com/docs)

### Comandos Útiles
```bash
# Ver logs
tail -f storage/logs/laravel.log

# Verificar estado
php artisan about

# Limpiar todo
php artisan optimize:clear
```

---

**¡Panel de Administración listo para usar!**
*Accede a http://tu-dominio.com/admin para comenzar* 