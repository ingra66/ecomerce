# Usuarios Administradores - Panel de Filament

## 🔐 Usuarios Creados por el Seeder

### **Usuario Principal:**
- **Email**: `admin@ecommerce.com`
- **Contraseña**: `admin123`
- **Nombre**: Administrador

### **Usuario Alternativo:**
- **Email**: `superadmin@ecommerce.com`
- **Contraseña**: `superadmin123`
- **Nombre**: Super Admin

## 🚀 Cómo Ejecutar el Seeder

### **Opción 1: Ejecutar todos los seeders**
```bash
php artisan db:seed
```

### **Opción 2: Ejecutar solo el seeder de admin**
```bash
php artisan db:seed --class=AdminUserSeeder
```

### **Opción 3: Usar el comando personalizado**
```bash
# Crear usuario con valores por defecto
php artisan admin:create-user

# Crear usuario personalizado
php artisan admin:create-user --email=miadmin@ejemplo.com --password=miPassword123 --name="Mi Administrador"
```

## 📱 Acceso al Panel

### **URL del Panel:**
```
http://tu-dominio.com/admin
```

### **Credenciales:**
- **Email**: `admin@ecommerce.com`
- **Contraseña**: `admin123`

## 🔧 Comandos Útiles

### **Verificar usuarios existentes:**
```bash
php artisan tinker
```
Luego ejecutar:
```php
App\Models\User::all(['name', 'email']);
```

### **Crear usuario manualmente:**
```bash
php artisan admin:create-user --email=tuemail@ejemplo.com --password=tuPassword123 --name="Tu Nombre"
```

### **Limpiar y recrear usuarios:**
```bash
php artisan migrate:fresh --seed
```

## 🛡️ Seguridad

### **Recomendaciones:**
1. **Cambiar contraseñas** después del primer acceso
2. **Usar contraseñas fuertes** en producción
3. **Configurar autenticación de dos factores** si es posible
4. **Limitar acceso** por IP si es necesario

### **Cambiar contraseña desde el panel:**
1. Accede al panel de administración
2. Ve a tu perfil (esquina superior derecha)
3. Selecciona "Cambiar contraseña"
4. Ingresa la nueva contraseña

## 📊 Funcionalidades Disponibles

### **Con estos usuarios puedes:**
- ✅ **Acceder al dashboard** con estadísticas
- ✅ **Gestionar productos** (crear, editar, eliminar)
- ✅ **Gestionar órdenes** (ver, actualizar estados)
- ✅ **Gestionar usuarios** (clientes y administradores)
- ✅ **Gestionar categorías** (organizar productos)
- ✅ **Ver reportes** y métricas

## 🔄 Actualizar Usuarios

### **Si necesitas crear más usuarios:**
```bash
php artisan admin:create-user --email=nuevo@ejemplo.com --password=nuevaPass123 --name="Nuevo Admin"
```

### **Si necesitas resetear usuarios:**
```bash
php artisan migrate:fresh --seed
```

## ❓ Solución de Problemas

### **Error: "Usuario ya existe"**
- El email ya está registrado
- Usa un email diferente o elimina el usuario existente

### **Error: "No puedo acceder al panel"**
- Verifica que Filament esté instalado correctamente
- Verifica que las credenciales sean correctas
- Limpia el cache: `php artisan config:clear`

### **Error: "Página no encontrada"**
- Verifica que la URL sea correcta: `/admin`
- Verifica que las rutas estén registradas: `php artisan route:list | grep admin`

---

**¡Usuarios administradores listos para usar el panel de Filament!** 🎯 