# Arquitectura del Sistema — SuperFresco

**SuperFresco** es una plataforma integral desarrollada en **Laravel** diseñada para la gestión operativa, control de inventario, punto de venta (POS) y catálogo público para un supermercado de productos frescos, gourmet y orgánicos.

---

## 1. Visión General del Sistema

El sistema implementa el patrón arquitectónico clásico y robusto **Modelo - Vista - Controlador (MVC)** complementado por la capa de abstracción de base de datos **Eloquent ORM** y el motor de plantillas **Blade**.

```mermaid
graph TD
    subgraph "Cliente (Navegador Web)"
        UI["Interfaz de Usuario / Vistas Blade"]
        JS["Interacciones JavaScript & AJAX"]
    end

    subgraph "Capa HTTP & Seguridad"
        Router["Enrutador (routes/web.php)"]
        MW_CSRF["Middleware CSRF (VerifyCsrfToken)"]
        MW_Auth["Middleware Autenticación (auth / guest)"]
    end

    subgraph "Capa de Aplicación (Controladores)"
        AuthController["AuthController"]
        DashboardController["DashboardController"]
        ProductoController["ProductoController"]
        InventarioController["InventarioController"]
        VentaController["VentaController"]
        ReporteController["ReporteController"]
        OtrosControllers["Usuarios / Categorias / Proveedores"]
    end

    subgraph "Capa de Dominio (Modelos Eloquent)"
        M_Producto["Producto"]
        M_Usuario["Usuario"]
        M_Venta["Venta & DetalleVenta"]
        M_Inv["MovimientoInventario"]
        M_Otros["Categoria, Proveedor, Cliente, Rol"]
    end

    subgraph "Capa de Persistencia & Almacenamiento"
        MySQL[("Base de Datos MySQL (InnoDB)\nPuerto: 3320")]
        Storage["Almacenamiento Local de Archivos\n(public/uploads/productos)"]
    end

    UI -->|Petición HTTP / Formulario| Router
    Router --> MW_CSRF --> MW_Auth
    MW_Auth --> AuthController & DashboardController & ProductoController & VentaController & InventarioController & ReporteController & OtrosControllers

    ProductoController --> M_Producto
    ProductoController --> Storage
    VentaController --> M_Venta
    InventarioController --> M_Inv
    DashboardController --> M_Producto & M_Venta
    AuthController --> M_Usuario

    M_Producto & M_Usuario & M_Venta & M_Inv & M_Otros <-->|Consultas SQL / PDO| MySQL

    ProductoController & VentaController & DashboardController -->|Renderiza con Datos| UI
```

---

## 2. Pila Tecnológica (Tech Stack)

| Capa | Tecnología | Descripción |
| :--- | :--- | :--- |
| **Lenguaje Backend** | **PHP 8.3 (NTS)** | Lenguaje base del servidor. |
| **Framework Backend** | **Laravel Framework (v13.x / base 11+)** | Framework web empresarial con soporte para routing, middleware, Eloquent y sesiones. |
| **Motor de Plantillas** | **Blade Templates** | Motor de vistas precompiladas de Laravel con directivas reactivas y layout modular. |
| **Base de Datos** | **MySQL 8.0 (InnoDB)** | Base de datos relacional con soporte transaccional y claves foráneas, configurada en puerto `3320`. |
| **Estilos & Diseño** | **CSS Moderno / Variables CSS** | Paleta prémium bosque/esmeralda, degradados lineales, sombras suaves y elevación. |
| **Iconografía & Tipografía** | **Google Fonts & FontAwesome 6** | Tipografías `Outfit` (títulos) y `Plus Jakarta Sans` (datos/cuerpo); iconos vectoriales. |
| **Frontend Scripts** | **Vanilla JavaScript (ES6+)** | Lógica de previsualización de imágenes, modales dinámicos, cálculo de ventas y alertas. |
| **Entorno Local** | **Laragon / Artisan Server** | Servidor de desarrollo en `http://127.0.0.1:8000`. |

---

## 3. Modelo de Datos y Entidades (Diagrama Entidad-Relación)

El diseño de la base de datos está normalizado y estructurado para garantizar la integridad referencial en operaciones de inventario y ventas:

```mermaid
erDiagram
    ROLES ||--o{ USUARIOS : "asigna rol"
    CATEGORIAS ||--o{ PRODUCTOS : "clasifica"
    PROVEEDORES ||--o{ PRODUCTOS : "suministra"
    PRODUCTOS ||--o{ DETALLE_VENTAS : "se vende en"
    PRODUCTOS ||--o{ MOVIMIENTOS_INVENTARIO : "registra movimientos"
    VENTAS ||--o{ DETALLE_VENTAS : "contiene"
    USUARIOS ||--o{ VENTAS : "registra venta"
    CLIENTES ||--o{ VENTAS : "realiza compra"

    ROLES {
        int id_rol PK
        string nombre
        string descripcion
    }

    USUARIOS {
        int id_usuario PK
        int id_rol FK
        string nombres
        string apellidos
        string email UK
        string password_hash
        string telefono
        tinyint estado
    }

    CATEGORIAS {
        int id_categoria PK
        string nombre
        string descripcion
        tinyint estado
    }

    PROVEEDORES {
        int id_proveedor PK
        string nombre
        string contacto
        string telefono
        string email
        string direccion
        tinyint estado
    }

    PRODUCTOS {
        int id_producto PK
        int id_categoria FK
        int id_proveedor FK
        string codigo UK
        string nombre
        text descripcion
        decimal precio
        int stock_actual
        int stock_minimo
        string imagen
        tinyint estado
    }

    CLIENTES {
        int id_cliente PK
        string nombre
        string documento UK
        string telefono
        string email
        string direccion
    }

    VENTAS {
        int id_venta PK
        int id_usuario FK
        int id_cliente FK
        decimal total
        string metodo_pago
        string estado
        datetime fecha_venta
    }

    DETALLE_VENTAS {
        int id_detalle PK
        int id_venta FK
        int id_producto FK
        int cantidad
        decimal precio_unitario
        decimal subtotal
    }

    MOVIMIENTOS_INVENTARIO {
        int id_movimiento PK
        int id_producto FK
        string tipo
        int cantidad
        string motivo
        datetime fecha_movimiento
    }
```

---

## 4. Módulos del Sistema

### 4.1. Tienda Pública y Catálogo E-commerce (`/`)
- **Controlador/Ruta**: `routes/web.php` -> `index.blade.php`.
- **Características**:
  - Navbar translúcida con efecto *glassmorphism*.
  - Hero slider promocional de frutas, verduras y productos orgánicos.
  - Catálogo de productos en cuadrícula con badges de estado y stock.
  - Temporizador interactivo de ofertas de temporada.
  - Presentación de beneficios corporativos (envío rápido, calidad garantizada).

### 4.2. Autenticación y Control de Accesos (`/login`, `/register`)
- **Controlador**: `AuthController`.
- **Seguridad**:
  - Encriptación de contraseñas mediante **Bcrypt** con costo 12.
  - Protección contra ataques de fuerza bruta mediante validaciones de formulario.
  - Diferenciación de roles de usuario: **Administrador** (acceso total) y **Empleado** (operaciones de inventario y ventas).

### 4.3. Panel de Control y Analítica (`/dashboard`)
- **Controlador**: `DashboardController`.
- **Funciones**:
  - Métricas KPI (total productos, ventas del día, alertas de stock mínimo y agotado).
  - Gráficos de tendencias y productos más comercializados.
  - Accesos directos a operaciones frecuentes.

### 4.4. Módulo de Productos e Imágenes (`/productos`)
- **Controlador**: `ProductoController`.
- **Modelo**: `Producto.php`.
- **Innovaciones**:
  - **Soporte de Imagen Dual**: Carga de archivo físico (`.jpg`, `.png`, `.webp`) guardado con nombre hash en `public/uploads/productos/` o asignación mediante URL externa.
  - **Accesor Eloquent `imagen_url`**: Normaliza la obtención del recurso independientemente de su origen con fallback a imágenes Unsplash de alta definición.
  - **Previsualización en Tiempo Real**: Vista previa inmediata en JavaScript antes de enviar el formulario.
  - **Limpieza Automática de Archivos**: Eliminación de imágenes obsoletas del disco al actualizar o eliminar un producto.

### 4.5. Monitor de Inventario en Tiempo Real (`/inventario`)
- **Controlador**: `InventarioController`.
- **Funciones**:
  - Registro de entradas, salidas y ajustes de existencias.
  - Indicadores de pulso en vivo para productos en estado óptimo, bajo stock y agotados.

### 4.6. Punto de Venta y Transacciones (`/ventas`)
- **Controlador**: `VentaController`.
- **Funciones**:
  - Creación de recibos con detalle de ítems, cantidades y cálculo dinámico de subtotales e IVA.
  - Integración de clientes y estados de transacción (completada, pendiente, cancelada).

### 4.7. Directorio de Proveedores y Categorías (`/proveedores`, `/categorias`)
- **Controladores**: `ProveedorController`, `CategoriaController`.
- **Funciones**:
  - Catálogo de proveedores con información de contacto y productos suministrados.
  - Gestión taxonómica de productos por familias y departamentos.

### 4.8. Reportes Ejecutivos (`/reportes`)
- **Controlador**: `ReporteController`.
- **Funciones**:
  - Informes mensuales y comparativos de ventas.
  - Alerta de mermas y productos de baja rotación.

---

## 5. Ciclo de Vida de una Petición (Request Lifecycle)

Un ejemplo práctico del flujo de datos cuando un administrador actualiza la imagen de un producto:

```mermaid
sequenceDiagram
    autonumber
    actor Admin as Administrador (Navegador)
    participant V as Vista Blade (productos/index)
    participant R as Router (routes/web.php)
    participant M as Middleware (VerifyCsrfToken + Auth)
    participant C as ProductoController (update)
    participant FS as Filesystem (public/uploads/productos)
    participant DB as Modelo Producto (MySQL)

    Admin->>V: Selecciona foto local o escribe URL
    V-->>Admin: Muestra previsualización inmediata en miniatura
    Admin->>V: Clic en "Actualizar Producto"
    V->>R: POST /productos/{id} (_method=PUT, multipart/form-data)
    R->>M: Verifica token CSRF y sesión activa
    M->>C: Invoca ProductoController@update
    C->>C: Valida campos y tipo de imagen (MIME, max 5MB)
    alt Sube archivo local
        C->>FS: Elimina imagen previa si era local
        C->>FS: Mueve nuevo archivo con hash único
        C->>DB: Actualiza registro con ruta 'uploads/productos/hash.jpg'
    else Ingresa URL externa
        C->>FS: Elimina imagen previa si era local
        C->>DB: Actualiza registro con URL 'https://...'
    else Marca quitar imagen
        C->>FS: Elimina imagen previa del disco
        C->>DB: Setea columna 'imagen' = null
    end
    DB-->>C: Confirmación de actualización
    C-->>Admin: Redirección a /productos con mensaje flash de éxito
    Admin->>V: Renderiza catálogo con la nueva imagen visible
```

---

## 6. Estructura de Directorios del Repositorio

```
inventario_laravel/
├── app/
│   ├── Http/
│   │   └── Controllers/         # Controladores de la aplicación
│   │       ├── AuthController.php
│   │       ├── DashboardController.php
│   │       ├── ProductoController.php
│   │       ├── InventarioController.php
│   │       ├── VentaController.php
│   │       └── ...
│   └── Models/                  # Modelos Eloquent y lógica de negocio
│       ├── Producto.php
│       ├── Usuario.php
│       ├── Venta.php
│       └── ...
├── bootstrap/                   # Inicialización y proveedores de servicios
├── config/                      # Archivos de configuración (app, database, etc.)
├── database/
│   ├── migrations/              # Definiciones del esquema de base de datos
│   └── seeders/                 # Datos iniciales y pruebas
├── docs/                        # Documentación técnica del sistema
│   └── arquitectura.md          # Este documento
├── public/                      # Raíz pública del servidor web
│   ├── index.php                # Punto de entrada HTTP
│   └── uploads/                 # Archivos multimedia subidos
│       └── productos/           # Imágenes locales de los productos
├── resources/
│   └── views/                   # Plantillas Blade del frontend y panel admin
│       ├── auth/                # Vistas de login y registro
│       ├── layouts/             # Plantilla base y barra lateral (sidebar)
│       ├── productos/           # Vistas del catálogo, creación y edición
│       ├── inventario/          # Monitor de stock
│       ├── ventas/              # POS y listado de transacciones
│       └── index.blade.php      # Tienda pública principal
├── routes/
│   └── web.php                  # Definición de rutas y asignación de middlewares
├── storage/                     # Logs, sesiones y cachés del framework
├── .env                         # Variables de entorno y credenciales locales
├── .gitignore                   # Exclusiones de control de versiones Git
└── composer.json                # Dependencias PHP del proyecto
```

---

## 7. Políticas de Seguridad y Confiabilidad

1. **Protección CSRF**: Todas las peticiones mutables (`POST`, `PUT`, `DELETE`) exigen directiva `@csrf` con validación estricta de tokens.
2. **Prevención de Inyección SQL**: Todas las consultas a la base de datos se ejecutan a través del Query Builder de Eloquent con *Prepared Statements* (parámetros vinculados por PDO).
3. **Protección contra XSS**: La sintaxis de Blade `{{ $variable }}` escapa automáticamente los caracteres HTML mediante `htmlspecialchars`.
4. **Protección de Credenciales**: El archivo `.env` se encuentra excluido del repositorio de Git para salvaguardar contraseñas de producción y claves de cifrado.
5. **Aislamiento de Archivos Públicos**: Solo los archivos alojados en `public/` son accesibles directamente por el navegador; el código fuente del núcleo (`app/`, `config/`, `.env`) permanece inaccesible desde peticiones externas.
