# Manual de Usuario y Guía de Operaciones — SuperFresco

Bienvenido al manual operativo de **SuperFresco**. Este documento fue redactado pensando en las personas que día a día interactúan con el supermercado: administradores, cajeros, bodegueros y personal de atención al cliente.

Aquí encontrarás de forma clara, detallada y sin lenguaje técnico enredado cómo utilizar cada herramienta del sistema, desde la recepción de mercancía y el control de existencias, hasta el cobro en caja y la emisión de facturas.

---

## Índice General

1. [Roles y Permisos del Sistema](#1-roles-y-permisos-del-sistema)
2. [Inicio de Sesión y Registro de Usuarios](#2-inicio-de-sesión-y-registro-de-usuarios)
3. [Tableros de Control (Dashboards)](#3-tableros-de-control-dashboards)
4. [Gestión del Catálogo de Productos](#4-gestión-del-catálogo-de-productos)
5. [Control de Inventario y Movimientos de Bodega](#5-control-de-inventario-y-movimientos-de-bodega)
6. [Sistema Inteligente de Alertas de Stock Bajo](#6-sistema-inteligente-de-alertas-de-stock-bajo)
7. [Punto de Venta (POS) y Registro de Ventas en Caja](#7-punto-de-venta-pos-y-registro-de-ventas-en-caja)
8. [Familias de Alimentos y Directorio de Proveedores](#8-familias-de-alimentos-y-directorio-de-proveedores)
9. [Gestión de Cuentas y Asignación de Roles](#9-gestión-de-cuentas-y-asignación-de-roles)
10. [Reportes Estadísticos y Exportaciones Oficiales](#10-reportes-estadísticos-y-exportaciones-oficiales)
11. [Acceso a Todo el Sistema y Seguridad Integral](#11-acceso-a-todo-el-sistema-y-seguridad-integral)

---

## 1. Roles y Permisos del Sistema

Para garantizar el orden y la seguridad en el supermercado, el sistema divide a las personas en tres niveles de acceso claramente definidos:

### 1. Administrador (Control Total)
Es la máxima autoridad de la empresa. Tiene acceso ilimitado a absolutamente todos los rincones de la plataforma: puede crear y administrar usuarios, otorgar o revocar roles de empleado, ajustar precios y costos, dar de alta proveedores, supervisar todas las ventas, anular comprobantes y descargar balances financieros en formatos PDF y Excel.

### 2. Empleado (Personal Operativo)
Es el equipo que mantiene en marcha el supermercado en los pasillos, la bodega y las cajas. Puede consultar el catálogo de alimentos, registrar entradas y salidas de mercancía en inventario, vigilar qué productos están por agotarse y cobrar ventas cotidianas. Por seguridad, tiene estrictamente prohibido entrar a la gestión de cuentas de usuarios, modificar roles o alterar configuraciones maestras del negocio.

### 3. Cliente (Comprador y Usuario Web)
Son las personas que visitan la tienda virtual para conocer la oferta de alimentos frescos. Pueden explorar libremente las frutas, vegetales y abarrotes, consultar precios actualizados y preparar sus compras. No tienen acceso a ninguna función administrativa ni operativa interna.

---

## 2. Inicio de Sesión y Registro de Usuarios

### Cómo Entrar al Sistema
1. Abre tu navegador de internet y escribe la dirección de inicio de sesión de SuperFresco.
2. Ingresa tu correo electrónico registrado y tu contraseña personal.
3. Presiona el botón **"Iniciar Sesión"**.
4. El sistema verificará tus datos en milisegundos y te conducirá automáticamente al panel que te corresponde según tu rol:
   - Si eres **Administrador**, verás el Tablero Ejecutivo Completo.
   - Si eres **Empleado**, verás el Panel Operativo de Bodega e Inventario.
   - Si eres **Cliente**, serás llevado directamente al Catálogo de Productos y la Tienda Online.

### Registro de Nuevas Personas
1. En la pantalla de acceso, pulsa el enlace para crear una cuenta nueva.
2. Completa tus datos básicos: nombres, apellidos, correo electrónico, teléfono de contacto y una contraseña segura de al menos seis caracteres.
3. Presiona **"Crear Cuenta"**.
4. **Regla de Oro del Sistema:** Toda persona que se registre desde el formulario público entra automáticamente como **Cliente**. Ningún usuario puede auto-asignarse el rol de empleado ni de administrador. Solo el Administrador de la empresa tiene la facultad de decidir si promueve a esa persona al rol de **Empleado**.

---

## 3. Tableros de Control (Dashboards)

### Vista para el Administrador
Al iniciar sesión, el Administrador tiene ante sus ojos una radiografía completa del negocio:
- **Balance Financiero y Ganancias Netas:** Visualización inmediata en tiempo real de las ganancias totales del negocio (calculadas a partir de la facturación de ventas menos las compras a proveedores), la facturación histórica total, las ventas generadas durante la jornada de hoy y la valoración económica total del inventario en almacén.
- **Control Operativo y Catálogo:** Indicadores clave con el total de usuarios activos, productos disponibles para la venta, familias de categorías creadas y proveedores aliados.
- **Panel de Urgencia por Stock Crítico:** Si algún producto llegó o bajó de su existencia mínima de seguridad, se despliega una tarjeta de advertencia destacada que muestra el nombre del producto, las unidades que quedan y un botón directo para abastecerlo de inmediato.
- **Accesos Rápidos a Módulos:** Enlaces directos a la gestión de cuentas, catálogo, inventario, ventas y reportes.

### Vista para el Empleado
Diseñada para agilizar las labores cotidianas sin distracciones:
- **Indicadores en Tiempo Real:** Total de productos activos en la tienda, existencias globales y valor monetario total del inventario disponible en bodega.
- **Acceso Rápido al Inventario:** Botones para consultar existencias vivas y registrar movimientos de mercancía al instante.

---

## 4. Gestión del Catálogo de Productos

El catálogo reúne todos los alimentos frescos y abarrotes disponibles para la venta.

### Cómo Dar de Alta un Producto Nuevo
1. En el menú lateral, selecciona **"Productos"**.
2. Haz clic en el botón superior **"+ Nuevo Producto"**.
3. Diligencia la información solicitada:
   - **Nombre Comercial:** Nombre claro del artículo (por ejemplo: *Manzana Royal Gala 1kg*).
   - **Código o Referencia:** Código de barras o identificador único para la caja.
   - **Categoría y Proveedor:** Selecciona la familia a la que pertenece y la empresa proveedora.
   - **Precio de Venta:** Valor de venta al público en moneda local.
   - **Stock Inicial y Stock Mínimo:** Cantidad con la que inicia en bodega y el número de seguridad que activará las alertas preventivas si las existencias bajan demasiado.
   - **Fotografía:** Puedes subir una foto desde tu computador o ingresar una dirección web de imagen.
4. Presiona **"Guardar Producto"** y quedará disponible inmediatamente.

### Modificar o Desactivar Productos
- **Editar:** Pulsa el botón con el icono de lápiz para cambiar precios, nombres, descripciones o imágenes.
- **Suspender:** Puedes pausar temporalmente un producto sin borrarlo usando el interruptor de activación. Esto es útil cuando un alimento es de temporada y no estará disponible por unos meses.
- **Eliminar:** Solo se permite borrar productos que no tengan ventas registradas en el historial para proteger la contabilidad.

---

## 5. Control de Inventario y Movimientos de Bodega

El módulo de Inventario te permite saber con total certeza qué hay en bodega en este instante sin tener que hacer conteos a ciegas.

### Cómo Registrar Entradas, Salidas y Ajustes
1. Ubica el producto deseado en la tabla de inventario y pulsa el botón **"Mover"**.
2. En la ventana emergente, escoge el tipo de operación:
   - **Entrada:** Se usa cuando llega un camión de reposición o una compra a un proveedor. Suma unidades al stock.
   - **Salida:** Se usa para registrar mermas, frutas en mal estado, productos vencidos o roturas. Resta unidades del stock.
   - **Ajuste:** Se usa tras un conteo físico para fijar manualmente el número real si hubo algún descuadre.
3. Escribe la cantidad de unidades y una justificación breve para la auditoría (por ejemplo: *Reposición de mercancía según factura de proveedor*).
4. Pulsa **"Registrar"**. El inventario se actualiza en ese mismo segundo.

---

## 6. Sistema Inteligente de Alertas de Stock Bajo

Para que el supermercado nunca se quede sin productos estrella, el sistema avisa constantemente sobre existencias críticas:

- **Indicador Permanente en el Menú Lateral:** Aparece una insignia llamativa con el número exacto de productos en riesgo.
- **Banner de Alerta Superior:** Un mensaje en la parte alta de la pantalla lista los artículos más urgentes por reabastecer.
- **Filtro Rápido en Inventario:** Un botón exclusivo para ver únicamente los productos que requieren compra urgente, ocultando los que tienen stock suficiente.
- **Advertencias en Caja:** Si al cobrar una venta un producto queda por debajo de su límite de seguridad o se agota, el sistema emite una alerta preventiva para que el cajero informe al área de compras.

---

## 7. Punto de Venta (POS) y Registro de Ventas en Caja

El módulo de Ventas está optimizado para cobrar rápido y con precisión en la línea de cajas.

### Cómo Registrar una Venta
1. Haz clic en el botón **"+ Nueva Venta"** para abrir el punto de cobro.
2. **Seleccionar Artículos:** Escoge los productos comprados por el cliente. El sistema muestra su precio, el stock actual y advierte si quedan pocas unidades.
3. **Definir Cantidades:** Usa los botones de aumento o escribe la cantidad directamente. Si el cliente lleva varios productos diferentes, pulsa **"+ Agregar otro producto"**.
4. **Seleccionar el Cliente:** Aquí puedes elegir entre dos opciones:
   - **Público General:** Para ventas rápidas de mostrador donde no se solicitan datos del comprador.
   - **Cliente Registrado del Sistema:** El selector lista con nombre, correo y teléfono a todos los clientes dados de alta en la plataforma, permitiendo asociar la compra a su cuenta oficial.
5. **Forma de Pago:** Selecciona si el pago se realiza en Efectivo, Tarjeta de Débito/Crédito o Transferencia Bancaria.
6. Pulsa **"Registrar Venta"**.
7. En ese instante, el sistema:
   - Descuenta las unidades vendidas de la bodega automáticamente.
   - Registra la salida en el libro de auditoría de inventario.
   - Genera de inmediato el comprobante oficial de venta.
   - Te permite imprimir la factura en papel o descargarla en formato digital PDF.

---

## 8. Familias de Alimentos y Directorio de Proveedores

### Familias de Categorías
Permite ordenar los productos por grupos lógicos (Frutas y Verduras, Lácteos, Panadería, Carnes y Abarrotes). Ayuda a que los clientes encuentren rápido lo que buscan y permite generar reportes de ventas por departamento.

### Directorio de Proveedores
Conserva la agenda comercial de las empresas aliadas:
- Nombre de la empresa o razón social.
- Persona de contacto directo.
- Teléfono móvil, correo electrónico corporativo y dirección de entrega.

---

## 9. Gestión de Cuentas y Asignación de Roles

Este módulo es de **acceso exclusivo para el Administrador** y se encuentra en la opción de Usuarios.

### Cómo Decidir Quién es Empleado
- Cuando una persona nueva se registra en la página web, entra automáticamente al sistema con el rol de **Cliente**.
- El Administrador entra al módulo de usuarios y encuentra a las personas recién registradas en los primeros lugares de la lista.
- Si esa persona forma parte del equipo de trabajo de la tienda, el Administrador solo debe pulsar el botón **"Hacer Empleado"**.
- En ese mismo instante, el usuario adquiere los permisos operativos de inventario y catálogo.
- Si un trabajador finaliza su contrato o pasa a ser cliente habitual, el Administrador presiona **"Pasar a Cliente"** para revocar sus permisos internos sin borrar sus registros históricos.

### Filtros Rápidos de Usuarios
El panel cuenta con tarjetas interactivas y pestañas para ver con un clic:
- **Todos los Usuarios:** El padrón completo de cuentas.
- **Solo Clientes:** Para evaluar nuevos registros y decidir promociones a empleados.
- **Solo Empleados:** Para supervisar al personal operativo activo.
- **Solo Administradores:** Las cuentas con llaves maestras del sistema.

---

## 10. Reportes Estadísticos y Exportaciones Oficiales

El módulo de Reportes reúne la inteligencia financiera y comercial del negocio por año y mes:
- **Panel Ejecutivo de Ganancias Netas:** Presenta en primer plano las ganancias netas del período, el margen porcentual de rentabilidad y el balance entre ingresos por ventas y compras a proveedores.
- **Comportamiento Mensual de Ventas y Ganancias:** Desglose interactivo mes a mes comparando la facturación recaudada, las compras realizadas y la ganancia neta obtenida en cada mes.
- **Los Diez Productos Más Vendidos:** Ranking de los alimentos preferidos por los clientes, tanto en volumen de unidades como en ingresos brutos generados.
- **Balance Consolidado de Stock Crítico:** Lista lista para el departamento de compras con las existencias que deben ordenarse a proveedores.
- **Gasto Operativo por Proveedor:** Distribución de compras e inversión por cada aliado comercial.

### Opciones de Descarga
- **Exportación en PDF:** Genera un documento formal con membrete institucional, balance de ganancias netas, desglose mensual y tablas analíticas listas para imprimir o enviar a la gerencia.
- **Exportación en Excel:** Descarga una hoja de cálculo limpia y ordenada compatible con Microsoft Excel y Google Sheets, incluyendo las métricas de ganancias, márgenes y ventas para análisis contable avanzado.

---

## 11. Acceso a Todo el Sistema y Seguridad Integral

La plataforma SuperFresco fue diseñada para proteger la información del supermercado sin entorpecer el trabajo diario:

- **Acceso a Todo el Sistema Reservado para el Administrador:** Ningún otro perfil puede ingresar a los módulos de auditoría, usuarios, proveedores o configuración general.
- **Protección contra Intentos de Acceso No Autorizado:** Si un cliente o empleado intenta ingresar manualmente a una pantalla prohibida, el sistema bloquea su paso al instante y le muestra un aviso de acceso denegado.
- **Contraseñas Cifradas:** Las claves de acceso viajan y se guardan con algoritmos matemáticos de máxima seguridad. Nadie en la empresa puede ver la contraseña de otra persona.
- **Desactivación Rápida:** Si se extravía un dispositivo o se retira un colaborador, la cuenta se puede suspender en un clic, impidiendo que vuelva a ingresar sin necesidad de borrar información histórica.
