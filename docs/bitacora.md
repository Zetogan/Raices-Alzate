# 📘 Bitácora del Proyecto — Raíces Alzate

> Registro cronológico de las actividades, decisiones, avances y cambios realizados durante el desarrollo de Raíces Alzate.

---

## 📌 Información general

| Campo                               | Detalle                                        |
| ----------------------------------- | ---------------------------------------------- |
| **Nombre del proyecto**             | Raíces Alzate                                  |
| **Institución**                     | Institución Educativa Gilberto Alzate Avendaño |
| **Desarrollador**                   | Santiago Emitola                               |
| **Inicio del proyecto**             | 2025                                           |
| **Estado actual**                   | En desarrollo                                  |
| **Versión documental**              | v0.1.1                                         |
| **Fase actual**                     | Fase 4 — Implementación                        |
| **Última actualización registrada** | 9 de octubre de 2026                           |

---

## 🎯 Propósito de la bitácora

Esta bitácora documenta la evolución de Raíces Alzate desde sus primeras etapas de planificación hasta su implementación técnica.

Su propósito es mantener un registro organizado y verificable de las actividades realizadas, las decisiones tomadas, los componentes desarrollados, las modificaciones efectuadas y las tareas pendientes.

La información registrada sirve como referencia para el seguimiento del proyecto, la actualización de la documentación técnica y la continuidad del desarrollo.

---

# 🗓️ Registro cronológico de actividades

## Fase 1 — Planificación y organización inicial

### Sesión 1 — Organización inicial del repositorio

**Fecha:** 9 de julio de 2026

**Actividades realizadas:**

* Organización inicial del repositorio de Raíces Alzate.
* Definición de una estructura base para organizar los archivos del proyecto.
* Creación de directorios y archivos iniciales para facilitar el desarrollo progresivo.
* Incorporación de archivos `.gitkeep` en directorios que debían conservarse en Git pese a encontrarse vacíos.
* Preparación del archivo README principal para presentar el proyecto.

**Resultado:**

Se estableció una base organizativa para continuar el desarrollo del proyecto y mantener sus archivos estructurados desde el repositorio.

### Sesión 2 — Consolidación de la estructura inicial

**Fecha:** 9 de julio de 2026

**Actividades realizadas:**

* Revisión de la estructura de directorios.
* Organización inicial de los recursos del proyecto.
* Ajustes al README principal.
* Preparación del repositorio para recibir progresivamente los documentos, recursos visuales y archivos de implementación.

**Resultado:**

Se consolidó la estructura inicial del repositorio y se preparó el entorno de trabajo para las siguientes actividades.

### Sesión 3 — Cierre de la documentación base

**Fecha:** 10 de julio de 2026

**Actividades realizadas:**

* Revisión de los documentos de planificación.
* Organización de la documentación general del proyecto.
* Definición de la transición entre la etapa documental y el trabajo de diseño UI/UX.
* Preparación de la siguiente fase de trabajo en Figma.

**Resultado:**

Se avanzó en la consolidación documental y se estableció una ruta de trabajo para comenzar el diseño de la interfaz.

---

# 🎨 Fase 2 — Diseño del sistema visual y componentes

## Sesión 4 — Definición inicial del sistema visual

**Fecha:** 16 de julio de 2026

**Actividades realizadas:**

* Inicio de la organización del sistema visual en Figma.
* Definición y revisión de colores para la interfaz.
* Organización de las primeras decisiones relacionadas con la tipografía.
* Preparación de los elementos visuales reutilizables.

**Resultado:**

Se establecieron las bases iniciales del sistema de diseño que serviría como referencia para las diferentes pantallas.

## Sesión 5 — Consolidación del Design System

**Fecha:** 20 de julio de 2026

**Actividades realizadas:**

* Organización de las categorías de color.
* Definición de la jerarquía tipográfica inicial.
* Establecimiento de una escala de espaciado.
* Diseño de los primeros estilos de botones.
* Organización de los elementos visuales para facilitar su reutilización.

**Decisiones de diseño:**

Se definieron las siguientes referencias cromáticas:

| Elemento         | Color     |
| ---------------- | --------- |
| Primario         | `#D32F2F` |
| Secundario       | `#1565C0` |
| Texto principal  | `#212121` |
| Texto secundario | `#757575` |
| Fondo claro      | `#F5F5F5` |
| Blanco           | `#FFFFFF` |
| Borde            | `#E0E0E0` |

La jerarquía tipográfica inicial se trabajó con Poppins. Posteriormente, se consolidó Inter como tipografía para las interfaces del proyecto.

También se estableció una escala de espaciado basada en múltiplos de cuatro:

`4, 8, 12, 16, 24, 32, 48 y 64 px`.

**Resultado:**

Se avanzó en la construcción del sistema de diseño, definiendo referencias visuales reutilizables para las siguientes pantallas.

## Sesión 6 — Componentes y estados de interacción

**Fecha:** 5 de septiembre de 2026

**Actividades realizadas:**

* Creación y organización de la página `Components & Responsive` en Figma.
* Diseño de componentes reutilizables para formularios.
* Definición de estados visuales para botones de opción y controles de selección.
* Revisión de la coherencia de los componentes con el sistema de diseño.

**Estados trabajados:**

* Normal.
* Seleccionado.
* Enfocado.
* Deshabilitado.
* Error, cuando corresponde al componente.

**Resultado:**

Se estableció una base de componentes reutilizables para mantener la consistencia visual entre las interfaces del proyecto.

## Sesión 7 — Estructura de navegación

**Fecha:** 8 de septiembre de 2026

**Actividades realizadas:**

* Diseño del diagrama de navegación en Figma.
* Identificación de las principales pantallas y relaciones entre ellas.
* Organización de los recorridos generales de navegación.

**Estructura definida:**

* Inicio → Autenticación → Dashboard.
* Dashboard → Perfil.
* Dashboard → Proyectos.
* Dashboard → Administración.
* Perfil → Egresados.
* Proyectos → Noticias.

**Resultado:**

Se definió una estructura general de navegación para orientar el diseño de las pantallas y la organización posterior de las rutas del sistema.

---

# 🖥️ Fase 3 — Diseño UI/UX de las interfaces

## Sesiones 8 a 14 — Diseño de pantallas Desktop y Mobile

**Periodo:** septiembre de 2026

Durante estas sesiones se desarrollaron las principales interfaces de Raíces Alzate en Figma, tomando como referencia el Design System y los componentes previamente definidos.

### Actividades realizadas

* Diseño de la pantalla de Inicio para escritorio.
* Organización de la estructura visual del encabezado y el pie de página.
* Diseño de las secciones de presentación y beneficios de la plataforma.
* Diseño progresivo de las pantallas de autenticación y del área privada.
* Elaboración de interfaces relacionadas con perfiles, proyectos, oportunidades, noticias y administración.
* Revisión de la jerarquía visual, las dimensiones y la distribución de los elementos.
* Adaptación de las principales estructuras para dispositivos móviles.
* Revisión de la consistencia entre los componentes reutilizables y las diferentes pantallas.

### Pantalla de Inicio — Desktop

Se consolidó la pantalla `01 - Inicio - Desktop`, con un ancho de referencia de 1440 px.

La estructura incluyó:

* Encabezado de navegación.
* Sección principal o Hero.
* Sección informativa «¿Qué es Raíces Alzate?».
* Sección de beneficios «¿Por qué usar Raíces Alzate?».
* Tarjetas de Conexión, Oportunidades y Comunidad.
* Pie de página con información general del proyecto.

La estructura se ajustó para mantener una distribución clara y evitar desbordamientos entre secciones.

### Consolidación de las pantallas Desktop

El trabajo de diseño avanzó hasta la pantalla `17 - Editar noticia - Desktop`.

Se organizaron las interfaces de acuerdo con las necesidades de navegación y las funcionalidades previstas para la plataforma.

### Adaptación Mobile

Se trabajó en la adaptación de las interfaces para dispositivos móviles, incluyendo:

* Inicio Mobile.
* Autenticación Mobile.
* Componentes y secciones del Dashboard Mobile, entre ellos Mi perfil, Mis proyectos y Oportunidades.

El trabajo responsive se organizó como parte del proceso de adaptación de las interfaces, conservando la identidad visual del proyecto.

### Resultado de la fase

Al finalizar septiembre de 2026, se dio por terminada la etapa principal de diseño UI/UX y se estableció Figma como referencia visual para la implementación frontend.

La adaptación responsive y la revisión de las interfaces móviles quedaron sujetas a validación durante el proceso de implementación, de acuerdo con el alcance efectivamente completado de cada pantalla.

---

# 💻 Fase 4 — Implementación

## Sesión 15 — Implementación de Inicio y estructura compartida

**Fecha:** 8 de octubre de 2026

**Actividades realizadas:**

* Inicio de la implementación frontend basada en los diseños de Figma.
* Desarrollo de la estructura de la página de Inicio.
* Organización de los componentes compartidos de encabezado y pie de página en PHP.
* Preparación de la navegación responsive.
* Integración de los recursos visuales disponibles, incluido el isotipo y el favicon.
* Organización de los estilos generales y de los archivos tipográficos.

**Archivos y recursos trabajados:**

* `index.php`.
* `includes/header.php`.
* `includes/footer.php`.
* `assets/css/main.css`.
* `assets/css/fonts.css`.
* `assets/css/header.css`.
* `assets/css/footer.css`.
* Recursos gráficos del proyecto.

**Decisiones de implementación:**

* Uso de PHP para integrar la estructura compartida.
* Organización de los estilos en archivos CSS separados según su responsabilidad.
* Centralización de la tipografía Inter.
* Preparación de la estructura para escritorio y dispositivos móviles.
* Organización del pie de página en tres columnas.

Se tomó como referencia un ancho de escritorio de 1440 px y un ancho móvil de 390 px para las comprobaciones iniciales de la interfaz.

**Resultado:**

Se estableció una base frontend reutilizable para continuar con la implementación de las demás pantallas.

La conexión entre PHP y MySQL ya había sido probada previamente. Sin embargo, la definición e integración del esquema definitivo de la base de datos permaneció como una tarea pendiente.

## Sesión 16 — Calibración de botones

**Fecha:** 8 de octubre de 2026

**Objetivo:**

Ajustar los botones globales para mantener la coherencia visual con el sistema de diseño de Figma.

**Actividades realizadas:**

* Ajuste de dimensiones.
* Normalización de la tipografía.
* Definición de los estados de interacción.
* Organización de clases reutilizables.

**Especificaciones registradas:**

| Clase                              |  Ancho |  Alto | Radio |
| ---------------------------------- | -----: | ----: | ----: |
| `.btn-primary` / `.hero-cta-btn`   | 123 px | 40 px |  8 px |
| `.btn-sm` / `.header-register-btn` | 112 px | 40 px |  8 px |

**Estilos definidos:**

* Tipografía Inter, peso 500 y tamaño de 15 px.
* Color primario `#D32F2F`.
* Texto blanco para los botones primarios.
* Hover: `#B71C1C`.
* Active: `#8E0000`.
* Retroalimentación de interacción mediante una reducción de escala de `0.98`, según la implementación registrada.

**Archivo principal:**

`assets/css/main.css`.

**Resultado:**

Se consolidaron estilos compartidos para los botones, facilitando su reutilización en las diferentes pantallas.

## Sesión 17 — Implementación de autenticación: inicio de sesión

**Fecha:** 8 de octubre de 2026

**Objetivo:**

Implementar la pantalla de inicio de sesión de acuerdo con el diseño definido en Figma.

**Archivos creados o trabajados:**

* `login.php`.
* `pages/login.php`.
* `auth/login-process.php`.
* `assets/css/auth.css`.
* `assets/js/auth.js`.

También se realizaron ajustes en los archivos compartidos necesarios para cargar los recursos de autenticación.

**Actividades realizadas:**

* Construcción del formulario de inicio de sesión.
* Incorporación de los campos de correo electrónico y contraseña.
* Implementación de la opción «Recordarme».
* Incorporación del enlace para recuperar la contraseña.
* Integración de alertas de retroalimentación.
* Preparación del procesamiento del formulario en PHP.
* Incorporación de validaciones en el frontend.
* Ajuste de la tarjeta de autenticación y de sus elementos visuales.

**Especificaciones visuales registradas:**

* Ancho máximo de la tarjeta: 440 px.
* Altura de referencia de los controles: 44 px.
* Ajustes de espaciado para dispositivos móviles.
* Uso de Inter y de los colores definidos en el sistema de diseño.

**Consideraciones técnicas:**

* Validación de los datos recibidos.
* Preparación del procesamiento mediante PHP.
* Uso de PDO y consultas preparadas en las operaciones de base de datos correspondientes.
* Prevención de envíos duplicados durante el procesamiento, según la implementación registrada.
* Retroalimentación accesible mediante atributos ARIA.

**Resultado:**

Se implementó la estructura de inicio de sesión y se preparó su integración con el procesamiento de autenticación.

La comprobación de la autenticación real debe distinguirse de la validación visual del formulario y de las pruebas de procesamiento.

## Sesión 18 — Ajustes visuales de autenticación

**Fecha:** 8 de octubre de 2026

**Objetivo:**

Corregir la alineación de los elementos del encabezado de autenticación con respecto a Figma.

**Actividades realizadas:**

* Revisión de la alineación del título y subtítulo.
* Ajuste del encabezado para mantener la alineación a la izquierda.
* Incorporación de reglas específicas para evitar conflictos con estilos heredados.

**Archivo trabajado:**

`assets/css/auth.css`.

**Resultado:**

Se mejoró la correspondencia visual del encabezado de autenticación con el diseño de referencia.

## Sesión 19 — Sistema global de alertas

**Fecha:** 8 de octubre de 2026

**Objetivo:**

Establecer un sistema de alertas reutilizable para comunicar resultados, errores y advertencias dentro de la plataforma.

**Actividades realizadas:**

* Creación de la clase base `.alert`.
* Definición de variantes semánticas.
* Incorporación de iconos SVG.
* Integración inicial con la pantalla de inicio de sesión.
* Preparación de la retroalimentación para formularios.

**Variantes registradas:**

| Variante    | Fondo     | Borde     | Texto     |
| ----------- | --------- | --------- | --------- |
| Éxito       | `#E8F5E9` | `#81C784` | `#2E7D32` |
| Error       | `#FFEBEE` | `#EF9A9A` | `#C62828` |
| Advertencia | `#FFF8E1` | `#FFE082` | `#F57F17` |
| Información | `#E3F2FD` | `#90CAF9` | `#1565C0` |

Se registró el uso de atributos como `role="alert"` y `aria-live="polite"` para mejorar la comunicación de los mensajes a tecnologías de asistencia, según el contexto de cada alerta.

**Archivos trabajados:**

* `assets/css/main.css`.
* `assets/css/auth.css`.
* `assets/js/auth.js`.

**Resultado:**

Se estableció una base de alertas reutilizables para los formularios actuales y futuros de la plataforma.

## Sesión 20 — Adaptación responsive del encabezado

**Fecha:** 8 de octubre de 2026

**Objetivo:**

Adaptar el encabezado de navegación para mantener una experiencia adecuada en dispositivos móviles.

**Actividades realizadas:**

* Reorganización de las acciones de autenticación.
* Ocultamiento de las acciones directas de escritorio en el punto de adaptación móvil.
* Incorporación de las acciones de inicio de sesión y registro en el menú desplegable.
* Ajustes en la estructura HTML y en los estilos responsive.

**Archivos trabajados:**

* `includes/header.php` o el componente de encabezado correspondiente.
* `assets/css/header.css`.

**Resultado:**

Se preparó una navegación móvil más compacta, conservando el acceso a las opciones principales sin saturar el encabezado.

## Sesión 21 — Implementación del registro de usuarios

**Fecha:** 8 de octubre de 2026

**Objetivo:**

Desarrollar la pantalla de registro y preparar el procesamiento de los datos enviados por los usuarios.

**Archivos creados o trabajados:**

* `registro.php`.
* `pages/registro.php`.
* `auth/registro-process.php`.
* `assets/css/auth.css`.
* `assets/js/auth.js`.
* `index.php`.

Durante esta etapa también se eliminaron archivos estáticos redundantes relacionados con las versiones anteriores de las pantallas de autenticación.

**Actividades realizadas:**

* Construcción del formulario de registro.
* Organización de los campos de identificación y acceso.
* Incorporación de la selección del tipo de usuario.
* Preparación de las validaciones del formulario.
* Implementación del procesamiento en PHP.
* Preparación de la verificación de correo electrónico duplicado.
* Uso de mecanismos de almacenamiento seguro de contraseñas.

**Consideraciones técnicas registradas:**

* Uso de `password_hash()` con `PASSWORD_DEFAULT`.
* Uso de PDO y consultas preparadas.
* Validación de los datos recibidos.
* Control de correos electrónicos duplicados.
* Uso de transacciones en el procesamiento correspondiente, según la implementación registrada.

**Resultado:**

Se desarrolló la estructura de registro y se preparó el procesamiento de la información recibida.

Los nombres definitivos de los campos, el texto del botón y las condiciones para los distintos tipos de usuario deben conservarse según la versión verificada de la interfaz y del código.

## Sesión 22 — Calibración de estados de formularios

**Fecha:** 8 de octubre de 2026

**Objetivo:**

Unificar los estados visuales y el comportamiento de los campos y controles de selección.

**Actividades realizadas:**

* Ajuste de los estados normal, enfocado, completado, deshabilitado y error de los campos.
* Corrección de los estados de los botones de opción.
* Diferenciación visual entre selección y foco.
* Incorporación de mensajes de validación.
* Limpieza reactiva de errores al modificar los campos.
* Reutilización de funciones auxiliares de validación.

**Criterios visuales registrados:**

* Selección de radio: `#D32F2F`.
* Foco: `#1565C0`.
* Borde y texto de error en rojo.
* Estado deshabilitado con tonos neutros.
* Conservación de los colores definidos en el Design System.

**Funciones auxiliares registradas:**

* `setInputError()`.
* `clearInputError()`.
* `setRadioGroupError()`.
* `clearRadioGroupError()`.

**Archivos trabajados:**

* `assets/css/auth.css`.
* `assets/js/auth.js`.

**Resultado:**

Se unificaron los estados de interacción y validación para mejorar la consistencia entre los formularios de autenticación.

## Sesión 23 — Implementación de recuperación de contraseña

**Fecha:** 9 de octubre de 2026

**Objetivo:**

Desarrollar la pantalla de recuperación de contraseña y preparar el procesamiento de las solicitudes correspondientes.

**Archivos creados o trabajados:**

* `recuperar-password.php`.
* `pages/recuperar-password.php`.
* `auth/recuperar-process.php`.
* `index.php`.
* `includes/header.php`.
* `includes/footer.php`.
* `assets/css/auth.css`.
* `assets/js/auth.js`.

**Actividades realizadas:**

* Creación del formulario de recuperación.
* Incorporación del campo de correo electrónico.
* Preparación del procesamiento de solicitudes en PHP.
* Incorporación de validaciones en el frontend.
* Integración de mensajes de retroalimentación.
* Incorporación del enlace para volver al inicio de sesión.
* Generación de tokens mediante `random_bytes(32)`, según la implementación reportada.

**Consideraciones de seguridad:**

* Validación del correo electrónico recibido.
* Uso de una respuesta genérica para reducir el riesgo de enumeración de cuentas.
* Preparación del mecanismo de recuperación mediante tokens.
* Necesidad de verificar el almacenamiento, la expiración y la validación de los tokens en el backend antes de considerar completo el flujo.

**Respuesta genérica registrada:**

> Si el correo electrónico ingresado coincide con una cuenta activa en la comunidad, recibirás las instrucciones correspondientes.

**Estado del servicio de correo:**

La configuración SMTP todavía está pendiente. Por lo tanto, la generación del token no debe confundirse con el envío efectivo de un correo ni con la finalización integral del proceso de restablecimiento de contraseña.

**Resultado:**

Se implementó la estructura de recuperación de contraseña y se preparó su procesamiento, quedando pendiente completar y verificar la integración con el servicio de correo y el flujo de restablecimiento.

## Sesión 24 — Implementación de la pantalla de Proyectos

**Fecha:** 9 de octubre de 2026

**Objetivo:**

Implementar con fidelidad visual y funcional la pantalla de Proyectos de la plataforma Raíces Alzate (v0.1.1), respetando estrictamente la arquitectura modular del repositorio y el sistema de diseño visual institucional.

**Archivos creados o trabajados:**

* `proyectos.php` (punto de entrada directo en la raíz).
* `pages/proyectos.php` (vista modular con contenedor semántico).
* `assets/css/portal.css` (hoja de estilos para el portal público y pantalla de proyectos).
* `assets/img/proyectos-illustration.svg` (ilustración vectorial oficial con laptop, brote, bombilla e insignias de proyectos).
* `assets/icons/logo sin fondo raices alzate.ico` (isotipo oficial reutilizado en cabecera y pie de página).
* `index.php` (incorporación de la ruta `case 'proyectos':` en el Front Controller).
* `includes/header.php` (incorporación condicional de `portal.css`).
* `components/header.php` (enlace activo con indicador inferior rojo de 2 px y logo oficial en icons).
* `components/footer.php` (pie de página minimalista con logo oficial y navegación principal de 4 enlaces).
* `assets/css/header.css` (calibración del logo a 42 px e indicador activo).
* `assets/css/footer.css` (fondo `#1E1E1E` y tipografía institucional `#B0BEC5`).
* `assets/css/main.css` (incorporación de la clase `.btn-outline-secondary`).
* `docs/bitacora.md` (registro de la sesión).
* `docs/changelog.md` (registro en el historial de cambios).

**Actividades realizadas:**

* Creación del controlador de vista directo `proyectos.php` en la raíz para despachar de forma modular el encabezado, la vista de contenido y el pie de página.
* Construcción de la vista semántica en `pages/proyectos.php` estructurada en un layout de dos columnas balanceadas en Desktop (1440 px) y una sola columna apilada en Mobile (< 768 px).
* Incorporación del título institucional «Proyectos» y el párrafo descriptivo de llamado a la comunidad.
* Calibración del botón principal «Iniciar sesión» (`.btn-primary`, `.btn-proyectos-login`) con `display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; text-align: center; min-width: 140px; height: 44px; padding: 0 24px; line-height: normal;` para garantizar que el texto se presente centrado y en una sola línea horizontal sin particiones.
* Configuración del botón secundario «Crear una cuenta» en estilo outline con borde y texto en azul institucional `#1565C0`.
* Diseño y renderizado de la ilustración vectorial oficial en formato SVG (`assets/img/proyectos-illustration.svg`), armonizada exclusivamente con la paleta de colores oficial: laptop en tonos neutros y azules, brote y planta en crecimiento con acentos en rojo Alzate, bombilla de innovación y tarjetas flotantes de código, comunidad e impacto.
* Unificación del recurso del logo en su ubicación oficial `assets/icons/logo sin fondo raices alzate.ico`, evitando duplicaciones en `assets/img/`.
* Actualización del indicador visual de pestaña activa en el menú de navegación (`border-bottom: 2px solid #D32F2F;`).
* Calibración del footer a `#1E1E1E` con la navegación principal aprobada (`Inicio`, `Proyectos`, `Egresados`, `Noticias`), eliminando el enlace a Contacto.
* Reordenamiento responsive en Mobile (<= 768 px) mediante Flexbox, estructurando la secuencia visual vertical: 1. Título y descripción (`.proyectos-header-text`, `order: 1`), 2. Tarjeta de ilustración (`.proyectos-illustration-card`, `order: 2`), 3. Botones de acción (`.proyectos-actions`, `order: 3`).
* Verificación de la adaptabilidad para pantallas móviles garantizando que no existan desbordamientos horizontales.

**Resultado:**

Se implementó con éxito la pantalla de Proyectos cumpliendo todas las reglas de arquitectura modular y respetando la paleta estricta de colores del sistema de diseño institucional.

## Sesión 25 — Implementación de la pantalla modular de Egresados

**Fecha:** 9 de octubre de 2026

**Objetivo:**

Implementar de forma modular y con estricta fidelidad visual la pantalla de Egresados (`05 - Egresados - Desktop` y Mobile), respetando la arquitectura modular existente en `proyectos.php`, reutilizando los componentes globales sin alteración y aplicando la paleta institucional oficial.

**Archivos creados o trabajados:**

* `egresados.php` (punto de entrada directo en la raíz).
* `pages/egresados.php` (vista modular con contenedor semántico y layout accesible).
* `assets/css/portal.css` (ampliación de estilos modulares para compartir selectores con Egresados).
* `assets/img/egresados-illustration.svg` (ilustración vectorial oficial con birrete de graduación, diplomas, red comunitaria y brote institucional).
* `index.php` (incorporación de la ruta `case 'egresados':` en el Front Controller).
* `docs/bitacora.md` (registro de la sesión de trabajo).
* `docs/changelog.md` (registro en el historial de cambios).

**Actividades realizadas:**

* Creación del controlador de vista directo `egresados.php` en la raíz del proyecto para despachar modularmente `header.php`, `pages/egresados.php` y `footer.php`, asignando `$currentPage = 'egresados'` para la activación automática del menú institucional.
* Construcción de la vista semántica en `pages/egresados.php` estructurada en un layout de dos columnas en escritorio (1440 px) y una sola columna en mobile (< 768 px).
* Incorporación del título institucional «Egresados» y párrafo descriptivo de vinculación a la comunidad alzatista.
* Inclusión de botones de acción institucionales «Iniciar sesión» (`#D32F2F`) y «Crear una cuenta» (outline `#1565C0`) con dimensionamiento de 44 px de altura, min-width 140 px y prevención de saltos de línea.
* Creación de la ilustración vectorial oficial `assets/img/egresados-illustration.svg` alineada con la paleta de colores oficial (`#D32F2F`, `#1565C0`, `#212121`, `#757575`, `#E0E0E0`, `#F5F5F5`): birrete con borla roja, certificado enrollado con sello institucional, tarjetas flotantes de red comunitaria y brote de raíces.
* Integración de la ruta `case 'egresados':` en el enrutador central `index.php`.
* Verificación de la carga modular de `portal.css` mediante la directiva condicional existente en `includes/header.php`.
* Aplicación de la secuencia vertical en Mobile (<= 768 px) mediante Flexbox: 1. Textos (`order: 1`), 2. Tarjeta de ilustración (`order: 2`), 3. Botones de acción (`order: 3`).
* Validación de sintaxis PHP y verificación de salida HTML limpia sin errores.

**Resultado:**

Se implementó con éxito la pantalla de Egresados bajo la arquitectura modular estándar, sin alterar los archivos base de encabezado, pie de página ni duplicar recursos de imagen.

## Sesión 26 — Implementación de la pantalla modular de Noticias

**Fecha:** 9 de octubre de 2026

**Objetivo:**

Implementar de forma modular y con estricta fidelidad visual la pantalla de Noticias (`07 - Noticias - Desktop` y Mobile), respetando la arquitectura modular ya consolidada en `proyectos.php` y `egresados.php`, reutilizando los componentes globales sin alteración y aplicando la paleta institucional oficial.

**Archivos creados o trabajados:**

* `noticias.php` (punto de entrada directo en la raíz).
* `pages/noticias.php` (vista modular con contenedor semántico y layout accesible).
* `assets/css/portal.css` (ampliación de estilos modulares para compartir selectores con Noticias).
* `assets/img/noticias-illustration.svg` (ilustración vectorial oficial con portal de noticias, megáfono de difusión, calendario de eventos y brote institucional).
* `index.php` (incorporación de la ruta `case 'noticias':` en el Front Controller).
* `docs/bitacora.md` (registro de la sesión de trabajo).
* `docs/changelog.md` (registro en el historial de cambios).

**Actividades realizadas:**

* Creación del controlador de vista directo `noticias.php` en la raíz del proyecto para despachar modularmente `header.php`, `pages/noticias.php` y `footer.php`, asignando `$currentPage = 'noticias'` para la activación automática de la pestaña en la navegación institucional.
* Construcción de la vista semántica en `pages/noticias.php` estructurada en un layout de dos columnas en escritorio (1440 px) y una sola columna en mobile (< 768 px).
* Incorporación del título institucional «Noticias» y párrafo descriptivo de novedades institucionales y eventos para la comunidad.
* Inclusión de botones de acción institucionales «Iniciar sesión» (`#D32F2F`) y «Crear una cuenta» (outline `#1565C0`) con dimensionamiento de 44 px de altura, min-width 140 px y texto continuo en una sola línea.
* Creación de la ilustración vectorial oficial `assets/img/noticias-illustration.svg` alineada estrictamente con la paleta de colores oficial: tablón digital con artículo destacado e insignia institucional, megáfono con ondas de difusión en rojo y azul, campana de notificaciones, tarjeta flotante de eventos con fecha 15 de octubre y brote de raíces alzatistas.
* Integración de la ruta `case 'noticias':` en el enrutador central `index.php`.
* Verificación de la carga modular de `portal.css` mediante la directiva condicional existente en `includes/header.php`.
* Aplicación de la secuencia vertical en Mobile (<= 768 px) mediante Flexbox: 1. Textos (`order: 1`), 2. Tarjeta de ilustración (`order: 2`), 3. Botones de acción (`order: 3`).
* Validación de sintaxis PHP y verificación de salida HTML limpia sin errores.

**Resultado:**

Se implementó con éxito la pantalla de Noticias bajo la arquitectura modular estándar, completando la tríada de pantallas informativas públicas (`Proyectos`, `Egresados`, `Noticias`) alineadas con Figma.

## Sesión 27 — Implementación del Dashboard Desktop y Mobile (Fase 4 — v0.1.1)

**Fecha:** 10 de octubre de 2026

**Objetivo:**

Implementar con fidelidad exacta la pantalla de Dashboard (`03 - Dashboard - Desktop` y Mobile) para usuarios autenticados de Raíces Alzate, aplicando control estricto de sesiones, extracción dinámica de información de usuario, componentes modulares reutilizables y el sistema de diseño institucional.

**Archivos creados o trabajados:**

* `dashboard.php` (controlador de entrada directo en la raíz con verificación de sesión activa).
* `logout.php` (controlador de cierre seguro de sesión).
* `pages/dashboard.php` (vista modular privada con grid de 3 tarjetas y bloque inferior organizado).
* `includes/sidebar.php` (componente reutilizable de barra lateral de 240px, navegación y marca).
* `includes/topbar.php` (componente reutilizable de barra superior con datos dinámicos y avatar).
* `assets/css/dashboard.css` (hoja de estilos modular para la interfaz privada).
* `index.php` (incorporación de la ruta `case 'dashboard':` en el Front Controller).
* `docs/bitacora.md` (registro de la sesión de trabajo).
* `docs/changelog.md` (registro en el historial de cambios).

**Actividades realizadas:**

* Creación del controlador de entrada `dashboard.php` en la raíz con verificación estricta de sesión activa (`empty($_SESSION['user'])`), redirigiendo a `login.php` en caso de acceso no autorizado.
* Implementación de la extracción dinámica de los datos del usuario en sesión (`nombre`, `rol`, inicial calculada en UTF-8), evitando nombres fijos en duro en el código.
* Construcción del componente reutilizable `includes/sidebar.php` (240px, fondo `#FFFFFF`) con cabecera institucional en `#D32F2F`, elemento activo `Dashboard` y enlaces secundarios.
* Creación de `includes/topbar.php` con título de vista, disparador accesible para menú móvil (`#dashboardMenuToggle`), nombre de usuario, rol y avatar circular institucional (`#D32F2F`).
* Implementación de la vista `pages/dashboard.php`:
  * Sección de bienvenida institucional con título y párrafo explicativo.
  * Fila de 3 tarjetas de resumen (`Mi perfil`, `Mis proyectos`, `Oportunidades`) en grid de 3 columnas (desktop) y 1 columna (mobile), con enlaces `Ver... →` en azul institucional `#1565C0`.
  * Bloque inferior a dos columnas en Desktop (~65% / ~35%):
    - Columna izquierda: tarjeta de `Actividad reciente` con lista de eventos temporales y bloque de `Acciones rápidas` (`Actualizar mi perfil` en `#D32F2F` y `Publicar proyecto` en contorno `#1565C0`).
    - Columna derecha: tarjeta de `Próximas oportunidades` categorizadas con insignias institucionales (Beca, Empleo, Capacitación).
* Adaptación responsive para Mobile (< 768px):
  * Barra lateral oculta off-canvas que se despliega como cajón lateral (drawer) con telón de fondo (`.sidebar-backdrop`).
  * Reordenamiento vertical del bloque inferior mediante Flexbox: 1. Actividad reciente (`order: 1`), 2. Próximas oportunidades (`order: 2`), 3. Acciones rápidas con botones al 100% de ancho (`order: 3`).
  * Exclusión del pie de página público en la vista del dashboard conforme al diseño de Figma.
* Implementación de la barra lateral colapsable (Mini-Sidebar en Desktop):
  * Toggle `#sidebarToggleBtn` con SVG animado (180deg).
  * Ancho reducido de 76px con isotipo e iconos de 48px centrados y textos ocultos.
  * Persistencia en `localStorage` (`sidebar_collapsed`) y prevención de FOUC al recargar.
  * Compatibilidad intacta con el menú móvil.
* Creación del controlador `logout.php` para cierre de sesión seguro con limpieza de cookies.
* Validación sintáctica PHP con `php -l` (0 errores) y pruebas unitarias de redirección de seguridad y dinamismo de sesión.

**Resultado:**

Se implementó el Dashboard Desktop y Mobile con plena fidelidad visual al frame de Figma `03 - Dashboard - Desktop`, garantizando seguridad en acceso de sesiones y dinamismo de datos.

---

# 📊 Estado general del proyecto

## Fases del proyecto

| Fase   | Descripción                  | Estado                                     |
| ------ | ---------------------------- | ------------------------------------------ |
| Fase 1 | Planificación y organización | Completada según el registro del proyecto  |
| Fase 2 | Documentación base           | Completada según el registro del proyecto  |
| Fase 3 | Diseño UI/UX                 | Finalizada a finales de septiembre de 2026 |
| Fase 4 | Implementación               | En desarrollo                              |

## Componentes implementados o desarrollados parcialmente

| Componente                            | Estado registrado                                                         |
| ------------------------------------- | ------------------------------------------------------------------------- |
| Estructura inicial del repositorio    | Consolidada                                                               |
| Documentación base                    | Elaborada; requiere actualización continua                                |
| Design System                         | Elaborado en Figma                                                        |
| Componentes reutilizables de Figma    | Elaborados                                                                |
| Diagrama de navegación                | Elaborado                                                                 |
| Diseño de Inicio Desktop              | Finalizado en Figma                                                       |
| Diseño de autenticación Desktop       | Diseñado en Figma                                                         |
| Diseños de otras pantallas Desktop    | Desarrollados hasta `17 - Editar noticia - Desktop`                       |
| Adaptación de interfaces Mobile       | Avanzada; requiere validación por pantalla                                |
| Estructura frontend de Inicio         | Implementada inicialmente                                                 |
| Encabezado y pie de página            | Implementados inicialmente                                                |
| Estilos globales y tipografía         | Organizados                                                               |
| Botones globales                      | Calibrados inicialmente                                                   |
| Sistema de alertas                    | Implementado inicialmente                                                 |
| Inicio de sesión                      | Estructura y procesamiento desarrollados; requieren pruebas integrales    |
| Registro de usuarios                  | Estructura y procesamiento desarrollados; requieren verificación integral |
| Recuperación de contraseña            | Estructura y generación de token desarrolladas; SMTP pendiente            |
| Pantalla de Proyectos                 | Implementada; vista modular, controlador y estilos calibrados             |
| Pantalla de Egresados                 | Implementada; vista modular, controlador y estilos calibrados             |
| Pantalla de Noticias                  | Implementada; vista modular, controlador y estilos calibrados             |
| Pantalla de Dashboard                 | Implementada; controlador, vista modular, sidebar, topbar y estilos       |
| Esquema definitivo de base de datos   | Pendiente de definición y validación                                      |
| Implementación de las demás pantallas | Pendiente de desarrollo progresivo                                        |
| Pruebas integrales de la plataforma   | Pendientes de completar                                                   |

> **Nota:** Los estados de esta tabla describen el avance registrado en la bitácora. No sustituyen una verificación del código, las pruebas funcionales ni la comparación visual de cada pantalla con Figma.

---

# 🔜 Próximas actividades

Las siguientes actividades deberán priorizarse de acuerdo con el estado real del repositorio y los requisitos del proyecto.

## 1. Validación visual

* Comparar las pantallas implementadas con sus referencias de Figma.
* Revisar dimensiones, espaciados, tipografía, colores y estados de interacción.
* Comprobar la presentación en escritorio y dispositivos móviles.
* Corregir diferencias visuales antes de continuar con la expansión de las interfaces.

## 2. Consolidación de la autenticación

* Verificar el inicio de sesión con cuentas válidas e inválidas.
* Comprobar el registro y la prevención de duplicados.
* Revisar las validaciones del frontend y del backend.
* Confirmar el manejo seguro de sesiones.
* Revisar los permisos de acceso y los roles de usuario.
* Completar la recuperación de contraseña, incluida la expiración y validación de tokens, y la integración SMTP.
* Probar los casos de error y los mensajes de retroalimentación.

## 3. Base de datos

* Definir y documentar el esquema definitivo.
* Identificar las entidades, relaciones, claves primarias y claves foráneas.
* Verificar la correspondencia entre los formularios y las estructuras de almacenamiento.
* Consolidar las operaciones de acceso a datos mediante PDO y consultas preparadas.
* Revisar las restricciones de integridad y las transacciones necesarias.

## 4. Implementación progresiva de las pantallas

* Continuar con las pantallas restantes siguiendo el orden de navegación establecido.
* Reutilizar los componentes compartidos.
* Mantener la separación entre presentación, procesamiento y acceso a datos.
* Adaptar cada pantalla a los diseños de escritorio y móvil correspondientes.

## 5. Pruebas y control de calidad

* Probar formularios y flujos principales.
* Verificar el comportamiento responsive.
* Revisar accesibilidad y navegación por teclado.
* Comprobar rutas, enlaces, alertas y estados vacíos.
* Detectar errores de integración.
* Confirmar que los cambios no afecten funcionalidades previamente desarrolladas.

## 6. Mantenimiento documental y Git

* Actualizar la bitácora después de cada avance significativo.
* Mantener sincronizados el README, el changelog, el roadmap y los manuales.
* Documentar las decisiones técnicas relevantes.
* Revisar las diferencias antes de realizar commits.
* Utilizar mensajes de commit descriptivos y coherentes con el cambio realizado.

---

# 📝 Observaciones generales

1. **Fecha de inicio:** Raíces Alzate se encuentra en desarrollo desde 2025. Las fechas de julio, septiembre y octubre de 2026 corresponden a sesiones y etapas registradas posteriormente.

2. **Diseño e implementación:** El cierre de la etapa principal de diseño UI/UX no implica que todas las pantallas estén implementadas en código. La implementación frontend comenzó formalmente en octubre de 2026.

3. **Figma como referencia:** Los diseños aprobados sirven como referencia para las dimensiones, la distribución, la tipografía, los colores y los estados de los componentes. Las diferencias encontradas durante la implementación deben revisarse y corregirse.

4. **Base de datos:** Se había verificado previamente la conexión entre PHP y MySQL. Esto no significa que el esquema definitivo de la plataforma se encuentre completamente diseñado e integrado.

5. **Seguridad:** La existencia de formularios y scripts de procesamiento no garantiza por sí sola que los flujos de autenticación y recuperación estén terminados. Deben verificarse las validaciones del backend, el manejo de sesiones, los permisos y el tratamiento seguro de los tokens.

6. **Recuperación de contraseña:** La generación de tokens está registrada como parte de la implementación. El envío de correos permanece pendiente mientras no se configure y pruebe el servicio SMTP.

7. **Seguimiento del proyecto:** Los avances futuros deben registrarse con fechas, archivos afectados, resultados comprobables, decisiones y tareas pendientes, evitando presentar como completadas las actividades que aún no se hayan verificado.

---

## ✅ Cierre

Esta bitácora consolida el recorrido documentado de Raíces Alzate desde la organización inicial del repositorio y la construcción del sistema de diseño hasta el comienzo de la implementación frontend.

El proyecto continúa en desarrollo, con la etapa principal de diseño UI/UX finalizada y la implementación técnica en curso.

La prioridad de las siguientes sesiones será validar lo implementado, completar los flujos de autenticación, consolidar la base de datos y avanzar progresivamente en las demás pantallas, manteniendo la coherencia con Figma y la documentación del proyecto.
