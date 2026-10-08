# Diccionario de Datos y Modelo de Información — SuperFresco

Este documento describe con claridad y en lenguaje completamente humano cómo está organizada la información del supermercado **SuperFresco**, cómo se relacionan entre sí los registros y cuáles son las reglas que garantizan que los datos nunca se pierdan ni se desordenen.

Aquí no se utilizan comandos de programación ni sintaxis técnica de bases de datos. Todo está explicado desde la perspectiva del funcionamiento real de un supermercado moderno.

---

## Índice

1. [Visión General de la Información del Negocio](#1-visión-general-de-la-información-del-negocio)
2. [Entidades y Grupos de Información](#2-entidades-y-grupos-de-información)
3. [Relaciones Naturales entre Módulos](#3-relaciones-naturales-entre-módulos)
4. [Reglas de Seguridad e Integridad de los Datos](#4-reglas-de-seguridad-e-integridad-de-los-datos)
5. [Acceso a Todo el Sistema y Niveles de Datos](#5-acceso-a-todo-el-sistema-y-niveles-de-datos)

---

## 1. Visión General de la Información del Negocio

Para que un supermercado funcione sin tropiezos, toda su información debe estar conectada como los engranajes de un reloj:
- Los **productos** necesitan saber a qué **familia de alimentos** pertenecen y qué **proveedor** los surte.
- Cada **venta** debe saber qué **cajero** la cobró, qué **cliente** la realizó y qué **artículos** se entregaron.
- Cada salida de bodega debe registrar exactamente **quién** autorizó el movimiento y **por qué motivo**.

Para lograr esto, la base de datos se estructura en grupos especializados que trabajan en perfecta sincronía.

---

## 2. Entidades y Grupos de Información

A continuación se detalla cada grupo de información que compone el sistema:

### 1. Roles y Perfiles de Autorización
Define los niveles de mando y alcance dentro del sistema:
- **Administrador:** Tiene el control total y exclusivo para gestionar cuentas, decidir quién es empleado y supervisar la empresa.
- **Empleado:** Personal operativo de bodega y pasillos con acceso a inventario y catálogo.
- **Cliente:** Usuarios compradores registrados en la tienda web.

### 2. Cuentas de Usuarios
Conserva las credenciales y los datos de las personas que ingresan al sistema:
- Nombres y apellidos completos.
- Correo electrónico oficial (utilizado como identificador único de ingreso).
- Contraseña cifrada matemáticamente para máxima seguridad.
- Teléfono móvil de contacto.
- Rol asignado (Administrador, Empleado o Cliente).
- Estado de la cuenta (Activa o Bloqueada preventivamente).
- Fecha y hora exacta de registro.

### 3. Familias y Categorías de Alimentos
Agrupa los artículos para facilitar su búsqueda en la tienda y la elaboración de estadísticas por pasillo:
- Nombre de la categoría (por ejemplo: *Frutas y Verduras*, *Lácteos*, *Panadería*, *Carnes*).
- Descripción informativa sobre los alimentos que contiene.
- Estado de visibilidad en tienda.

### 4. Proveedores Comerciales
La agenda de las empresas aliadas que abastecen al supermercado:
- Razón social o nombre de la empresa proveedora.
- Nombre de la persona o asesor de contacto directo.
- Teléfono comercial y correo electrónico para órdenes de compra.
- Dirección física de despacho o bodegas de origen.

### 5. Catálogo de Productos
La ficha completa de cada alimento disponible para la venta:
- Nombre comercial del producto (por ejemplo: *Fresas Orgánicas 500g*).
- Código único de barras o referencia para caja registradora.
- Categoría a la que pertenece y proveedor que lo suministra.
- Precio oficial de venta al público en moneda nacional.
- Cantidad actual disponible en bodega (stock vivo).
- Umbral de existencia mínima de seguridad (el límite que activa las alertas cuando las existencias bajan peligrosamente).
- Fotografía oficial del producto.
- Estado del producto (Activo para venta o Pausado temporalmente).

### 6. Registro de Clientes Comerciales
Guarda los datos de los compradores para emitir facturas legales y asociar ventas:
- Nombre completo o razón social del comprador.
- Número de documento de identidad, cédula o identificación tributaria.
- Teléfono móvil y correo electrónico para envío de facturas digitales.
- Dirección de entrega o domicilio.
> **Sincronización:** Cada vez que un usuario se registra en la tienda web con el perfil de Cliente, el sistema lo enlaza automáticamente con este registro para que el cajero pueda seleccionarlo al cobrar una venta.

### 7. Ventas en Caja y Facturación
La cabecera de cada cobro realizado en el supermercado:
- Número consecutivo oficial de la venta.
- Fecha y hora exacta en la que se cobró.
- Cajero u operador que atendió la transacción.
- Cliente al que se le facturó (o asignación automática a *Público General* si es venta rápida de mostrador).
- Método de pago empleado (Efectivo, Tarjeta de Débito/Crédito o Transferencia Bancaria).
- Estado de la operación (Completada con éxito, Pendiente de cobro o Cancelada).
- Valor total cobrado en pesos.

### 8. Detalle de Ítems Vendidos
Cada renglón que aparece impreso en la factura de venta:
- Venta a la que pertenece.
- Producto específico que se entregó.
- Cantidad de unidades vendidas.
- Precio unitario al que se vendió en ese momento histórico.
- Subtotal calculado para ese artículo.

### 9. Historial y Auditoría de Movimientos de Inventario
El libro de control estricto que registra todo lo que entra y sale de la bodega:
- Producto que experimentó el movimiento.
- Cantidad exacta de unidades sumadas o restadas.
- Tipo de movimiento: Entrada por reposición, Salida por merma/vencimiento o Ajuste por conteo físico.
- Fecha y hora exacta de la operación.
- Empleado o Administrador que realizó el registro.
- Motivo o justificación de la operación.

### 10. Compras a Proveedores y Detalle de Adquisiciones
El registro de los pedidos de abastecimiento realizados por la empresa para reponer la bodega:
- Fecha de compra y proveedor emisor.
- Comprador institucional que ordenó el pedido.
- Desglose de productos, cantidades recibidas y costo total de la adquisición.

---

## 3. Relaciones Naturales entre Módulos

La información nunca vive aislada; cada entidad colabora con las demás:

- **Un Usuario tiene un único Rol asignado:** El sistema sabe de inmediato si es Administrador, Empleado o Cliente.
- **Un Producto pertenece a una Categoría y a un Proveedor:** Si se consulta una manzana, se sabe que es fruta y quién la vendió.
- **Una Venta contiene múltiples Detalles de Productos:** Una sola factura puede incluir diez productos diferentes sin confundir sus precios.
- **Una Venta descuenta Inventario automáticamente:** Cuando se cobra una venta, el sistema genera de forma transparente una salida de inventario para que el stock de bodega coincida exactamente con la realidad física.

---

## 4. Reglas de Seguridad e Integridad de los Datos

Para proteger el negocio contra descuidos o errores humanos, la base de datos aplica reglas estrictas:

- **Prohibido borrar productos con historial contable:** Si un producto ya fue vendido en el pasado, el sistema prohíbe eliminarlo por completo para no descuadrar los balances ni dejar facturas vacías. En su lugar, permite inactivarlo para que no aparezca en ventas nuevas.
- **Protección de Identificadores Únicos:** No es posible registrar dos usuarios con el mismo correo electrónico, ni dos productos con el mismo código de barras.
- **Contraseñas Invisibles:** Las claves se transforman con un algoritmo matemático irreversible. Incluso si alguien revisara directamente los archivos de la base de datos, solo vería códigos encriptados indescifrables.
- **Conservación de Registros por Salida de Personal:** Si un trabajador deja de laborar en el supermercado, su cuenta se bloquea con el interruptor de estado, pero sus ventas pasadas y sus movimientos de inventario se conservan intactos para la auditoría de la empresa.

---

## 5. Acceso a Todo el Sistema y Niveles de Datos

A nivel de información, el acceso está jerarquizado para que nadie pueda ver ni alterar datos ajenos a su función:

- **Nivel Total (Administrador):** Puede consultar y modificar todas las entidades del negocio sin excepción. Es el único que tiene acceso a la entidad de usuarios y roles, por lo que **solo el Administrador decide si un cliente pasa a ser empleado**.
- **Nivel Operativo (Empleado):** Solo puede consultar y actualizar productos e inventario. Tiene bloqueado el acceso a la tabla de usuarios, a las cuentas del personal y a la reasignación de permisos.
- **Nivel de Consulta (Cliente):** Solo puede leer los productos activos y los precios públicos. No tiene acceso de escritura sobre el catálogo ni puede ver la información interna de costos, compras o inventario.

Gracias a este modelo de datos, SuperFresco garantiza una contabilidad limpia, un inventario confiable y un blindaje total de la información de la empresa.
