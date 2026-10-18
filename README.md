# 🌱 Raíces Alzate

> Plataforma digital para la gestión, el seguimiento y el fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

## 📖 Descripción

**Raíces Alzate** es una plataforma web desarrollada con el propósito de fortalecer el vínculo entre la Institución Educativa Gilberto Alzate Avendaño y su comunidad de egresados.

El proyecto busca centralizar información académica, profesional y de contacto de los exalumnos, facilitando el seguimiento institucional, la creación de oportunidades de networking y la consolidación de una comunidad activa que impulse el crecimiento académico, profesional y personal de sus integrantes.

La plataforma contempla funcionalidades para la gestión de perfiles, la consulta de egresados, la publicación de proyectos y noticias, la administración de usuarios y la comunicación entre la institución y sus exalumnos.

A través de herramientas digitales organizadas y accesibles, Raíces Alzate busca fortalecer el sentido de pertenencia, fomentar la colaboración y mantener una conexión permanente entre la institución y quienes han formado parte de ella.

El proyecto comenzó en **2025** y actualmente se encuentra en desarrollo.

---

## 🎯 Objetivo

Desarrollar una plataforma web que permita gestionar la información de los egresados, fortalecer la comunicación entre la institución y sus exalumnos y generar oportunidades de desarrollo académico, profesional y colaborativo para toda la comunidad Alzate.

---

## ✨ Características

La plataforma contempla las siguientes funcionalidades y características.

### Funcionalidades de la plataforma

* Gestión integral de perfiles de egresados (información personal, académica y profesional).
* Registro, inicio de sesión y gestión de sesiones de usuario.
* Restablecimiento seguro de contraseña vía correo electrónico con tokens criptográficos uniuso (`random_bytes(32)` + hash SHA-256).
* Directorio, consulta, búsqueda y filtrado dinámico de egresados.
* Sistema de mensajería interna privada y gestión de solicitudes de conexión entre egresados.
* Módulo de PQRS con soporte para carga de archivos adjuntos (dropzone interactivo).
* Publicación, exploración y gestión de proyectos comunitarios e institucionales.
* Publicación, categorización y consulta de noticias institucionales.
* Panel de administración institucional para gestión de usuarios, roles, publicaciones y solicitudes.
* Dashboard interactivo para egresados con métricas, accesos rápidos y estado del perfil.
* Sistema de roles y permisos (Administrador, Egresado).

### Características técnicas y de diseño

* Arquitectura modular basada en PHP 8.x con Front Controller centralizado (`index.php` -> `pages/`).
* Componentes PHP reutilizables (`components/`, `includes/`).
* HTML5 semántico y accesible.
* Estilos CSS modulares organizados por módulo y Design System consistente.
* JavaScript vanilla interactivo con consumo asíncrono de APIs (`fetch`), dropzones, modales y toasts.
* Endpoints REST asíncronos en formato JSON (`api/`) para mensajería y conexiones en tiempo real.
* Base de datos relacional MySQL / MariaDB con esquema DDL de 11 tablas normalizadas e integridad referencial (`database/schema.sql`).
* Script instalador y migrador automatizado ejecutable vía CLI o navegador (`database/setup.php`).
* Acceso seguro a la base de datos mediante PDO con consultas preparadas (Prepared Statements).
* Integración nativa de **PHPMailer** para envío de correos transaccionales vía SMTP autenticado (TLS/SSL).
* Seguridad criptográfica: tokens uniuso con expiración estricta (1 hora), protección CSRF y hash de contraseñas con `password_hash()` BCRYPT.
* Diseño UI/UX completo desarrollado en Figma: 34 pantallas (17 Desktop y 17 Mobile).
* Criterios rigurosos de diseño responsive y tipografía local (Inter).
* Control de versiones profesional mediante Git y GitHub.

> **Nota:** Las funcionalidades descritas corresponden al alcance del sistema en su versión **v0.2.1**. La base de datos, el flujo de recuperación por correo, las APIs asíncronas y las interfaces principales se encuentran operativas, continuando el proceso de integración completa entre el frontend y el backend relacional.

---

## 🚀 Tecnologías

| Tecnología             | Uso                                                                         |
| ---------------------- | --------------------------------------------------------------------------- |
| HTML5                  | Estructura semántica y accesible de las interfaces.                         |
| CSS3                   | Diseño visual, estilos modulares y adaptación responsive.                   |
| JavaScript (Vanilla)   | Interactividad, peticiones asíncronas (`fetch`), validaciones y toasts.     |
| PHP (8.x)              | Procesamiento del servidor, lógica de negocio y Front Controller.           |
| PHPMailer              | Envío automatizado de correos electrónicos transaccionales vía SMTP seguro. |
| MySQL / MariaDB        | Almacenamiento y gestión de datos relacionales (11 tablas normalizadas).    |
| PDO                    | Acceso a la base de datos mediante PHP con consultas preparadas seguras.   |
| API REST / JSON        | Endpoints asíncronos para mensajería y conexiones entre egresados.          |
| Git                    | Control de versiones distribuido.                                           |
| GitHub                 | Alojamiento y gestión colaborativa del repositorio.                         |
| Figma                  | Diseño UI/UX, prototipos interactivos y Design System (34 interfaces).      |
| Visual Studio Code     | Entorno de desarrollo integrado principal.                                  |
| XAMPP                  | Entorno de desarrollo local (Apache, PHP, MariaDB).                         |

El frontend se desarrolla sin frameworks pesados, utilizando **PHP, HTML, CSS y JavaScript vanilla**. La estructura se organiza de manera modular para garantizar alto rendimiento, mantenibilidad y total control sobre el código.

---

## 🎨 Diseño UI/UX

La fase de diseño UI/UX se encuentra **completada y cerrada**. Las interfaces fueron diseñadas en Figma antes de continuar con la implementación.

### Design System

El sistema visual establece criterios comunes para mantener la consistencia de la plataforma:

* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Botones y campos de entrada.
* Checkbox, Radio Button y Switch.
* Tablas y estados de interacción.
* Criterios de diseño responsive.

### Interfaces diseñadas

Se completaron **34 pantallas en total**: 17 para Desktop y sus 17 adaptaciones para Mobile.

Las pantallas abarcan:

* Inicio.
* Autenticación.
* Dashboard.
* Perfil.
* Egresados.
* Proyectos.
* Noticias.
* Administración.
* Creación, edición y detalle de proyectos.
* Registro.
* Recuperación de contraseña.
* Creación, edición y detalle de noticias.

La finalización del diseño no implica que todas las pantallas estén implementadas. La construcción y validación del frontend continúan de manera progresiva.

---

## 📂 Estructura del proyecto

La estructura general del repositorio se organiza de la siguiente manera:

```text
raices-alzate/
├── .github/              # Configuración de GitHub Actions e incidencias
├── api/                  # Endpoints REST/JSON (mensajes, conexiones, respuestas)
├── assets/
│   ├── css/              # Estilos modulares y fonts.css
│   ├── js/               # Lógica interactiva vanilla JS
│   ├── img/              # Imágenes institucionales
│   ├── icons/            # Favicones e iconografía
│   ├── fonts/            # Tipografía local Inter (.woff, .woff2)
│   └── videos/           # Recursos multimedia
├── auth/                 # Controladores backend de autenticación
├── components/           # Componentes visuales reutilizables (header, footer)
├── config/               # Configuración global y credenciales
│   ├── config.php        # Constantes, base URL dinámica y helpers de URLs
│   ├── database.php      # Conexión PDO con reintentos y singleton getDBConnection()
│   └── mail.php          # Configuración de credenciales y servidor SMTP
├── database/             # Esquema SQL y scripts de migración
│   ├── schema.sql        # Esquema DDL con 11 tablas, FK, índices y semillas
│   └── setup.php         # Script instalador/migrador (CLI y navegador)
├── docs/                 # Documentación técnica y bitácora del proyecto
├── includes/             # Lógica modular, helpers comunes, auth, CSRF, layout
│   ├── mailer.php        # Wrapper centralizado para envío de correos con PHPMailer
│   └── PHPMailer/        # Biblioteca nativa de PHPMailer (PHPMailer, SMTP, Exception)
├── pages/                # Vistas y controladores de todas las pantallas
├── uploads/              # Almacenamiento dinámico de fotos y documentos
├── README.md             # Documentación principal del repositorio
└── index.php             # Enrutador principal / Front Controller único en raíz
```

> **Estructura limpia:** En la raíz del proyecto residen únicamente `index.php` y `README.md`. Todas las vistas y controladores se gestionan de forma organizada dentro de `pages/` a través del Front Controller centralizado.

### Organización de estilos

La organización de los estilos se realiza mediante archivos CSS separados según las responsabilidades de cada componente o módulo.

Entre los archivos utilizados se encuentran:

```text
assets/css/
├── main.css          # Variables globales, reset y estilos base
├── fonts.css         # Declaraciones de tipografía local Inter
├── header.css        # Encabezado institucional y navegación pública
├── footer.css        # Pie de página institucional
├── auth.css          # Formularios de autenticación, registro y recuperación
├── dashboard.css     # Estructura del panel de usuario y widgets
├── admin.css         # Panel de control de administración y tablas
├── profile.css       # Vista de perfil y edición de egresado
├── graduates.css     # Directorio de egresados, filtros y tarjetas
├── projects.css      # Módulo de proyectos y detalle
├── news.css          # Módulo de noticias institucionales
├── pqrs.css          # Módulo de PQRS y dropzone de archivos
├── messages.css      # Sistema de mensajería y chat privado
├── loader.css        # Indicadores de carga visual
└── portal.css        # Componentes comunes del portal
```

La lista puede ampliarse conforme se implementen nuevas interfaces y componentes.

### Componentes y recursos compartidos

El proyecto utiliza componentes PHP reutilizables para elementos comunes de la interfaz, como el encabezado, el pie de página, la navegación lateral y la barra superior del dashboard.

Los archivos compartidos se organizan entre `components/` e `includes/`, según su responsabilidad dentro de la arquitectura.

---

## 📚 Documentación

La documentación del proyecto se encuentra organizada principalmente dentro del directorio `docs/`.

| Documento                                            | Descripción                                                          |
| ---------------------------------------------------- | -------------------------------------------------------------------- |
| [`docs/README.md`](docs/README.md)                   | Índice general, estado y guía de navegación de la documentación.     |
| [`docs/bitacora.md`](docs/bitacora.md)               | Registro cronológico de actividades y sesiones de desarrollo.        |
| [`docs/planificacion.md`](docs/planificacion.md)     | Planificación general, fases, metodología y cronograma.              |
| [`docs/requisitos.md`](docs/requisitos.md)           | Requisitos funcionales (RF-01 a RF-11) y no funcionales (RNF).       |
| [`docs/arquitectura.md`](docs/arquitectura.md)       | Arquitectura técnica, capas del sistema, esquema DDL y seguridad.    |
| [`docs/manual-tecnico.md`](docs/manual-tecnico.md)   | Manual técnico oficial para desarrolladores y administradores (v0.2.1). |
| [`docs/manual-usuario.md`](docs/manual-usuario.md)   | Manual de usuario oficial y guía de navegación comunitaria (v0.2.1). |
| [`docs/CHANGELOG.md`](docs/CHANGELOG.md)             | Historial detallado de cambios, versiones y entregas (v0.2.1).       |
| [`docs/ROADMAP.md`](docs/ROADMAP.md)                 | Hoja de ruta estratégica y seguimiento detallado de hitos por fase.  |
| [`docs/decisiones.md`](docs/decisiones.md)           | Registro formal de decisiones de arquitectura (ADR DEC-001 a DEC-037)|
| [`docs/glosario.md`](docs/glosario.md)               | Glosario de términos técnicos, conceptuales y de dominio.            |

La documentación se actualiza continuamente en cada ciclo de desarrollo para mantener la trazabilidad de las decisiones, los requisitos y los avances realizados.

Tanto el Manual Técnico como el Manual de Usuario se encuentran completamente redactados y estructurados para guiar respectivamente a los desarrolladores y a la comunidad de egresados.

---

## 🚧 Estado del proyecto

* **Versión actual:** `v0.2.1`
* **Estado:** 🟡 En desarrollo (Avance significativo en backend, autenticación, base de datos y vistas modulares)
* **Inicio del proyecto:** 2025
* **Fase actual:** Fase 4 — Implementación
* **Última actualización documental:** 17 de octubre de 2026

Las fases de preparación, planificación, documentación base y diseño UI/UX se encuentran consolidadas y cerradas. La implementación del sistema avanza de manera sostenida en frontend, backend relacional y seguridad.

### Avances registrados

* [x] Preparación inicial del repositorio y configuración de Git y GitHub.
* [x] Planificación general del proyecto y cronograma metodológico.
* [x] Definición de requisitos funcionales (RF-01 a RF-11) y no funcionales.
* [x] Elaboración y actualización continua de la documentación técnica en `docs/`.
* [x] Definición de la identidad visual institucional y Design System completo en Figma.
* [x] Diseño UI/UX completado: 17 interfaces Desktop y 17 adaptaciones Mobile (34 pantallas).
* [x] Cierre formal de la fase de diseño UI/UX.
* [x] Arquitectura limpia con Front Controller (`index.php`) y vistas centralizadas en `pages/`.
* [x] Componentes PHP modulares y reutilizables (`header.php`, `footer.php`, `sidebar.php`, `topbar.php`).
* [x] Organización modular de hojas de estilo CSS por módulo (`assets/css/`).
* [x] Implementación y maquetación de interfaces clave en `pages/`: Inicio, Dashboard, Login, Registro, Recuperación de Contraseña, Perfil, Egresados, Proyectos, Noticias, PQRS y Administración.
* [x] Diseño e implementación del esquema relacional DDL con 11 tablas e integridad referencial (`database/schema.sql`).
* [x] Script de instalación y migración automatizada por CLI y navegador (`database/setup.php`).
* [x] Flujo completo y seguro de recuperación de contraseña con tokens criptográficos uniuso (`random_bytes(32)` + hash SHA-256) y caducidad de 1 hora.
* [x] Integración nativa de **PHPMailer** con soporte SMTP autenticado (`config/mail.php`, `includes/mailer.php`) y plantillas HTML con isotipo incrustado CID.
* [x] Endpoints REST asíncronos en formato JSON (`api/`) para mensajería interna y gestión de solicitudes de conexión entre egresados.
* [x] Redacción y publicación oficial del Manual Técnico (`docs/manual-tecnico.md`) v0.2.1.
* [x] Redacción y publicación oficial del Manual de Usuario (`docs/manual-usuario.md`) v0.2.1.

### Actividades pendientes

* [ ] Conectar los formularios y vistas frontend restantes con la base de datos vía PDO.
* [ ] Implementar la persistencia de datos para los módulos de proyectos, noticias y PQRS.
* [ ] Desarrollar los endpoints API adicionales para la gestión de proyectos y publicaciones.
* [ ] Completar los paneles interactivos de administración con operaciones CRUD funcionales.
* [ ] Realizar pruebas funcionales, de integración, responsive y de seguridad (inyección SQL, XSS, CSRF).
* [ ] Preparar el entorno de despliegue y producción.

> Los avances de interfaz y procesamiento no implican que todos los flujos estén completamente operativos. Las funcionalidades deberán validarse mediante pruebas antes de considerarse terminadas.

---

## 📊 Estado por etapas

| Etapa               | Estado         |
| ------------------- | -------------- |
| Preparación         | ✅ Completada   |
| Planificación       | ✅ Completada   |
| Documentación base  | ✅ Completada   |
| Diseño UI/UX        | ✅ Completada   |
| Implementación      | 🟡 En progreso |
| Pruebas             | ⏳ Pendiente    |
| Despliegue y cierre | ⏳ Pendiente    |

El estado se presenta por etapas generales y no como un porcentaje exacto de funcionalidades terminadas. La implementación contiene módulos en diferentes niveles de avance y validación.

---

## 🗺️ Roadmap

### ✅ Fase 0 — Preparación

* Configuración del repositorio.
* Configuración de Git y GitHub.
* Organización de la estructura inicial.
* Creación de la documentación base.

**Estado:** Completada.

### ✅ Fase 1 — Planificación

* Definición del objetivo y el alcance.
* Identificación de las funcionalidades principales.
* Selección de tecnologías.
* Definición de la metodología.
* Planificación general.

**Estado:** Completada.

### ✅ Fase 2 — Documentación

* Bitácora.
* Planificación.
* Requisitos.
* Arquitectura inicial.
* Roadmap.
* README.
* Changelog.
* Decisiones.
* Glosario.
* Índice de documentación.

Los manuales Técnico y de Usuario continúan pendientes de completar.

**Estado:** Documentación base completada.

### ✅ Fase 3 — Diseño UI/UX

**Design System**

* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Botones y campos de entrada.
* Checkbox, Radio Button y Switch.
* Tablas.
* Estados de interacción.
* Criterios responsive.

**Flujo y navegación**

* Flujo general de información.
* Recorridos principales.
* Estructura de navegación.
* Relación entre módulos.

**Interfaces Desktop y Mobile**

Se diseñaron 17 interfaces principales y sus 17 adaptaciones para Mobile, incluyendo Inicio, Autenticación, Dashboard, Perfil, Egresados, Proyectos, Noticias, Administración y pantallas de creación, edición y detalle.

**Estado:** Completada y cerrada a finales de septiembre de 2026.

### 🔄 Fase 4 — Implementación

**Frontend y Vistas**

* Estructura limpia PHP con Front Controller (`index.php`) y vistas centralizadas en `pages/`.
* Componentes reutilizables (`header.php`, `footer.php`, `sidebar.php`, `topbar.php`).
* Navegación responsive y menú móvil interactivo.
* Isotipo y favicon institucionales integrados.
* Hojas de estilo CSS modulares organizadas por módulo (`assets/css/`).
* Interfaz de Inicio para Desktop y Mobile.
* Dashboard interactivo para egresados con métricas y accesos rápidos.
* Maquetación y desarrollo de interfaces: Perfil, Edición de Perfil, Directorio de Egresados, Proyectos, Noticias, PQRS y Administración.
* Componentes interactivos vanilla JS: dropzone para adjuntar archivos, sistema de notificaciones toast y modales dinámicos.

**Backend, Autenticación y Seguridad**

* Flujo completo y seguro de recuperación de contraseña con tokens criptográficos uniuso (`random_bytes(32)` + hash SHA-256) y caducidad de 1 hora.
* Integración nativa de **PHPMailer** con soporte SMTP autenticado (`config/mail.php`, `includes/mailer.php`) y plantillas HTML institucionales.
* Endpoints REST asíncronos en formato JSON (`api/`) para mensajería interna y gestión de solicitudes de conexión entre egresados.
* Controladores de autenticación y sesiones con hash seguro BCRYPT (`password_hash()`) y protección CSRF.

**Base de Datos Relacional**

* Esquema DDL normalizado con 11 tablas, claves foráneas, restricciones `ON DELETE CASCADE` e índices de optimización (`database/schema.sql`).
* Script automatizado de instalación y migración ejecutable por terminal o navegador (`database/setup.php`).
* Conexión robusta mediante PDO con reintentos y singleton (`config/database.php`).

**Pendiente**

* Conectar los formularios y vistas frontend restantes con la base de datos vía PDO.
* Implementar la persistencia de datos para los módulos de proyectos, noticias y PQRS.
* Desarrollar los endpoints API adicionales para la gestión de proyectos y publicaciones.
* Completar los paneles interactivos de administración con operaciones CRUD funcionales.
* Realizar pruebas funcionales, de integración, responsive y de seguridad.

**Estado:** 🟡 En progreso (Avance significativo).

### ⏳ Fase 5 — Pruebas

* Pruebas funcionales.
* Pruebas de integración.
* Pruebas de interfaz.
* Pruebas responsive.
* Compatibilidad entre navegadores.
* Validaciones de seguridad.
* Corrección de errores.
* Validación final.

**Estado:** Pendiente.

### ⏳ Fase 6 — Despliegue y cierre

* Actualización final de la documentación.
* Finalización de los manuales Técnico y de Usuario.
* Preparación de una versión estable.
* Configuración del entorno de producción.
* Publicación.
* Verificación en producción.
* Revisión y cierre del proyecto.

**Estado:** Pendiente.

Para consultar las tareas detalladas y sus actualizaciones, revisa [`ROADMAP.md`](ROADMAP.md).

---

## ⚙️ Instalación

Las siguientes instrucciones describen la preparación inicial del entorno de desarrollo local. La configuración definitiva puede cambiar a medida que se consoliden la arquitectura y la base de datos.

### Requisitos previos

* PHP compatible con la implementación actual.
* MySQL.
* Un servidor local compatible con PHP, como XAMPP, Laragon o WAMP.
* Git.
* Visual Studio Code u otro editor de código.

> Las versiones específicas de PHP y MySQL deberán confirmarse a partir de la configuración real del proyecto antes de establecer los requisitos definitivos.

### 1. Clonar el repositorio

```bash
git clone https://github.com/Zetogan/Raices-Alzate.git
```

### 2. Ingresar al directorio

```bash
cd Raices-Alzate
```

### 3. Preparar el entorno local

1. Instala y configura un entorno compatible con PHP y MySQL.
2. Ubica el proyecto dentro del directorio correspondiente del servidor local, si el entorno lo requiere.
3. Inicia el servidor web y MySQL.
4. Configura los parámetros de conexión a la base de datos de acuerdo con los archivos de configuración del proyecto.
5. Abre la dirección local correspondiente en el navegador.

### 4. Configurar la base de datos

El esquema relacional de la plataforma está completamente definido en [`database/schema.sql`](database/schema.sql) y cuenta con un script de instalación automatizado:

1. **Instalación automática (Recomendada):**
   * Desde la terminal / consola:
     ```bash
     php database/setup.php
     ```
   * O desde el navegador web accediendo a:
     ```text
     http://localhost/Raices-Alzate/database/setup.php
     ```
   El script creará la base de datos `raices_alzate` (si no existe), generará las 11 tablas con sus claves foráneas e índices, e insertará los registros semilla requeridos (`roles` y `habilidades`).

2. **Instalación manual:**
   * Importa directamente [`database/schema.sql`](database/schema.sql) en phpMyAdmin o MySQL Workbench.

3. **Parámetros de conexión:**
   * Los parámetros de conexión (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`) se configuran de manera centralizada en [`config/config.php`](config/config.php).

### 5. Configurar el servicio de correo electrónico (SMTP)

Para habilitar el envío real de correos electrónicos transaccionales (como la recuperación de contraseña):

1. Edita el archivo de configuración de correo: [`config/mail.php`](config/mail.php).
2. Configura los parámetros SMTP de tu proveedor (por ejemplo, Gmail con contraseña de aplicación, SendGrid, Mailgun o el servidor SMTP institucional):
   ```php
   'smtp_host' => 'smtp.gmail.com',
   'smtp_port' => 587,
   'smtp_user' => 'tu-correo@gmail.com',
   'smtp_pass' => 'tu-contraseña-de-aplicación',
   'smtp_secure' => 'tls',
   ```
3. Si el servicio de correo no está configurado, la plataforma registrará los intentos y mostrará alertas informativas para el entorno de desarrollo.

### 6. Consultar la documentación técnica

Para conocer los procedimientos detallados de instalación, arquitectura de base de datos, APIs y despliegue, consulta el [`docs/manual-tecnico.md`](docs/manual-tecnico.md). Para el uso y navegación de la plataforma por parte de egresados, consulta el [`docs/manual-usuario.md`](docs/manual-usuario.md).

---

## ▶️ Uso

Raíces Alzate se encuentra en desarrollo y sus funcionalidades se incorporarán progresivamente.

La plataforma contempla:

* Registro de usuarios.
* Inicio y cierre de sesión.
* Recuperación de contraseña.
* Gestión de perfiles.
* Consulta y búsqueda de egresados.
* Gestión de proyectos.
* Publicación y gestión de noticias.
* Administración de usuarios.
* Gestión de roles y permisos.
* Paneles de usuario y administración.

Actualmente existen avances en la interfaz de Inicio, los elementos compartidos de navegación, el dashboard y los formularios de autenticación.

La disponibilidad y el funcionamiento de cada módulo dependerán de su nivel de implementación, integración y validación.

**Importante:** las interfaces de desarrollo y las rutas de prueba no deben considerarse funcionalidades listas para producción. Antes de publicar el sistema será necesario verificar los controles de acceso, la gestión de sesiones, la persistencia de datos y los mecanismos de seguridad.

---

## 📅 Próximos objetivos

### Frontend

* Conectar las interfaces maquetadas con los datos persistidos en la base de datos vía PDO.
* Consolidar los estados dinámicos e interactivos en las vistas de proyectos y noticias.
* Perfeccionar los filtros y búsquedas en tiempo real en el directorio de egresados.
* Implementar y verificar los comportamientos responsive en todas las pantallas.
* Mantener la estricta coherencia con el Design System de Figma.

### Backend y APIs

* Implementar operaciones CRUD completas para el módulo de administración institucional.
* Desarrollar endpoints API adicionales para la gestión de proyectos, noticias y estados de PQRS.
* Finalizar el procesamiento de archivos adjuntos en el módulo de PQRS y subida de fotos de perfil.
* Consolidar las políticas de autorización, roles y control de acceso por sesión.

### Base de datos

* Poblar datos de prueba representativos para validar el rendimiento de consultas y filtros.
* Optimizar consultas complejas en el directorio de egresados y el historial de mensajes.
* Verificar la integridad referencial en operaciones de eliminación y actualización en cascada.

### Integración, pruebas y despliegue

* Realizar pruebas integrales de seguridad (validación de entradas, sanitización XSS, prevención de CSRF y SQL Injection).
* Pruebas de compatibilidad multidispositivo y cross-browser.
* Redacción de los manuales Técnico y de Usuario durante la Fase 6.
* Preparación del entorno de producción y verificación de despliegue.

---

## 👥 Equipo de desarrollo

Proyecto desarrollado por estudiantes de la **Institución Educativa Gilberto Alzate Avendaño**.

### Integrantes

* Emanuel Cadavid.
* Harold Montoya.
* Santiago Delgado Martínez.
* Santiago Sánchez Emitola.

---

## 🤝 Contribuciones

Actualmente, el desarrollo de Raíces Alzate está a cargo del equipo responsable del proyecto.

A medida que evolucione el repositorio, se podrán establecer directrices de contribución que incluyan:

* Flujo de trabajo con Git.
* Convenciones de código.
* Estándares de documentación.
* Procedimientos para reportar errores.
* Proceso para proponer nuevas funcionalidades.
* Criterios para revisar e integrar cambios.

Las modificaciones deben mantener la coherencia con la arquitectura, las decisiones documentadas y los requisitos del proyecto.

---

## 📄 Licencia

La licencia del proyecto debe confirmarse mediante el archivo de licencia incluido en el repositorio.

Si el proyecto se distribuye bajo la licencia MIT, consulta el archivo `LICENSE` para conocer los términos de uso, modificación y distribución.

---

## 💙 Agradecimientos

Agradecemos a la **Institución Educativa Gilberto Alzate Avendaño**, a los docentes, directivos y a todas las personas que hacen posible el desarrollo de este proyecto.

Su acompañamiento contribuye a la construcción de una plataforma que busca mantener un vínculo permanente entre la institución y su comunidad de egresados.

---

<div align="center">

# 🌱 Raíces Alzate

### Conectando generaciones, fortaleciendo el futuro.

**Versión actual:** `v0.2.1`

Desarrollado con ❤️ por el equipo de Raíces Alzate.

</div>
