# Configuración de Variables de Entorno

## Variables de Mercado Pago

Agrega estas variables a tu archivo `.env`:

```env
# Mercado Pago Configuration
MERCADOPAGO_PUBLIC_KEY=TEST-9d789008-52a6-4517-8271-7c735b3806c6
MERCADOPAGO_ACCESS_TOKEN=TEST-1209269489778646-010517-6ba0c3913960821f7b3b4f4b94973f92-1188129936
MERCADOPAGO_SANDBOX=true
MERCADOPAGO_NOTIFICATION_URL=https://tu-dominio.com/webhooks/mercadopago
MERCADOPAGO_SUCCESS_URL=https://tu-dominio.com/payment/success
MERCADOPAGO_FAILURE_URL=https://tu-dominio.com/payment/failure
MERCADOPAGO_PENDING_URL=https://tu-dominio.com/payment/pending
MERCADOPAGO_CURRENCY=ARS
MERCADOPAGO_CURRENCY_SYMBOL=$
```

## Configuración de Base de Datos

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=beltspot
DB_USERNAME=root
DB_PASSWORD=
```

## Configuración de la Aplicación

```env
APP_NAME="Mi Tienda Online"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
```

## Generar Clave de Aplicación

Después de configurar las variables, ejecuta:

```bash
php artisan key:generate
```

## Configuración de Archivos

```env
FILESYSTEM_DISK=public
```

## Configuración de Sesión

```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## Notas Importantes

1. **Credenciales de Mercado Pago**: Las credenciales proporcionadas son de prueba (sandbox). Para producción, necesitarás las credenciales reales.

2. **URLs de Notificación**: Cambia `tu-dominio.com` por tu dominio real.

3. **Base de Datos**: Asegúrate de que la base de datos `beltspot` exista antes de ejecutar las migraciones.

4. **Permisos de Archivos**: Asegúrate de que el directorio `storage` tenga permisos de escritura.

## Para Producción

Cuando estés listo para producción:

1. Cambia `APP_ENV=production`
2. Cambia `APP_DEBUG=false`
3. Usa las credenciales reales de Mercado Pago
4. Cambia `MERCADOPAGO_SANDBOX=false`
5. Configura las URLs reales de tu dominio
6. Configura HTTPS 