# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# Changelog

Todos los cambios importantes realizados en **Raíces Alzate** se registran en este documento.

El proyecto se encuentra en desarrollo desde **2025**. Las fechas registradas corresponden a sesiones y actualizaciones realizadas durante las diferentes etapas del proyecto.

---

# [v0.1.1] - En desarrollo

## 📅 Septiembre de 2026

### 🎨 Desarrollo de interfaces UI/UX

#### Agregado

* Definición de la estructura de navegación de la plataforma.
* Creación de la sección **Estructura de Navegación** en Figma.
* Definición del flujo principal:

  * Inicio.
  * Autenticación.
  * Dashboard.
  * Perfil.
  * Egresados.
  * Proyectos.
  * Noticias.
  * Administración.
* Inicio del diseño de las interfaces principales en Desktop.
* Aplicación del Design System a las interfaces finales.
* Definición de la estructura visual de las pantallas.
* Diseño de la interfaz **Inicio - Desktop**.
* Diseño de la interfaz **Autenticación - Desktop**.
* Diseño del **Dashboard - Desktop**.
* Diseño de la interfaz **Perfil - Desktop**.
* Diseño de la interfaz **Egresados - Desktop**.
* Diseño de la interfaz **Proyectos - Desktop**.
* Diseño de la interfaz **Noticias - Desktop**.
* Diseño de la interfaz **Administración - Desktop**.
* Diseño de **Crear proyecto - Desktop**.
* Diseño de **Detalle de proyecto - Desktop**.
* Diseño de **Editar perfil - Desktop**.
* Diseño de **Recuperar contraseña - Desktop**.
* Diseño de **Registro - Desktop**.
* Diseño de **Editar proyecto - Desktop**.
* Diseño de **Crear noticia - Desktop**.
* Diseño de **Detalle de noticia - Desktop**.
* Diseño de **Editar noticia - Desktop**.

### 📱 Diseño responsive

#### Agregado

* Adaptación de las interfaces al formato Mobile.
* Definición del ancho de referencia Mobile de `390 px`.
* Diseño de **Inicio - Mobile**.
* Diseño de **Autenticación - Mobile**.
* Adaptación de **Dashboard - Mobile**.
* Diseño de las vistas de:

  * Mi perfil.
  * Mis proyectos.
  * Oportunidades.
* Aplicación de los criterios responsive establecidos en **Components & Responsive**.
* Adaptación de jerarquías, espaciados y componentes para pantallas de menor tamaño.

### 🔄 Consolidación del diseño

#### Cambiado

* Se consolidó la estructura visual de las interfaces principales.
* Se aplicaron de manera consistente los colores, tipografía, espaciado y componentes definidos en el Design System.
* Se revisó la consistencia visual entre las diferentes pantallas.
* Se ajustaron dimensiones y distribución de los elementos para mejorar la legibilidad.
* Se consolidó la navegación entre los módulos principales.
* Se estableció Figma como referencia visual para la futura implementación frontend.

---

## 📅 Septiembre de 2026

### ✅ Finalización de la Fase 3 — Diseño UI/UX

#### Agregado

* Revisión general del conjunto de interfaces diseñadas.
* Revisión de la consistencia visual entre las interfaces Desktop y Mobile.
* Revisión de la aplicación del Design System.
* Revisión de los componentes reutilizables.
* Revisión de la estructura de navegación.
* Consolidación de las pantallas finales diseñadas en Figma.
* Preparación de las especificaciones visuales para la etapa de implementación.

#### Cambiado

* Se dio por finalizada la Fase 3 — Diseño UI/UX.
* El diseño dejó de considerarse una etapa en desarrollo y pasó a funcionar como referencia para la implementación.
* Se consolidaron los criterios responsive definidos durante el diseño.
* Se estableció la arquitectura visual necesaria para comenzar el desarrollo frontend.

---

## 📅 08 de octubre de 2026

### 💻 Inicio de la implementación frontend

#### Agregado

* Inicio de la Fase 4 — Implementación.
* Implementación inicial de la pantalla **Inicio** a partir del diseño definido en Figma.
* Creación de componentes PHP reutilizables para Header y Footer.
* Creación de la estructura base de estilos frontend.
* Creación de `assets/css/fonts.css` para centralizar la tipografía Inter.
* Creación de `assets/css/header.css` para los estilos específicos del Header.
* Creación de `assets/css/footer.css` para los estilos específicos del Footer.
* Implementación de la navegación responsive mediante CSS y JavaScript vanilla.
* Implementación del Header responsive para Desktop y Mobile.
* Implementación del Footer institucional.
* Incorporación del isotipo de Raíces Alzate en Header y Footer.
* Incorporación del favicon institucional.
* Implementación de la estructura de navegación principal.
* Implementación de la referencia Desktop de `1440 px`.
* Inicio de la adaptación de la pantalla Inicio para Mobile con referencia de `390 px`.
* Creación de la pantalla de **Autenticación (Login)** (`pages/login.php`) siguiendo la especificación de Figma Desktop / Mobile.
* Creación de `assets/css/auth.css` para el aislamiento modular de estilos de la tarjeta de autenticación.
* Creación de `assets/js/auth.js` con validación de formularios accesible y control de doble submit.
* Creación del controlador de acceso directo `login.php` en la raíz.
* Soporte de enrutamiento por parámetro `?page=login` en `index.php`.
* Carga condicional y aislada de estilos y scripts en `includes/header.php` e `includes/footer.php`.
* Creación del endpoint preliminar `auth/login-process.php`.
* Creación del componente global reutilizable de alertas (`.alert`) en `assets/css/main.css` con cuatro variantes semánticas (`success`, `warning`, `error`, `info`), accesibilidad ARIA e iconografía SVG vectorial inline.
* Traslado de las acciones de autenticación ("Iniciar sesión" y "Registrarse") al bloque `.mobile-drawer-actions` dentro del drawer de navegación para dispositivos móviles.

#### Cambiado

* Se reorganizaron los estilos frontend para separar los estilos generales de los estilos específicos de Header y Footer.
* Se mantuvieron en `main.css` los tokens visuales definidos durante el Design System.
* Se mantuvo la paleta institucional:

  * Primary: `#D32F2F`
  * Secondary: `#1565C0`
  * Texto principal: `#212121`
  * Texto secundario: `#757575`
  * Fondo claro: `#F5F5F5`
  * Blanco: `#FFFFFF`
  * Borde: `#E0E0E0`
* **Ocultamiento de acciones en cabecera responsive móvil:** Se ocultaron por completo los botones directos de autenticación de la barra superior en pantallas móviles (`<= 768px`) en `components/header.php` y `assets/css/header.css`, logrando una barra superior limpia con únicamente el logotipo a la izquierda y el botón hamburguesa (`.menu-toggle`) al extremo derecho, tal como en Figma Mobile.
* Se trasladaron tanto **Iniciar sesión** (`.btn-login-drawer`) como **Registrarse** (`.btn.btn-primary.btn-block`) a ancho completo (100%) al interior del menú lateral desplegable (`.main-nav`).
* Se corrigió la estructura HTML del menú de navegación y del agrupador de acciones de escritorio (`.desktop-actions`).
* Se eliminaron estilos de enlaces que no corresponden al diseño definido.
* Se mantuvo la implementación sin frameworks frontend, utilizando PHP, HTML, CSS y JavaScript vanilla.
* **Calibración visual en Autenticación:** Se forzó la alineación izquierda (`text-align: left !important`) en el encabezado institucional (`.auth-header`, `.auth-brand-title`, `.auth-brand-subtitle`) en `assets/css/auth.css` para coincidir 1:1 con la referencia visual de Figma.
* **Integración de alertas en Login:** Se sustituyó el contenedor plano de feedback por el componente visual `.alert.alert-error` en `pages/login.php` y `assets/js/auth.js`, desplegando advertencias con iconografía vectorial SVG sin alterar la retícula.

#### Estado de implementación

Las interfaces del proyecto continúan su transición progresiva de Figma a código.

Actualmente se encuentran implementados:

* Estructura base de Inicio.
* Pantalla de Autenticación / Inicio de Sesión (`pages/login.php`).
* Header reutilizable.
* Footer reutilizable.
* Navegación responsive.
* Tipografía Inter.
* Sistema base de estilos (`main.css`) y módulos específicos (`header.css`, `footer.css`, `auth.css`).
* Favicon institucional.
* Referencia Desktop de `1440 px` y adaptaciones Mobile.

Las demás interfaces diseñadas en Figma permanecen en desarrollo.

---

# [v0.1.0] - Documentación y fundamentos

## 📅 09 de julio de 2026

### 🎉 Consolidación de la documentación base

#### Agregado

* Creación del repositorio en GitHub.
* Configuración inicial del proyecto.
* Organización de la estructura de carpetas.
* Configuración de Git y GitHub.
* Creación de la documentación base.
* Bitácora del proyecto.
* Planificación del proyecto.
* Requisitos funcionales y no funcionales.
* Arquitectura del sistema.
* Hoja de ruta (Roadmap).
* Registro de decisiones.
* Glosario de términos.
* Índice de documentación (`docs/README.md`).
* Estructura inicial del manual técnico.
* Estructura inicial del manual de usuario.
* Elaboración del `README.md` principal del repositorio.
* Documentación de la descripción general del proyecto.
* Documentación de las tecnologías utilizadas.
* Documentación de la estructura del proyecto.
* Documentación del estado y roadmap del desarrollo.
* Inclusión de las instrucciones de instalación y uso.
* Documentación del equipo de desarrollo, contribuciones, licencia y agradecimientos.
* Finalización de la documentación base del proyecto.

---

## 📅 10 de julio de 2026

### 🎨 Planificación de la Fase 3 — Diseño UI/UX

#### Agregado

* Inicio de la planificación de la Fase 3 — Diseño UI/UX.
* Actualización de la planificación general del proyecto.
* Definición de los objetivos de la fase de diseño.
* Definición de los entregables del diseño UI/UX.
* Establecimiento de los criterios de finalización de la fase.
* Organización del plan de trabajo para el diseño de la interfaz en Figma.

---

## 📅 16 de julio de 2026

### 🎨 Construcción del Design System

#### Agregado

* Inicio del desarrollo del Design System en Figma.
* Creación y organización de la sección **Colors**.
* Definición de las categorías **Primary**, **Secondary**, **Neutral**, **Semantic** y **Preview**.
* Definición de la identidad visual institucional.
* Establecimiento del rojo institucional como color principal.
* Establecimiento del azul institucional como color secundario.
* Organización inicial de la jerarquía tipográfica.
* Preparación de la estructura visual para continuar el desarrollo del Design System.

#### Cambiado

* Se reorganizó la distribución de los elementos del Design System.
* Se evaluó el uso de Color Styles y Variables de Figma para una etapa posterior.

---

## 📅 20 de julio de 2026

### 🎨 Consolidación del Design System

#### Agregado

* Finalización de la paleta de colores institucional.
* Finalización de las categorías **Primary**, **Secondary**, **Neutral** y **Semantic**.
* Definición inicial de **Poppins** como familia tipográfica del sistema de diseño.
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
* Creación del sistema de botones reutilizables.
* Creación de los botones **Primary**, **Secondary**, **Outline**, **Danger** y **Disabled**.
* Definición de dimensiones, colores, radios y estilos tipográficos para los botones.
* Preparación de la segunda página del archivo de Figma destinada a componentes reutilizables.

#### Cambiado

* Se reorganizó manualmente la distribución del Design System.
* Se decidió separar los componentes reutilizables del contenido base mediante una segunda página.
* Se estableció que el diseño de esta etapa se desarrollaría sin utilizar Auto Layout.

---

## 📅 05 de septiembre de 2026

### 🧩 Finalización de Components & Responsive

#### Agregado

* Finalización de la página **Components & Responsive** en Figma.
* Organización de los componentes reutilizables.
* Definición de estados para los componentes de interacción.
* Finalización del componente **Radio Button**.
* Implementación de los estados:

  * Normal.
  * Selected.
  * Focus.
  * Disabled.
  * Error.
* Finalización del componente **Switch**.
* Implementación de los estados:

  * Off.
  * On.
  * Focus.
  * Disabled.
  * Error.
* Aplicación de los colores institucionales.
* Revisión de proporciones, tamaños, espaciado y distribución.
* Definición de criterios de diseño responsive.
* Organización final de **Components & Responsive**.

#### Cambiado

* Se consolidó la relación entre los estilos del Design System y los componentes reutilizables.
* Se mantuvo el desarrollo manual sin utilizar Auto Layout.
* Se reorganizó la documentación visual para facilitar su posterior implementación.
* Se estableció **Components & Responsive** como sección terminada.

---

# Estado actual

🟡 **Proyecto en desarrollo — Fase 4: Implementación.**

## Fases completadas

### Fase 1 — Planificación

* Planificación general del proyecto.
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
* Estructura inicial de manuales.

### Fase 3 — Diseño UI/UX

* Design System.
* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Sistema de botones.
* Componentes reutilizables.
* Estados de componentes.
* Components & Responsive.
* Criterios de diseño responsive.
* Estructura de navegación.
* Interfaces principales Desktop.
* Adaptaciones Mobile.
* Revisión y consolidación final del diseño.

### Fase 4 — Implementación

En desarrollo.

Hasta el momento se ha iniciado la implementación frontend con:

* Estructura base de Inicio.
* Componentes reutilizables de Header y Footer.
* Sistema de estilos modular.
* Tipografía centralizada.
* Navegación responsive.
* Favicon institucional.
* Implementación inicial de Inicio Desktop.
* Adaptación inicial de Inicio Mobile.

---

# Próxima etapa

## 🚀 Continuación de la Fase 4 — Implementación

Las siguientes actividades estarán orientadas a continuar transformando los diseños y especificaciones definidos en Figma en una plataforma funcional.

### Próximas actividades

* Revisar visualmente **Inicio - Desktop** frente a Figma.
* Revisar visualmente **Inicio - Mobile** frente a Figma.
* Corregir diferencias visuales encontradas.
* Continuar con la implementación de las demás interfaces.
* Construir y reutilizar los componentes definidos en el Design System.
* Integrar progresivamente la lógica PHP.
* Definir la estructura definitiva de la base de datos según las necesidades reales de las interfaces.
* Implementar el backend.
* Implementar autenticación y roles.
* Integrar frontend, backend y base de datos.
* Realizar pruebas funcionales.
* Corregir errores y realizar ajustes.
* Mantener actualizada la documentación durante el desarrollo.
