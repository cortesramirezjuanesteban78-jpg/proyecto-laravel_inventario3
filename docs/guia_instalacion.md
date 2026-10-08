# Guía Práctica de Instalación y Puesta en Marcha — SuperFresco

Esta guía explica, paso a paso y de manera sencilla y humana, cómo instalar, encender y dejar completamente operativo el sistema de **SuperFresco** en cualquier computador personal o servidor local.

Está redactada para que cualquier persona, sin importar su nivel de experiencia técnica, pueda seguir las instrucciones sin enredos y comprenda qué está sucediendo en cada etapa.

---

## Índice

1. [Programas y Requisitos Previos](#1-programas-y-requisitos-previos)
2. [Pasos para la Instalación y Puesta en Marcha](#2-pasos-para-la-instalación-y-puesta-en-marcha)
3. [Cuentas de Acceso Iniciales y Roles](#3-cuentas-de-acceso-iniciales-y-roles)
4. [Mantenimiento y Cuidados del Sistema](#4-mantenimiento-y-cuidados-del-sistema)
5. [Solución de Inconvenientes Comunes](#5-solución-de-inconvenientes-comunes)

---

## 1. Programas y Requisitos Previos

Antes de comenzar la instalación, asegúrate de tener instaladas las herramientas básicas de trabajo en tu equipo:

- **Entorno de Trabajo Local:** Se recomienda utilizar **Laragon** en sistemas Windows, ya que incluye de fábrica el servidor web, el motor de base de datos y la terminal de trabajo lista para usar.
- **Motor de Base de Datos:** Contar con el servicio de MySQL activo en el computador.
- **Manejador de Paquetes:** La herramienta de gestión de dependencias del sistema instalada y actualizada.
- **Navegador Web Moderno:** Google Chrome, Microsoft Edge, Mozilla Firefox o cualquier navegador actualizado para ver e interactuar con la tienda y el panel de control.

---

## 2. Pasos para la Instalación y Puesta en Marcha

Sigue estos pasos en orden para poner en funcionamiento la plataforma:

### Paso 1: Ubicar la carpeta del proyecto
Coloca la carpeta del proyecto dentro del directorio de trabajo de tu servidor local. Si utilizas Laragon, la ubicación estándar es la carpeta de aplicaciones web dentro del disco principal.

### Paso 2: Descarga de las librerías del sistema
Abre la terminal de tu entorno de trabajo en la carpeta del proyecto y solicita la instalación de las librerías. El gestor se encargará de descargar automáticamente todos los componentes que necesita la plataforma, incluyendo el generador de facturas y los motores de diseño.

### Paso 3: Configurar la libreta de ajustes del entorno
El sistema cuenta con un archivo de ajustes donde se definen las conexiones principales:
- Toma el archivo de plantilla de configuración y crea una copia con el nombre de configuración activa del sistema.
- Abre dicho archivo con cualquier editor de texto y revisa que el nombre de la base de datos y el puerto de conexión coincidan con los de tu motor de base de datos local (por lo general, el puerto suele ser el estándar o el asignado por Laragon en tu máquina).

### Paso 4: Generar el sello digital de seguridad
Solicita al sistema que cree su clave maestra de cifrado. Esta clave es un sello digital único que el supermercado utiliza para proteger las sesiones de los usuarios, encriptar las comunicaciones y resguardar la privacidad de los clientes.

### Paso 5: Enlazar la bodega de fotografías de productos
Para que las fotos que subas de frutas, verduras y abarrotes se muestren de inmediato en la tienda online y en el catálogo, es necesario activar el enlace de almacenamiento público. Esto conecta la carpeta interna de archivos con el visor público de la página web.

### Paso 6: Construir las tablas y roles en la base de datos
Ejecuta la instrucción de construcción de base de datos con carga inicial. Esta acción creará de forma automática todas las tablas necesarias (usuarios, roles, productos, categorías, proveedores, inventario, ventas y facturas) e insertará los tres perfiles base del negocio: **Administrador**, **Empleado** y **Cliente**.

### Paso 7: Encender el servidor y comenzar
Inicia el servidor local de la aplicación. Una vez encendido, abre tu navegador de preferencia y visita la dirección local del sistema en el puerto habitual para ver la tienda de SuperFresco en vivo y en directo.

---

## 3. Cuentas de Acceso Iniciales y Roles

Una vez finalizada la preparación inicial, el sistema queda listo con dos cuentas principales para que puedas ingresar y comenzar a trabajar:

### Cuenta del Administrador Principal
- **Correo electrónico:** admin@superfresco.com
- **Contraseña inicial:** admin123
- **Alcance:** Tiene acceso irrestricto y absoluto a todos los módulos: creación y administración de usuarios, asignación de roles, inventario, compras, ventas y reportes ejecutivos.

### Cuenta del Empleado Operativo
- **Correo electrónico:** cortes@gmail.com
- **Contraseña inicial:** 123456
- **Alcance:** Acceso operativo para registrar movimientos de bodega, actualizar precios y existencias de productos, y monitorear el inventario diario.

> **Importante sobre nuevos registros:** Cualquier persona externa que cree una cuenta desde el formulario público de la tienda ingresará de forma automática y estricta como **Cliente**. Solo el Administrador principal tiene la potestad de decidir si le asigna el rol de **Empleado** desde el panel interno de gestión de usuarios.

---

## 4. Mantenimiento y Cuidados del Sistema

Para conservar la plataforma ágil, estable y rápida a lo largo del tiempo, ten en cuenta las siguientes recomendaciones prácticas:

- **Limpieza periódica de archivos temporales:** Con el uso constante, el sistema guarda copias temporales de pantallas y ajustes para acelerar la carga. Si realizas cambios visuales o ajustas configuraciones y no los ves reflejados de inmediato en pantalla, solicita al sistema limpiar la memoria temporal.
- **Revisión del estado de la base de datos:** Siempre asegúrate de que el servicio de MySQL esté encendido en tu panel de Laragon antes de iniciar el servidor web, de lo contrario la página no podrá cargar los productos.
- **Resguardo de contraseñas:** Una vez instalado el proyecto en un entorno real o productivo, se recomienda cambiar inmediatamente las contraseñas iniciales por defecto desde el módulo de gestión de usuarios.

---

## 5. Solución de Inconvenientes Comunes

Si al encender el sistema encuentras algún contratiempo, aquí tienes las soluciones más habituales explicadas con sencillez:

- **Mensaje de fallo de conexión con la base de datos:**
  * *Causa habitual:* El puerto configurado en el archivo de ajustes no coincide con el puerto donde está encendido MySQL en Laragon.
  * *Cómo solucionarlo:* Haz clic derecho en el icono de Laragon, dirígete a la sección de MySQL y verifica qué puerto tiene asignado. Ajusta ese mismo número en tu archivo de configuración del sistema y vuelve a probar.

- **Mensaje sobre roles faltantes al registrar usuarios:**
  * *Causa habitual:* La base de datos se creó pero aún no se le cargaron los perfiles iniciales de acceso.
  * *Cómo solucionarlo:* Solicita la ejecución de la siembra inicial de datos para restablecer los roles de Administrador, Empleado y Cliente en las tablas del supermercado.

- **Las imágenes de los productos no se visualizan en la tienda:**
  * *Causa habitual:* Falta crear el enlace de almacenamiento entre la carpeta de guardado y la vista pública.
  * *Cómo solucionarlo:* Activa el enlace de almacenamiento del sistema para que las imágenes queden inmediatamente visibles al público.
