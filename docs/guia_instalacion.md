# Guía de Instalación, Configuración y Mantenimiento — SuperFresco

Esta guía detalla los pasos para desplegar, configurar y mantener en funcionamiento el proyecto **SuperFresco** en entornos locales (especialmente con **Laragon** en Windows) y en servidores de producción.

---

## 1. Requisitos del Sistema

Antes de iniciar, asegúrate de contar con las siguientes dependencias instaladas en tu equipo o servidor:

* **PHP:** Versión `8.2` o `8.3` con las siguientes extensiones habilitadas:
  * `pdo_mysql`
  * `mbstring`
  * `openssl`
  * `curl`
  * `gd` (necesaria para el procesamiento de imágenes y logos de PDF)
  * `fileinfo` (para validación de tipos MIME al subir imágenes)
* **Gestor de Paquetes:** Composer `2.x`.
* **Motor de Base de Datos:** MySQL `8.0+` o MariaDB `10.4+`.
* **Entorno recomendado en Windows:** [Laragon](https://laragon.org/) (incluye PHP, MySQL, Apache/Nginx y terminal integrada).

---

## 2. Instalación Paso a Paso (Entorno Laragon / Local)

### Paso 1: Clonar o ubicar el repositorio
Ubica el código fuente dentro del directorio web de tu servidor local (ejemplo en Laragon: `C:\laragon\www\inventario_laravel`):

```bash
cd c:\laragon\www\inventario_laravel
```

### Paso 2: Instalar dependencias de PHP
Ejecuta Composer para descargar las librerías del framework y paquetes como `barryvdh/laravel-dompdf`:

```bash
composer install
```

### Paso 3: Configuración de Variables de Entorno (`.env`)
Si no existe el archivo `.env`, copia el archivo de plantilla:

```bash
cp .env.example .env
```

Abre `.env` y ajusta los parámetros de conexión. Por ejemplo, en Laragon o entornos con MySQL en puertos personalizados:

```dotenv
APP_NAME=SuperFresco
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3320        # Ajustar según tu puerto (3320 o 3306)
DB_DATABASE=bdsupermercado_dev
DB_USERNAME=root
DB_PASSWORD=
```

### Paso 4: Generar la Clave de Cifrado (`APP_KEY`)
Genera la llave secreta que utiliza Laravel para cifrar cookies y sesiones:

```bash
php artisan key:generate
```

### Paso 5: Enlace Simbólico de Almacenamiento
Vincula la carpeta `storage/app/public` con `public/storage` para servir las imágenes de productos:

```bash
php artisan storage:link
```

### Paso 6: Migraciones y Datos Semilla (Seeders)
Crea las tablas y los roles esenciales (`administrador`, `empleado`, `cliente`):

```bash
php artisan migrate --seed
```

### Paso 7: Iniciar el Servidor de Desarrollo
Enciende el servidor integrado de Artisan:

```bash
php artisan serve
```

La aplicación estará disponible inmediatamente en [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 3. Credenciales de Acceso Iniciales

Tras correr el seeder o importar la base de datos `bdsupermercado_dev`, dispones de las siguientes cuentas predeterminadas:

* **Administrador Principal:**
  * **Email:** `admin@superfresco.com`
  * **Contraseña:** `admin123`
* **Empleado Operativo:**
  * **Email:** `cortes@gmail.com`
  * **Contraseña:** `123456`

---

## 4. Comandos de Mantenimiento Frecuentes

Durante el desarrollo o actualización de la plataforma, estos comandos son fundamentales:

### Limpieza de Caché
```bash
# Limpiar caché de vistas Blade compiladas
php artisan view:clear

# Limpiar caché de rutas
php artisan route:clear

# Limpiar caché de configuración (.env)
php artisan config:clear

# Limpieza general de caché de la aplicación
php artisan cache:clear
```

### Verificación de Rutas y Base de Datos
```bash
# Listar todas las rutas registradas y sus controladores
php artisan route:list

# Verificar estado y conectividad con MySQL
php artisan db:show
```

---

## 5. Solución de Problemas Frecuentes (Troubleshooting)

### Problema 1: "SQLSTATE[HY000] [2002] Connection refused"
* **Causa:** El puerto de MySQL configurado en `.env` no coincide con el puerto donde está corriendo el servicio MySQL en Laragon.
* **Solución:** Verifica en Laragon qué puerto tiene asignado MySQL (clic derecho en Laragon > MySQL > Cambiar puerto). Si es `3306` o `3320`, asegúrate de actualizar `DB_PORT` en [.env](file:///c:/laragon/www/inventario_laravel/.env) y reiniciar el servidor.

### Problema 2: "Integrity constraint violation: 1452 Cannot add or update a child row (fk_usuarios_rol)"
* **Causa:** Se intenta registrar un usuario con un `id_rol` que no existe en la tabla `roles`.
* **Solución:** Ejecuta el seeder oficial para restablecer los 3 roles del sistema:
  ```bash
  php artisan db:seed --class=DatabaseSeeder
  ```
  Esto garantizará la existencia de los roles `1: administrador`, `2: empleado` y `3: cliente`.

### Problema 3: "The stream or file .../storage/logs/laravel.log could not be opened: failed to open stream: Permission denied"
* **Causa:** Permisos insuficientes en las carpetas de logs o sesiones.
* **Solución:** Otorga permisos de escritura a las carpetas `storage` y `bootstrap/cache`:
  * En Linux: `chmod -R 775 storage bootstrap/cache`
  * En Windows: Asegúrate de que el usuario tenga control total sobre la carpeta del proyecto.
