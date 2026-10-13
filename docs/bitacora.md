# 📘 Bitácora del Proyecto — Raíces Alzate

> Registro cronológico de las actividades, decisiones, avances y cambios realizados durante el desarrollo de Raíces Alzate.

---

## 📌 Información general

| Campo                               | Detalle                                            |
| ----------------------------------- | -------------------------------------------------- |
| **Nombre del proyecto**             | Raíces Alzate                                      |
| **Institución**                     | Institución Educativa Gilberto Alzate Avendaño     |
| **Desarrollador**                   | Santiago Emitola                                   |
| **Inicio del proyecto**             | 2025                                               |
| **Estado actual**                   | En desarrollo                                      |
| **Versión documental**              | v0.1.1                                             |
| **Fase actual**                     | Fase 4 — Implementación y refinamiento de interfaz |
| **Última actualización registrada** | 13 de octubre de 2026                              |
| **Rama de trabajo reportada**       | `dev-emitola`                                      |
| **Entorno de desarrollo**           | XAMPP, Apache y PHP nativo                         |

---

## 🎯 Propósito de la bitácora

Esta bitácora documenta la evolución de Raíces Alzate desde sus primeras etapas de planificación hasta su implementación técnica.

Su propósito es mantener un registro organizado y verificable de las actividades realizadas, las decisiones tomadas, los componentes desarrollados, las modificaciones efectuadas y las tareas pendientes.

La información registrada sirve como referencia para el seguimiento del proyecto, la actualización de la documentación técnica y la continuidad del desarrollo.

Los avances procedentes de informes de herramientas de desarrollo se registran como actividades reportadas. Cuando una funcionalidad requiere pruebas adicionales o inspección directa del repositorio, se indica expresamente para no confundir su implementación con su validación definitiva.

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

| Elemento         | Color     |
| ---------------- | --------- |
| Primario         | `#D32F2F` |
| Hover primario   | `#B71C1C` |
| Active primario  | `#8E0000` |
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
* Diseño de las pantallas de autenticación y del área privada.
* Elaboración de interfaces relacionadas con perfiles, egresados, proyectos, noticias y administración.
* Revisión de la jerarquía visual, las dimensiones y la distribución de los elementos.
* Adaptación de las interfaces para dispositivos móviles.
* Revisión de la consistencia entre los componentes reutilizables y las diferentes pantallas.

### Pantalla de Inicio — Desktop

Se consolidó la pantalla `01 - Inicio - Desktop`, con un ancho de referencia de 1440 px.

La estructura final incluye:

* Encabezado de navegación.
* Sección principal o Hero.
* Sección informativa «¿Qué es Raíces Alzate?».
* Sección de beneficios «¿Por qué usar Raíces Alzate?».
* Tarjetas de Conexión, Oportunidades y Comunidad.
* Pie de página con información general del proyecto.

La estructura se ajustó para mantener una distribución clara y evitar desbordamientos entre secciones.

### Consolidación de las pantallas Desktop

El diseño avanzó hasta la pantalla `17 - Editar noticia - Desktop`.

El conjunto de interfaces definido comprende:

1. Inicio.
2. Autenticación.
3. Dashboard.
4. Perfil.
5. Egresados.
6. Proyectos.
7. Noticias.
8. Administración.
9. Crear proyecto.
10. Detalle de proyecto.
11. Editar perfil.
12. Recuperar contraseña.
13. Registro.
14. Editar proyecto.
15. Crear noticia.
16. Detalle de noticia.
17. Editar noticia.

### Adaptación Mobile

Se diseñaron las versiones móviles de las 17 pantallas, tomando como referencia un ancho de 390 px.

Se trabajaron las adaptaciones de navegación, formularios, contenido y componentes del área privada, según las necesidades de cada pantalla.

### Resultado de la fase

La etapa de diseño UI/UX se declaró finalizada a finales de septiembre de 2026.

Se estableció Figma como referencia visual para la implementación frontend.

**Estado:** fase cerrada. Las diferencias visuales que se encuentren durante la programación se gestionan como ajustes de implementación y control de calidad, no como reapertura de la fase de diseño.

---

# 💻 Fase 4 — Implementación y refinamiento de interfaz

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

Se tomaron como referencia un ancho de escritorio de 1440 px y un ancho móvil de 390 px para las comprobaciones iniciales de la interfaz.

**Resultado:**

Se estableció una base frontend reutilizable para continuar con la implementación de las demás pantallas.

La conexión entre PHP y MySQL ya se había probado previamente. Sin embargo, esa prueba no acredita que el esquema definitivo de la base de datos esté diseñado e integrado en toda la plataforma.

## Sesión 16 — Calibración de botones

**Fecha:** 8 de octubre de 2026

**Objetivo:**

Ajustar los botones para mantener la coherencia visual con el sistema de diseño de Figma.

**Actividades realizadas:**

* Ajuste de dimensiones.
* Normalización de la tipografía.
* Definición de estados de interacción.
* Organización de clases reutilizables.

**Especificaciones registradas para los botones generales:**

| Clase                              | Ancho de referencia |  Alto | Radio |
| ---------------------------------- | ------------------: | ----: | ----: |
| `.btn-primary` / `.hero-cta-btn`   |              123 px | 40 px |  8 px |
| `.btn-sm` / `.header-register-btn` |              112 px | 40 px |  8 px |

**Estilos definidos:**

* Tipografía Inter, peso 500 y tamaño de 15 px.
* Color primario `#D32F2F`.
* Texto blanco para los botones primarios.
* Hover: `#B71C1C`.
* Active: `#8E0000`.
* Retroalimentación de interacción mediante una reducción de escala de `0.98`, según la implementación registrada.

**Archivo principal:** `assets/css/main.css`.

**Resultado:**

Se consolidaron estilos compartidos para los botones, facilitando su reutilización. Posteriormente se detectó que una regla global de ancho fijo podía afectar a botones de otras pantallas, lo que motivó ajustes de especificidad documentados en la sesión 28.

## Sesión 17 — Implementación de autenticación: inicio de sesión

**Fecha:** 8 de octubre de 2026

**Objetivo:**

Implementar la pantalla de inicio de sesión de acuerdo con el diseño definido en Figma.

**Archivos reportados:**

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

**Consideraciones técnicas registradas:**

* Validación de los datos recibidos.
* Preparación del procesamiento mediante PHP.
* Uso reportado de PDO y consultas preparadas en las operaciones correspondientes.
* Prevención de envíos duplicados durante el procesamiento, según la implementación reportada.
* Retroalimentación accesible mediante atributos ARIA.

**Resultado:**

Se desarrolló la estructura de inicio de sesión y se preparó su procesamiento.

La existencia de la vista y de un archivo de procesamiento no demuestra por sí sola que la autenticación real funcione de extremo a extremo. Es necesario verificar las credenciales, las consultas, las sesiones y los casos de error.

## Sesión 18 — Ajustes visuales de autenticación

**Fecha:** 8 de octubre de 2026

**Objetivo:**

Corregir la alineación de los elementos del encabezado de autenticación con respecto a Figma.

**Actividades realizadas:**

* Revisión de la alineación del título y subtítulo.
* Ajuste del encabezado para mantener la alineación a la izquierda.
* Incorporación de reglas específicas para evitar conflictos con estilos heredados.

**Archivo trabajado:** `assets/css/auth.css`.

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

Se estableció una base de alertas reutilizables para los formularios actuales y futuros.

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

Se preparó una navegación móvil más compacta, conservando el acceso a las opciones principales.

## Sesión 21 — Implementación del registro de usuarios

**Fecha:** 8 de octubre de 2026

**Objetivo:**

Desarrollar la pantalla de registro y preparar el procesamiento de los datos enviados por los usuarios.

**Archivos reportados:**

* `registro.php`.
* `pages/registro.php`.
* `auth/registro-process.php`.
* `assets/css/auth.css`.
* `assets/js/auth.js`.
* `index.php`.

Durante esta etapa también se reportó la eliminación de archivos estáticos redundantes relacionados con versiones anteriores de las pantallas de autenticación.

**Actividades realizadas:**

* Construcción del formulario de registro.
* Organización de los campos de identificación y acceso.
* Incorporación de la selección del tipo de usuario.
* Preparación de las validaciones del formulario.
* Implementación del procesamiento en PHP.
* Preparación de la verificación de correo electrónico duplicado.
* Uso reportado de mecanismos de almacenamiento seguro de contraseñas.

**Consideraciones técnicas registradas:**

* Uso de `password_hash()` con `PASSWORD_DEFAULT`.
* Uso reportado de PDO y consultas preparadas.
* Validación de los datos recibidos.
* Control de correos electrónicos duplicados.
* Uso de transacciones en el procesamiento correspondiente, según el informe recibido.

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

**Funciones auxiliares reportadas:**

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

**Archivos reportados:**

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
* Necesidad de verificar el almacenamiento, la expiración y la validación de los tokens en el backend.

**Respuesta genérica registrada:**

> Si el correo electrónico ingresado coincide con una cuenta activa en la comunidad, recibirás las instrucciones correspondientes.

**Estado del servicio de correo:**

La configuración SMTP permanece pendiente. La generación de un token no equivale al envío efectivo de un correo ni a la finalización integral del proceso de restablecimiento.

**Resultado:**

Se implementó la estructura de recuperación de contraseña y se preparó su procesamiento. Quedó pendiente completar y verificar la integración con el servicio de correo y el flujo de restablecimiento.

## Sesión 24 — Implementación de la pantalla de Proyectos

**Fecha:** 9 de octubre de 2026

**Objetivo:**

Implementar la pantalla de Proyectos de Raíces Alzate, respetando la arquitectura modular y el sistema de diseño institucional.

**Archivos reportados:**

* `proyectos.php`.
* `pages/proyectos.php`.
* `assets/css/portal.css`.
* `assets/img/proyectos-illustration.svg`.
* `assets/icons/logo sin fondo raices alzate.ico`.
* `index.php`.
* `includes/header.php`.
* `components/header.php`.
* `components/footer.php`.
* `assets/css/header.css`.
* `assets/css/footer.css`.
* `assets/css/main.css`.
* `docs/bitacora.md`.
* `docs/changelog.md`.

**Actividades realizadas:**

* Creación del punto de entrada `proyectos.php` en la raíz para integrar encabezado, vista y pie de página.
* Construcción de la vista semántica `pages/proyectos.php`.
* Organización del contenido en dos columnas para escritorio y una columna para móvil.
* Incorporación del título «Proyectos» y el texto de presentación.
* Ajuste de los botones «Iniciar sesión» y «Crear una cuenta».
* Creación de una ilustración SVG específica para la pantalla.
* Reutilización del isotipo oficial del proyecto.
* Incorporación de un indicador visual para la pestaña activa.
* Ajuste del pie de página y sus enlaces de navegación.
* Reordenamiento del contenido en dispositivos móviles.
* Verificación reportada de la adaptación responsive.

**Resultado:**

Se reportó la implementación de la pantalla de Proyectos mediante una vista modular, un punto de entrada y estilos compartidos.

La correspondencia visual definitiva con Figma y el funcionamiento integral de sus enlaces deben validarse durante el control de calidad.

## Sesión 25 — Implementación de la pantalla modular de Egresados

**Fecha:** 9 de octubre de 2026

**Objetivo:**

Implementar la pantalla de Egresados siguiendo la arquitectura modular establecida para el portal público.

**Archivos reportados:**

* `egresados.php`.
* `pages/egresados.php`.
* `assets/css/portal.css`.
* `assets/img/egresados-illustration.svg`.
* `index.php`.
* `docs/bitacora.md`.
* `docs/changelog.md`.

**Actividades realizadas:**

* Creación del punto de entrada `egresados.php`.
* Construcción de la vista semántica `pages/egresados.php`.
* Incorporación del título «Egresados» y el texto de vinculación con la comunidad.
* Integración de los botones de inicio de sesión y registro.
* Creación de una ilustración SVG relacionada con la graduación y la comunidad de egresados.
* Integración de la ruta correspondiente en `index.php`.
* Reutilización de los estilos compartidos de `portal.css`.
* Aplicación de un diseño de dos columnas en escritorio y una distribución vertical en móvil.
* Validación de sintaxis PHP y salida HTML, según el informe recibido.

**Resultado:**

Se reportó la implementación modular de Egresados, reutilizando la estructura y los recursos compartidos del portal público.

## Sesión 26 — Implementación de la pantalla modular de Noticias

**Fecha:** 9 de octubre de 2026

**Objetivo:**

Implementar la pantalla de Noticias, manteniendo la coherencia con las pantallas públicas de Proyectos y Egresados.

**Archivos reportados:**

* `noticias.php`.
* `pages/noticias.php`.
* `assets/css/portal.css`.
* `assets/img/noticias-illustration.svg`.
* `index.php`.
* `docs/bitacora.md`.
* `docs/changelog.md`.

**Actividades realizadas:**

* Creación del punto de entrada `noticias.php`.
* Construcción de la vista semántica `pages/noticias.php`.
* Incorporación del título «Noticias» y un texto introductorio.
* Integración de los botones de inicio de sesión y registro.
* Creación de una ilustración SVG relacionada con noticias y eventos.
* Integración de la ruta de Noticias en `index.php`.
* Aplicación de una estructura de dos columnas para escritorio y una columna para móvil.
* Ajustes de orden visual responsive.
* Validación de sintaxis PHP y salida HTML, según el informe recibido.

**Resultado:**

Se reportó la implementación de Noticias bajo la arquitectura modular del portal público.

Con esta pantalla se documentó la implementación de la tríada pública de Proyectos, Egresados y Noticias. Su validación integral queda sujeta a pruebas de navegación, presentación y correspondencia con Figma.

## Sesión 27 — Implementación inicial del Dashboard Desktop y Mobile

**Fecha:** 10 de octubre de 2026

**Objetivo:**

Desarrollar la interfaz privada del Dashboard para usuarios autenticados, incorporando componentes reutilizables y adaptación responsive.

**Archivos reportados:**

* `dashboard.php`.
* `logout.php`.
* `pages/dashboard.php`.
* `includes/sidebar.php`.
* `includes/topbar.php`.
* `includes/loader.php`.
* `assets/css/dashboard.css`.
* `assets/css/loader.css`.
* `assets/js/loader.js`.
* `index.php`.
* `docs/bitacora.md`.
* `docs/changelog.md`.

**Actividades realizadas:**

* Creación del controlador de entrada `dashboard.php`.
* Incorporación de una comprobación de sesión mediante `$_SESSION['user']`.
* Preparación de la redirección al inicio de sesión cuando no existe una sesión válida.
* Extracción dinámica de datos de usuario, como nombre y rol, a partir de la sesión.
* Construcción de una barra lateral con navegación del área privada.
* Creación de una barra superior con título de vista, avatar y datos del usuario.
* Desarrollo de una vista de Dashboard con tarjetas de resumen.
* Incorporación de bloques de actividad reciente, oportunidades y acciones rápidas.
* Organización de la vista para escritorio y dispositivos móviles.
* Preparación de un menú lateral móvil tipo drawer con telón de fondo.
* Exclusión del pie de página público en la vista privada, de acuerdo con el diseño de Figma.
* Creación de un controlador de cierre de sesión en `logout.php`.

**Contenido de la vista:**

* Bienvenida institucional.
* Tarjetas de Mi perfil, Mis proyectos y Oportunidades.
* Actividad reciente.
* Acciones rápidas.
* Próximas oportunidades con categorías como beca, empleo y capacitación.

**Resultado:**

Se reportó una estructura inicial del Dashboard con controlador, vista, componentes compartidos y estilos responsive.

Los datos de actividad y oportunidades deben tratarse como información de demostración mientras no se verifique su procedencia desde la base de datos.

---

## Sesión 28 — Estandarización del portal y mejoras del Dashboard

**Periodo reportado:** 11 al 13 de octubre de 2026

**Versión de trabajo:** v0.1.1
**Rama reportada:** `dev-emitola`

**Objetivo:**

Refinar la interfaz pública y privada, mejorar el comportamiento responsive, corregir problemas de estilos y navegación, y resolver bloqueos relacionados con el loader y el inicio de sesión PHP.

### 28.1. Estandarización del encabezado público

**Archivos reportados:**

* `includes/header.php`.
* `components/header.php`.
* `assets/css/header.css`.
* `assets/css/portal.css`.

**Actividades realizadas:**

* Normalización de la altura del encabezado a 72 px.
* Ajuste del fondo blanco y el borde inferior.
* Organización del contenido dentro de un contenedor.
* Ajuste del tamaño del logo y de la jerarquía tipográfica de la marca.
* Revisión de las dimensiones del botón de acceso.
* Incorporación del comportamiento sticky para mantener el encabezado visible durante el desplazamiento.
* Aplicación de fondo sólido y sombra sutil.
* Revisión de la navegación y de la presentación del encabezado en diferentes tamaños de pantalla.

**Resultado:**

Se reportó la estandarización del encabezado público y su comportamiento sticky.

**Pendiente de verificación:** los informes recibidos presentan dos medidas distintas para el ancho máximo del contenedor: 1200 px y 1400 px. El valor definitivo deberá confirmarse en el CSS vigente y compararse con Figma antes de fijarlo como especificación oficial.

### 28.2. Corrección de estilos de botones y reglas globales

**Archivos reportados:**

* `assets/css/portal.css`.
* `assets/css/dashboard.css`.

**Problema identificado:**

Una regla global asociada a `.btn-primary` establecía un ancho fijo de 123 px y afectaba el dimensionamiento de botones utilizados en el Dashboard.

**Corrección reportada:**

Se aisló el estilo específico de las acciones del Dashboard mediante selectores más concretos, incluyendo `.dashboard-actions .btn-primary`.

Las dimensiones registradas para el botón «Actualizar mi perfil» fueron:

* Altura: 44 px.
* Ancho mínimo: 180 px.
* Ancho automático.
* Padding horizontal: 24 px.
* Texto sin salto de línea.
* Tipografía Inter, peso 600 y tamaño aproximado de 0.95 rem.
* Fondo primario `#D32F2F`.
* Hover `#B71C1C`.

**Resultado:**

Se reportó una corrección de especificidad para evitar que los estilos generales de los botones alteraran las dimensiones de acciones específicas del Dashboard.

### 28.3. Refinamiento de la barra lateral y navegación privada

**Archivos reportados:**

* `includes/sidebar.php`.
* `pages/dashboard.php`.
* `assets/css/dashboard.css`.

**Actividades realizadas:**

* Retiro del enlace redundante «Inicio» de la barra lateral autenticada.
* Conservación de enlaces para Dashboard, Mi perfil, Proyectos, Oportunidades, Configuración y Cerrar sesión.
* Incorporación del botón de colapso `#sidebarToggleBtn`.
* Implementación de una versión compacta de la barra lateral para escritorio.
* Organización del ancho expandido de 240 px y el ancho contraído de 76 px.
* Ocultamiento de etiquetas de navegación en el estado contraído.
* Centrado de los iconos y ajustes de sus áreas de interacción.
* Incorporación de una transición para el cambio de estado.
* Rotación del indicador visual del botón de colapso.
* Incorporación de atributos de accesibilidad, como `aria-label` y `title` dinámicos.
* Persistencia del estado mediante `localStorage`, utilizando la clave `sidebar_collapsed`.
* Preparación de una clase aplicada antes de la representación de la interfaz para reducir el parpadeo visual al recargar.
* Conservación del comportamiento del menú móvil independiente del colapso de escritorio.

**Resultado:**

Se reportó una barra lateral colapsable para escritorio y un menú lateral móvil tipo drawer.

Su comportamiento final deberá comprobarse con navegación por teclado, diferentes anchos de pantalla y recargas de página.

### 28.4. Refinamiento de la barra superior móvil

**Archivos reportados:**

* `includes/topbar.php`.
* `assets/css/dashboard.css`.

**Actividades realizadas:**

* Reorganización de la información del usuario.
* Ubicación del avatar, nombre y rol en el lado izquierdo.
* Ubicación del botón hamburguesa en el lado derecho.
* Uso del identificador `#dashboardMenuToggle` para el disparador móvil.
* Revisión del comportamiento en pantallas de hasta 768 px.
* Reducción de elementos redundantes para aprovechar el espacio disponible.

**Resultado:**

Se reportó una distribución más compacta de la barra superior en dispositivos móviles.

### 28.5. Bienvenida personalizada del Dashboard

**Archivos reportados:**

* `pages/dashboard.php`.
* `includes/topbar.php`.

**Actividades realizadas:**

* Preparación de un saludo basado en el nombre almacenado en `$_SESSION['user']['nombre']`.
* Extracción del primer nombre para personalizar la bienvenida.
* Incorporación de un texto introductorio para orientar al usuario dentro de la plataforma.
* Preparación de un texto alternativo cuando no se dispone del nombre esperado en la sesión.

**Lema registrado en el informe de desarrollo:**

> Tus raíces siguen creciendo. Conecta con tu comunidad, descubre proyectos y encuentra nuevas oportunidades para construir tu futuro.

**Resultado:**

Se reportó la personalización de la bienvenida a partir de datos de sesión. La presencia de datos simulados en el entorno de desarrollo no debe confundirse con la carga de información de usuarios desde una base de datos.

### 28.6. Rediseño y desbloqueo del Page Loader

**Archivos reportados:**

* `includes/loader.php`.
* `assets/css/loader.css`.
* `assets/js/loader.js`.
* `pages/dashboard.php`.

**Problemas identificados:**

* El loader podía permanecer visible y bloquear la interacción.
* La estructura inicial incluía un skeleton complejo que no resultaba apropiado para todas las pantallas.
* Algunas páginas públicas de autenticación podían quedar cubiertas por la capa de carga.
* Se reportó una etiqueta `</script>` faltante en `pages/dashboard.php`, lo que impedía ejecutar correctamente código posterior.

**Actividades realizadas:**

* Retiro del skeleton complejo y de los bloques grises que simulaban la interfaz.
* Simplificación del loader mediante una capa blanca semitransparente.
* Incorporación de desenfoque de fondo mediante `backdrop-filter`.
* Conservación del spinner institucional con rojo `#D32F2F` y azul `#1565C0`.
* Incorporación del logotipo oficial y el texto «Cargando…».
* Ajustes a las clases utilizadas para ocultar el loader y evitar que intercepte clics.
* Incorporación de mecanismos para ocultarlo al completar la carga o alcanzar el tiempo de seguridad.
* Corrección reportada del cierre de la etiqueta de script en `pages/dashboard.php`.

**Resultado:**

Los informes indican que se corrigieron los bloqueos del loader y se simplificó su presentación.

**Pendiente de verificación:** se reportaron mecanismos de ocultamiento tanto en un script insertado en `includes/loader.php` como en `assets/js/loader.js`. Debe revisarse si ambos mecanismos siguen activos y si pueden generar ejecuciones duplicadas. También debe confirmarse el tiempo de seguridad definitivo, reportado entre 600 y 700 ms.

### 28.7. Corrección del inicio de sesión PHP y de los encabezados HTTP

**Archivos reportados:**

* `login.php`.
* `registro.php`.
* `recuperar-password.php`.
* `recuperar-contrasena.php`.
* `index.php`.
* `pages/login.php`.
* `pages/registro.php`.
* `pages/recuperar-password.php`.

**Problema identificado:**

Se reportaron advertencias relacionadas con `session_start()` ejecutado después de haber enviado contenido HTML, situación que puede impedir el envío correcto de encabezados HTTP y redirecciones.

**Correcciones reportadas:**

* Centralización del inicio de sesión en los puntos de entrada, antes de producir salida HTML.
* Incorporación de comprobaciones del estado de sesión.
* Revisión de las llamadas a `session_start()` dentro de las vistas.
* Corrección de la etiqueta de script faltante que afectaba la ejecución de código posterior.

**Resultado:**

Los informes indican que se corrigieron los problemas de inicialización de sesión y de envío de encabezados.

La condición `!headers_sent()` no sustituye una arquitectura correcta de inicialización: el inicio de sesión debe realizarse antes de generar salida y de forma coherente en los puntos de entrada de la aplicación.

### 28.8. Normalización de la ruta de recuperación de contraseña

**Archivos reportados:**

* `recuperar-contrasena.php`.
* `recuperar-password.php`.
* `index.php`.

**Actividades realizadas:**

* Incorporación de compatibilidad para la ruta `recuperar-contrasena.php`.
* Revisión de las rutas alternativas `recuperar`, `recuperar-password` y `recuperar-contrasena` en el enrutador central.
* Ajustes orientados a evitar errores de navegación entre páginas de autenticación.

**Resultado:**

Se reportó la incorporación de un alias para la recuperación de contraseña.

**Pendiente de verificación:** un informe describe el alias como una redirección 301 y otro como un controlador que renderiza la vista correspondiente. Se debe inspeccionar el archivo actual y comprobar el comportamiento HTTP real antes de documentar una de estas alternativas como definitiva.

### 28.9. Acceso de prueba y navegación durante el desarrollo

**Archivo reportado:**

* `dev-login.php`.

**Actividades realizadas:**

* Incorporación de una utilidad local para crear una sesión simulada durante las pruebas de interfaz.
* Preparación de datos de prueba para facilitar la revisión del Dashboard sin depender temporalmente de una cuenta real.
* Ajustes de rutas y navegación para el entorno local de XAMPP.
* Revisión de rutas de recursos cuando el proyecto se ejecuta desde una subcarpeta.
* Desactivación temporal de determinadas redirecciones de autenticación para facilitar la maquetación, según los informes recibidos.

**Resultado:**

Se reportó un mecanismo de acceso de prueba para trabajar en el Dashboard sin una integración completa con MySQL.

**Advertencia de seguridad:** `dev-login.php` debe permanecer limitado al entorno local de desarrollo, sin acceso público en producción. También deben restaurarse y comprobarse las redirecciones de autenticación antes de considerar terminados los flujos de acceso y registro.

La protección de `dashboard.php` debe conservarse incluso durante las pruebas.

### 28.10. Verificaciones técnicas reportadas

Según los informes de Antigravity y Gemini, se realizaron las siguientes comprobaciones:

* Análisis sintáctico de archivos PHP mediante `php -l`.
* Solicitudes HTTP con `curl` a determinadas páginas de autenticación y a Inicio.
* Comprobación de respuestas HTTP 200 en las rutas indicadas.
* Pruebas de navegación del Dashboard y de su comportamiento visual.
* Revisión de las redirecciones y de la información obtenida desde la sesión.

**Resultado registrado:**

Los informes señalan que no se encontraron errores sintácticos PHP en los archivos comprobados y que las rutas probadas respondieron correctamente.

Estas comprobaciones no sustituyen las pruebas funcionales integrales de autenticación, autorización, persistencia de datos, seguridad ni recuperación de contraseña.

### 28.11. Documentación y control de versiones

**Archivos documentales reportados:**

* `docs/bitacora.md`.
* `docs/changelog.md`.

Se reportó la actualización de la bitácora y del historial de cambios para registrar los avances de la versión v0.1.1.

También se informó de un commit y un `git push` en la rama `dev-emitola`.

**Estado del control de versiones:**

El informe de Gemini proporciona un mensaje de commit propuesto o reportado, pero no incluye un hash ni la salida de la terminal que permitan confirmar independientemente su existencia y contenido.

Por ello, el commit y el push quedan registrados como operaciones reportadas, pendientes de confirmación mediante `git log`, `git status` y la revisión del repositorio remoto.

**Resultado general de la sesión 28:**

Se reportaron mejoras de interfaz en el encabezado público, el Dashboard, la barra lateral, la barra superior móvil, los botones y el loader. También se abordaron problemas de inicialización de sesiones PHP, rutas y navegación local.

El alcance final de cada cambio debe confirmarse en el repositorio actual antes de cerrar su validación técnica.

---

# 📊 Estado general del proyecto

## Fases del proyecto

| Fase   | Descripción                               | Estado                       |
| ------ | ----------------------------------------- | ---------------------------- |
| Fase 1 | Planificación y organización inicial      | Completada según el registro |
| Fase 2 | Diseño del sistema visual y componentes   | Completada según el registro |
| Fase 3 | Diseño UI/UX de las interfaces            | Finalizada                   |
| Fase 4 | Implementación y refinamiento de interfaz | En desarrollo                |

## Estado de los componentes

| Componente                                       | Estado registrado                                                        |
| ------------------------------------------------ | ------------------------------------------------------------------------ |
| Estructura inicial del repositorio               | Consolidada                                                              |
| Documentación base                               | Elaborada; requiere actualización continua                               |
| Design System                                    | Elaborado en Figma                                                       |
| Componentes reutilizables de Figma               | Elaborados                                                               |
| Diagrama de navegación                           | Elaborado                                                                |
| Diseño de Inicio Desktop y Mobile                | Finalizado en Figma                                                      |
| Diseño de las 17 pantallas Desktop               | Finalizado en Figma                                                      |
| Diseño de las 17 pantallas Mobile                | Finalizado en Figma                                                      |
| Estructura frontend de Inicio                    | Implementada según los informes                                          |
| Encabezado público                               | Implementado; ajustes y medidas finales por verificar                    |
| Pie de página público                            | Implementado; validación visual pendiente                                |
| Estilos globales y tipografía                    | Organizados                                                              |
| Botones globales                                 | Implementados; reglas específicas ajustadas según los informes           |
| Sistema de alertas                               | Reportado como implementado inicialmente                                 |
| Inicio de sesión                                 | Vista y procesamiento reportados; autenticación integral por verificar   |
| Registro de usuarios                             | Vista y procesamiento reportados; integración y validación por verificar |
| Recuperación de contraseña                       | Vista y generación de token reportadas; SMTP y flujo completo pendientes |
| Pantalla de Proyectos                            | Implementada según el informe; validación integral pendiente             |
| Pantalla de Egresados                            | Implementada según el informe; validación integral pendiente             |
| Pantalla de Noticias                             | Implementada según el informe; validación integral pendiente             |
| Dashboard Desktop                                | Maquetado e integrado con componentes reportados                         |
| Dashboard Mobile                                 | Maquetado con navegación responsive reportada                            |
| Mini-sidebar de escritorio                       | Implementada según el informe; comportamiento por validar                |
| Menú lateral móvil                               | Implementado según el informe; comportamiento por validar                |
| Loader global                                    | Rediseñado; revisión de mecanismos de ocultamiento pendiente             |
| Controlador de cierre de sesión                  | Reportado como implementado; pruebas de seguridad pendientes             |
| Acceso de prueba `dev-login.php`                 | Reportado como implementado; debe limitarse al entorno local             |
| Esquema definitivo de base de datos              | Pendiente de definición y validación                                     |
| Integración dinámica completa con MySQL          | Pendiente                                                                |
| Datos reales de actividad y oportunidades        | Pendientes de integración                                                |
| Pantallas restantes de la plataforma             | Pendientes de implementación progresiva                                  |
| Pruebas integrales de la plataforma              | Pendientes de completar                                                  |
| Verificación final de Git y del commit reportado | Pendiente de confirmar                                                   |

> **Nota:** Los estados de esta tabla reflejan los avances documentados y reportados. No sustituyen una inspección del código, pruebas funcionales ni la comparación visual de cada pantalla con Figma.

---

# 🔜 Próximas actividades

## 1. Verificación del estado actual del repositorio

* Revisar `git status` para conocer los cambios pendientes.
* Confirmar el historial mediante `git log`.
* Comprobar si el commit reportado se encuentra en `dev-emitola` y si fue enviado al repositorio remoto.
* Revisar las diferencias entre los archivos actuales y los cambios documentados.
* Confirmar los nombres y ubicaciones definitivos de los archivos.
* Resolver las discrepancias documentales antes de actualizar otros documentos.

## 2. Validación visual de las pantallas implementadas

* Comparar Inicio, Proyectos, Egresados, Noticias y Dashboard con sus referencias de Figma.
* Revisar dimensiones, espaciados, tipografía, colores y estados de interacción.
* Comprobar la presentación en escritorio y dispositivos móviles.
* Confirmar el ancho máximo definitivo del contenedor del encabezado.
* Revisar el comportamiento sticky, el menú móvil y la mini-sidebar.
* Corregir diferencias visuales antes de continuar con nuevas pantallas.

## 3. Consolidación de la autenticación

* Verificar el inicio de sesión con credenciales válidas e inválidas.
* Comprobar el registro y la prevención de duplicados.
* Revisar las validaciones del frontend y del backend.
* Confirmar el manejo seguro de sesiones.
* Verificar los permisos de acceso y los roles de usuario.
* Revisar la destrucción de sesión y las cookies durante el cierre de sesión.
* Restaurar y comprobar las redirecciones de autenticación que se hayan desactivado temporalmente.
* Confirmar que el acceso de prueba no pueda utilizarse en producción.
* Completar la recuperación de contraseña, incluida la expiración y validación de tokens y la integración SMTP.
* Probar los casos de error y los mensajes de retroalimentación.

## 4. Diseño e integración de la base de datos

* Definir y documentar el modelo entidad-relación.
* Identificar las entidades, relaciones, claves primarias y claves foráneas.
* Definir las tablas necesarias para usuarios, perfiles, proyectos, noticias y las demás funcionalidades previstas.
* Verificar la correspondencia entre los formularios y las estructuras de almacenamiento.
* Consolidar las operaciones de acceso a datos mediante PDO y consultas preparadas.
* Revisar las restricciones de integridad y las transacciones necesarias.
* Sustituir progresivamente los datos simulados por consultas reales a la base de datos.

## 5. Implementación progresiva de las pantallas restantes

* Continuar con las pantallas restantes siguiendo el orden de navegación establecido.
* Reutilizar los componentes compartidos.
* Mantener la separación entre presentación, procesamiento y acceso a datos.
* Adaptar cada pantalla a los diseños de escritorio y móvil correspondientes.
* Evitar duplicar encabezados, pies de página y estilos cuando ya existan componentes reutilizables.

## 6. Pruebas y control de calidad

* Probar los formularios y los flujos principales.
* Verificar el comportamiento responsive.
* Revisar la accesibilidad y la navegación por teclado.
* Comprobar rutas, enlaces, alertas y estados vacíos.
* Detectar errores de integración.
* Comprobar el funcionamiento del loader en las páginas públicas y privadas.
* Revisar errores PHP y de consola del navegador.
* Confirmar que los cambios no afecten funcionalidades previamente desarrolladas.

## 7. Mantenimiento documental y Git

* Actualizar la bitácora después de cada avance significativo.
* Mantener sincronizados el README, el changelog, el roadmap y los manuales.
* Documentar las decisiones técnicas relevantes.
* Revisar las diferencias antes de realizar commits.
* Utilizar mensajes de commit descriptivos y coherentes con los cambios realizados.
* Registrar las pruebas realizadas y sus resultados.
* Evitar documentar como completadas las actividades que todavía estén pendientes de validación.

---

# 📝 Observaciones generales

1. **Fecha de inicio:** Raíces Alzate se encuentra en desarrollo desde 2025. Las fechas de julio, septiembre y octubre de 2026 corresponden a sesiones y etapas registradas posteriormente.

2. **Diseño e implementación:** La fase de diseño UI/UX está finalizada. Esto no significa que las 34 pantallas estén implementadas en código. La implementación frontend continúa de manera progresiva.

3. **Figma como referencia:** Los diseños aprobados sirven como referencia para dimensiones, distribución, tipografía, colores y estados de los componentes. Las diferencias encontradas durante la programación deben revisarse y corregirse dentro del proceso de implementación y control de calidad.

4. **Base de datos:** Se había probado previamente la conexión entre PHP y MySQL. Esto no significa que el esquema definitivo de la plataforma esté completamente diseñado e integrado.

5. **Autenticación:** La existencia de formularios y scripts de procesamiento no garantiza que los flujos de inicio de sesión, registro y cierre de sesión estén terminados. Deben verificarse las validaciones del backend, las sesiones, los permisos y el tratamiento seguro de los datos.

6. **Recuperación de contraseña:** La generación de tokens está registrada como parte de la implementación. El envío de correos permanece pendiente mientras no se configure y pruebe el servicio SMTP y se verifique el flujo integral de restablecimiento.

7. **Datos simulados:** Los datos utilizados para facilitar la maquetación del Dashboard no deben considerarse información obtenida de la base de datos hasta verificar la integración correspondiente.

8. **Acceso de prueba:** Las herramientas locales de simulación de sesión deben permanecer restringidas al entorno de desarrollo y no deben habilitar accesos de prueba en una instalación pública.

9. **Loader y sesiones PHP:** Las correcciones de bloqueos, inicialización de sesiones y encabezados HTTP están reportadas, pero requieren pruebas funcionales para confirmar que los problemas no reaparezcan en otras rutas.

10. **Control de versiones:** Las operaciones de commit y push mencionadas en informes externos deben confirmarse en Git antes de utilizarlas como evidencia definitiva del estado del repositorio.

11. **Seguimiento del proyecto:** Los avances futuros deben registrarse con fechas, archivos afectados, resultados comprobables, decisiones y tareas pendientes, evitando presentar como completadas las actividades que aún no se hayan verificado.

---

# ✅ Cierre

Esta bitácora consolida el recorrido documentado de Raíces Alzate desde la organización inicial del repositorio y la construcción del sistema de diseño hasta los avances de implementación reportados entre el 8 y el 13 de octubre de 2026.

El proyecto continúa en desarrollo, con la etapa principal de diseño UI/UX finalizada y la implementación técnica en curso.

Los avances reportados comprenden la estructura del portal público, las pantallas de Proyectos, Egresados y Noticias, el Dashboard, sus componentes de navegación y diversas correcciones de interfaz, loader y sesiones PHP.

La prioridad de las siguientes sesiones será verificar los cambios en el repositorio, validar las pantallas implementadas, consolidar los flujos de autenticación, definir e integrar el modelo de datos y avanzar progresivamente en las demás interfaces, manteniendo la coherencia con Figma y la documentación del proyecto.
