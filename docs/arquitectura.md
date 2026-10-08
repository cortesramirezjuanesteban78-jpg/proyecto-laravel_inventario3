# Arquitectura General del Sistema — SuperFresco

Este documento explica de forma clara, didáctica y en lenguaje totalmente humano cómo está estructurado el sistema de **SuperFresco**, cómo se comunican sus distintos componentes y cuáles son los principios de diseño que lo convierten en una plataforma segura, ágil y preparada para el crecimiento comercial.

Aquí no se incluyen diagramas de código de programación ni fragmentos técnicos complejos. Todo se describe para que cualquier persona interesada en el proyecto entienda la ingeniería detrás de la solución.

---

## Índice

1. [Visión General de la Solución](#1-visión-general-de-la-solución)
2. [Las Capas que Componen el Sistema](#2-las-capas-que-componen-el-sistema)
3. [Flujos de Información en Operaciones Clave](#3-flujos-de-información-en-operaciones-clave)
4. [Acceso a Todo el Sistema y Modelo de Autorización](#4-acceso-a-todo-el-sistema-y-modelo-de-autorización)
5. [Criterios de Rendimiento, Seguridad y Estabilidad](#5-criterios-de-rendimiento-seguridad-y-estabilidad)

---

## 1. Visión General de la Solución

SuperFresco fue concebido como una plataforma modular donde cada parte cumple una función muy específica para evitar que los errores se propaguen y para que el sistema sea fácil de mantener y evolucionar.

La arquitectura se divide en cinco grandes áreas de trabajo:
- **La Interfaz del Usuario:** Lo que las personas ven e interactúan en sus pantallas (computadores de caja, oficinas administrativas o teléfonos móviles).
- **Los Filtros de Protección:** La aduana digital que inspecciona cada solicitud para comprobar que la persona tenga permiso de estar allí.
- **Los Coordinadores de Procesos:** Los encargados de recibir las órdenes, verificar que no falten datos y solicitar las operaciones necesarias.
- **Las Reglas del Negocio:** El conocimiento del supermercado sobre precios, existencias, cálculo de impuestos, alertas de stock y validación de roles.
- **La Bodega de Datos y Archivos:** El lugar seguro donde se guardan permanentemente los registros contables, las ventas históricas y las fotografías de los productos.

---

## 2. Las Capas que Componen el Sistema

Para entender cómo viaja la información dentro del supermercado, analicemos cada una de sus capas de trabajo:

### Capa 1: Presentación y Experiencia Visual
Es la cara visible del supermercado. Se caracteriza por:
- Un diseño visual prémium con tonos verdes bosque, esmeraldas y dorados que evocan frescura, higiene y alimentos gourmet.
- Pantallas que se adaptan automáticamente a cualquier tamaño de pantalla (computadores de escritorio, portátiles o tabletas).
- Formularios interactivos con validación inmediata, para que si un usuario olvida un dato o escribe un correo incompleto, el sistema le indique exactamente qué corregir antes de enviar la información.

### Capa 2: Seguridad, Sesiones y Portería Digital
Antes de que cualquier solicitud llegue al interior del sistema, pasa por una serie de revisiones de seguridad:
- **Comprobación de Identidad:** Verifica si la persona ya inició sesión con su cuenta oficial o si es un visitante anónimo.
- **Sello Contra Fraudes:** A cada formulario se le asigna un sello digital invisible único para garantizar que la solicitud provenga genuinamente del usuario y no de un programa malicioso externo.
- **Protección de Navegación:** Impide que personas que no han iniciado sesión puedan ver información interna del negocio.

### Capa 3: Coordinación y Toma de Decisiones
Cuando la solicitud pasa la seguridad, llega a los coordinadores del sistema:
- Si el usuario solicitó el inventario, el coordinador correspondiente pide el balance de existencias, calcula cuáles productos tienen stock bajo y arma la pantalla de respuesta.
- Si el usuario envió una venta en caja, el coordinador verifica que haya stock suficiente de cada artículo antes de descontarlo, calcula el monto total y ordena la creación de la factura oficial.

### Capa 4: Reglas del Negocio y Entidades
Es el corazón inteligente de SuperFresco. Aquí se aplican las políticas comerciales de la tienda:
- Las existencias nunca pueden quedar en negativo sin una advertencia.
- Toda venta descuenta automáticamente las unidades de la bodega y deja constancia en el libro de auditoría.
- Si un producto cae por debajo de su umbral mínimo, se activa inmediatamente una alerta visual en todos los paneles operativos.
- Las cuentas de usuario tienen un único rol activo que determina con exactitud su nivel de acceso.

### Capa 5: Almacenamiento y Persistencia Segura
Es donde descansan todos los datos del negocio:
- **Base de Datos Principal:** Almacena de forma ordenada las ventas, usuarios, clientes, productos, categorías y proveedores.
- **Bodega de Imágenes:** Guarda las fotografías oficiales de los productos, asignándoles nombres digitales únicos para que nunca se confundan ni se sobrescriban.

---

## 3. Flujos de Información en Operaciones Clave

Para ilustrar cómo colaboran las capas, veamos tres procesos habituales del negocio:

### Flujo de Registro de una Venta en Caja (Punto de Venta)
1. El cajero abre el punto de venta, selecciona los artículos comprados y define las cantidades.
2. Escoge si la venta es a **Público General** o selecciona a uno de los **Clientes Registrados** del sistema.
3. El sistema valida al instante que haya stock suficiente para cada producto.
4. Se calcula automáticamente el subtotal y el total con impuestos.
5. Al pulsar confirmar, la base de datos registra la venta, descuenta las unidades de bodega y genera la salida en el libro de movimientos de inventario.
6. La pantalla devuelve la confirmación con el botón para imprimir la factura física o descargar el documento digital en PDF.

### Flujo de Subida y Publicación de una Foto de Producto
1. El administrador o empleado adjunta una foto desde su computador.
2. El sistema revisa que el archivo sea una imagen válida y que su peso no sobrecargue el servidor.
3. Se genera un identificador digital único para el archivo y se almacena en la bodega de imágenes.
4. El producto se enlaza con su nueva foto y esta queda visible de inmediato tanto en el catálogo administrativo como en la tienda web pública.

### Flujo de Emisión de Reportes Financieros
1. La gerencia solicita el informe de ventas y stock crítico de un año específico.
2. El sistema compila todas las transacciones de ese período, organiza las ventas mes a mes y calcula el ranking de los productos más vendidos.
3. Genera un documento formal con diseño institucional y encabezado oficial listo para imprimir en PDF o exporta una hoja de cálculo en Excel para auditorías contables.

---

## 4. Acceso a Todo el Sistema y Modelo de Autorización

El control de accesos es uno de los pilares arquitectónicos más cuidados de SuperFresco. Su diseño garantiza que nadie pueda exceder los límites de su responsabilidad:

### Regla Fundamental de Auto-Registro
Cuando cualquier persona ajena a la empresa visita la página web y decide registrarse, el sistema le asigna de forma estricta y automática el rol de **Cliente**. 

Bajo ninguna circunstancia un usuario externo puede convertirse a sí mismo en empleado ni en administrador. El sistema rechaza cualquier intento de manipulación en el registro público.

---

### La Autoridad Exclusiva del Administrador
El **Administrador** es la única persona que tiene acceso a la consola de usuarios y roles:
- Puede revisar a todos los clientes que se han registrado en la plataforma.
- Tiene la potestad exclusiva de decidir quién labora en el negocio y asignarle el rol de **Empleado** con un solo clic mediante el botón **"Hacer Empleado"**.
- Si un trabajador finaliza sus labores o pasa a ser cliente habitual, el Administrador pulsa **"Pasar a Cliente"**, revocando inmediatamente sus permisos de bodega e inventario sin perder su historial comercial.

---

### Pirámide de Accesos del Sistema

A nivel de arquitectura, el sistema aplica tres niveles de aislamiento:

```
                  ┌───────────────────────────────┐
                  │       ADMINISTRADOR           │
                  │  Acceso Total a Todo el       │
                  │  Sistema y Gestión de Roles   │
                  └───────────────┬───────────────┘
                                  │
                  ┌───────────────┴───────────────┐
                  │          EMPLEADO             │
                  │  Operación de Bodega, Stock,  │
                  │  Inventario y Catálogo        │
                  └───────────────┬───────────────┘
                                  │
                  ┌───────────────┴───────────────┐
                  │          CLIENTE              │
                  │  Catálogo Público, Precios    │
                  │  y Compras en Tienda Virtual  │
                  └───────────────────────────────┘
```

- **El Administrador:** Tiene acceso a todas las pantallas, configuraciones, usuarios, ventas y reportes financieros.
- **El Empleado:** Solo tiene acceso a los módulos operativos diarios (Catálogo e Inventario). Las pantallas de usuarios, proveedores, reportes ejecutivos y configuración le están completamente bloqueadas.
- **El Cliente:** Solo tiene acceso al Catálogo y la Tienda Web para explorar productos y planear sus compras. Cualquier intento de entrar a áreas internas del supermercado es bloqueado por el sistema con un aviso de acceso denegado.

---

### Menú Lateral Inteligente
La barra de navegación del sistema cambia de forma dinámica según la persona que inició sesión:
- Al Administrador le despliega todas las herramientas de la empresa.
- Al Empleado le muestra únicamente las herramientas de bodega y catálogo con sus alertas de stock.
- Al Cliente le muestra únicamente las opciones de compra y productos.

Además de ocultar las opciones en el menú, **cada pantalla verifica internamente la autorización del usuario**, de modo que incluso si alguien intentara escribir manualmente la dirección web de una pantalla restringida en su navegador, el sistema le impedirá el paso de manera categórica.

---

## 5. Criterios de Rendimiento, Seguridad y Estabilidad

La plataforma fue diseñada para operar de forma fluida y confiable:
- **Consultas Optimizadas:** Al abrir la lista de productos o el historial de ventas, el sistema carga los datos de forma inteligente en una sola consulta agrupada para no ralentizar el servidor.
- **Cifrado de Alta Seguridad:** Las contraseñas de todos los usuarios se transforman con algoritmos matemáticos irreversibles, garantizando la privacidad de clientes y empleados.
- **Operaciones Transaccionales en Caja:** Al registrar una venta, el cobro y la salida de inventario se realizan como una sola unidad indivisible. Si ocurriera algún fallo eléctrico o de red en ese instante, el sistema deshace la operación incompleta para evitar descuadres de dinero o existencias fantasmas en bodega.

Con esta arquitectura, SuperFresco ofrece una experiencia de compra placentera para los clientes y una herramienta de gestión sólida, segura y eficiente para el equipo de trabajo.
