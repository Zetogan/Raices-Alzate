# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# Changelog

Todos los cambios importantes realizados en **Raíces Alzate** se registran en este documento.

El proyecto se encuentra en desarrollo desde **2025**. Las fechas registradas corresponden a sesiones de trabajo, avances y actualizaciones realizadas durante las diferentes etapas del proyecto.

El formato de este documento está inspirado en las convenciones de **Keep a Changelog**, con el propósito de mantener un historial claro, organizado y comprensible.

---

# [v0.1.1] — En desarrollo

## 📅 13 de octubre de 2026

### 🧭 Refinamiento del portal y navegación compartida

#### Agregado

* Incorporación y ajuste de un encabezado público compartido para mantener la consistencia visual entre las páginas del portal.
* Aplicación de un encabezado de aproximadamente 72 px de altura, con fondo blanco, borde inferior y distribución adaptable.
* Ajustes de alineación, espaciado, logotipo, marca institucional y acciones de navegación.
* Incorporación del comportamiento *sticky* para mantener visible el encabezado durante el desplazamiento.
* Reorganización de los estilos compartidos del portal mediante hojas de estilos modulares.

#### Cambiado

* Revisión de `includes/header.php`, `components/header.php`, `assets/css/header.css` y `assets/css/portal.css`, según los cambios reportados.
* Ajustes de los estilos responsive para mejorar la presentación del encabezado en diferentes tamaños de pantalla.
* Revisión del comportamiento de los enlaces y de la navegación compartida.
* Continuación de la unificación visual entre las páginas públicas y el resto de la plataforma.

> **Nota:** El ancho máximo definitivo del contenedor del encabezado debe verificarse en el código, ya que los informes de trabajo registran referencias diferentes durante los ajustes.

### 📊 Refinamiento del Dashboard Desktop y Mobile

#### Agregado

* Consolidación de la estructura visual del Dashboard privado en `pages/dashboard.php`.

* Organización del contenido principal mediante secciones de bienvenida, tarjetas de resumen, actividad reciente, acciones rápidas y próximas oportunidades.

* Incorporación de un saludo personalizado a partir del nombre almacenado en `$_SESSION['user']['nombre']`, con un mensaje alternativo cuando no está disponible.

* Incorporación del mensaje institucional:

  > Tus raíces siguen creciendo. Conecta con tu comunidad, descubre proyectos y encuentra nuevas oportunidades para construir tu futuro.

* Organización de las tarjetas de resumen relacionadas con el perfil, los proyectos y las oportunidades.

* Continuación de los ajustes de presentación para escritorio y dispositivos móviles.

#### Cambiado

* Eliminación del enlace redundante **Inicio** de `includes/sidebar.php`, manteniendo la navegación del área privada centrada en Dashboard, Mi perfil, Proyectos, Oportunidades, Configuración y Cerrar sesión.
* Ajuste del botón **Actualizar mi perfil** para evitar que los estilos globales de `.btn-primary` alteren sus dimensiones.
* Aislamiento de las reglas específicas del Dashboard mediante selectores como `.dashboard-actions .btn-primary`.
* Ajuste del botón principal del Dashboard con una altura de referencia de 44 px, ancho adaptable, ancho mínimo de 180 px, espaciado horizontal y texto en una sola línea.
* Revisión de los estilos en `assets/css/dashboard.css` para reducir las colisiones con otros componentes.

### 🧩 Barra lateral colapsable y navegación responsive

#### Agregado

* Incorporación de un botón para expandir o contraer la barra lateral mediante el identificador `#sidebarToggleBtn`.
* Implementación del modo expandido de la barra lateral, con un ancho de referencia de 240 px.
* Implementación del modo contraído o *mini-sidebar*, con un ancho de referencia de 76 px.
* Incorporación de iconos centrados en el modo contraído y ocultamiento de las etiquetas de texto.
* Persistencia de la preferencia del usuario mediante `localStorage`, con la clave `sidebar_collapsed`.
* Incorporación de un mecanismo preventivo para reducir el parpadeo visual al cargar la página (*Flash of Unstyled Content*, FOUC).
* Ajustes de la transición entre la barra lateral y el área principal del Dashboard.
* Revisión del comportamiento de la barra lateral en pantallas móviles.

#### Cambiado

* Separación del comportamiento de escritorio y móvil.
* En escritorio, la barra lateral puede permanecer expandida o contraída según la preferencia guardada.
* En móvil, la navegación utiliza un panel lateral desplegable (*drawer*) con fondo de superposición (*backdrop*).
* Organización de los estilos y comportamientos relacionados con la navegación en `includes/sidebar.php`, `pages/dashboard.php` y `assets/css/dashboard.css`.

### 📱 Ajustes de la barra superior móvil

#### Agregado

* Organización de la barra superior móvil mediante `includes/topbar.php`.
* Ubicación de la información del usuario, el avatar y el rol en el lado izquierdo.
* Ubicación del botón de menú hamburguesa en el lado derecho.
* Integración del botón con el identificador `#dashboardMenuToggle`.

#### Cambiado

* Ajustes de la disposición de los elementos para pantallas de hasta 768 px.
* Revisión de la interacción entre la barra superior, el menú desplegable y la barra lateral móvil.

### ⏳ Simplificación del cargador global

#### Agregado

* Consolidación de un cargador global minimalista mediante `includes/loader.php`, `assets/css/loader.css` y `assets/js/loader.js`.
* Incorporación de un fondo semitransparente, un indicador circular de carga, el logotipo del proyecto y el mensaje accesible «Cargando…».
* Uso de los colores institucionales rojo `#D32F2F` y azul `#1565C0`.
* Incorporación de mecanismos de seguridad temporal para evitar que el cargador permanezca indefinidamente sobre el contenido.

#### Cambiado

* Retiro de la propuesta inicial basada en *Skeleton Screen*, debido a los problemas de interacción reportados durante las pruebas.
* Simplificación de la interfaz de carga para eliminar las siluetas grises que simulaban el contenido de la página.
* Incorporación de un mecanismo de desmontaje del cargador que contempla el estado de carga del documento y un temporizador de seguridad de aproximadamente 600–700 ms, según el informe de implementación.
* Corrección del cierre de una etiqueta `</script>` en `pages/dashboard.php`.
* Revisión del comportamiento del cargador en las páginas públicas y privadas.
* Ajustes de visibilidad e interacción para evitar que la capa de carga continúe bloqueando los controles después de que la página esté disponible.

> **Pendiente de verificación:** comprobar que la lógica de desmontaje del cargador no esté duplicada de forma innecesaria entre el script en línea de `includes/loader.php` y `assets/js/loader.js`, y validar el comportamiento en diferentes condiciones de carga.

### 🔐 Ajustes de inicialización de sesiones y navegación

#### Cambiado

* Revisión de los problemas reportados de PHP relacionados con `session_start()` y el envío previo de cabeceras (*Headers already sent*).
* Ajustes en las páginas de inicio de sesión, registro y recuperación de contraseña para evitar inicializaciones de sesión innecesarias desde las vistas.
* Revisión de la inicialización de las sesiones desde los puntos de entrada y controladores, antes de emitir contenido HTML cuando corresponde.
* Revisión de las redirecciones asociadas a usuarios con una sesión activa.
* Corrección de rutas y referencias a recursos al ejecutar el proyecto desde una subcarpeta de XAMPP, como `/Raices-Alzate/`.

> **Nota:** Los ajustes reportados deben verificarse en el flujo completo de navegación. La ausencia de advertencias sintácticas no demuestra por sí sola que todas las sesiones, redirecciones y condiciones de autenticación funcionen correctamente.

### 🔑 Compatibilidad de la ruta de recuperación de contraseña

#### Agregado

* Incorporación del archivo `recuperar-contrasena.php` como alias de compatibilidad para la ruta `recuperar-password.php`.
* Revisión del reconocimiento de la ruta de recuperación desde el Front Controller `index.php`.

#### Cambiado

* Ajustes del enrutamiento para aceptar la variante de la ruta con la palabra «contraseña».
* Revisión de las referencias internas para mantener la navegación consistente.

> **Pendiente de verificación:** confirmar el código de respuesta y el comportamiento definitivo de la redirección entre ambas rutas.

### 🧪 Entorno de pruebas de acceso y cierre de sesión

#### Agregado

* Incorporación reportada de `dev-login.php` para crear una sesión simulada de desarrollo sin depender del inicio de sesión real contra MySQL.
* Incorporación de `logout.php` para cerrar la sesión y limpiar los datos de autenticación correspondientes.
* Preparación de un flujo de navegación que permite revisar el Dashboard durante el desarrollo visual.

#### Cambiado

* Ajustes temporales de las redirecciones desde las pantallas de autenticación para facilitar las pruebas de maquetación.
* Mantenimiento de la comprobación de sesión en el acceso al Dashboard, según el informe de implementación.

> **Importante:** `dev-login.php` pertenece al entorno de pruebas. Antes de publicar el proyecto, debe comprobarse que no quede accesible como mecanismo de autenticación en producción. También deben revisarse las redirecciones temporales y restaurarse el flujo definitivo de autenticación.

### 🧾 Actualización de la documentación

#### Cambiado

* Actualización reportada de `docs/bitacora.md` y del documento Changelog para registrar los avances de implementación.
* Organización de los cambios recientes relacionados con el Dashboard, la navegación, las sesiones y las pruebas.
* Consolidación del estado del proyecto en la versión documental `v0.1.1`.

#### Verificación pendiente

* Confirmar directamente en Git los cambios registrados por las herramientas de desarrollo.
* Revisar el estado de la rama de trabajo y los commits antes de considerar confirmado cualquier commit o *push* reportado por una herramienta.
* Comprobar que los nombres y las rutas de los archivos documentados coincidan con la estructura actual del repositorio.

---

## 📅 10–13 de octubre de 2026

### 🧪 Validación técnica preliminar

#### Verificaciones reportadas

* Ejecución de comprobaciones de sintaxis PHP mediante `php -l`, sin errores reportados en los archivos revisados.
* Comprobaciones HTTP mediante `curl` para rutas de inicio de sesión, registro, recuperación de contraseña e inicio, con respuestas HTTP 200 reportadas.
* Pruebas de navegación en el navegador para revisar el comportamiento del cargador y detectar bloqueos de interacción.
* Revisión de errores relacionados con etiquetas de script, inicialización de sesiones y rutas del proyecto.

#### Pendiente

* Repetir las pruebas en el entorno local para confirmar los resultados.
* Verificar el comportamiento funcional de los formularios y las redirecciones.
* Comprobar la integración entre PHP, sesiones y base de datos.
* Realizar pruebas visuales de escritorio y móvil frente a los diseños de Figma.
* Ejecutar pruebas de seguridad antes de considerar terminados los flujos de autenticación.

> **Nota:** Las comprobaciones sintácticas y las respuestas HTTP satisfactorias son verificaciones preliminares. No equivalen a una validación funcional, visual o de seguridad completa.

---

## 📅 10 de octubre de 2026

### 📊 Dashboard Desktop y Mobile

#### Agregado

* Creación del controlador de acceso directo `dashboard.php` en la raíz del proyecto para gestionar el acceso a la vista privada.
* Creación de la vista modular `pages/dashboard.php`, tomando como referencia el diseño `03 - Dashboard - Desktop` de Figma.
* Creación del componente reutilizable `includes/sidebar.php`, con navegación vertical para el área privada.
* Creación del componente reutilizable `includes/topbar.php`, con título, información del usuario y controles de navegación.
* Creación de la hoja de estilos `assets/css/dashboard.css` para organizar los estilos específicos del Dashboard.
* Incorporación de información de usuario obtenida de `$_SESSION['user']`, incluyendo nombre y rol cuando están disponibles.
* Implementación de una sección de bienvenida y tres tarjetas de resumen: Mi perfil, Mis proyectos y Oportunidades.
* Incorporación del bloque de actividad reciente y acciones rápidas.
* Incorporación de una sección de próximas oportunidades.
* Integración inicial de la vista con la estructura de navegación del proyecto.
* Incorporación de la ruta `dashboard` al Front Controller `index.php`.
* Preparación de la presentación del Dashboard para escritorio y dispositivos móviles.

#### Cambiado

* Ajustes de los estilos de los botones y de las dimensiones de los elementos principales.
* Organización de la navegación privada mediante una barra lateral y una barra superior.
* Revisión de la estructura modular para separar el contenido, la presentación y los componentes compartidos.

### ⏳ Primera implementación del cargador global

#### Agregado

* Creación de `includes/loader.php`.
* Incorporación inicial de un cargador con fondo semitransparente, animación de carga y logotipo institucional.
* Creación y conexión de `assets/js/loader.js`.
* Inclusión del componente de carga en los componentes compartidos y en las vistas correspondientes.

#### Cambiado

* Sustitución posterior del diseño inicial con *Skeleton Screen* por una versión minimalista.
* Corrección de problemas de bloqueo de interacción reportados durante las pruebas.

### 🔧 Correcciones de navegación y autenticación

#### Cambiado

* Corrección del cierre de la etiqueta `</script>` en `pages/dashboard.php`.
* Revisión de los problemas de inicialización de sesión reportados en `pages/login.php`, `pages/registro.php` y `pages/recuperar-password.php`.
* Revisión de las rutas de recuperación de contraseña.
* Ajustes temporales de redirección para facilitar las pruebas de maquetación del Dashboard.

---

## 📅 09 de octubre de 2026

### 📰 Pantalla de Noticias

#### Agregado

* Creación del controlador de acceso directo `noticias.php`.
* Creación de la vista `pages/noticias.php` con estructura semántica mediante `<main>` y `<section>`.
* Implementación de una distribución adaptable para escritorio y móvil.
* Creación de la ilustración vectorial `assets/img/noticias-illustration.svg`, utilizando la paleta visual del proyecto.
* Incorporación de la ruta `noticias` en el Front Controller `index.php`.
* Ampliación de `assets/css/portal.css` para compartir estilos del portal público.
* Incorporación del reordenamiento vertical de textos, ilustración y botones en móvil.
* Configuración del estado activo de la navegación mediante `$currentPage = 'noticias'`.

### 🎓 Pantalla de Egresados

#### Agregado

* Creación del controlador de acceso directo `egresados.php`.
* Creación de la vista `pages/egresados.php` con estructura semántica.
* Implementación de una distribución adaptable para escritorio y móvil.
* Creación de la ilustración vectorial `assets/img/egresados-illustration.svg`, con elementos relacionados con la graduación y la comunidad.
* Incorporación de la ruta `egresados` en el Front Controller `index.php`.
* Ampliación de `assets/css/portal.css` para compartir los estilos del módulo.
* Incorporación del reordenamiento vertical de textos, ilustración y botones en móvil.
* Configuración del estado activo de la navegación mediante `$currentPage = 'egresados'`.

### 📁 Pantalla de Proyectos

#### Agregado

* Creación del controlador de acceso directo `proyectos.php`.
* Creación de la vista `pages/proyectos.php`.
* Implementación de una distribución adaptable para escritorio y móvil.
* Creación de la ilustración vectorial `assets/img/proyectos-illustration.svg`.
* Incorporación de la ruta `proyectos` en el Front Controller `index.php`.
* Incorporación de la carga condicional de `assets/css/portal.css` desde `includes/header.php`.
* Incorporación de botones de acción para iniciar sesión y crear una cuenta.

#### Cambiado

* Actualización de la navegación en `components/header.php` para mostrar la pestaña activa.
* Unificación de la referencia del logotipo con el recurso institucional `assets/icons/logo sin fondo raices alzate.ico`.
* Actualización del pie de página y de sus estilos, con fondo oscuro, texto claro y navegación principal.
* Eliminación del enlace redundante a Contacto en el pie de página.
* Ajustes del botón principal para evitar saltos de línea en su texto.
* Reorganización de la vista móvil mediante Flexbox.
* Incorporación de la clase reutilizable `.btn-outline-secondary` en `assets/css/main.css`.

### 🔑 Recuperación de contraseña

#### Agregado

* Implementación de la interfaz de recuperación de contraseña en `pages/recuperar-password.php`.
* Creación del controlador de acceso directo `recuperar-password.php`.
* Incorporación de las rutas `?page=recuperar` y `?page=recuperar-password` en `index.php`.
* Creación del endpoint de procesamiento `auth/recuperar-process.php`.
* Incorporación de validación del correo electrónico mediante `FILTER_VALIDATE_EMAIL`.
* Registro de la generación de tokens de recuperación mediante `random_bytes(32)`.
* Incorporación de una respuesta genérica para reducir el riesgo de enumeración de cuentas.
* Implementación de validación interactiva mediante `initRecuperarForm()` en `assets/js/auth.js`.
* Aplicación de estados visuales para los campos del formulario.
* Incorporación de mensajes de validación y limpieza reactiva de errores.
* Integración de la navegación entre inicio de sesión y recuperación de contraseña.
* Carga condicional de los recursos de autenticación.

#### Cambiado

* Ampliación del módulo de autenticación para incorporar la recuperación de contraseña.
* Reutilización de estilos y funciones de validación de los formularios existentes.

#### Pendiente

* Configurar y probar el servicio de correo electrónico mediante SMTP.
* Verificar el almacenamiento seguro de los tokens.
* Confirmar la expiración y validación de los tokens.
* Implementar o verificar el flujo completo para establecer una nueva contraseña.
* Ejecutar pruebas integrales del proceso de recuperación.

> **Nota:** La generación de tokens y la implementación de la interfaz no implican que el envío de correos o el restablecimiento completo de contraseñas estén terminados.

---

## 📅 08 de octubre de 2026

### 💻 Inicio de la implementación frontend

#### Agregado

* Inicio de la Fase 4 — Implementación.
* Implementación inicial de la página de Inicio a partir del diseño definido en Figma.
* Organización de los componentes compartidos de encabezado y pie de página mediante PHP.
* Creación y organización de los estilos generales del frontend.
* Creación de `assets/css/fonts.css` para centralizar la tipografía Inter.
* Creación de `assets/css/header.css` para los estilos específicos del encabezado.
* Creación de `assets/css/footer.css` para los estilos específicos del pie de página.
* Implementación inicial de la navegación responsive mediante CSS y JavaScript vanilla.
* Incorporación del isotipo de Raíces Alzate en el encabezado y el pie de página.
* Incorporación del favicon institucional.
* Organización de la estructura de navegación principal.
* Preparación de las referencias visuales de escritorio de 1440 px y móvil de 390 px.

### 🧭 Navegación responsive

#### Agregado

* Incorporación de un menú de navegación adaptable a dispositivos móviles.
* Organización de las acciones de autenticación dentro del menú desplegable.
* Preparación de la estructura para mostrar el logotipo y el botón de menú en pantallas pequeñas.

#### Cambiado

* Reorganización del encabezado para diferenciar las acciones de escritorio y móvil.
* Ajustes de los estilos responsive para mantener una cabecera compacta.

### 🎨 Sistema de estilos y botones

#### Agregado

* Organización de los estilos globales en `assets/css/main.css`.
* Definición de estilos reutilizables para botones.
* Incorporación de estados visuales para las interacciones principales.

#### Cambiado

* Consolidación de las dimensiones iniciales de los botones compartidos:

| Clase                              | Ancho de referencia |  Alto | Radio |
| ---------------------------------- | ------------------: | ----: | ----: |
| `.btn-primary` / `.hero-cta-btn`   |              123 px | 40 px |  8 px |
| `.btn-sm` / `.header-register-btn` |              112 px | 40 px |  8 px |

* Establecimiento de Inter como tipografía de los botones, con peso 500 y tamaño de 15 px.
* Mantenimiento del color primario `#D32F2F` y el texto blanco para los botones principales.
* Definición del color hover `#B71C1C`.
* Definición del color active `#8E0000`.
* Incorporación de una respuesta visual de escala `0.98` para los estados de interacción correspondientes.

> **Nota:** Estas dimensiones corresponden a una calibración inicial. Los componentes pueden recibir ajustes específicos durante la implementación de cada pantalla.

### 🔐 Inicio de sesión

#### Agregado

* Creación de `pages/login.php`.
* Creación del controlador de acceso directo `login.php`.
* Creación del endpoint de procesamiento `auth/login-process.php`.
* Creación de `assets/css/auth.css` para organizar los estilos de autenticación.
* Creación de `assets/js/auth.js` para centralizar la validación y el comportamiento de los formularios.
* Incorporación de los campos de correo electrónico y contraseña.
* Incorporación de la opción «Recordarme».
* Incorporación del enlace para recuperar la contraseña.
* Integración inicial del formulario con el sistema global de alertas.
* Incorporación de validaciones de formulario y controles para prevenir envíos duplicados.
* Preparación del enrutamiento de inicio de sesión mediante `?page=login`.
* Carga condicional de los estilos y scripts de autenticación.

#### Cambiado

* Ajuste de la tarjeta de autenticación para aproximarla al diseño de Figma.
* Establecimiento de un ancho máximo de referencia de 440 px para la tarjeta.
* Ajuste de la altura de los controles a una referencia de 44 px.
* Corrección de la alineación del encabezado de autenticación.
* Incorporación de ajustes de espaciado para dispositivos móviles.
* Organización de las responsabilidades visuales y funcionales entre PHP, CSS y JavaScript.

### 🚨 Sistema global de alertas

#### Agregado

* Creación de la clase reutilizable `.alert` en `assets/css/main.css`.
* Incorporación de las variantes semánticas:

  * `success`.
  * `warning`.
  * `error`.
  * `info`.
* Incorporación de iconografía SVG en línea.
* Incorporación de atributos ARIA para mejorar la accesibilidad.
* Integración inicial de las alertas en el formulario de inicio de sesión.

#### Cambiado

* Sustitución del contenedor de retroalimentación anterior por el componente de alertas.
* Organización de los estilos para facilitar su reutilización en otras pantallas.

### 📝 Registro de usuarios

#### Agregado

* Creación de `pages/registro.php`.
* Creación del controlador de acceso directo `registro.php`.
* Creación del endpoint de procesamiento `auth/registro-process.php`.
* Incorporación de la pantalla de registro al sistema de enrutamiento.
* Implementación de la selección del tipo de usuario mediante botones de opción.
* Incorporación de los tipos de usuario Estudiante y Egresado.
* Implementación de validaciones en el frontend y preparación de validaciones en el backend.
* Preparación del control de correos electrónicos duplicados.
* Registro del uso de `password_hash()` con `PASSWORD_DEFAULT` para el tratamiento de contraseñas.
* Registro del uso de PDO y consultas preparadas en las operaciones de base de datos correspondientes.
* Incorporación de funciones reutilizables para mostrar y limpiar errores de validación.
* Integración del formulario con los estilos compartidos de autenticación.

#### Cambiado

* Reorganización del registro para utilizar una estructura modular en PHP.
* Eliminación de versiones estáticas redundantes de algunas pantallas de autenticación, según los cambios registrados.
* Ajuste de los estados visuales de los botones de opción.
* Unificación de los comportamientos de validación compartidos.

> **Nota:** La presencia de código para validaciones, PDO o tratamiento de contraseñas no sustituye las pruebas de integración y seguridad del proceso de registro.

### 🎨 Calibración de los estados de formularios

#### Agregado

* Incorporación de los estados visuales de los campos:

  * Normal.
  * Focus.
  * Filled.
  * Error.
  * Disabled.
* Incorporación de los estados de los botones de opción:

  * Normal.
  * Selected.
  * Focus.
  * Disabled.
  * Error.
* Incorporación de mensajes de error asociados a los campos.
* Incorporación de funciones auxiliares para administrar los estados de validación.

#### Cambiado

* Establecimiento de `#D32F2F` como color de selección de los botones de opción.
* Mantenimiento de `#1565C0` como color de foco.
* Ajuste de bordes, textos y placeholders en estado de error.
* Incorporación de la limpieza reactiva de los errores al escribir o enfocar los campos.
* Centralización de funciones de validación compartidas en `assets/js/auth.js`.

---

## 📅 Septiembre de 2026

### 🎨 Diseño de interfaces UI/UX

#### Agregado

* Definición de la estructura de navegación de la plataforma.
* Creación de la sección `Estructura de Navegación` en Figma.
* Definición del flujo general entre Inicio, Autenticación, Dashboard y los módulos principales.
* Diseño de las interfaces principales para escritorio.
* Aplicación del Design System a las interfaces.
* Organización de la estructura visual de las pantallas.

Se completaron las siguientes interfaces Desktop:

1. `01 - Inicio - Desktop`.
2. `02 - Autenticación - Desktop`.
3. `03 - Dashboard - Desktop`.
4. `04 - Perfil - Desktop`.
5. `05 - Egresados - Desktop`.
6. `06 - Proyectos - Desktop`.
7. `07 - Noticias - Desktop`.
8. `08 - Administración - Desktop`.
9. `09 - Crear proyecto - Desktop`.
10. `10 - Detalle de proyecto - Desktop`.
11. `11 - Editar perfil - Desktop`.
12. `12 - Recuperar contraseña - Desktop`.
13. `13 - Registro - Desktop`.
14. `14 - Editar proyecto - Desktop`.
15. `15 - Crear noticia - Desktop`.
16. `16 - Detalle de noticia - Desktop`.
17. `17 - Editar noticia - Desktop`.

### 📱 Diseño responsive

#### Agregado

* Finalización de las adaptaciones de las 17 interfaces para Mobile.
* Establecimiento de un ancho de referencia móvil de 390 px.
* Diseño de `Inicio - Mobile` y `Autenticación - Mobile`.
* Finalización de las adaptaciones móviles de Dashboard, Perfil, Egresados, Proyectos, Noticias, Administración y los formularios correspondientes.
* Aplicación de los criterios responsive definidos en `Components & Responsive`.
* Ajustes de jerarquía visual, espaciado y componentes para pantallas de menor tamaño.

#### Cambiado

* Consolidación de las referencias de diseño Desktop y Mobile en Figma.
* Preparación de las 34 pantallas para servir como referencia de la implementación frontend.

### 🔄 Consolidación del diseño

#### Cambiado

* Consolidación de la estructura visual de las interfaces.
* Aplicación consistente de los colores, la tipografía, el espaciado y los componentes del Design System.
* Revisión de la consistencia visual entre las diferentes pantallas.
* Ajuste de dimensiones y distribución de elementos para mejorar la legibilidad.
* Consolidación de la navegación entre los módulos principales.
* Establecimiento de Figma como referencia visual para la implementación frontend.

### ✅ Finalización de la Fase 3 — Diseño UI/UX

#### Agregado

* Revisión general de las interfaces diseñadas.
* Revisión de la aplicación del Design System.
* Revisión de los componentes reutilizables.
* Revisión de la estructura de navegación.
* Consolidación de las 17 pantallas Desktop y las 17 pantallas Mobile.
* Preparación de las referencias visuales para la etapa de implementación.

#### Cambiado

* Cierre de la Fase 3 — Diseño UI/UX a finales de septiembre de 2026.
* Establecimiento de Figma como referencia visual de la implementación.
* Consolidación de los criterios responsive definidos durante el diseño.
* Preparación de las bases visuales para el desarrollo frontend.

> **Nota:** La finalización del diseño UI/UX significa que las referencias visuales están definidas. La fidelidad visual y el comportamiento responsive de cada pantalla deben validarse durante la implementación en código.

---

## 📅 05 de septiembre de 2026

### 🧩 Components & Responsive

#### Agregado

* Consolidación de la página `Components & Responsive` en Figma.
* Organización de los componentes reutilizables.
* Definición de estados para los componentes de interacción.
* Finalización del componente Radio Button.
* Finalización del componente Switch.
* Revisión de proporciones, tamaños, espaciado y distribución.
* Definición de criterios de diseño responsive.

**Estados de Radio Button:**

* Normal.
* Selected.
* Focus.
* Disabled.
* Error.

**Estados de Switch:**

* Off.
* On.
* Focus.
* Disabled.
* Error.

#### Cambiado

* Consolidación de la relación entre el Design System y los componentes reutilizables.
* Mantenimiento del trabajo manual en Figma sin utilizar Auto Layout.
* Organización de la página para facilitar la implementación posterior.
* Cierre de `Components & Responsive` como parte del diseño.

---

# [v0.1.0] — Fundamentos y documentación

## 📅 20 de julio de 2026

### 🎨 Consolidación del Design System

#### Agregado

* Consolidación de la paleta de colores institucional.
* Organización de las categorías Primary, Secondary, Neutral y Semantic.
* Definición inicial de Poppins como familia tipográfica para la jerarquía del Design System.
* Creación de la jerarquía tipográfica.
* Definición del sistema de espaciado:

  * 4 px.
  * 8 px.
  * 12 px.
  * 16 px.
  * 24 px.
  * 32 px.
  * 48 px.
  * 64 px.
* Creación de los botones reutilizables Primary, Secondary, Outline, Danger y Disabled.
* Definición de dimensiones, colores, radios y estilos tipográficos para los botones.
* Preparación de la página de componentes reutilizables en Figma.

#### Cambiado

* Reorganización manual de la distribución del Design System.
* Separación de los componentes reutilizables del contenido base mediante una segunda página.
* Establecimiento del trabajo sin Auto Layout durante esta etapa.

---

## 📅 16 de julio de 2026

### 🎨 Inicio del Design System

#### Agregado

* Inicio del desarrollo del Design System en Figma.
* Creación y organización de la sección `Colors`.
* Definición de las categorías Primary, Secondary, Neutral, Semantic y Preview.
* Definición inicial de la identidad visual.
* Establecimiento del rojo institucional como color principal.
* Establecimiento del azul institucional como color secundario.
* Organización inicial de la jerarquía tipográfica.
* Preparación de la estructura visual para continuar el desarrollo del sistema de diseño.

#### Cambiado

* Reorganización de la distribución de los elementos del Design System.
* Postergación de la evaluación de Color Styles y Variables de Figma para una etapa posterior.

---

## 📅 10 de julio de 2026

### 🎨 Planificación de la Fase 3 — Diseño UI/UX

#### Agregado

* Inicio de la planificación de la Fase 3 — Diseño UI/UX.
* Actualización de la planificación general.
* Definición de los objetivos de la fase de diseño.
* Definición de los entregables de UI/UX.
* Establecimiento de criterios de finalización.
* Organización del plan de trabajo para diseñar las interfaces en Figma.

---

## 📅 09 de julio de 2026

### 📚 Consolidación de la documentación base

#### Agregado

* Creación y organización del repositorio en GitHub.
* Configuración inicial del proyecto.
* Organización de la estructura de carpetas.
* Configuración inicial de Git y GitHub.
* Creación de la documentación base:

  * Bitácora del proyecto.
  * Planificación.
  * Requisitos funcionales y no funcionales.
  * Arquitectura del sistema.
  * Roadmap.
  * Registro de decisiones.
  * Glosario.
  * Índice de documentación en `docs/README.md`.
  * Estructura inicial del manual técnico.
  * Estructura inicial del manual de usuario.
* Elaboración del `README.md` principal.
* Documentación de la descripción general del proyecto.
* Documentación de las tecnologías previstas.
* Documentación de la estructura del repositorio.
* Documentación del estado y la hoja de ruta.
* Incorporación de instrucciones iniciales de instalación y uso.
* Documentación de los apartados relacionados con el equipo, las contribuciones, la licencia y los agradecimientos.
* Finalización de la primera etapa de documentación base.

---

# 📊 Estado actual del proyecto

**Estado:** En desarrollo.
**Versión documental:** v0.1.1.
**Inicio del proyecto:** 2025.
**Fase actual:** Fase 4 — Implementación.
**Última actualización del documento:** 13 de octubre de 2026.

## Fases consolidadas

### Fase 1 — Planificación

* Planificación general.
* Definición del alcance.
* Organización inicial del trabajo.

### Fase 2 — Documentación

* Documentación base.
* Requisitos.
* Arquitectura.
* Bitácora.
* Roadmap.
* Glosario.
* Decisiones.
* Estructura inicial de los manuales.

### Fase 3 — Diseño UI/UX

**Estado: Finalizada.**

* Design System.
* Identidad visual.
* Paleta de colores.
* Jerarquía tipográfica.
* Sistema de espaciado.
* Sistema de botones.
* Componentes reutilizables.
* Estados de interacción.
* Components & Responsive.
* Estructura de navegación.
* 17 interfaces Desktop.
* 17 interfaces Mobile.
* Consolidación de las referencias visuales.

### Fase 4 — Implementación

**Estado: En desarrollo.**

Se han reportado avances en:

* Estructura base de Inicio.
* Componentes compartidos de encabezado y pie de página.
* Sistema modular de estilos.
* Tipografía centralizada.
* Navegación responsive.
* Favicon institucional.
* Pantallas públicas de Inicio, Proyectos, Egresados y Noticias.
* Estructura visual del Dashboard Desktop y Mobile.
* Barra lateral expandible y contraíble.
* Barra superior móvil.
* Cargador global minimalista.
* Estructura de las pantallas de inicio de sesión, registro y recuperación de contraseña.
* Validaciones de formularios y sistema de alertas.
* Ajustes de sesiones, rutas y navegación para el entorno de desarrollo.
* Entorno auxiliar para probar el Dashboard mediante una sesión simulada.

**Importante:** Estos avances corresponden a la estructura y la implementación reportadas. La validación visual completa, la autenticación integral, la integración definitiva con la base de datos y las pruebas de seguridad continúan pendientes.

---

# 🧱 Estado de los principales componentes

| Componente                  | Estado registrado                                                     | Observaciones                                                                                   |
| --------------------------- | --------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------- |
| Design System               | Finalizado en Figma                                                   | Referencia visual para la implementación.                                                       |
| Components & Responsive     | Finalizado en Figma                                                   | Los componentes están definidos; su comportamiento debe validarse en código.                    |
| Inicio                      | Implementación reportada                                              | Revisar fidelidad visual y comportamiento responsive frente a Figma.                            |
| Header y Footer             | Implementación reportada                                              | Verificar consistencia entre páginas y rutas.                                                   |
| Proyectos                   | Maquetación reportada                                                 | Confirmar fidelidad visual y funcionalidad de las acciones.                                     |
| Egresados                   | Maquetación reportada                                                 | Confirmar fidelidad visual y funcionalidad de las acciones.                                     |
| Noticias                    | Maquetación reportada                                                 | Confirmar fidelidad visual y funcionalidad de las acciones.                                     |
| Dashboard                   | Estructura y maquetación reportadas                                   | Verificar comportamiento, datos y protección del acceso.                                        |
| Sidebar y Topbar            | Implementación reportada                                              | Probar navegación, colapso, persistencia y comportamiento móvil.                                |
| Inicio de sesión            | Estructura y procesamiento registrados; validación integral pendiente | Verificar autenticación real, sesiones, redirecciones y seguridad.                              |
| Registro                    | Estructura y procesamiento registrados; validación integral pendiente | Verificar persistencia, duplicados, validaciones y seguridad.                                   |
| Recuperación de contraseña  | Interfaz y lógica inicial registradas                                 | SMTP, expiración de tokens y restablecimiento final pendientes de confirmar.                    |
| Cargador global             | Implementación reportada                                              | Verificar desmontaje, ausencia de bloqueos y posibles scripts duplicados.                       |
| Sesiones y cierre de sesión | Ajustes reportados                                                    | Validar el ciclo completo y retirar los accesos exclusivos de desarrollo antes de publicar.     |
| Base de datos               | Preparación previa y conexión de prueba reportadas                    | El esquema definitivo y la integración funcional de los módulos están pendientes de validación. |
| Manual técnico              | Estructura inicial                                                    | Actualizar conforme se consolide la implementación.                                             |
| Manual de usuario           | Estructura inicial                                                    | Completar cuando las funciones principales estén validadas.                                     |

---

# 🚀 Próximas actividades

Las siguientes actividades están orientadas a continuar la Fase 4 — Implementación:

1. Comparar visualmente las pantallas implementadas con sus referencias de Figma en Desktop y Mobile.
2. Corregir las diferencias visuales y de comportamiento identificadas.
3. Validar la navegación del portal público y del área privada.
4. Revisar la inicialización de sesiones, las redirecciones y el control de acceso.
5. Verificar los flujos de inicio de sesión y registro con datos reales.
6. Revisar el tratamiento de contraseñas, la gestión de sesiones y las medidas de seguridad.
7. Completar y probar el flujo de recuperación de contraseña.
8. Configurar y verificar el servicio de correo mediante SMTP.
9. Definir y validar el modelo entidad-relación (MER) y el esquema definitivo de la base de datos.
10. Integrar progresivamente las vistas con consultas reales y abandonar los datos simulados cuando corresponda.
11. Revisar el cargador global en diferentes páginas y condiciones de carga.
12. Confirmar que las herramientas auxiliares de desarrollo no puedan utilizarse como mecanismos de acceso en producción.
13. Continuar con la implementación de las interfaces restantes: Perfil, Administración, Crear proyecto, Detalle de proyecto, Editar perfil, Registro, Editar proyecto, Crear noticia, Detalle de noticia y Editar noticia, así como las demás pantallas que requieran ajustes.
14. Ejecutar pruebas funcionales, visuales, responsive y de seguridad.
15. Actualizar la bitácora, el Changelog y los demás documentos conforme se confirmen los avances.
16. Verificar los commits, el estado de la rama de trabajo y la sincronización con GitHub.

---

> **Nota sobre las fechas:** Raíces Alzate se encuentra en desarrollo desde 2025. El 9 de julio de 2026 corresponde a una fecha registrada de consolidación documental, no al inicio del proyecto.

> **Nota sobre el estado de los cambios:** Las entradas de octubre incorporan avances reportados por las herramientas utilizadas durante el desarrollo. Las pruebas, los commits y los resultados que no se hayan comprobado directamente en el repositorio o en el entorno local deben considerarse pendientes de verificación.
