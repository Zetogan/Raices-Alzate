# 🛠️ Manual Técnico — Raíces Alzate

> **Documento:** Manual Técnico para Desarrolladores y Administradores de Sistemas  
> **Proyecto:** Raíces Alzate — Plataforma de Gestión de Egresados  
> **Institución:** Institución Educativa Gilberto Alzate Avendaño  
> **Versión del Sistema:** `v0.2.1`  
> **Fecha de Elaboración:** 17 de octubre de 2026  
> **Estado:** Documentación Técnica Oficial Vigente  

---

## 📑 Tabla de Contenidos

1. [Introducción y Propósito del Documento](#1-introducción-y-propósito-del-documento)
2. [Visión General del Sistema y Objetivos Técnicos](#2-visión-general-del-sistema-y-objetivos-técnicos)
3. [Requisitos del Sistema y Entorno de Ejecución](#3-requisitos-del-sistema-y-entorno-de-ejecución)
4. [Arquitectura de Software y Patrones de Diseño](#4-arquitectura-de-software-y-patrones-de-diseño)
5. [Estructura Detallada de Archivos y Directorios](#5-estructura-detallada-de-archivos-y-directorios)
6. [Modelo de Datos y Base de Datos Relacional](#6-modelo-de-datos-y-base-de-datos-relacional)
7. [Proceso de Instalación, Configuración y Migración](#7-proceso-de-instalación-configuración-y-migración)
8. [Servicio de Correo Electrónico (PHPMailer & SMTP)](#8-servicio-de-correo-electrónico-phpmailer--smtp)
9. [Mecanismos de Seguridad y Criptografía](#9-mecanismos-de-seguridad-y-criptografía)
10. [Especificación de la API REST / JSON](#10-especificación-de-la-api-rest--json)
11. [Frontend, Estilos Modulares y JavaScript](#11-frontend-estilos-modulares-y-javascript)
12. [Mantenimiento, Diagnóstico y Solución de Problemas](#12-mantenimiento-diagnóstico-y-solución-de-problemas)
13. [Control de Cambios del Documento](#13-control-de-cambios-del-documento)

---

## 1. Introducción y Propósito del Documento

El presente **Manual Técnico** constituye la guía de referencia integral para el equipo de desarrollo, administradores de infraestructura, evaluadores técnicos y futuros mantenedores de la plataforma **Raíces Alzate**.

Su propósito es proporcionar una descripción rigurosa y exhaustiva de:
* La infraestructura de ejecución requerida.
* Los componentes de software y su integración.
* La arquitectura modular y patrones de diseño aplicados.
* El modelo de datos relacional y las restricciones de integridad referencial.
* Los estándares de codificación, protocolos de seguridad y mecanismos criptográficos implementados.
* Las especificaciones de los endpoints asíncronos y servicios transaccionales.

Toda modificación al código fuente, base de datos o infraestructura del sistema debe alinearse estrictamente con los lineamientos aquí establecidos.

---

## 2. Visión General del Sistema y Objetivos Técnicos

**Raíces Alzate** es una plataforma web institucional orientada al registro, seguimiento, networking, colaboración e integración comunitaria de los egresados de la **Institución Educativa Gilberto Alzate Avendaño**.

### Principios y Objetivos Técnicos Fundamentales

1. **Eficiencia y Cero Dependencias Pesadas en Frontend:**
   Desarrollo en PHP nativo, HTML5 semántico, CSS3 modularizado y JavaScript vanilla. Se evita deliberadamente el uso de frameworks pesados (React, Angular, Bootstrap o TailwindCSS) para garantizar tiempos de carga ultrarrápidos, total soberanía del código y compatibilidad universal con navegadores.

2. **Modularidad Estricta y Desacoplamiento:**
   Adopción del patrón **Front Controller** en la raíz (`index.php`), centralizando la gestión de rutas hacia controladores y vistas situados dentro del directorio `pages/`.

3. **Seguridad Defensiva y por Diseño:**
   * Cifrado irreversible de credenciales mediante BCRYPT (`password_hash()`).
   * Protección integral contra ataques CSRF (*Cross-Site Request Forgery*) mediante tokens de sesión uniuso.
   * Mitigación de inyecciones SQL mediante consultas preparadas con PDO (*Prepared Statements*).
   * Restablecimiento seguro de contraseñas mediante tokens criptográficos uniuso generados con `random_bytes(32)` y almacenados como hashes SHA-256 con ventana de expiración estricta (1 hora).

4. **Integridad Relacional Completa:**
   Persistencia en base de datos MySQL / MariaDB estructurada en 11 tablas normalizadas con claves foráneas, reglas `ON DELETE CASCADE` e índices optimizados para consultas de alta concurrencia.

---

## 3. Requisitos del Sistema y Entorno de Ejecución

### 3.1. Requerimientos de Hardware

| Recurso | Entorno de Desarrollo (Mínimo) | Servidor de Producción (Recomendado) |
| :--- | :--- | :--- |
| **Procesador (CPU)** | 2 núcleos @ 2.0 GHz (x86_64) | 4 núcleos @ 2.4 GHz o superior |
| **Memoria RAM** | 4 GB | 8 GB o superior |
| **Almacenamiento** | 2 GB libres en disco | 20 GB en SSD / NVMe con espacio para `uploads/` |
| **Red** | Conexión local / Internet para SMTP | Conexión simétrica con IP pública estática y HTTPS |

### 3.2. Requerimientos de Software

| Componente | Versión Mínima | Versión Recomendada | Notas de Configuración |
| :--- | :--- | :--- | :--- |
| **Sistema Operativo** | Windows 10 / Linux Ubuntu 20.04 | Ubuntu Server 22.04 LTS / Debian 12 | Compatible con entornos POSIX y Windows (XAMPP/Laragon) |
| **Servidor Web** | Apache 2.4.x | Apache 2.4.5x con `mod_rewrite` activo | Soporte para `.htaccess` y cabeceras de seguridad |
| **Intérprete PHP** | PHP 8.0.0 | PHP 8.1.x / PHP 8.2.x | Modo `declare(strict_types=1);` aplicado |
| **Base de Datos** | MySQL 5.7 o MariaDB 10.4 | MariaDB 10.6+ / MySQL 8.0+ | Motor de almacenamiento InnoDB obligatorio |

### 3.3. Extensiones PHP Requeridas

Para garantizar el funcionamiento de todas las capas del sistema, el archivo `php.ini` debe tener habilitadas las siguientes extensiones:

```ini
extension=pdo_mysql      ; Conectividad con la base de datos relacional
extension=openssl        ; Generación criptográfica segura y negociación TLS/SSL para SMTP
extension=mbstring       ; Manejo avanzado de codificación UTF-8
extension=json           ; Serialización y deserialización de peticiones en api/
extension=session        ; Gestión de sesiones seguras de usuario
extension=fileinfo       ; Detección precisa de tipos MIME en subida de archivos (PQRS)
```

---

## 4. Arquitectura de Software y Patrones de Diseño

La plataforma implementa una arquitectura monolítica modular en capas, optimizada para alta velocidad de respuesta y bajo consumo de recursos:

```
+-----------------------------------------------------------------------------------+
|                                  CLIENTE (Navegador)                               |
|        HTML5 Semántico | CSS3 Modular | JavaScript Vanilla (Fetch API, DOM)        |
+-----------------------------------------------------------------------------------+
                                         |
                                (HTTP / HTTPS Requests)
                                         v
+-----------------------------------------------------------------------------------+
|                        ENRUTADOR CENTRAL (Front Controller)                       |
|                                    index.php                                      |
+-----------------------------------------------------------------------------------+
       |                                  |                                   |
       v                                  v                                   v
+------------------+             +------------------+             +-----------------+
| Vistas / Páginas |             |   API REST JSON  |             | Control Acceso  |
|     (pages/)     |             |      (api/)      |             | (includes/auth) |
+------------------+             +------------------+             +-----------------+
       |                                  |                                   |
       +----------------------------------+-----------------------------------+
                                         |
                                         v
+-----------------------------------------------------------------------------------+
|                                CAPA DE SERVICIOS                                  |
|         - mailer.php (PHPMailer Wrapper / SMTP Transaccional)                     |
|         - helpers.php (Sanitización, Generación URLs, CSRF)                       |
+-----------------------------------------------------------------------------------+
                                         |
                                         v
+-----------------------------------------------------------------------------------+
|                         CAPA DE ACCESO A DATOS (DAL)                              |
|         - config/database.php (Patrón Singleton PDO + Reintentos)                 |
|         - Consultas preparadas (Prepared Statements con PDO::ATTR_EMULATE_PREPARES|
+-----------------------------------------------------------------------------------+
                                         |
                                         v
+-----------------------------------------------------------------------------------+
|                            BASE DE DATOS RELACIONAL                               |
|                     MySQL / MariaDB (11 Tablas Normalizadas)                      |
+-----------------------------------------------------------------------------------+
```

### Patrones de Diseño Aplicados

1. **Front Controller (Enrutador Central):**
   Implementado en [`index.php`](file:///d:/SSE/BSD/htdocs/Raices-Alzate/index.php). Recibe el parámetro `$_GET['page']`, valida la ruta contra un catálogo canónico y despacha la ejecución hacia la vista correspondiente en `pages/`. En caso de ruta inválida, emite un código HTTP 404 estandarizado con la plantilla visual institucional.

2. **Singleton (Acceso a Base de Datos):**
   Implementado en la función `getDBConnection()` de [`config/database.php`](file:///d:/SSE/BSD/htdocs/Raices-Alzate/config/database.php). Garantiza una única instancia activa de `PDO` por ciclo de vida de la petición HTTP, mitigando la sobrecarga por apertura excesiva de sockets MySQL.

3. **Service Wrapper (Abstracción de Correo Electrónico):**
   Implementado en [`includes/mailer.php`](file:///d:/SSE/BSD/htdocs/Raices-Alzate/includes/mailer.php). Encapsula las clases nativas de **PHPMailer**, gestiona la autenticación SMTP, aplica plantillas HTML institucionales e incrusta el isotipo corporativo mediante Content-ID (`CID:alzate_logo`).

4. **Componentización de Interfaz:**
   Elementos transversales como encabezado ([`components/header.php`](file:///d:/SSE/BSD/htdocs/Raices-Alzate/components/header.php)), pie de página ([`components/footer.php`](file:///d:/SSE/BSD/htdocs/Raices-Alzate/components/footer.php)), barra lateral ([`includes/sidebar.php`](file:///d:/SSE/BSD/htdocs/Raices-Alzate/includes/sidebar.php)) y barra superior ([`includes/topbar.php`](file:///d:/SSE/BSD/htdocs/Raices-Alzate/includes/topbar.php)) se integran de forma desacoplada y reutilizable.

---

## 5. Estructura Detallada de Archivos y Directorios

El árbol del proyecto mantiene una organización limpia y estricta:

```text
raices-alzate/
├── .github/                      # Automatizaciones e incidencias de GitHub Actions
├── api/                          # Endpoints REST asíncronos que retornan JSON
│   ├── conectar-egresado.php     # Gestión de envío de solicitudes de conexión
│   ├── enviar-mensaje.php        # Procesamiento y almacenamiento de mensajes directos
│   ├── obtener-mensajes.php      # Consulta asíncrona de conversaciones e historial
│   └── responder-solicitud.php   # Aceptación o rechazo de solicitudes de contacto
├── assets/                       # Recursos estáticos de la plataforma
│   ├── css/                      # Hojas de estilo modulares por interfaz
│   │   ├── admin.css             # Estilos del panel de control de administración
│   │   ├── auth.css              # Formularios de autenticación, registro y reseteo
│   │   ├── dashboard.css         # Panel principal de usuario y widgets
│   │   ├── fonts.css             # Definiciones `@font-face` de la tipografía Inter
│   │   ├── footer.css            # Pie de página institucional
│   │   ├── graduates.css         # Directorio de egresados y tarjetas de contacto
│   │   ├── header.css            # Cabecera institucional y navegación pública
│   │   ├── loader.css            # Spinners e indicadores visuales de carga
│   │   ├── main.css              # Reset, variables CSS globales y componentes base
│   │   ├── messages.css          # Módulo de mensajería y chat interactivo
│   │   ├── news.css              # Sección de noticias institucionales
│   │   ├── portal.css            # Estilos transversales del portal de egresados
│   │   ├── pqrs.css              # Módulo de PQRS y zona de dropzone de adjuntos
│   │   └── profile.css           # Vistas de visualización y edición del perfil
│   ├── fonts/                    # Archivos locales de fuentes Inter (.woff, .woff2)
│   ├── icons/                    # Favicones e iconografía SVG
│   ├── img/                      # Logotipos e imágenes institucionales
│   ├── js/                       # Lógica interactiva en Vanilla JavaScript
│   │   ├── admin.js              # Comportamiento dinámico de administración
│   │   ├── auth.js               # Validaciones reactivas de login y registro
│   │   ├── main.js               # Menú responsive, utilidades de interfaz y modales
│   │   ├── messages.js           # Envío y renderizado dinámico de chat
│   │   ├── pqrs.js               # Manejo del dropzone y validación de archivos
│   │   ├── profile.js            # Dinámica de edición de habilidades y campos
│   │   └── reset-password.js     # Validaciones de fortaleza y coincidencia de clave
│   └── videos/                   # Recursos multimedia institucionales
├── auth/                         # Controladores backend de autenticación
│   ├── login.php                 # Procesamiento de inicio de sesión
│   ├── logout.php                # Cierre y destrucción de sesión segura
│   ├── recuperar.php             # Despacho de correo de reseteo con token
│   ├── registro.php              # Registro de nuevos egresados y hash de clave
│   └── reset.php                 # Validación de token y actualización de clave en BD
├── components/                   # Componentes visuales reutilizables
│   ├── footer.php                # Pie de página compartido
│   └── header.php                # Encabezado institucional compartido
├── config/                       # Archivos de configuración del entorno
│   ├── config.php                # Constantes globales, URLs dinámicas y rutas base
│   ├── database.php              # Clase Singleton de conexión PDO y reintentos
│   └── mail.php                  # Parámetros y credenciales del servidor SMTP
├── database/                     # Scripts de persistencia y base de datos
│   ├── schema.sql                # DDL de 11 tablas, claves foráneas, índices y semillas
│   └── setup.php                 # Instalador y migrador automatizado (CLI / Web)
├── docs/                         # Documentación técnica y metodológica completa
│   ├── arquitectura.md           # Especificación arquitectónica del sistema
│   ├── bitacora.md               # Registro histórico y cronológico de sesiones
│   ├── CHANGELOG.md              # Registro formal de versiones y cambios
│   ├── decisiones.md             # Registro de Decisiones de Arquitectura (ADR)
│   ├── glosario.md               # Glosario técnico y conceptual
│   ├── manual-tecnico.md         # Manual Técnico oficial para desarrolladores
│   ├── manual-usuario.md         # Manual de Usuario oficial para la comunidad
│   ├── planificacion.md          # Planificación metodológica y de recursos
│   ├── README.md                 # Índice y guía de navegación documental
│   ├── requisitos.md             # Requisitos funcionales y no funcionales
│   └── ROADMAP.md                # Hoja de ruta y seguimiento de fases
├── includes/                     # Helpers, lógica compartida y librerías externas
│   ├── auth.php                  # Funciones de verificación de sesión y permisos
│   ├── helpers.php               # Funciones auxiliares de sanitización, URL y CSRF
│   ├── mailer.php                # Wrapper de PHPMailer y plantillas de correo
│   ├── sidebar.php               # Menú lateral para el panel privado
│   ├── topbar.php                # Barra superior con datos de usuario
│   └── PHPMailer/                # Biblioteca nativa PHPMailer v6.x (sin Composer)
│       ├── Exception.php         # Manejo de excepciones de correo
│       ├── PHPMailer.php         # Clase central de composición de correo
│       └── SMTP.php              # Controlador del protocolo SMTP
├── pages/                        # Controladores y vistas de todas las pantallas
│   ├── administracion.php        # Panel de control administrativo
│   ├── crear-noticia.php         # Formulario de publicación de noticias
│   ├── crear-oportunidad.php     # Formulario de publicación de ofertas laborales
│   ├── crear-proyecto.php        # Formulario de registro de proyectos
│   ├── dashboard.php             # Panel de inicio del egresado autenticado
│   ├── detalle-egresado.php      # Vista pública de perfil de un egresado
│   ├── detalle-noticia.php       # Vista detallada de artículo de noticia
│   ├── detalle-oportunidad.php   # Vista detallada de oportunidad laboral
│   ├── detalle-proyecto.php      # Vista detallada de proyecto comunitario
│   ├── editar-perfil.php         # Formulario de edición del perfil propio
│   ├── editar-proyecto.php       # Formulario de modificación de proyecto
│   ├── egresados.php             # Directorio interactivo de egresados
│   ├── inicio.php                # Página principal del portal público
│   ├── login.php                 # Interfaz de inicio de sesión
│   ├── logout.php                # Controlador de salida de sesión
│   ├── mensajes.php              # Centro de mensajería privada y chat
│   ├── noticias.php              # Catálogo de noticias institucionales
│   ├── oportunidades.php         # Bolsa de oportunidades para egresados
│   ├── perfil.php                # Vista del perfil propio
│   ├── pqr.php                   # Formulario y radicación de PQRS con adjuntos
│   ├── proyectos.php             # Catálogo de proyectos comunitarios
│   ├── recuperar-password.php    # Formulario para solicitar reseteo de clave
│   ├── registro.php              # Formulario de registro de egresados
│   └── reset-password.php        # Formulario para ingresar nueva contraseña
├── uploads/                      # Directorio de almacenamiento de archivos dinámicos
│   ├── pqrs/                     # Evidencias adjuntas a tickets PQRS
│   └── profiles/                 # Fotografías de perfil de usuarios
├── index.php                     # Front Controller único en raíz
└── README.md                     # Documentación principal del repositorio
```

---

## 6. Modelo de Datos y Base de Datos Relacional

La base de datos del sistema está optimizada en **MySQL / MariaDB** bajo el conjunto de caracteres `utf8mb4` y cotejamiento `utf8mb4_unicode_ci`. Se compone de **11 tablas relacionales normalizadas** (Tercera Forma Normal - 3NF).

### 6.1. Diagrama Entidad-Relación Conceptual

```
+---------------+        1:N        +---------------+
|     ROLES     |-------------------|   USUARIOS    |
+---------------+                   +---------------+
                                            | 1:1
                                            v
+---------------+        1:N        +---------------+        1:N        +---------------+
|  HABILIDADES  |-------------------|   EGRESADOS   |-------------------|  PROYECTOS    |
+---------------+                   +---------------+                   +---------------+
        |                                   |                                   |
        | N:M                               | 1:N                               | 1:N
        v                                   v                                   v
+---------------+                   +---------------+                   +---------------+
|   EGRESADO_   |                   |  SOLICITUDES_ |                   |   NOTICIAS    |
|  HABILIDADES  |                   |   CONTACTO    |                   +---------------+
+---------------+                   +---------------+                           |
                                            | 1:N                               | 1:N
                                            v                                   v
                                    +---------------+                   +---------------+
                                    |   MENSAJES    |                   |     PQRS      |
                                    +---------------+                   +---------------+
                                            |
                                            v
                                    +---------------+
                                    |PASSWORD_RESETS|
                                    +---------------+
```

### 6.2. Catálogo Detallado de Tablas

#### 1. `roles`
Almacena los perfiles de acceso autorizados en el sistema.
* `id`: `INT AUTO_INCREMENT PRIMARY KEY`
* `nombre`: `VARCHAR(50) NOT NULL UNIQUE` (ej. `'administrador'`, `'egresado'`)
* `descripcion`: `VARCHAR(255) NULL`
* `created_at`: `TIMESTAMP DEFAULT CURRENT_TIMESTAMP`

#### 2. `usuarios`
Entidad principal de autenticación y credenciales de acceso.
* `id`: `INT AUTO_INCREMENT PRIMARY KEY`
* `rol_id`: `INT NOT NULL` (FK a `roles(id)`)
* `email`: `VARCHAR(150) NOT NULL UNIQUE` (Indexado para búsqueda rápida)
* `password`: `VARCHAR(255) NOT NULL` (Hash BCRYPT)
* `estado`: `ENUM('activo', 'inactivo', 'pendiente') DEFAULT 'activo'`
* `ultimo_login`: `DATETIME NULL`
* `created_at`, `updated_at`: `TIMESTAMP`

#### 3. `egresados`
Perfil detallado académico, profesional y biográfico del usuario egresado.
* `id`: `INT AUTO_INCREMENT PRIMARY KEY`
* `usuario_id`: `INT NOT NULL UNIQUE` (FK a `usuarios(id)` `ON DELETE CASCADE`)
* `nombres`: `VARCHAR(100) NOT NULL`
* `apellidos`: `VARCHAR(100) NOT NULL`
* `documento_tipo`: `ENUM('CC', 'TI', 'CE', 'PASAPORTE') DEFAULT 'CC'`
* `documento_numero`: `VARCHAR(30) NULL`
* `telefono`: `VARCHAR(20) NULL`
* `fecha_graduacion`: `YEAR NOT NULL` (Año de promoción)
* `titulo_obtenido`: `VARCHAR(150) NULL`
* `biografia`: `TEXT NULL`
* `experiencia_laboral`: `TEXT NULL`
* `educacion_superior`: `TEXT NULL`
* `linkedin_url`: `VARCHAR(255) NULL`
* `foto_perfil`: `VARCHAR(255) NULL`
* `created_at`, `updated_at`: `TIMESTAMP`

#### 4. `habilidades`
Catálogo estandarizado de competencias técnicas y profesionales.
* `id`: `INT AUTO_INCREMENT PRIMARY KEY`
* `nombre`: `VARCHAR(100) NOT NULL UNIQUE`
* `categoria`: `VARCHAR(50) NULL`

#### 5. `egresado_habilidades`
Tabla de asociación N:M entre egresados y habilidades.
* `egresado_id`: `INT NOT NULL` (FK a `egresados(id)` `ON DELETE CASCADE`)
* `habilidad_id`: `INT NOT NULL` (FK a `habilidades(id)` `ON DELETE CASCADE`)
* `PRIMARY KEY (egresado_id, habilidad_id)`

#### 6. `solicitudes_contacto`
Control de conexiones profesionales entre miembros de la comunidad.
* `id`: `INT AUTO_INCREMENT PRIMARY KEY`
* `solicitante_id`: `INT NOT NULL` (FK a `egresados(id)`)
* `receptor_id`: `INT NOT NULL` (FK a `egresados(id)`)
* `estado`: `ENUM('pendiente', 'aceptada', 'rechazada') DEFAULT 'pendiente'`
* `created_at`, `updated_at`: `TIMESTAMP`
* `UNIQUE KEY (solicitante_id, receptor_id)`

#### 7. `mensajes`
Mensajería interna privada y bidireccional entre egresados conectados.
* `id`: `INT AUTO_INCREMENT PRIMARY KEY`
* `remitente_id`: `INT NOT NULL` (FK a `egresados(id)`)
* `destinatario_id`: `INT NOT NULL` (FK a `egresados(id)`)
* `mensaje`: `TEXT NOT NULL`
* `leido`: `TINYINT(1) DEFAULT 0`
* `created_at`: `TIMESTAMP DEFAULT CURRENT_TIMESTAMP`

#### 8. `proyectos`
Banco de iniciativas, emprendimientos y proyectos comunitarios.
* `id`: `INT AUTO_INCREMENT PRIMARY KEY`
* `egresado_id`: `INT NOT NULL` (FK a `egresados(id)`)
* `titulo`: `VARCHAR(200) NOT NULL`
* `descripcion`: `TEXT NOT NULL`
* `categoria`: `VARCHAR(100) NULL`
* `enlace`: `VARCHAR(255) NULL`
* `estado`: `ENUM('borrador', 'publicado', 'finalizado') DEFAULT 'publicado'`
* `created_at`, `updated_at`: `TIMESTAMP`

#### 9. `noticias`
Boletín institucional y comunicados de la institución educativa.
* `id`: `INT AUTO_INCREMENT PRIMARY KEY`
* `usuario_id`: `INT NOT NULL` (FK a `usuarios(id)`)
* `titulo`: `VARCHAR(255) NOT NULL`
* `resumen`: `TEXT NULL`
* `contenido`: `LONGTEXT NOT NULL`
* `imagen_portada`: `VARCHAR(255) NULL`
* `categoria`: `VARCHAR(100) NULL`
* `estado`: `ENUM('borrador', 'publicado', 'archivado') DEFAULT 'publicado'`
* `created_at`, `updated_at`: `TIMESTAMP`

#### 10. `pqrs`
Sistema de Peticiones, Quejas, Reclamos y Sugerencias ciudadanas.
* `id`: `INT AUTO_INCREMENT PRIMARY KEY`
* `usuario_id`: `INT NULL` (FK a `usuarios(id)`, nullable para radicaciones anónimas)
* `tipo`: `ENUM('peticion', 'queja', 'reclamo', 'sugerencia') NOT NULL`
* `asunto`: `VARCHAR(200) NOT NULL`
* `descripcion`: `TEXT NOT NULL`
* `archivo_adjunto`: `VARCHAR(255) NULL`
* `estado`: `ENUM('pendiente', 'en_proceso', 'resuelto', 'cerrado') DEFAULT 'pendiente'`
* `respuesta`: `TEXT NULL`
* `created_at`, `updated_at`: `TIMESTAMP`

#### 11. `password_resets`
Tabla transaccional para el flujo criptográfico de recuperación de contraseñas.
* `id`: `INT AUTO_INCREMENT PRIMARY KEY`
* `email`: `VARCHAR(150) NOT NULL` (Indexado)
* `token`: `VARCHAR(64) NOT NULL UNIQUE` (Hash SHA-256 del token plano)
* `expires_at`: `DATETIME NOT NULL` (Fecha y hora límite de validez)
* `created_at`: `TIMESTAMP DEFAULT CURRENT_TIMESTAMP`

---

## 7. Proceso de Instalación, Configuración y Migración

### 7.1. Despliegue en Entorno de Desarrollo Local

1. **Clonación del Repositorio:**
   Ubicar el proyecto dentro del directorio raíz del servidor web local (por ejemplo, `C:\xampp\htdocs\Raices-Alzate` o `/var/www/html/Raices-Alzate`):
   ```bash
   git clone https://github.com/Zetogan/Raices-Alzate.git
   cd Raices-Alzate
   ```

2. **Verificación de Permisos:**
   Asegurar que los directorios `uploads/` y sus subcarpetas tengan permisos de lectura y escritura:
   ```bash
   chmod -R 775 uploads/
   ```

3. **Configuración de Conexión a Base de Datos:**
   Editar [`config/config.php`](file:///d:/SSE/BSD/htdocs/Raices-Alzate/config/config.php) para ajustar las constantes de conexión si tu entorno local no usa las predeterminadas de XAMPP (`root` sin contraseña):
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'raices_alzate');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_CHARSET', 'utf8mb4');
   ```

4. **Ejecución del Script Instalador / Migrador:**
   El proyecto incluye el script automatizado [`database/setup.php`](file:///d:/SSE/BSD/htdocs/Raices-Alzate/database/setup.php), que puede ser ejecutado por consola o navegador:
   * **Vía CLI (Terminal):**
     ```bash
     php database/setup.php
     ```
   * **Vía Navegador Web:**
     ```text
     http://localhost/Raices-Alzate/database/setup.php
     ```
   El script creará automáticamente la base de datos `raices_alzate` (si no existía), construirá las 11 tablas con sus claves foráneas e índices, e insertará los registros semilla requeridos (`roles` y `habilidades`).

---

## 8. Servicio de Correo Electrónico (PHPMailer & SMTP)

El sistema integra de forma nativa la biblioteca **PHPMailer** sin requerir Composer, ubicándose en `includes/PHPMailer/`.

### 8.1. Configuración de Credenciales (`config/mail.php`)

```php
return [
    'driver'     => 'smtp',
    'smtp_host'  => 'smtp.gmail.com',
    'smtp_port'  => 587,
    'smtp_secure'=> 'tls',
    'smtp_auth'  => true,
    'smtp_user'  => 'raicesalzate@gmail.com',
    'smtp_pass'  => 'xxxx xxxx xxxx xxxx', // Contraseña de Aplicación de Google de 16 caracteres
    'from_email' => 'no-reply@alzate.edu.co',
    'from_name'  => 'Raíces Alzate - I.E. Gilberto Alzate Avendaño',
    'debug'      => 0 // Cambiar a 2 para diagnóstico detallado en consola
];
```

> [!TIP]
> **Generación de Contraseña de Aplicación (Gmail):**  
> Para cuentas con verificación en dos pasos (2FA), se debe ingresar a **Gestionar cuenta de Google > Seguridad > Contraseñas de aplicaciones**, generar una clave de 16 caracteres para "Correo / Servidor Web" e ingresarla en `'smtp_pass'`.

### 8.2. Abstracción Centralizada (`includes/mailer.php`)

La función `sendSystemEmail($to, $subject, $bodyHtml, $altBodyText = '')` administra el ciclo de vida del envío:
* Inicializa la clase `PHPMailer(true)`.
* Configura la conexión SMTP autenticada bajo cifrado STARTTLS (`587`) o SMTPS (`465`).
* Incrusta el logotipo institucional como recurso embebido mediante `AddEmbeddedImage()` con el identificador `alzate_logo`, evitando el bloqueo de imágenes remotas en clientes de correo como Outlook o Gmail.
* Envía tanto la versión HTML estilizada con la identidad visual institucional como la versión en texto plano de respaldo (*multipart/alternative*).

---

## 9. Mecanismos de Seguridad y Criptografía

### 9.1. Hashing de Contraseñas
Se prohíbe el uso de algoritmos obsoletos (MD5, SHA-1). Todas las credenciales se procesan mediante `password_hash($password, PASSWORD_BCRYPT, ['cost' => 12])` y se validan mediante `password_verify()`.

### 9.2. Flujo Criptográfico de Restablecimiento de Contraseñas
Para mitigar ataques de enumeración y robo de enlaces temporales:
1. El usuario solicita el cambio en `recuperar-password.php`.
2. El sistema genera 32 bytes de entropía criptográficamente segura:
   ```php
   $rawToken = bin2hex(random_bytes(32)); // 64 caracteres hexadecimales
   $tokenHash = hash('sha256', $rawToken); // Hash almacenado en BD
   ```
3. Se almacena `$tokenHash` en la tabla `password_resets` con una caducidad de `NOW() + INTERVAL 1 HOUR`.
4. El enlace enviado al correo contiene únicamente el token plano:
   `https://alzate.edu.co/index.php?page=reset-password&token=<rawToken>`
5. Cuando el usuario accede a la vista de reseteo, el backend calcula el hash del token recibido y consulta la base de datos comparando hashes SHA-256.
6. Tras aplicar la nueva contraseña, el registro en `password_resets` es eliminado inmediatamente (uniuso estricto).

### 9.3. Protección contra Inyecciones SQL (SQLi)
Todas las consultas con parámetros dinámicos se ejecutan mediante declaraciones preparadas con PDO:
```php
$stmt = $pdo->prepare("SELECT id, password FROM usuarios WHERE email = :email LIMIT 1");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
```

### 9.4. Mitigación de Cross-Site Scripting (XSS)
Toda salida de datos suministrada por el usuario se escapa en la vista mediante la función auxiliar:
```php
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
```

### 9.5. Protección CSRF
Las vistas con formularios incluyen un token uniuso en un campo oculto:
```html
<input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
```
El controlador valida la correspondencia con `$_SESSION['csrf_token']` antes de procesar cualquier mutación en base de datos.

---

## 10. Especificación de la API REST / JSON

Los endpoints situados en el directorio `api/` permiten operaciones asíncronas desde el cliente mediante `fetch()`. Todos retornan cabeceras `Content-Type: application/json; charset=UTF-8` y estructuras semánticas uniformes:

```json
{
  "success": true,
  "message": "Mensaje descriptivo del resultado",
  "data": {}
}
```

### 10.1. `POST /api/conectar-egresado.php`
Envía una solicitud de conexión a otro egresado.
* **Headers:** `Content-Type: application/json`, `X-Requested-With: XMLHttpRequest`
* **Cuerpo de la Petición:**
  ```json
  {
    "receptor_id": 14,
    "csrf_token": "a8f3b..."
  }
  ```
* **Códigos HTTP:** `200 OK`, `400 Bad Request`, `401 Unauthorized`, `409 Conflict`.

### 10.2. `POST /api/responder-solicitud.php`
Acepta o rechaza una solicitud de contacto recibida.
* **Cuerpo de la Petición:**
  ```json
  {
    "solicitud_id": 8,
    "accion": "aceptar", // O "rechazar"
    "csrf_token": "a8f3b..."
  }
  ```
* **Códigos HTTP:** `200 OK`, `400 Bad Request`, `403 Forbidden`.

### 10.3. `POST /api/enviar-mensaje.php`
Registra un nuevo mensaje en una conversación privada entre egresados conectados.
* **Cuerpo de la Petición:**
  ```json
  {
    "destinatario_id": 14,
    "mensaje": "Hola, ¿cómo estás? Te contacto por el proyecto colaborativo.",
    "csrf_token": "a8f3b..."
  }
  ```
* **Códigos HTTP:** `200 OK`, `400 Bad Request`, `403 Forbidden` (si no están conectados).

### 10.4. `GET /api/obtener-mensajes.php`
Obtiene el historial cronológico de mensajes intercambiados con un contacto.
* **Parámetros URL:** `?contacto_id=14`
* **Respuesta Exitosa:**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 102,
        "remitente_id": 5,
        "mensaje": "Hola, ¿cómo estás?",
        "fecha": "2026-10-17 15:30:00",
        "es_propio": true
      }
    ]
  }
  ```

---

## 11. Frontend, Estilos Modulares y JavaScript

### 11.1. Design System y Paleta de Colores
Los estilos visuales se apoyan en variables CSS declaradas en `assets/css/main.css`:

```css
:root {
  --color-primary: #D32F2F;        /* Rojo Institucional Alzate */
  --color-primary-dark: #B71C1C;
  --color-primary-light: #FFCDD2;
  --color-secondary: #1976D2;      /* Azul Institucional */
  --color-secondary-dark: #0D47A1;
  --color-neutral-dark: #212121;
  --color-neutral-medium: #757575;
  --color-neutral-light: #F5F5F5;
  --color-success: #2E7D32;
  --color-warning: #ED6C02;
  --color-error: #D32F2F;
  --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
```

### 11.2. Tipografía Local
Para garantizar privacidad, cumplimiento normativo y eliminar tiempos de latencia por conexión a servidores de Google Fonts, la tipografía **Inter** se aloja localmente en `assets/fonts/` y se enlaza mediante `assets/css/fonts.css`.

### 11.3. Componentes JavaScript Interactivos
* **Dropzone de Archivos:** Implementado en `assets/js/pqrs.js` para arrastrar y soltar archivos adjuntos (PDF, JPG, PNG) con validación reactiva de tamaño máximo (5 MB) y tipo MIME.
* **Sistema de Notificaciones Toast:** Notificaciones flotantes no bloqueantes que informan al usuario sobre el éxito o fallo de peticiones asíncronas sin necesidad de recargar la página.
* **Modales de Diálogo:** Ventanas emergentes accesibles para confirmación de acciones destructivas (eliminar publicación, rechazar solicitud).

---

## 12. Mantenimiento, Diagnóstico y Solución de Problemas

### Problema 1: "Error 500 al enviar correos o recuperar contraseña"
* **Causa Frecuente:** Credenciales SMTP inválidas en `config/mail.php` o bloqueo de puerto 587 por parte del cortafuegos local.
* **Solución:**
  1. Verificar que en `config/mail.php` el campo `'debug'` se establezca en `2`.
  2. Confirmar que la contraseña de Gmail sea una *Contraseña de Aplicación* de 16 letras y no la clave personal de la cuenta.
  3. Asegurar que la extensión `php_openssl` esté habilitada en `php.ini`.

### Problema 2: "Error PDO: SQLSTATE[HY000] [1049] Unknown database 'raices_alzate'"
* **Causa:** La base de datos aún no ha sido aprovisionada en el servidor MySQL.
* **Solución:** Ejecutar en terminal `php database/setup.php` o abrir `http://localhost/Raices-Alzate/database/setup.php` en el navegador.

### Problema 3: "Error 404 al navegar por subpáginas"
* **Causa:** El Front Controller no está recibiendo el parámetro `page` o el servidor web no redirige adecuadamente hacia `index.php`.
* **Solución:** Comprobar que los enlaces utilicen el helper canónico `url('index.php?page=nombre')` o verificar que el módulo `mod_rewrite` de Apache esté activado con directiva `AllowOverride All`.

---

## 13. Control de Cambios del Documento

| Versión | Fecha | Autor / Equipo | Descripción de Cambios |
| :--- | :--- | :--- | :--- |
| `v0.1.0` | 2025 | Equipo Raíces Alzate | Estructuración inicial del alcance técnico de la plataforma. |
| `v0.2.0` | 13 de octubre de 2026 | Equipo Raíces Alzate | Definición del esquema DDL de base de datos relacional y Front Controller. |
| `v0.2.1` | 17 de octubre de 2026 | Equipo Raíces Alzate | Elaboración y publicación oficial del Manual Técnico integral: 11 tablas relacionales, script `setup.php`, integración PHPMailer con SMTP seguro, endpoints REST/JSON y seguridad criptográfica. |
