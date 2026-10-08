# 🛒 SuperFresco — Plataforma de Gestión de Inventario & POS

> Sistema web integral desarrollado con **Laravel**, diseñado para el control de inventario en tiempo real, punto de venta (POS), gestión de catálogo de alimentos frescos, reportería estadística y control de roles para supermercados.

---

## ✨ Características Principales

* 📊 **Dashboard Ejecutivo:** Métricas de inventario, ventas, usuarios y accesos directos diferenciados para administradores y empleados.
* ⚠️ **Sistema Inteligente de Alertas de Stock Bajo:**
  * Indicadores visuales en el menú lateral con insignias animadas (`⚠️ X`).
  * Notificaciones de stock crítico en cabecera y banners interactivos en toda la aplicación.
  * Filtro instantáneo de productos con stock bajo en el inventario.
  * Advertencias preventivas en el punto de venta (POS) al momento de cobrar.
* 🏗️ **Control de Inventario en Tiempo Real:** Monitoreo dinámico con recarga automática por polling cada 8 segundos y registro detallado de entradas, salidas y mermas.
* 🛒 **Punto de Venta (POS):** Facturación rápida con selector múltiple de ítems, cálculo automático de importes, diversos métodos de pago y descuento de existencias transaccional.
* 📦 **Catálogo de Productos:** Clasificación por categorías, proveedores, gestión de precios, control de SKU/código de barras e imágenes locales o remotas.
* 👥 **Control y Acceso a Todo el Sistema:** Perfiles de `Administrador`, `Empleado` y `Cliente`. Los nuevos registros ingresan como cliente y únicamente el Administrador tiene la facultad de promoverlos a empleado desde el panel de control.
* 📈 **Módulo de Reportes & Exportación:** Gráficas de ventas anuales, top 10 productos más comercializados y exportación en formato **PDF** y **Excel**.

---

## 🛠️ Stack Tecnológico

* **Backend:** PHP 8.3 / [Laravel Framework 13.x](https://laravel.com)
* **Base de Datos:** MySQL / MariaDB (Driver PDO)
* **Frontend:** Blade Templates, JavaScript nativo con AJAX / Fetch API, Google Fonts (Outfit & Plus Jakarta Sans)
* **Diseño:** CSS personalizado con estética premium (Glassmorphism, gradientes, animaciones sutiles)
* **Reportes:** `barryvdh/laravel-dompdf`

---

## 🚀 Inicio Rápido

### 1. Clonar y configurar entorno
```bash
# Instalar dependencias
composer install

# Copiar variables de entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 2. Configurar Base de Datos en `.env`
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3320        # Ajustar a 3306 o 3320 según tu instalación
DB_DATABASE=bdsupermercado_dev
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrar y poblar roles
```bash
php artisan migrate --seed
```

### 4. Encender servidor
```bash
php artisan serve
```
Acceso web en: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🔐 Credenciales Predeterminadas

| Rol | Correo Electrónico | Contraseña |
| :--- | :--- | :--- |
| **Administrador** | `admin@superfresco.com` | `admin123` |
| **Empleado** | `cortes@gmail.com` | `123456` |

---

## 📚 Documentación del Proyecto

Toda la documentación técnica y de usuario se encuentra organizada en el directorio [`docs/`](file:///c:/laragon/www/inventario_laravel/docs/):

1. 📖 [**Manual de Usuario y Operaciones**](file:///c:/laragon/www/inventario_laravel/docs/manual_usuario.md): Guía paso a paso en lenguaje claro para cajeros, administradores y personal de bodega.
2. 🗄️ [**Diccionario de Datos y Modelo de Información**](file:///c:/laragon/www/inventario_laravel/docs/base_de_datos.md): Explicación humana de las entidades, relaciones y reglas del negocio del supermercado.
3. 🏛️ [**Arquitectura General del Sistema**](file:///c:/laragon/www/inventario_laravel/docs/arquitectura.md): Estructura por capas, flujos de información y modelo de autorización sin enredos técnicos.
4. 🎓 [**Conceptos Fundamentales de Laravel**](file:///c:/laragon/www/inventario_laravel/docs/conceptos_laravel.md): Guía didáctica y humana sobre el funcionamiento interno de Laravel y control de acceso.
5. ⚙️ [**Guía Práctica de Instalación y Puesta en Marcha**](file:///c:/laragon/www/inventario_laravel/docs/guia_instalacion.md): Pasos sencillos para desplegar el proyecto en Laragon y resolver incidencias comunes.

---

## 📄 Licencia

El software está desarrollado para fines educativos y empresariales bajo la licencia [MIT](https://opensource.org/licenses/MIT).
