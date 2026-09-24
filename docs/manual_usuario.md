# Manual de Usuario y Guía de Operaciones — SuperFresco

**SuperFresco** es una solución integral de punto de venta (POS), gestión de catálogo, control de inventario en tiempo real y reportes estadísticos para supermercados y tiendas de alimentos frescos y orgánicos.

---

## Índice

1. [Roles y Permisos del Sistema](#1-roles-y-permisos-del-sistema)
2. [Inicio de Sesión y Registro](#2-inicio-de-sesión-y-registro)
3. [Panel de Control (Dashboard)](#3-panel-de-control-dashboard)
4. [Catálogo de Productos](#4-catálogo-de-productos)
5. [Control de Inventario y Movimientos](#5-control-de-inventario-y-movimientos)
6. [Sistema de Alertas de Stock Bajo](#6-sistema-de-alertas-de-stock-bajo)
7. [Punto de Venta (POS) y Registro de Ventas](#7-punto-de-venta-pos-y-registro-de-ventas)
8. [Categorías y Proveedores](#8-categorías-y-proveedores)
9. [Gestión de Usuarios y Cuentas](#9-gestión-de-usuarios-y-cuentas)
10. [Reportes y Exportación (PDF / Excel)](#10-reportes-y-exportación-pdf--excel)

---

## 1. Roles y Permisos del Sistema

El sistema cuenta con tres niveles de acceso claramente definidos mediante control de roles:

| Rol | ID Rol | Permisos y Alcance Operativo |
| :--- | :---: | :--- |
| **Administrador** | `1` | Acceso irrestricto a todos los módulos: creación de usuarios, gestión de categorías, proveedores, catálogo de productos, control de inventario, registro y cancelación de ventas, y descarga de reportes financieros y auditorías. |
| **Empleado** | `2` | Operaciones cotidianas de la tienda: consulta de catálogo, edición de precios y existencias, registro de movimientos de inventario (entradas/salidas/mermas) y monitoreo de stock en vivo. |
| **Cliente** | `3` | Navegación del catálogo público de la tienda, visualización de precios y disponibilidad de productos. |

---

## 2. Inicio de Sesión y Registro

### 2.1 Acceso al Sistema
1. Ingresa a la URL: `http://127.0.0.1:8000/login`.
2. Escribe tu correo electrónico y contraseña.
3. Haz clic en **"Iniciar Sesión"**. El sistema validará tus credenciales cifradas con Bcrypt y te redirigirá a tu panel correspondiente.

> [!NOTE]
> **Credenciales por defecto del Administrador:**
> * **Email:** `admin@superfresco.com`
> * **Contraseña:** `admin123`

### 2.2 Registro de Nuevos Clientes
1. Dirígete a `http://127.0.0.1:8000/register` o haz clic en *"¿No tienes cuenta? Regístrate aquí"* desde el formulario de login.
2. Diligencia tus nombres, apellidos, correo, teléfono y contraseña (mínimo 6 caracteres).
3. Haz clic en **"Crear Cuenta"**. Automáticamente se te asignará el rol de cliente y se iniciará tu sesión de forma segura.

---

## 3. Panel de Control (Dashboard)

### 3.1 Vista de Administrador
Al autenticarte como administrador, visualizarás:
* **Tarjetas métricas clave:** Total de usuarios activos, productos registrados, categorías creadas y proveedores aliados.
* **Widget de Alerta de Stock Crítico:** Si existen productos que alcanzaron o están por debajo de su stock mínimo, aparecerá un panel destacado con la lista de productos críticos, su cantidad actual y un botón de acceso directo a inventario.
* **Accesos Rápidos:** Accesos directos a todos los submódulos de gestión.

### 3.2 Vista de Empleado
Diseñado para la agilidad operativa:
* **Indicadores en vivo:** Productos activos, total de existencias, valor monetario del inventario y productos en alerta.
* **Herramientas rápidas:** Botones directos a Catálogo e Inventario en vivo.

---

## 4. Catálogo de Productos

Permite mantener actualizada la oferta comercial de la tienda.

### 4.1 Crear un Producto
1. En el menú lateral, selecciona **"Productos"**.
2. Haz clic en el botón superior **"+ Nuevo Producto"**.
3. Completa los campos:
   * **Nombre:** Nombre comercial del producto (ej. *Manzanas Rojas Royal Gala 1kg*).
   * **Código:** Código de barras o SKU único (ej. *PROD-MANZ-01*).
   * **Categoría y Proveedor:** Selecciona de las listas desplegables.
   * **Precio:** Precio de venta al público en pesos.
   * **Stock Inicial y Stock Mínimo:** Existencias actuales y el umbral mínimo que activará las alertas automáticas.
   * **Imagen:** Puedes subir un archivo local (JPG, PNG, WebP) o ingresar una URL externa de imagen.
4. Haz clic en **"Guardar Producto"**.

### 4.2 Modificar o Inactivar Productos
* **Editar:** Haz clic en el botón con ícono de lápiz en la fila del producto para actualizar precios, descripción o existencias.
* **Activar/Inactivar:** Puedes suspender la visibilidad de un producto sin borrar su historial usando el interruptor de estado.
* **Eliminar:** Solo permitido si el producto no tiene ventas asociadas que comprometan la integridad referencial.

---

## 5. Control de Inventario y Movimientos

El módulo **Inventario** (`/inventario`) ofrece sincronización automática en tiempo real sin necesidad de recargar la página.

### 5.1 Niveles de Stock
Cada fila muestra una barra visual con el nivel de abastecimiento:
*  **OK (Verde):** Stock holgado por encima del umbral mínimo.
* ⚠️ **BAJO (Ámbar):** El stock actual es menor o igual al stock mínimo configurado.
* ❌ **AGOTADO (Rojo):** Existencias en 0 unidades.

### 5.2 Registrar Entradas, Salidas o Ajustes
1. Localiza el producto en la tabla y presiona el botón **"📦 Mover"**.
2. En la ventana modal, selecciona el tipo de movimiento:
   * **📥 Entrada:** Suma unidades recibidas por compra o reposición.
   * **📤 Salida:** Resta unidades por merma, daño o autoconsumo.
   * **🔧 Ajuste:** Establece manualmente la cantidad exacta resultante de un conteo físico.
3. Ingresa la cantidad y una breve observación o justificación.
4. Presiona **"Registrar"**. El inventario y el historial de auditoría se actualizarán al instante.

---

## 6. Sistema de Alertas de Stock Bajo

Para prevenir el desabastecimiento, la plataforma cuenta con alertas automáticas interactivas:

1. **Insignia en el menú lateral:** Aparece un contador animado `⚠️ [X]` junto a la pestaña *Inventario* visible en todas las pantallas.
2. **Botón superior en cabecera:** Notifica cuántos productos están en alerta con acceso inmediato.
3. **Banner Global de Advertencia:** Un banner informativo superior con etiquetas que indican los nombres y cantidades restantes de los productos más urgentes.
4. **Filtro rápido en Inventario:** Botón **"⚠️ Ver solo stock bajo"** que oculta los productos en estado óptimo y muestra exclusivamente los que requieren reposición.
5. **Avisos automáticos en Ventas y Movimientos:** Si una transacción provoca que un producto quede en stock bajo o se agote, el sistema emitirá una notificación de advertencia preventiva.

---

## 7. Punto de Venta (POS) y Registro de Ventas

Ruta: `/ventas` *(Exclusivo administradores y cajeros autorizados)*.

### 7.1 Pasos para Registrar una Venta
1. Haz clic en **"+ Nueva Venta"**.
2. **Seleccionar Productos:** Escoge los productos del desplegable. El sistema mostrará su precio, stock actual y alertará con `[⚠️ Stock bajo]` si está por agotarse.
3. **Cantidades:** Define las unidades a vender. Puedes presionar **"+ Agregar producto"** para ventas de múltiples ítems.
4. **Cliente:** Selecciona un cliente registrado o déjalo en *Público General*.
5. **Método de Pago:** Selecciona Efectivo, Tarjeta o Transferencia bancaria.
6. Haz clic en **"Registrar Venta"**.
   * El sistema descuenta automáticamente el stock en la base de datos.
   * Registra el movimiento de salida en la auditoría de inventario.
   * Muestra un resumen del total cobrado y advierte si algún producto quedó en nivel crítico.

---

## 8. Categorías y Proveedores

### 8.1 Categorías (`/categorias`)
Organiza el catálogo en familias de alimentos (ej. *Frutas y Verduras*, *Lácteos*, *Panadería*, *Abarrotes*).
* Permite crear, renombrar y clasificar los productos para agilizar los filtros y los reportes de rendimiento.

### 8.2 Proveedores (`/proveedores`)
Directorio de empresas y aliados comerciales que surten la tienda:
* Permite registrar nombre de la empresa, contacto, teléfono, correo y dirección física.
* Facilita el seguimiento de órdenes de compra y la emisión de reportes de compras por proveedor.

---

## 9. Gestión de Usuarios y Cuentas

Ruta: `/usuarios` *(Solo Administrador)*.

* **Listado de Cuentas:** Permite filtrar y buscar usuarios por nombre o correo.
* **Creación de Cuentas Internas:** Crear perfiles con rol de *Empleado* o *Administrador*.
* **Cambio de Contraseña y Datos:** Permite actualizar nombres, teléfonos y reasignar roles.
* **Activación y Bloqueo:** El botón de alternar estado permite revocar el acceso a empleados que ya no laboren en la empresa sin eliminar sus registros históricos.

---

## 10. Reportes y Exportación (PDF / Excel)

Ruta: `/reportes`.

El módulo de inteligencia de negocios compila estadísticas completas por año:
* **Ventas mensuales:** Gráfica comparativa mes a mes del total recaudado.
* **Top 10 Productos Más Vendidos:** Ranking por volumen de unidades y por ingresos generados.
* **Productos con Stock Crítico:** Listado consolidado para compras.
* **Compras por Proveedor:** Distribución del gasto operativo por proveedor.

### Exportaciones Disponibles:
* **Exportar a PDF:** Genera un documento formal maquetado con encabezado institucional, tablas formateadas y métricas listo para imprimir o enviar a gerencia.
* **Exportar a Excel:** Descarga un archivo compatible con Microsoft Excel y Google Sheets para análisis de datos avanzado.
