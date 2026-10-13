# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# Changelog

Todos los cambios importantes realizados en **Raíces Alzate** se registran en este documento.

El proyecto se encuentra en desarrollo desde **2025**. Las fechas registradas corresponden a sesiones de trabajo, avances y actualizaciones realizadas durante las diferentes etapas del proyecto.

El formato de este documento está inspirado en las convenciones de *Keep a Changelog*, con el propósito de mantener un historial claro, organizado y comprensible.

---

# [v0.1.1] — En desarrollo

## 📅 10 de octubre de 2026

### 📊 Dashboard Desktop y Mobile

#### Agregado

* Creación del controlador de acceso directo `dashboard.php` en la raíz del proyecto con control estricto de acceso y verificación de sesión activa.
* Implementación de la vista modular privada `pages/dashboard.php` basada con exactitud en el frame de Figma `03 - Dashboard - Desktop`.
* Creación del componente reutilizable `includes/sidebar.php` (ancho 240px, fondo `#FFFFFF`, cabecera de marca en `#D32F2F`, navegación vertical con elemento activo, enlaces del sistema y cierre de sesión).
* Creación del componente reutilizable `includes/topbar.php` con título `Dashboard`, disparador de menú para mobile, datos dinámicos del usuario y avatar circular (`#D32F2F`) con inicial calculada en UTF-8.
* Creación del controlador `logout.php` en la raíz para destrucción segura de sesiones y cookies.
* Creación de la hoja de estilos modular `assets/css/dashboard.css` aislada para evitar colisiones con el portal público.
* Extracción dinámica de datos de usuario desde `$_SESSION['user']` (`nombre`, `rol`, inicial), prohibiendo nombres fijos en duro en el código.
* Implementación de la sección de bienvenida institucional y fila de 3 tarjetas de resumen (`Mi perfil`, `Mis proyectos`, `Oportunidades`) con enlaces de acción `Ver... →` en azul institucional `#1565C0`.
* Implementación del bloque inferior a dos columnas en Desktop: columna izquierda (~65%) con `Actividad reciente` y `Acciones rápidas` (`Actualizar mi perfil` en `#D32F2F` y `Publicar proyecto` en contorno `#1565C0`); columna derecha (~35%) con `Próximas oportunidades` categorizadas con insignias institucionales.
* Adaptación responsive para Mobile (< 768px): barra lateral en cajón desplegable (drawer con backdrop), tarjetas en 1 columna y reordenamiento estricto mediante Flexbox (1. Actividad reciente, 2. Próximas oportunidades, 3. Acciones rápidas al 100%).
* Incorporación de la ruta `case 'dashboard':` en el Front Controller `index.php`.

#### Modificado

* **Ajuste UI de dimensiones y legibilidad del botón "Actualizar mi perfil":** En `pages/dashboard.php` y `assets/css/dashboard.css`, se actualizaron las reglas para `.dashboard-actions .btn-primary` y `.btn-actualizar-perfil` con altura accesible estándar de `44px`, padding horizontal de `0 24px`, ancho mínimo `min-width: 180px` (`width: auto !important;`), tipografía `Inter` semibold (`600`, `0.95rem`) y `white-space: nowrap` para evitar saltos o recortes de línea, manteniendo el rojo oficial `#D32F2F` y hover `#B71C1C`.
* **Implementación de Barra Lateral Colapsable (Mini-Sidebar en Desktop):** En `includes/sidebar.php`, `assets/css/dashboard.css` y `pages/dashboard.php`:
  * Adición del botón de alternancia `#sidebarToggleBtn` con icono SVG rotatorio en la cabecera `.sidebar-header`.
  * Estado expandido por defecto (`240px`) con logotipo, textos institucionales y etiquetas de menú completas.
  * Estado contraído mini-sidebar (`76px`) en pantallas de escritorio (`>= 769px`), centrando el isotipo institucional e iconos de navegación dentro de casillas de `48px`, y ocultando limpiamente las etiquetas de texto.
  * Persistencia de preferencia del usuario mediante `localStorage.setItem('sidebar_collapsed', 'true' | 'false')` y prevención de parpadeo visual (FOUC) mediante script inicial en el `<head>`.
  * Transición suave (`0.3s ease`) en el sidebar y el área principal (`.dashboard-main-area`).
  * Aislamiento completo del modo móvil (`<= 768px`), donde el botón de colapso se oculta y el cajón desplegable (drawer) mantiene su comportamiento habitual.

## 📅 09 de octubre de 2026

### 📰 Pantalla de Noticias

#### Agregado

* Creación del controlador de acceso directo `noticias.php` en la raíz del proyecto para acceso modular respetando la arquitectura del repositorio.
* Creación de la vista de contenido `pages/noticias.php` con contenedor semántico `<main>` y `<section>` (`07 - Noticias - Desktop` y adaptación Mobile).
* Implementación de la grilla responsive de dos columnas balanceadas (Desktop 1440 px) y apilamiento centrado (Mobile < 768 px).
* Creación de la ilustración vectorial oficial `assets/img/noticias-illustration.svg` con portal digital de novedades, megáfono de difusión comunitaria, calendario de eventos alzatistas, campana de alertas y brote institucional en la paleta oficial estricta (`#D32F2F`, `#1565C0`, tonos neutros).
* Incorporación de la ruta `case 'noticias':` en el Front Controller `index.php`.
* Ampliación de la hoja de estilos modular `assets/css/portal.css` para compartir las reglas de diseño y tokens del portal público en Noticias.
* Implementación del reordenamiento visual vertical en Mobile (< 768 px) mediante Flexbox: 1. Textos explicativos (`order: 1`), 2. Tarjeta con la ilustración (`order: 2`), 3. Botones de acción (`order: 3`).
* Activación automática de la pestaña en `components/header.php` mediante `$currentPage = 'noticias';`.

### 🎓 Pantalla de Egresados

#### Agregado

* Creación del controlador de acceso directo `egresados.php` en la raíz del proyecto para acceso modular.
* Creación de la vista de contenido `pages/egresados.php` con contenedor semántico `<main>` y `<section>` (`05 - Egresados - Desktop` y adaptación Mobile).
* Implementación de la grilla responsive de dos columnas balanceadas (Desktop 1440 px) y apilamiento centrado (Mobile < 768 px).
* Creación de la ilustración vectorial oficial `assets/img/egresados-illustration.svg` con birrete de graduación, diplomas, red de conexiones comunitarias y brote institucional en la paleta oficial estricta (`#D32F2F`, `#1565C0`, tonos neutros).
* Incorporación de la ruta `case 'egresados':` en el Front Controller `index.php`.
* Ampliación de la hoja de estilos modular `assets/css/portal.css` para compartir las reglas de diseño y tokens del portal público en Egresados.
* Implementación del reordenamiento visual vertical en Mobile (< 768 px) mediante Flexbox: 1. Textos explicativos (`order: 1`), 2. Ilustración comunitaria (`order: 2`), 3. Botones de acción (`order: 3`).
* Activación automática del menú de navegación en `components/header.php` mediante `$currentPage = 'egresados';`.

### 📁 Pantalla de Proyectos

#### Agregado

* Creación del controlador de acceso directo `proyectos.php` en la raíz del proyecto para acceso modular.
* Creación de la vista de contenido `pages/proyectos.php` con contenedor semántico `<main>` y `<section>`.
* Implementación de la grilla responsive de dos columnas balanceadas (Desktop 1440 px) y apilamiento centrado (Mobile < 768 px).
* Creación de la hoja de estilos modular `assets/css/portal.css` para el módulo de proyectos y componentes del portal público.
* Creación de la ilustración vectorial oficial `assets/img/proyectos-illustration.svg` con la laptop, el brote de planta en crecimiento (metáfora de raíces), la bombilla de innovación y credenciales de comunidad en la paleta institucional estricta (`#D32F2F`, `#1565C0`, tonos neutros).
* Incorporación de la ruta `case 'proyectos':` en el Front Controller `index.php`.
* Incorporación de la carga condicional de `portal.css` en `includes/header.php`.
* Implementación de botones de acción con dimensiones y estados de interacción según Design System: «Iniciar sesión» (`#D32F2F`) y «Crear una cuenta» (outline `#1565C0`).

#### Cambiado

* Se actualizó la barra de navegación en `components/header.php` para vincular a `proyectos.php` con indicador visual de pestaña activa de 2 px en `#D32F2F`.
* Se unificó la referencia del logo oficial a la ruta institucional existente en `assets/icons/logo sin fondo raices alzate.ico`, eliminando copias duplicadas en `assets/img/`.
* Se actualizó `components/footer.php` y `assets/css/footer.css` al fondo minimalista `#1E1E1E`, tipografía `#B0BEC5` y navegación principal aprobada (`Inicio`, `Proyectos`, `Egresados`, `Noticias`), eliminando el enlace redundante a Contacto.
* Se calibró el botón principal «Iniciar sesión» (`.btn-primary`, `.btn-proyectos-login`) con `white-space: nowrap;`, centrado perfecto, `min-width: 140px;` y altura de 44 px para garantizar su visualización en una sola línea horizontal sin quiebres.
* Se reordenó la vista mobile (`@media (max-width: 768px)`) con Flexbox para secuenciar verticalmente: 1. Título y descripción (`order: 1`), 2. Ilustración de la laptop (`order: 2`), y 3. Botones de acción (`order: 3`).
* Se incorporó la clase reutilizable `.btn-outline-secondary` a `assets/css/main.css`.

### 🔑 Recuperación de contraseña

#### Agregado

* Implementación de la interfaz de recuperación de contraseña en `pages/recuperar-password.php`.
* Creación del controlador de acceso directo `recuperar-password.php` en la raíz del proyecto.
* Incorporación de las rutas `?page=recuperar` y `?page=recuperar-password` en `index.php`.
* Creación del endpoint de procesamiento `auth/recuperar-process.php`.
* Incorporación de validación del correo electrónico en el backend mediante `FILTER_VALIDATE_EMAIL`.
* Generación de tokens de recuperación mediante `random_bytes(32)`, según la implementación registrada.
* Incorporación de una respuesta genérica para reducir el riesgo de enumeración de cuentas.
* Implementación de validación interactiva del formulario mediante `initRecuperarForm()` en `assets/js/auth.js`.
* Aplicación de estados visuales para los campos: normal, enfocado, completado, error y deshabilitado.
* Incorporación de mensajes de validación y limpieza reactiva de errores al escribir o enfocar el campo.
* Integración de la navegación entre inicio de sesión y recuperación de contraseña.
* Carga condicional de los recursos de autenticación para la pantalla de recuperación.

#### Cambiado

* Se amplió el módulo de autenticación para incluir el flujo de recuperación de contraseña.
* Se reutilizaron los estilos y las funciones de validación de los formularios existentes.
* Se mantuvo la respuesta genérica para evitar revelar si un correo electrónico está registrado.

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
* Inicio de la adaptación de la pantalla de Inicio para dispositivos móviles.
* Preparación de las referencias de visualización para escritorio de 1440 px y móvil de 390 px.

### 🧭 Navegación responsive

#### Agregado

* Incorporación de un menú de navegación adaptable a dispositivos móviles.
* Organización de las acciones de autenticación dentro del menú desplegable.
* Preparación de la estructura para mostrar el logotipo y el botón de menú en pantallas pequeñas.

#### Cambiado

* Se ocultaron las acciones directas de inicio de sesión y registro en el encabezado móvil.
* Se trasladaron las acciones de autenticación al menú desplegable.
* Se reorganizó la estructura del encabezado para diferenciar las acciones de escritorio de las acciones móviles.
* Se ajustaron los estilos responsive para mantener una cabecera compacta.

### 🎨 Sistema de estilos y botones

#### Agregado

* Organización de los estilos globales en `assets/css/main.css`.
* Definición de estilos reutilizables para botones.
* Incorporación de estados visuales para las interacciones principales.

#### Cambiado

* Se consolidaron las dimensiones de los botones compartidos:

| Clase                              |  Ancho |  Alto | Radio |
| ---------------------------------- | -----: | ----: | ----: |
| `.btn-primary` / `.hero-cta-btn`   | 123 px | 40 px |  8 px |
| `.btn-sm` / `.header-register-btn` | 112 px | 40 px |  8 px |

* Se estableció Inter como tipografía de los botones, con peso 500 y tamaño de 15 px.
* Se mantuvo el color primario `#D32F2F` y el texto blanco para los botones principales.
* Se definió el color de interacción hover `#B71C1C`.
* Se definió el color de interacción active `#8E0000`.
* Se incorporó una respuesta visual de escala `0.98` para los estados de interacción correspondientes.

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
* Carga condicional de los estilos y scripts de autenticación desde los componentes compartidos.

#### Cambiado

* Se ajustó la tarjeta de autenticación para aproximarla a las dimensiones y proporciones de Figma.
* Se estableció un ancho máximo de referencia de 440 px para la tarjeta.
* Se ajustó la altura de los controles a una referencia de 44 px.
* Se corrigió la alineación del encabezado de autenticación hacia la izquierda.
* Se incorporaron ajustes de espaciado para dispositivos móviles.
* Se organizaron las responsabilidades visuales y funcionales entre PHP, CSS y JavaScript.

### 🚨 Sistema global de alertas

#### Agregado

* Creación de la clase reutilizable `.alert` en `assets/css/main.css`.
* Incorporación de las variantes semánticas:

  * `success`.
  * `warning`.
  * `error`.
  * `info`.
* Incorporación de iconografía SVG inline.
* Incorporación de atributos ARIA para mejorar la accesibilidad de los mensajes.
* Integración inicial de las alertas en el formulario de inicio de sesión.

#### Cambiado

* Se sustituyó el contenedor de retroalimentación anterior por el componente de alertas.
* Se organizaron los estilos de las alertas para facilitar su reutilización en otras pantallas.
* Se mantuvieron los colores y las reglas visuales de cada variante.

### 📝 Registro de usuarios

#### Agregado

* Creación de `pages/registro.php`.
* Creación del controlador de acceso directo `registro.php`.
* Creación del endpoint de procesamiento `auth/registro-process.php`.
* Incorporación de la pantalla de registro al sistema de enrutamiento.
* Implementación de la selección del tipo de usuario mediante botones de opción.
* Incorporación de los tipos de usuario Estudiante y Egresado.
* Implementación de validaciones en el frontend.
* Incorporación de validaciones en el backend.
* Preparación del control de correos electrónicos duplicados.
* Uso de `password_hash()` con `PASSWORD_DEFAULT` para el tratamiento seguro de las contraseñas.
* Uso de PDO y consultas preparadas en las operaciones de base de datos correspondientes.
* Incorporación de funciones reutilizables para mostrar y limpiar errores de validación.
* Integración del formulario con los estilos compartidos de autenticación.

#### Cambiado

* Se reorganizó la implementación del registro para utilizar una estructura modular en PHP.
* Se eliminaron versiones estáticas redundantes de las pantallas de autenticación, entre ellas `registro.html`, `registro.css`, `registro.js` y `login.html`, según los cambios registrados.
* Se ajustaron los estados visuales de los botones de opción.
* Se unificó el comportamiento de validación entre el registro y el inicio de sesión.

### 🎨 Calibración de los estados de formularios

#### Agregado

* Incorporación de los estados visuales de los campos de formulario:

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

* Se estableció `#D32F2F` como color de selección de los botones de opción.
* Se mantuvo `#1565C0` como color de foco.
* Se ajustaron los bordes, textos y placeholders de error.
* Se incorporó la limpieza reactiva de los errores al escribir o enfocar los campos.
* Se centralizaron las funciones de validación compartidas en `assets/js/auth.js`.

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

Se desarrollaron las siguientes interfaces Desktop:

* `01 - Inicio - Desktop`.
* `02 - Autenticación - Desktop`.
* `03 - Dashboard - Desktop`.
* `04 - Perfil - Desktop`.
* `05 - Egresados - Desktop`.
* `06 - Proyectos - Desktop`.
* `07 - Noticias - Desktop`.
* `08 - Administración - Desktop`.
* `09 - Crear proyecto - Desktop`.
* `10 - Detalle de proyecto - Desktop`.
* `11 - Editar perfil - Desktop`.
* `12 - Recuperar contraseña - Desktop`.
* `13 - Registro - Desktop`.
* `14 - Editar proyecto - Desktop`.
* `15 - Crear noticia - Desktop`.
* `16 - Detalle de noticia - Desktop`.
* `17 - Editar noticia - Desktop`.

### 📱 Diseño responsive

#### Agregado

* Adaptación de las interfaces al formato Mobile.
* Definición de un ancho de referencia móvil de 390 px.
* Diseño de `Inicio - Mobile`.
* Diseño de `Autenticación - Mobile`.
* Avance en la adaptación de `Dashboard - Mobile`.
* Diseño de las vistas móviles de Mi perfil, Mis proyectos y Oportunidades.
* Aplicación de los criterios responsive definidos en `Components & Responsive`.
* Ajustes de jerarquía visual, espaciado y componentes para pantallas de menor tamaño.

### 🔄 Consolidación del diseño

#### Cambiado

* Se consolidó la estructura visual de las interfaces principales.
* Se aplicaron de manera consistente los colores, la tipografía, el espaciado y los componentes del Design System.
* Se revisó la consistencia visual entre las diferentes pantallas.
* Se ajustaron dimensiones y distribución de elementos para mejorar la legibilidad.
* Se consolidó la navegación entre los módulos principales.
* Se estableció Figma como referencia visual para la implementación frontend.

### ✅ Finalización de la Fase 3 — Diseño UI/UX

#### Agregado

* Revisión general de las interfaces diseñadas.
* Revisión de la aplicación del Design System.
* Revisión de los componentes reutilizables.
* Revisión de la estructura de navegación.
* Consolidación de las pantallas Desktop diseñadas en Figma.
* Preparación de las referencias visuales para la etapa de implementación.

#### Cambiado

* Se dio por finalizada la etapa principal de diseño UI/UX a finales de septiembre de 2026.
* Figma pasó a funcionar como referencia visual para la implementación.
* Se consolidaron los criterios responsive definidos durante el diseño.
* Se establecieron las bases visuales para iniciar el desarrollo frontend.

> **Nota:** La finalización de la fase de diseño no implica que todas las pantallas móviles estén completamente implementadas o que cada adaptación responsive haya sido validada en código.

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

* Se consolidó la relación entre el Design System y los componentes reutilizables.
* Se mantuvo el trabajo manual en Figma sin utilizar Auto Layout.
* Se organizó la página para facilitar la posterior implementación.
* Se estableció `Components & Responsive` como una sección finalizada del diseño.

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

* Se reorganizó manualmente la distribución del Design System.
* Se decidió separar los componentes reutilizables del contenido base mediante una segunda página.
* Se estableció que el diseño de esta etapa se desarrollaría sin Auto Layout.

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

* Se reorganizó la distribución de los elementos del Design System.
* Se dejó la evaluación de Color Styles y Variables de Figma para una etapa posterior.

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
**Fase actual:** Fase 4 — Implementación.

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
* Interfaces Desktop.
* Adaptaciones Mobile.
* Consolidación de las referencias visuales.

### Fase 4 — Implementación

En desarrollo.

Se ha avanzado en:

* Estructura base de Inicio.
* Componentes reutilizables de Header y Footer.
* Sistema modular de estilos.
* Tipografía centralizada.
* Navegación responsive.
* Favicon institucional.
* Implementación inicial de Inicio.
* Inicio de sesión.
* Registro de usuarios.
* Sistema global de alertas.
* Recuperación de contraseña.
* Pantalla de Proyectos.

Las demás interfaces diseñadas en Figma se implementarán progresivamente.

---

# 🚀 Próximas actividades

Las siguientes actividades están orientadas a continuar la Fase 4 — Implementación:

* Revisar visualmente `Inicio - Desktop` frente a Figma.
* Revisar visualmente `Inicio - Mobile` frente a Figma.
* Corregir las diferencias visuales identificadas.
* Validar los formularios de inicio de sesión y registro.
* Completar y probar el flujo de recuperación de contraseña.
* Configurar el servicio de correo necesario para la recuperación.
* Definir y validar el esquema definitivo de la base de datos.
* Continuar con la implementación de las demás interfaces.
* Reutilizar los componentes definidos en el Design System.
* Integrar progresivamente frontend, lógica PHP y base de datos.
* Implementar y verificar autenticación, sesiones y roles.
* Ejecutar pruebas funcionales y de seguridad.
* Corregir errores y realizar ajustes.
* Mantener actualizados la documentación y el historial de cambios.

---

> **Nota sobre las fechas:** Raíces Alzate se encuentra en desarrollo desde 2025. El 9 de julio de 2026 corresponde a una fecha registrada de consolidación documental, no al inicio del proyecto.
