# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# Changelog

Todos los cambios importantes realizados en **Raíces Alzate** se registran en este documento.

El proyecto se encuentra en desarrollo desde **2025**. Las fechas registradas corresponden a sesiones de trabajo, avances y actualizaciones realizadas durante las diferentes etapas del proyecto.

El formato de este documento está inspirado en las convenciones de **Keep a Changelog**, con el propósito de mantener un historial claro, organizado y comprensible.

---

# [v0.2.1] — 17 de octubre de 2026

### 🔐 Flujo de Recuperación y Restablecimiento Seguro de Contraseñas

#### Implementado y Añadido

* **Generación de Tokens Criptográficos y Validación Temporal (`auth/solicitar_reset.php` / `auth/recuperar-process.php`):**
  - Generación de tokens criptográficamente seguros mediante `bin2hex(random_bytes(32))` (cadena pseudoaleatoria de 64 caracteres).
  - Almacenamiento seguro del hash `hash('sha256', $token)` en las columnas `reset_token` y `reset_expira` de la tabla `usuarios`.
  - Expiración estricta establecida a 1 hora (`DATE_ADD(NOW(), INTERVAL 1 HOUR)`).
* **Integración Transaccional de PHPMailer y SMTP Institucional (`includes/PHPMailer/` / `config/mail.php` / `includes/mailer.php`):**
  - Inclusión modular de las clases de PHPMailer (`PHPMailer.php`, `SMTP.php`, `Exception.php`) en `includes/PHPMailer/` sin requerir Composer.
  - Centralización de credenciales y configuración SMTP en `config/mail.php` con soporte para encriptación TLS/SSL y depuración configurable.
  - Plantilla HTML responsiva para correos con cabecera roja institucional (`#D32F2F`), tarjeta blanca flotante, logotipo embebido mediante CID Inline (`logo-raices-alzate.png`), botón de llamada a la acción y advertencias de seguridad.
* **Interfaz de Restablecimiento de Contraseña (`pages/reset-password.php` / `assets/css/auth.css` / `assets/js/auth.js`):**
  - Pantalla adaptada al diseño de Figma con tarjeta institucional centrada.
  - Alternador interactivo para visualizar u ocultar la contraseña (ícono de ojo).
  - Validación dinámica en el frontend: longitud mínima de 8 caracteres y confirmación de coincidencia de claves.
* **Procesamiento de Cambio Seguro de Contraseña (`auth/cambiar_password.php`):**
  - Validación de vigencia del token contra `reset_expira > NOW()`.
  - Hashing seguro de la nueva clave mediante `password_hash($nueva_password, PASSWORD_DEFAULT)`.
  - Inmediata invalidación del token (`reset_token = NULL`, `reset_expira = NULL`) para prevenir ataques de repetición.

### 🗄️ Esquema Relacional de Base de Datos y Aprovisionamiento Idempotente

#### Implementado y Añadido

* **Esquema Relacional Integral (`database/schema.sql`):**
  - Definición DDL de 11 tablas del sistema: `roles`, `usuarios`, `perfiles`, `educacion`, `experiencia`, `habilidades`, `perfil_habilidades`, `proyectos`, `noticias`, `conexiones`, `mensajes`.
  - Declaración de claves foráneas con reglas de integridad referencial (`ON DELETE CASCADE` y `ON DELETE RESTRICT`).
  - Creación de índices optimizados para búsquedas por correo, documento, autor y destinatario.
  - Inserción de semillas predeterminadas para los roles institucionales (`administrador`, `egresado`, `estudiante`) y catálogo de habilidades.
* **Instalador y Verificador Automatizado (`database/setup.php`):**
  - Script autoejecutable compatible con CLI (`php database/setup.php`) y entorno web (`http://localhost/Raices-Alzate/database/setup.php`).
  - Creación condicional de la base de datos `raices_alzate` si no existe con codificación `utf8mb4_unicode_ci`.
  - Ejecución por bloques y verificación de existencia de tablas con reporte detallado.
* **Scripts de Migración de Conexiones y Mensajería (`database/create_conexiones_table.php` / `database/create_mensajes_table.php`):**
  - Generación de tablas dedicadas para la red social interna y chat entre egresados.

### 💬 Endpoints Asíncronos de Mensajería y Red de Egresados (`api/`)

#### Implementado y Añadido

* **Arquitectura de APIs JSON para interacción dinámica:**
  - `api/conectar-egresado.php`: Envío de solicitudes de conexión institucional entre perfiles.
  - `api/responder-solicitud.php`: Aceptación o rechazo de solicitudes de vinculación.
  - `api/enviar-mensaje.php`: Registro y despacho asíncrono de mensajes en conversaciones privadas.
  - `api/obtener-mensajes.php`: Consulta y sincronización del historial de chat entre usuarios conectados.
  - Blindaje con control de sesión obligatoria y validación estricta de parámetros de entrada.

### 📂 Centralización y Reorganización Estructural de Vistas

#### Modificado y Optimizado

* **Estandarización de Vistas en `pages/`:**
  - Reubicación y centralización de vistas bajo el directorio `pages/` (`pages/reset-password.php`, `pages/administracion.php`, `pages/proyectos.php`, etc.).
  - Limpieza de scripts de prueba obsoletos en la raíz del proyecto.
  - Sincronización de rutas y actualización del número de versión a **v0.2.1**.

### 📖 Documentación Oficial Completa: Manual Técnico y Manual de Usuario

#### Implementado y Añadido

* **Manual Técnico para Desarrolladores y Administradores (`docs/manual-tecnico.md`):**
  - Guía técnica integral que detalla los requisitos de hardware y software, extensiones PHP indispensables y arquitectura en capas con Front Controller.
  - Documentación exhaustiva del modelo de datos de 11 tablas con DDL, claves foráneas, índices y script de aprovisionamiento automatizado `setup.php`.
  - Configuración y operación del servicio SMTP transaccional con PHPMailer y plantillas de correo responsivas con logotipo embebido (CID).
  - Especificación formal de protocolos de seguridad (BCRYPT, tokens SHA-256 temporales de un solo uso, CSRF y Prepared Statements con PDO) y contratos de la API REST (`api/`).
  - Guía de mantenimiento, diagnóstico y resolución de problemas técnicos frecuentes.
* **Manual de Usuario Oficial para la Comunidad (`docs/manual-usuario.md`):**
  - Manual de uso paso a paso para egresados, docentes, directivos y administradores.
  - Instrucciones detalladas para navegación en portal público, registro, inicio de sesión seguro y flujo de recuperación de contraseña por correo electrónico institucional.
  - Guías ilustradas para gestión de perfil, directorio de egresados, red de contactos, chat privado, proyectos comunitarios, noticias y radicación de PQRS con zona interactiva (*Dropzone*).
  - Guía de operación del módulo de administración institucional y sección de preguntas frecuentes (FAQ).

---

# [v0.1.1] — 15 de octubre de 2026

## 📅 15 de octubre de 2026

### 🛡️ Módulo de Administración y Moderación (`administracion.php` / `assets/css/administracion.css` / `includes/sidebar.php` / `admin.php`)

#### Implementado y Añadido

* **Pantalla de Administración Oficial (Figma: "08 - Administración - Desktop & Mobile"):**
  - **Arquitectura Frontend Pura:** Implementación con datos 100% estáticos en PHP y manipulación del DOM en tiempo real con Vanilla JavaScript, respetando la regla estricta de CERO base de datos y CERO APIs/AJAX externos.
  - **Barra Lateral y Navegación Privada (`includes/sidebar.php`):** Integración directa del enlace a `administracion.php` con el ícono SVG de cuadrícula administrativa de 4 bloques (`<rect x="3" y="3"...>`), soporte de estado activo y compatibilidad responsive.
  - **Redirección de compatibilidad (`admin.php`):** Archivo puente con redirección 302 automática hacia `administracion.php`.
  - **Panel de Métricas (KPI Cards):** Tarjetas interactivas con actualización dinámica para *Registros Pendientes*, *Contenidos en Revisión*, *PQRS por Resolver* y *Egresados Verificados*.
  - **Navegación por Pestañas (Tabs) y Filtros en Tiempo Real:** 
    - Pestaña 1: *Aprobación de Registros* (verificación de nuevos egresados).
    - Pestaña 2: *Moderación de Contenidos* (noticias, oportunidades y proyectos).
    - Pestaña 3: *Resolución de PQRS* (atención a solicitudes y reportes de error).
    - Búsqueda en vivo por texto (nombre, documento, radicado, asunto o correo) y filtro por estado (*Pendientes*, *Aprobados/Publicados*, *Resueltos*, *Rechazados*).
  - **Modales Interactivos Accesibles:**
    - `modalRegistro`: Ficha detallada con datos académicos, documento, observaciones y botones para aprobación inmediata o rechazo de egresados.
    - `modalContenido`: Inspección del texto completo, categoría y autor con acciones de publicación o desestimación.
    - `modalPQRS`: Detalle de radicado, remitente, descripción de la incidencia y formulario de respuesta institucional con cierre de caso.
  - **Notificación Toast Flotante (`#adminToast`):** Alerta en la esquina inferior derecha con retroalimentación inmediata al aprobar, rechazar, publicar o responder, con auto-ocultamiento a los 3.8 segundos.
  - **Estilos Institucionales (`assets/css/administracion.css`):** Diseño tipográfico con Inter, paleta oficial (#D32F2F, #1565C0, #212121, #757575, #E0E0E0, #F5F5F5), botones estándar de 44px de alto con radio de 8px y adaptaciones fluidas para tablet y móvil.

### 📬 Módulo de PQRS y Reporte de Errores (Organización de Menú y Formulario Limpio)

#### Corregido e Implementado

* **Corrección de visibilidad del ícono de PQRS en el Sidebar (`includes/sidebar.php` / `assets/css/dashboard.css` / `assets/css/pqr.css`):**
  - **Limpieza de clases incompatibles en el marcado (`includes/sidebar.php`):** Se eliminó la clase `.nav-link` del elemento `<a>` y la clase `.nav-item` del contenedor `<li>` de PQRS. La presencia de `.nav-link` activaba la regla `.nav-link.active { color: #D32F2F !important; }` proveniente de `header.css`, lo que teñía el trazo del SVG en rojo sobre el fondo rojo institucional (`#D32F2F`) haciéndolo completamente invisible.
  - **Blindaje del trazo e ícono en estado activo (`assets/css/dashboard.css` / `assets/css/pqr.css`):** Se declararon reglas explícitas con `color: #FFFFFF !important; stroke: #FFFFFF !important; fill: none !important;` tanto para `.sidebar-nav-link.active svg` como para `.dashboard-sidebar.is-collapsed`, garantizando que el ícono institucional de ayuda y soporte (círculo con signo de interrogación) permanezca perfectamente nítido y visible en blanco puro en modo expandido y colapsado.
* **Corrección y organización del menú lateral y barra superior (`pqr.php` / `assets/css/pqr.css`):**
  - **Inclusión de estilos del panel (`assets/css/dashboard.css`):** Se resolvió el problema donde el menú lateral (`sidebar`) y la barra superior (`topbar`) se renderizaban apilados sin estilos visuales debido a la falta de vinculación de `dashboard.css`.
  - **Importación directa y clases estructurales:** Se añadió `@import url('dashboard.css');` en [`assets/css/pqr.css`](file:///d:/SSE/BSD/htdocs/Raices-Alzate/assets/css/pqr.css) y se aseguraron las clases `.layout-wrapper.dashboard-layout`, `.main-wrapper.dashboard-main-area` y el telón móvil `#sidebarBackdrop`.
  - **Script de interacción (`assets/js/dashboard.js`):** Vinculación para habilitar el colapso del menú lateral y el menú responsive hamburguesa en móviles.

### 🌐 Flujo, Estados de Conexión y Notificación Toast (`egresados.php` / `includes/egresados-privado.php` / `detalle-egresado.php` / `assets/css/egresados.css` / `assets/css/perfil-detalle.css` / `assets/js/egresados.js` / `assets/js/dashboard.js`)

#### Añadido y Corregido

* **Actualización del estado de conexión institucional en `$egresados`:**
  - Configuración estática oficial en `egresados.php`, `includes/egresados-privado.php` y catálogo ampliado en `detalle-egresado.php`:
    - Valentina Ríos Gómez (ID 1): `conectado => true` (Botón "Enviar mensaje" activo hacia `mensajes.php`).
    - Carlos Andrés Morales (ID 2): `conectado => false` (Botón interactivo "Conectar").
    - Camila Zuluaga Henao (ID 3): `conectado => true` (Botón "Enviar mensaje" activo hacia `mensajes.php`).
    - Mateo Henao Castro (ID 4): `conectado => true` (Botón "Enviar mensaje" activo hacia `mensajes.php`).
    - Laura Restrepo Silva (ID 5): `conectado => false` (Botón interactivo "Conectar").
    - Alejandro Quintero (ID 6): `conectado => true` (Botón "Enviar mensaje" activo hacia `mensajes.php`).
    - Daniela Cardona Pineda (ID 7): `conectado => false` (Botón interactivo "Conectar").
* **Sincronización del badge de conectados:**
  - Ajuste inicial del contador en la pestaña rápida de navegación a `4` egresados conectados.
  - Sincronización dinámica automática en `assets/js/egresados.js` al interactuar con el botón o cargar la vista.
* **Componente y estilos del Toast de Notificación Flotante:**
  - Integración del marcado accesible `.egresado-toast` en `detalle-egresado.php` y vinculación con la función `window.conectarConEgresado(btn)` en `assets/js/dashboard.js`.
  - Notificación contextual dinámica: `¡Solicitud enviada a [Nombre]! Ya están en contacto.` con desaparición automática a los 3.5 segundos.
  - Reglas de estilo completas en `assets/css/perfil-detalle.css` y `assets/css/egresados.css` respetando la paleta institucional (#212121 fondo, #FFFFFF tipografía, #4CAF50 ícono de verificación, sombra flotante y animación `transform/opacity`).
  - Corrección de la variable `destacado` en el filtrado rápido de pestañas en `assets/js/egresados.js`.

### 🎴 Botones Condicionales en Tarjetas del Directorio (`includes/egresados-privado.php` / `assets/css/egresados.css` / `assets/js/egresados.js`)

#### Mejorado

* **Integración del contenedor de acciones `.egresado-card-actions`:**
  - **Botón izquierdo ("Ver perfil"):** Implementación de la clase `.btn-card-ver` con fondo rojo oficial `#D32F2F`, texto blanco y hover `#B71C1C`.
  - **Botón derecho condicional según `$egresado['conectado']`:**
    - **Si ya está conectado (`conectado === true`):** Enlace hacia `mensajes.php` con clase `.btn-card-action.btn-card-message`, ícono de mensaje SVG y texto "Enviar mensaje", con fondo azul tenue `#EBF3FA` y hover en azul institucional `#1565C0`.
    - **Si no está conectado (`conectado === false`):** Botón `.btn-card-action.btn-card-connect` con `onclick="conectarEgresado(this)"`, ícono SVG lineal de User Plus y texto "Conectar", reaccionando con microinteracción de conexión activa (`.connected`) a verde tenue `#E8F5E9` y `#2E7D32`.
  - **Soporte de filtrado dinámico:** Reconocimiento de `.btn-card-message` dentro de la pestaña de egresados conectados.

### 🔲 Optimización de Espaciado y Holgura en Botones de Cabecera (`detalle-egresado.php` / `assets/css/perfil-detalle.css`)

#### Mejorado

* **Amplitud, padding y separación visual en la botonera de perfil:**
  - **Espaciado del contenedor (`.profile-header-actions`):** Incremento de la separación entre botones a `16px` con alineación limpia a la derecha (`margin-left: auto;`).
  - **Botón "Enviar mensaje" / "Conectar":** Ampliación a `height: 44px`, padding cómodo `0 24px`, separación interna entre icono y texto de `10px`, tipografía `Inter 14.5px 600` y `min-width: max-content` para evitar cualquier compresión o salto de línea.
  - **Botón secundario "Compartir":** Altura de `44px`, padding equilibrado `0 20px`, tipografía `Inter 14.5px 600` e íconos y textos perfectamente alineados mediante `.btn-text`.

### 👤 Refinamiento de Ícono y Manejo Interactivo del Botón "Conectar" (`detalle-egresado.php` / `assets/css/perfil-detalle.css` / `assets/js/dashboard.js`)

#### Mejorado

* **Ícono institucional y función interactiva de conexión:**
  - **SVG lineal estándar:** Integración del ícono institucional User Plus en `16x16` con trazo `2.2px`, esquinas y terminaciones redondeadas (`stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"`).
  - **Separación de contenido semántico:** Implementación de `<span class="btn-text">Conectar</span>` para mantener alineación y permitir transiciones de texto sin distorsionar el elemento gráfico.
  - **Función interactiva `conectarConEgresado(btn)`:** Deshabilita el botón con clase `.solicitud-enviada`, actualiza el texto a "Solicitud enviada" y reemplaza dinámicamente el SVG por el icono de verificación (checkmark) estilizado.
  - **Reglas CSS dedicadas:** Dimensiones fijas de `16x16`, `flex-shrink: 0`, alineación vertical media y tipografía coordinada.

### 🔘 Botonera de Cabecera en Detalle Egresado (`detalle-egresado.php` / `assets/css/perfil-detalle.css`)

#### Mejorado

* **Calibración de la botonera institucional (`.profile-header-actions`):**
  - **Botón Principal ("Conectar" / "Enviar mensaje"):** Fondo rojo institucional sólido `#D32F2F`, texto blanco `#FFFFFF`, trazo SVG en `#FFFFFF`, borde de 1.5px `#D32F2F`, altura de 42px, esquinas de 8px, tipografía Inter 14px SemiBold y microinteracción `:hover` en `#B71C1C`.
  - **Botón Secundario ("Compartir"):** Outline azul institucional con fondo transparente, borde de 1.5px sólido `#1565C0`, texto `#1565C0` y trazo SVG en `#1565C0`. En `:hover` reacciona con fondo azul tenue translúcido `rgba(21, 101, 192, 0.08)`, borde y texto `#0D47A1`, e icono SVG en `#0D47A1`. En `:active` aplica tinte `rgba(21, 101, 192, 0.15)`.
  - **Estado deshabilitado / solicitud enviada:** Fondo `#F5F5F5`, borde `#E0E0E0`, texto y trazo SVG en `#757575` con cursor por defecto.
  - **Adaptación móvil fluida (<= 768px):** Apilamiento vertical con ancho al 100%, separación de 10px y altura de 44px.

### 🎴 Estilos Institucionales en Botones de Tarjetas del Directorio (`assets/css/egresados.css` / `includes/egresados-privado.php`)

#### Mejorado

* **Calibración de botones de acción en tarjetas de egresados:**
  - **Botón "Ver perfil" (Rojo institucional fijo):** Fondo `#D32F2F`, texto blanco `#FFFFFF`, borde de 1.5px sólido `#D32F2F`, altura de 40px, esquinas redondeadas de 8px, tipografía Inter 14px SemiBold y microinteracción `:hover` en rojo intenso `#B71C1C`.
  - **Botón "Conectar" (Outline azul institucional #1565C0):** Fondo transparente, borde de 1.5px sólido `#1565C0`, texto `#1565C0` e icono SVG en `#1565C0`. En estado `:hover` reacciona con un tinte azul suave `rgba(21, 101, 192, 0.08)`, borde y texto `#0D47A1`, e icono SVG en `#0D47A1`. En estado activo/clic adopta `rgba(21, 101, 192, 0.15)`.
  - **Encapsulación y selectores universales:** Cobertura para `.btn-ver-perfil`, `.btn-card-profile`, `.btn-conectar`, `.btn-card-connect`, `.btn-conectar-egresado`, `.btn-outline-connect` y `.btn-outline-secondary` preservando el estado de conexión activa.

### 🔙 Botón de Retorno Limpio con Hover Dinámico (`detalle-egresado.php` / `assets/css/perfil-detalle.css`)

#### Corregido

* **Comportamiento interactivo calibrado del botón de retorno:**
  - **Estado en reposo transparente:** Cero color de fondo (`background: transparent`), dejando visibles únicamente el texto y la flecha en azul `#1565C0` sobre el fondo de la página.
  - **Estado `:hover` dinámico:** Al pasar el cursor, aparece suavemente la pastilla con fondo azul claro tenue (`#EBF3FA`), esquinas de 8px, texto azul `#0D47A1` y desplazamiento de -2px en el icono SVG, sin saltos de maquetación ni desfases verticales.
  - **Contenedor con margen inferior:** Separación de 20px antes del banner de la tarjeta de perfil para una transición limpia y despejada.

### 🎨 Calibración del Banner Superior y Textura Institucional (`assets/css/perfil-detalle.css`)

#### Corregido

* **Homologación exacta del banner institucional con `perfil.php`:**
  - **Textura de micropuntos translúcidos:** Implementación de capa compuesta con `radial-gradient(rgba(255, 255, 255, 0.18) 1.2px, transparent 1.2px)` sobre `linear-gradient(90deg, #D32F2F 0%, #8E0000 35%, #1565C0 100%)` a 140px de altura (`background-size: 14px 14px, 100% 100%`).
  - **Eliminación del espaciado/borde desalineado:** Asignación de `padding: 0 !important;` y `overflow: hidden !important;` en `.profile-hero-card` y `.profile-card.profile-hero-card`, garantizando un encaje perfecto de esquina a esquina contra la tarjeta blanca.
  - **Posición del avatar:** Ajuste de `.profile-avatar-wrapper` con `margin-top: -60px;` para sobresalir simétricamente a la mitad sobre el corte inferior del banner.
  - **Insignia institucional:** Mantenimiento de `.banner-institution-badge` en la esquina superior derecha (`top: 16px; right: 20px;`).

### 🖥️ Corrección de Distribución Horizontal en Desktop de la Cabecera de Egresado (`detalle-egresado.php` / `assets/css/perfil-detalle.css`)

#### Corregido

* **Distribución horizontal idéntica a `perfil.php` en versión Desktop (> 768px):**
  - **Estructura semántica agrupada (`.profile-hero-body`):** Incorporación del contenedor `.profile-identity-info` para agrupar el nombre de usuario (`.profile-user-name`), subtítulo institucional (`.profile-user-subtitle`) y cápsulas de metadatos (`.profile-meta-pills`).
  - **Alineación horizontal en 3 bloques:**
    1. **Izquierda:** Avatar circular de 108px (`.profile-avatar-wrapper` con margen superior `-54px`) montado sobre el banner.
    2. **Centro-Izquierda:** Información de identidad alineada a la izquierda (`.profile-identity-info` con `flex: 1 1 auto; text-align: left; align-items: flex-start;`).
    3. **Derecha:** Botonera de acciones alineada a la derecha (`.profile-header-actions` con `margin-left: auto; justify-content: flex-end; flex-wrap: nowrap;`).
  - **Consistencia responsive:** Se mantiene el apilamiento 100% centrado y fluido para dispositivos móviles (`@media (max-width: 768px)`).

### 👤 Homologación Estética Integral de Detalle de Egresado (`detalle-egresado.php` / `assets/css/perfil-detalle.css`)

#### Mejorado

* **Calibración estructural y visual idéntica a `perfil.php`:**
  - **Envoltorio y Layout del Dashboard:** Adopción de `.layout-wrapper.dashboard-layout`, `.main-wrapper.dashboard-main-area` y `.content-area.dashboard-content.profile-content-wrapper` reutilizando los componentes `includes/header.php`, `includes/sidebar.php`, `includes/topbar.php` e `includes/footer.php`.
  - **Breadcrumb de navegación:** Inclusión de `.profile-breadcrumb` con enlace `.breadcrumb-back-link` hacia `egresados.php?view=panel` con ícono de flecha SVG y microinteracción hover.
  - **Hero Card (`.profile-card.profile-hero-card`):** Banner oficial institucional con degradado rojo a azul, badge institucional `I.E. Gilberto Alzate Avendaño` en esquina superior derecha, avatar grande circular de 108px con inicial 'V' montado sobre el banner, titular de usuario centrado, subtítulo alzatista y cápsulas de metadatos (`Medellín, Colombia`, `Egresada Alzatista`, `Mercado Libre`).
  - **Botonera de acciones contextual:** Botón principal rojo `#D32F2F` ("Enviar mensaje" si `conectado === true` hacia `mensajes.php` / botón outline azul `#1565C0` "Conectar" si `conectado === false`) y botón secundario "Compartir" con copia al portapapeles.
  - **Fila de 4 tarjetas de estadísticas (`.profile-stats-grid`):** Tarjetas con íconos vectoriales SVG en fondos rojo, azul, verde y naranja para proyectos publicados (2), promoción (2021), egresados en su red (34) y barra de perfil verificado (95%).
  - **Distribución principal en dos columnas (`.profile-layout-grid`):**
    * Columna lateral izquierda (350px): Tarjeta de información de contacto (correo, teléfono, documento de identidad, ubicación y fecha de vinculación con íconos de subtítulo dedicados) y tarjeta de redes y enlaces (LinkedIn, GitHub y web personal con flecha de salida `↗`).
    * Columna principal derecha (1fr): Tarjeta "Sobre mí" con párrafo descriptivo y cita institucional entrecomillada (`.profile-quote-box`), tarjeta de "Formación académica" con timeline vertical y nodo rojo, tarjeta de "Habilidades y competencias" con píldoras interactivas, y tarjeta clickeable de "Proyectos en Raíces Alzate" con badge azul de categoría `TECNOLOGÍA`.
  - **Arquitectura estricta:** Cero bases de datos (catálogo en arreglo estático PHP), cero endpoints AJAX/APIs y estricto apego a la paleta institucional (#D32F2F, #1565C0, #212121, #757575, #E0E0E0, #F5F5F5, #FFFFFF).

### 📱 Clonación y Calibración Responsive de la Cabecera en `detalle-egresado.php` (Mobile 390px / `assets/css/perfil-detalle.css`)

#### Mejorado

* **Homologación exacta de la estructura HTML y estilos CSS con `perfil.php` en vista móvil (390px):**
  - **Breadcrumb de navegación:** Inclusión de `.profile-back-nav` con `.btn-back-link` estilizado con flecha direccional `← Volver al directorio de egresados` y transición institucional al color rojo `#D32F2F`.
  - **Tarjeta Hero (`.profile-card.profile-hero-card`):** Adopción de la envoltura oficial con degradado lineal (`.profile-banner`) a 120px de altura en móvil y patrón de textura radial institucional.
  - **Insignia institucional:** Posicionamiento en esquina superior derecha (`.banner-institution-badge`) con fondo blanco translúcido (`rgba(255, 255, 255, 0.92)`), desenfoque backdrop, tipografía roja `#D32F2F` y tamaño optimizado para pantallas estrechas.
  - **Cuerpo de identidad 100% centrado (`.profile-hero-body`):**
    * **Avatar circular centrado:** Montado simétricamente sobre el corte del banner (`.profile-avatar-wrapper` con `margin-top: -46px;` y `.profile-avatar-large` escalado a 92px × 92px con tipografía de 2.1rem y borde blanco sólido de 4px).
    * **Nombre y subtítulo centrados:** `.profile-user-name` a 1.45rem y `.profile-user-subtitle` a 0.95rem en color `#757575`, alineados al centro.
    * **Pills de metadatos centrados (`.profile-meta-pills`):** Tres cápsulas informativas (`Medellín, Colombia`, `Egresada Alzatista`, `Mercado Libre`) con íconos vectoriales SVG lineales en trazo rojo `#D32F2F`, centradas y flexibles.
    * **Botonera de acciones vertical a ancho completo (100%):** `.profile-header-actions` con botones apilados verticalmente a 44px de altura (`width: 100% !important;`), manteniendo el botón principal (`Enviar mensaje` / `Conectar`) con fondo rojo institucional `#D32F2F` y el botón secundario (`Compartir`) con outline gris `#E0E0E0`.
  - **Arquitectura estricta:** Cero bases de datos (datos estáticos PHP), cero endpoints AJAX/API y aplicación rigurosa de la paleta institucional (#D32F2F, #1565C0, #212121, #757575, #E0E0E0, #F5F5F5, #FFFFFF).



### 🎓 Corrección del Flujo de Navegación Pública de Egresados (`egresados.php` / `pages/egresados-public.php`)

#### Corregido

* **Restauración del flujo público institucional en `egresados.php`:**
  - **Eliminación de la redirección forzada:** Removida la lógica restrictiva que dirigía automáticamente a visitantes no autenticados o visitas directas desde el portal al panel privado/dashboard.
  - **Enrutamiento condicional estandarizado:** Al igual que `proyectos.php` y `noticias.php`, las visitas públicas sin parámetro `?view=panel` o sin sesión activa cargan limpiamente la vista pública institucional (`pages/egresados-public.php`) con `includes/header.php` y `includes/footer.php`. Solo cuando existe sesión activa y se especifica `?view=panel` (desde el sidebar) se da paso a `pages/egresados-panel.php`.
  - **Creación de `pages/egresados-public.php` y actualización de `pages/egresados.php`:** Presentación modular oficial en dos columnas (especificación Figma 05):
    * Columna izquierda: Título `Egresados` (`#212121`, Inter Bold 36px), texto descriptivo oficial y botones de acceso ("Iniciar sesión" en rojo `#D32F2F` y "Crear una cuenta" en outline azul `#1565C0` a 44px de altura con radio 8px).
    * Columna derecha: Tarjeta blanca con esquinas redondeadas (`16px`), borde perimetral tenue `#E0E0E0`, sombra suave e ilustración vectorial oficial (`assets/img/egresados-illustration.svg`).
  - **Integridad de enlaces:** Verificación de enlaces limpios en `components/header.php` y actualización del breadcrumb de retorno en `detalle-egresado.php` hacia `egresados.php?view=panel`.

#### Corregido

* **Eliminación del salto/desplazamiento vertical en `:hover` del botón "Iniciar sesión" en el drawer móvil (`assets/css/header.css`):**
  - **Bloqueo dimensional estático e inmovilidad total:** Fijación de `height: 44px !important; min-height: 44px !important; max-height: 44px !important;`, `padding: 0 !important;` y `margin: 0 !important;` tanto en reposo como en `:hover` y `:active`, previniendo cualquier alteración de altura o empuje hacia abajo sobre el botón "Registrarse".
  - **Uniformidad estricta de borde:** Mantenimiento del grosor perimetral de `1.5px solid` sin alteraciones (`#1565C0` en reposo y `#0D47A1` en hover), evitando recálculos de caja.
  - **Centrado geométrico con Flexbox:** Implementación de `display: flex !important; align-items: center !important; justify-content: center !important; line-height: 44px !important;` con tipografía `Inter 15px`, `font-weight: 600` y `box-sizing: border-box !important;`.
  - **Efecto Hover estático:** Transición cromática exclusiva a fondo azul tenue translúcido `rgba(21, 101, 192, 0.08)` con `transform: none !important; box-shadow: none !important;`.

* **Homologación visual del botón "Iniciar sesión" en el drawer móvil con el botón outline del Dashboard ("Publicar proyecto") (`assets/css/header.css`):**
  - **Replicación fidedigna del botón secundario outline:** Implementación de `display: inline-flex !important; align-items: center !important; justify-content: center !important;`, `width: 100% !important; height: 44px !important;`, `padding: 0 20px !important;`, borde institucional de `1.5px solid #1565C0` y radio de `8px`.
  - **Tipografía y alineación exacta:** Tipografía `Inter` a `14.5px` con `font-weight: 500` y `line-height: normal !important;`, garantizando un centrado visual limpio tanto en anchura como en altura.
  - **Efecto :hover idéntico a "Publicar proyecto":** Transición suave y nítida (`background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease`) con fondo azul translúcido sutil `rgba(21, 101, 192, 0.06)`, borde azul más oscuro `#0D47A1` y texto `#0D47A1`, erradicando por completo cualquier salto, parpadeo o sombra no deseada.
  - **Estado activo / clic:** Fondo `rgba(21, 101, 192, 0.12)` con borde `#0D47A1`.

### 🌟 Calibración de Cabecera Hero, Avatar Montado y Cuadrícula de Estadísticas (`detalle-egresado.php` / `assets/css/perfil-detalle.css`)

#### Mejorado

* **Calibración estructural y jerarquía visual de la tarjeta de presentación idéntica a `perfil.php`:**
  - **Badge institucional en el banner:** Reubicación de la cápsula translúcida `I.E. Gilberto Alzate Avendaño` en la esquina superior derecha (`top: 20px; right: 24px; bottom: auto !important;`) con desenfoque de fondo y borde sutil.
  - **Avatar circular montado sobre el banner:** Rediseño a tamaño destacado de 110px x 110px con borde blanco sólido de 5px, sombra elegante y margen superior negativo (`margin-top: -55px; margin-left: 32px;`) para invadir visualmente la mitad del banner degradado institucional.
  - **Chips de metadatos bajo el titular:** Sustitución de los elementos previos por cápsulas estilizadas (`.profile-meta-pills` / `.meta-pill`) con íconos de ubicación (`📍`), condición institucional (`🎓 Egresado/a Alzatista`) y entidad o empresa (`💼`), extrayendo limpiamente la empresa de manera dinámica.
  - **Botonera de acciones integrada:** Disposición en contenedor flexible derecho (`.profile-header-actions`), combinando el botón de acción principal (`Enviar mensaje` para conectados / `Conectar` para pendientes) con el nuevo botón secundario `Compartir` (`.btn-action-share`), con copia automática del enlace al portapapeles y alerta de confirmación.
* **Inserción de la fila de 4 tarjetas de métricas / estadísticas (`.profile-stats-grid`):**
  - Ubicación estratégica justo debajo de la tarjeta principal antes de las dos columnas de información.
  - Tarjeta 1: *Proyectos publicados* (ícono de carpeta sobre fondo rojo translúcido).
  - Tarjeta 2: *Promoción egresado* (año dinámico extraído del perfil, ej. 2021, 2020, 2019, con ícono de birrete sobre fondo azul).
  - Tarjeta 3: *Egresados en su red* (ícono de comunidad sobre fondo verde translúcido).
  - Tarjeta 4: *Perfil verificado* (ícono de reloj sobre fondo naranja y barra de progreso animada al 90%).
  - Diseño responsive optimizado: 4 columnas en desktop, 2 en tablets y 1 fluida en móvil.

### 📱 Activación de Menú Responsive en Detalle de Egresado y Calibración de Botones en Menú Móvil (`detalle-egresado.php` / `assets/js/dashboard.js` / `assets/css/header.css`)

#### Corregido

* **Activación de interacción y toggle de navegación en `detalle-egresado.php`:**
  - Creación del script modular `assets/js/dashboard.js` con soporte universal para los disparadores de menú hamburguesa (`#dashboardMenuToggle`, `#menuToggle`, `.dashboard-menu-toggle`, `.mobile-menu-toggle`, `.btn-menu-hamburguesa`).
  - Control de apertura y cierre del drawer lateral (`.dashboard-sidebar`) mediante alternancia de clases `.is-open` y `.active`, sincronizado con el telón de fondo (`#sidebarBackdrop`, clases `.is-visible` y `.active`) y `aria-expanded`.
  - Soporte de cierre con tecla `Escape`, clic fuera del drawer y auto-restablecimiento en redimensionamiento de pantalla (`> 768px`).
  - Conexión del botón chevron (`#sidebarToggleBtn`) para colapsar y expandir la barra lateral en escritorio con persistencia de preferencia en `localStorage ('sidebar_collapsed')` y actualización de accesibilidad (`aria-label` / `title`).
  - Inclusión de `<script src="assets/js/dashboard.js"></script>` al pie de `detalle-egresado.php` antes del footer institucional.
* **Alineación del radio de curvatura en botones del menú móvil (`assets/css/header.css`):**
  - Corrección del radio exagerado tipo píldora en los botones de "Iniciar sesión" y "Registrarse" dentro de `.mobile-drawer-actions`, estableciendo `border-radius: 8px !important;` acorde a la directriz y coherencia del Design System.
  - Ajuste de tipografía (`font-family: 'Inter', sans-serif`, `font-size: 15px`, `font-weight: 600`), altura de 44px, espaciado vertical de 12px y separador superior limpio (`border-top: 1px solid #E0E0E0`, `padding-top: 20px`).

### 🎨 Reemplazo de Íconos por SVGs Lineales Rojos y Renderizado Dinámico (`detalle-egresado.php` / `assets/css/perfil-detalle.css`)

#### Corregido

* **Sustitución de emojis y caracteres especiales por SVGs lineales institucionales:**
  - Reemplazo de emojis/caracteres en los 6 encabezados de tarjeta (`.card-header-icon`) por íconos SVG vectoriales limpios con trazo rojo institucional (`stroke="#D32F2F"`, `stroke-width="2"`, viewBox `0 0 24 24`), idénticos a `perfil.php`:
    * *Información de contacto*: ícono de correo/mensaje SVG.
    * *Redes y enlaces*: ícono de hipervínculo/cadena SVG.
    * *Sobre mí*: ícono de usuario/perfil SVG.
    * *Formación académica*: ícono de birrete académico SVG.
    * *Habilidades y competencias*: ícono de estrella poligonal SVG.
    * *Proyectos en Raíces Alzate*: ícono de carpeta de proyectos SVG.
  - Reemplazo de caracteres de flecha (`&larr;`, `↗`, `→`) por SVGs lineales nítidos en el botón de retorno (`.btn-back-link`), enlaces externos de redes (`.external-icon svg`) y enlace de acción de proyecto (`.project-action-link svg`).

#### Mejorado

* **Renderizado de datos 100% dinámicos según el egresado seleccionado:**
  - Sincronización completa de las tarjetas de información para reflejar los datos del egresado consultado vía `?id=...` (Carlos `id=2`, Mariana `id=3`, Laura `id=5`, Alejandro `id=6`, etc.):
    * Redes y enlaces dinámicos con URLs personalizadas (`linkedin`, `github`, `portafolio`).
    * Habilidades dinámicas renderizadas mediante bucle PHP con tags `.skill-pill`.
    * Formación superior condicional y trayectoria alzatista adaptadas al perfil.
    * Proyecto institucional vinculado con badge de categoría, título y descripción propios de cada egresado.
  - Estilos CSS afinados en `.card-header-icon` (`border-bottom: 1px solid #E0E0E0`, espaciado de 10px y `stroke: #D32F2F`) y `.external-icon` con transiciones suaves en hover.

### 💬 Corrección de Duplicidad y Navegación en Solicitudes (`mensajes.php` / `assets/css/mensajes.css` / `assets/js/mensajes.js`)

#### Corregido

* **Eliminación de la columna lateral repetida al activar pestaña "Solicitudes":**
  - Ocultamiento de la columna lateral izquierda (`.chat-sidebar-list` / `#panelConectados` / `#panelSolicitudes`) al seleccionar "Solicitudes".
  - Expansión de la vista principal de solicitudes (`.chat-main-container.view-requests .requests-main-view`) al 100% de ancho con `padding: 32px;` y distribución limpia en cuadrícula (`.solicitudes-grid`).
  - Al alternar nuevamente a la pestaña "Conectados", se restablece la columna de contactos (340px) y el área de conversación activa.

#### Añadido

* **Tarjetas de solicitud clickeables hacia detalle de egresado:**
  - Redirección completa de cada tarjeta (`.request-card-large`) a `detalle-egresado.php?id=...` con cursor `pointer`, elevación sutil al hover (`translateY(-2px)`, sombra suave y borde `#BDBDBD`), sin subrayados de texto.
  - Sincronización de identificadores con el catálogo de egresados (Carlos Andrés Morales `id=2`, Mariana Ospina Londoño `id=3`, Alejandro Vélez Castro `id=6`).
  - Control estricto de propagación de eventos (`event.stopPropagation()`) en los botones de acción "Aceptar" (`.btn-action-accept`) y "Rechazar" (`.btn-action-reject`) para procesar la conexión sin disparar la navegación al perfil.

### 📱 Integración de Acciones de Autenticación en Menú Drawer Móvil (`components/header.php` / `assets/css/header.css`)

#### Corregido

* Corrección del menú lateral desplegable en vista responsive (móvil `<= 768px`):
  - Inclusión de las acciones de autenticación (`.mobile-drawer-actions`) al pie del menú lateral (`#mainNav`), debajo de la lista de navegación (`.nav-list`).
  - Botón **Iniciar sesión** (`.btn-drawer-login`): enlace con borde y texto azul institucional (`#1565C0`), fondo transparente, altura de 44px, radio de 8px y hover suave.
  - Botón **Registrarse** (`.btn-drawer-register`): botón a ancho completo con fondo rojo oficial (`#D32F2F`, hover `#B71C1C`), texto blanco, altura de 44px y radio de 8px.
  - Distribución vertical del drawer mediante `justify-content: space-between;` y `margin-top: auto;` para empujar las acciones hacia el fondo con separador visual superior (`border-top: 1px solid #E0E0E0`).
  - Ocultamiento estricto en vista de escritorio (`> 768px`) mediante `.mobile-drawer-actions { display: none !important; }`.

### 🎯 Calibración Estructural y Visual Idéntica a Perfil (`detalle-egresado.php` / `perfil-detalle.css`)

#### Cambiado

* Reorganización del layout inferior en Grid de 2 columnas idéntico a `perfil.php` (`.profile-layout-grid` con `35%` / `65%`):
  - **Columna Izquierda (35% de ancho - `.profile-col-side`):**
    * Tarjeta "Información de contacto": encabezado con ícono rojo (`✉`), correo electrónico en rojo `#D32F2F`, teléfono celular y ubicación/barrio.
    * Tarjeta "Redes y enlaces": encabezado con ícono rojo (`🔗`), accesos directos a LinkedIn y GitHub con enlaces externos y flecha `↗`.
  - **Columna Derecha (65% de ancho - `.profile-col-main`):**
    * Tarjeta "Sobre mí": encabezado con ícono rojo (`👤`) y texto biográfico del egresado.
    * Tarjeta "Formación académica": encabezado con ícono rojo (`🎓`), timeline vertical con nodos circulares institucionales (`.red-dot` y `.blue-dot`), badge azul de nivel educativo y trayectoria alzatista.
    * Tarjeta "Habilidades y competencias": encabezado con ícono rojo (`⭐`) y etiquetas `.skill-pill` en distribución `flex-wrap`.
    * Tarjeta "Proyectos en Raíces Alzate": encabezado con ícono rojo (`📁`), tarjeta clickeable (`.clickable-project`) con redirección a `detalle-proyecto.php`, badge de categoría azul y enlace de consulta.
* Calibración de la cabecera principal e identidad institucional:
  - Insignia del banner (`.banner-institution-badge`): chip translúcido alineado abajo a la derecha (`bottom: 12px; right: 20px;`) con `backdrop-filter: blur(4px);`, borde suave y texto blanco.
  - Botón de acción (`.btn-action-profile`): altura exacta de 42px, radio de borde de 8px, padding de `10px 22px` y alineación vertical con los datos de identidad.
    * Estado conectado (ej. Valentina Ríos): botón primario rojo institucional (`#D32F2F`, hover `#B71C1C`) con enlace directo a `mensajes.php`.
    * Estado por conectar (ej. Carlos Andrés Morales): botón secundario outline azul (`#1565C0`, hover fondo `#E3F2FD`) con interacción dinámica en cliente a "Solicitud enviada".

### 🎓 Perfeccionamiento de Navegación y Layout Visual en Detalle de Egresado (`detalle-egresado.php` / `egresados.php`)

#### Agregado

* Creación de la hoja de estilos modular `assets/css/perfil-detalle.css` con:
  - Grid de 2 columnas asimétrico para escritorio (`.profile-details-grid` con `1.8fr 1.2fr` y `gap: 24px`, colapsable a `1fr` en pantallas `<= 992px`).
  - Tarjeta de proyecto clickeable (`.clickable-project`) con elevación sutil (`transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06); border-color: #BDBDBD;`) y sin subrayados en títulos.
  - Botón de acción condicional (`.btn-action-profile`) con soporte para estado conectado (rojo `#D32F2F`) y estado por conectar (outline azul `#1565C0`), con transición a "Solicitud enviada".
  - Badges institucionales redondeados con fondo tenue (`#F0F4F8`) para habilidades y competencias.
  - Tarjetas independientes para "Información de contacto" y "Redes y enlaces profesionales".

#### Cambiado

* Reorganización estructural limpia del layout en `detalle-egresado.php`:
  - **Columna Izquierda (Principal - 1.8fr):** "Sobre mí", "Formación académica", "Experiencia profesional y laboral" y "Proyectos en Raíces Alzate" con tarjeta clickeable.
  - **Columna Derecha (Complementaria - 1.2fr):** "Habilidades y competencias" (con badges ordenados en flex-wrap), "Información de contacto" (correo, teléfono celular, ciudad) y "Redes y enlaces profesionales" (LinkedIn, GitHub y Portafolio web con enlaces limpios en `#1565C0`).
* Enlace superior de retorno estandarizado a `<a href="egresados.php" class="btn-back-link">← Volver al directorio de egresados</a>`.
* Estandarización de las tarjetas en `egresados.php` (`includes/egresados-privado.php`) con enlace estándar `<a href="detalle-egresado.php?id=..." class="btn-ver-perfil">Ver perfil</a>`.
* Retiro total de la ventana modal emergente antigua `#modalDetalleEgresado` y desactivación de la lógica en `assets/js/egresados.js`, eliminando cualquier parpadeo o bloqueo de scroll (`overflow: hidden`).
* Manejo directo en `egresados.php` para usuarios autenticados que retornan desde `detalle-egresado.php`.

### 🎨 Estandarización del Efecto Hover en Tarjetas de Proyectos y Oportunidades (`proyectos.css` / `oportunidades.css`)

#### Cambiado

* Estandarización del comportamiento `:hover` en las tarjetas de proyectos (`.proyecto-card` / `.project-card`) y oportunidades (`.oportunidad-card` / `.opportunity-card`) para alinearse fielmente con el módulo de Noticias:
  - Borde en gris claro (`border-color: #BDBDBD !important;`).
  - Elevación suave mediante transformación (`transform: translateY(-2px) !important;`).
  - Sombra tenue y limpia (`box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;`).
  - Eliminación total de bordes, fondos o destellos azulados (`#1565C0` / `--color-secondary`).
  - Blindaje estricto de selectores en `proyectos.css` para `a`, `h2`, `.stretched-link` y títulos (`color: #212121 !important; text-decoration: none !important;`).
  - Estandarización de clases duales en la plantilla PHP (`.proyecto-card.project-card`, `.proyecto-title.project-title`, `.proyecto-title-link.project-link`) y en generación dinámica JS.
  - Implementación de control de caché (`assetCssUrl`) en la inclusión directa de hojas de estilo en `proyectos-privado.php` y `oportunidades.php`.
* Preservación intacta de estilos de botones de acción secundarios ("Ver más proyectos", "Ver más oportunidades", badges de categoría y botones "Editar").

### 📨 Limpieza de Solicitudes en Mensajes y Pantalla Completa de Detalle de Egresado (`mensajes.php` / `detalle-egresado.php`)

#### Agregado

* Creación de la vista a pantalla completa `detalle-egresado.php` con la misma estructura y calidad visual que `perfil.php`, sin modales recortados y con navegación de retorno hacia `egresados.php`.
* Estructura modular completa basada en el panel institucional (`layout-wrapper` > `sidebar.php` > `main-wrapper` > `topbar.php` > `content-area`).
* Composición de 6 tarjetas idéntica a `perfil.php`:
  - **Tarjeta 1 (Identidad y contacto directo):** Portada con degradado institucional, avatar circular de 68px con inicial en rojo `#D32F2F`, nombre completo, rol institucional, ubicación y botón "Enviar mensaje" hacia `mensajes.php`.
  - **Tarjeta 2 (Sobre mí):** Resumen biográfico y compromiso con la comunidad institucional.
  - **Tarjeta 3 (Formación académica):** Línea de tiempo con formación media técnica institucional y educación superior.
  - **Tarjeta 4 (Experiencia profesional y laboral):** Cargo actual, empresa y área de especialidad.
  - **Tarjeta 5 (Habilidades y competencias):** Badges redondeados con tecnologías y competencias blandas.
  - **Tarjeta 6 (Proyectos en Raíces Alzate):** Tarjeta de proyecto vinculado con enlace directo a `detalle-proyecto.php`.
* Integración del aviso del sistema `.chat-system-notice` tras aceptar una conexión, iniciando el hilo de mensajes limpio sin textos ficticios.
* Estilos CSS específicos para solicitudes compactas (`.solicitud-mini-card`), programa académico (`.solicitud-academic-program`) y avisos sutiles de sistema en `assets/css/mensajes.css`.

#### Cambiado

* Eliminación del párrafo de texto prefabricado en las tarjetas de solicitud tanto en `mensajes.php` como en `assets/js/mensajes.js`.
* Actualización de las solicitudes pendientes en `#panelSolicitudes` y `#requestsMainView` para mostrar exclusivamente el avatar con inicial, nombre completo, programa académico institucional y botones de acción directos ("Aceptar" en `#D32F2F` y "Rechazar" en outline neutro).
* Actualización de las tarjetas en `egresados.php` (`includes/egresados-privado.php`) para que el botón "Ver perfil" sea un enlace nativo directo a `detalle-egresado.php?id=...`.
* Actualización de la cabecera de conversación en `mensajes.php` para que "Ver perfil" y el usuario enlacen directamente a `detalle-egresado.php`.
* Remoción del modal recortado `#modalEgresado` en `mensajes.php`.
* Actualización de `index.php` con la ruta `case 'detalle-egresado':`.

---

## 📅 13 de octubre de 2026

### 🚀 Implementación de la pantalla de Proyectos (`proyectos.php` / `06 - Proyectos`)

#### Agregado

* Actualización del controlador autenticado `proyectos.php` en la raíz del proyecto con protección estricta de sesión (`$_SESSION['user']` / `$_SESSION['usuario']`), consulta preparada mediante PDO de los primeros 6 proyectos publicados, conteo total y redirección segura a `login.php`.
* Creación de la vista completa y modular `pages/proyectos.php` conforme a la especificación Figma `06 - Proyectos - Desktop` y su correspondiente adaptación responsive.
* Creación de la hoja de estilos modular `assets/css/proyectos.css` con estricto uso de la paleta institucional (`#D32F2F`, `#1565C0`, `#212121`, `#757575`, `#E0E0E0`, `#F5F5F5`, `#FFFFFF`), escala de espaciado estándar (8, 16, 24, 32 px), grid simétrico de 3 columnas para Desktop, adaptación a 2 columnas para Tablet (≤1024px) y 1 columna fluida para Mobile (≤600px).
* Tarjetas de proyecto con proporción rectangular 16:9, placeholder SVG neutro institucional (`#ECECEC` y `#BDBDBD`) sin deformación visual, títulos en negrita enlazados a `detalle-proyecto.php?id={id}`, descripción breve con elipsis CSS en máximo 2 líneas y autoría al pie con formato «Por [Nombre del autor]» (12px, `#757575`).
* Creación del script JavaScript modular `assets/js/proyectos.js` para la paginación asíncrona mediante `fetch()`, control de estado de carga visual en el botón con spinner animado, desactivación y mensaje amigable de finalización de registros.
* Creación del endpoint AJAX `ajax/cargar_mas_proyectos.php` con validación de sesión activa, sanitización de parámetros `offset` y `limite`, consulta preparada PDO y respuesta JSON estándar.
* Creación del script de base de datos `database/proyectos.sql` con la definición de la tabla `proyectos` y el sembrado inicial de los 6 proyectos visibles de Figma más 3 registros de prueba para carga asíncrona.
* Implementación de la vista de detalle individual `detalle-proyecto.php` para resolver de forma funcional y completa la navegación desde las tarjetas.

#### Cambiado

* Actualización de `includes/sidebar.php` para marcar el ítem **Proyectos** como activo (`.active` con fondo `#D32F2F` y texto `#FFFFFF`) y `aria-current="page"` cuando `$currentPage === 'proyectos'`.
* Actualización del enrutador central `index.php` para redirigir `case 'proyectos':` al controlador autenticado `proyectos.php`.
* Robustecimiento de `config/database.php` para capturar excepciones PDO sin terminar la ejecución abruptamente (`die()`), permitiendo a las vistas renderizar estados de error amigables.

### 🎓 Implementación de la pantalla de Directorio de Egresados (`egresados.php` / `05 - Egresados`)

#### Agregado

* Actualización del controlador autenticado `egresados.php` en la raíz del proyecto con protección estricta de sesión (`$_SESSION['user']` / `$_SESSION['usuario']`) y redirección a `login.php`.
* Creación de la vista completa y responsive `pages/egresados.php` correspondiente a la especificación Figma `05 - Egresados - Desktop` y adaptación Mobile.
* Creación de la hoja de estilos modular `assets/css/egresados.css` con la paleta oficial (`#D32F2F`, `#1565C0`, `#212121`, `#757575`, `#E0E0E0`, `#F5F5F5`).
* Incorporación de la cabecera institucional (Hero Header) con resumen numérico (+1,250 egresados registrados, 42 promociones y red institucional activa).
* Implementación de la barra de búsqueda y filtros en tiempo real: campo de búsqueda con icono SVG, selectores desplegables por promoción, énfasis técnico/académico y ciudad de residencia, botón de reinicio y chips activos de filtros.
* Barra de navegación por pestañas de vista rápida: «Todos los egresados», «Mi promoción (2021)», «Destacados» y «Conectados».
* Catálogo de tarjetas de egresados con banner institucional, avatar circular con iniciales, badge oficial de verificación, titulación alzatista, cargo actual, empresa, ubicación, chips de habilidades y botones de acción («Ver perfil» y «Conectar»).
* Estado vacío interactivo (`#emptyStateEgresados`) con ilustración y botón para restablecer filtros cuando no hay coincidencias.
* Paginación accesible institucional.
* Modal dialog accesible de detalle del egresado (`#modalDetalleEgresado`) con portada, avatar, trayectoria alzatista, habilidades, enlaces a LinkedIn/correo y botón para establecer conexión.
* Notificación flotante (Toast) interactiva para retroalimentación de solicitudes de contacto.
* Enrutamiento de `case 'egresados':` en `index.php` con redirección a `egresados.php`.

#### Cambiado

* Actualización de `includes/sidebar.php` para marcar el ítem **Egresados** con `.active` (`#D32F2F`) y `aria-current="page"` cuando `$currentPage === 'egresados'`.

### 👤 Implementación de la pantalla de Perfil del Egresado (`perfil.php` / `04 - Perfil`)

#### Agregado

* Creación del controlador autenticado `perfil.php` en la raíz del proyecto con protección estricta de sesión (`$_SESSION['user']` / `$_SESSION['usuario']`) y redirección a `login.php`.
* Creación de la vista completa y responsive `pages/perfil.php` conforme a la especificación Figma `04 - Perfil - Desktop` y variante Mobile.
* Creación de la hoja de estilos modular `assets/css/perfil.css` con variables y paleta institucional (`#D32F2F`, `#1565C0`, `#212121`, `#757575`, `#E0E0E0`, `#F5F5F5`).
* Integración del componente de portada (Hero Card) con degradado institucional, avatar de gran formato (108 px / 92 px), badge de verificación oficial, titulación del egresado y botones de acción («Editar perfil» y «Compartir»).
* Implementación de la fila de métricas de impacto: proyectos publicados, promoción de egreso, egresados conectados y barra de completitud de perfil (95%).
* Organización del contenido en dos columnas para escritorio y flujo continuo apilado en móvil: información de contacto, redes profesionales, visibilidad en el directorio institucional, biografía («Sobre mí»), línea de tiempo académica, trayectoria laboral, habilidades técnicas/blandas y proyectos vinculados en Raíces Alzate.
* Incorporación de modal accesible de edición de perfil (`#modalEditarPerfil`) con navegación por pestañas (datos personales, académico/laboral y redes), actualización en tiempo real del DOM y notificación flotante (Toast).
* Enrutamiento de `case 'perfil':` en `index.php`.

#### Cambiado

* Actualización de `includes/sidebar.php` para marcar el ítem **Perfil** con `.active` (`#D32F2F`) y `aria-current="page"` cuando `$currentPage === 'perfil'`.
* Actualización de `includes/topbar.php` para admitir título dinámico mediante la variable `$topbarTitle`, mostrando «Mi perfil» en la vista de perfil y «Dashboard» por defecto.

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

### 🔒 Autenticación definitiva con Base de Datos Segura y Retiro de dev-login

#### Eliminado

* Eliminación definitiva de `dev-login.php` y de todo bypass de autenticación por motivos de seguridad.
* Retiro de la ruta `'dev-login'` del enrutador central `index.php`.

#### Agregado

* Autenticación 100% segura mediante PDO contra MySQL con hashes bcrypt (`password_verify`).
* Protección contra Session Fixation mediante `session_regenerate_id(true)`.
* Generación de cuenta administrativa inicial segura en `database/setup.php` y `database/schema.sql`.
* Incorporación de `logout.php` con invalidación de cookies de sesión y destrucción total.

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
