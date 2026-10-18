# 📚 Documentación del Proyecto — Raíces Alzate

> Índice general y guía de navegación de la documentación técnica del proyecto.

---

## 🌱 Información general

| Campo                  | Información                                                                                    |
| ---------------------- | ---------------------------------------------------------------------------------------------- |
| Proyecto               | Raíces Alzate                                                                                  |
| Institución            | Institución Educativa Gilberto Alzate Avendaño                                                 |
| Tipo de proyecto       | Plataforma digital para la gestión, seguimiento y fortalecimiento de la comunidad de egresados |
| Inicio del proyecto    | 2025                                                                                           |
| Versión actual         | v0.2.1                                                                                         |
| Estado                 | 🟡 En desarrollo                                                                               |
| Fase actual            | Fase 4 — Implementación e integración funcional                                                |
| Última actualización   | 17 de octubre de 2026                                                                          |
| Responsable documental | Santiago Emitola                                                                               |

---

# 📖 Sobre esta documentación

Este directorio contiene la documentación del proyecto **Raíces Alzate**, en la que se registran la planificación, los requisitos, la arquitectura, las decisiones técnicas, la evolución y los avances de desarrollo de la plataforma.

Su objetivo es mantener organizada la información del proyecto, facilitar la comprensión de su estructura y proporcionar una referencia para las distintas etapas de implementación, integración, pruebas y mantenimiento.

Actualmente, el proyecto se encuentra en la **Fase 4 — Implementación e integración funcional**, alcanzando la versión **v0.2.1** al 17 de octubre de 2026.

Las etapas de planificación y diseño UI/UX están consolidadas (34 pantallas diseñadas en Figma). La implementación técnica dispone de la arquitectura relacional formalizada en MySQL (`database/schema.sql` con 11 tablas) y su aprovisionador `database/setup.php`, el flujo seguro de recuperación de contraseñas con PHPMailer, SMTP y tokens criptográficos SHA-256 temporales, vistas centralizadas bajo `pages/` (egresados, proyectos, noticias, perfil, PQRS, administración, mensajería) y endpoints API asíncronos en `api/`.

> La existencia de un diseño, una estructura de código o un procesamiento inicial no implica que una funcionalidad esté terminada. Los módulos deben validarse mediante las pruebas correspondientes antes de considerarse completos.

---

# 📂 Estructura de la documentación

La documentación se organiza en archivos ubicados dentro de `docs/` y documentos complementarios situados en la raíz del repositorio.

```text
raices-alzate/
│
├── README.md
└── docs/
    ├── README.md
    ├── bitacora.md
    ├── planificacion.md
    ├── requisitos.md
    ├── arquitectura.md
    ├── manual-tecnico.md
    ├── manual-usuario.md
    ├── CHANGELOG.md
    ├── ROADMAP.md
    ├── decisiones.md
    └── glosario.md
```

> Esta estructura representa la organización documental prevista. La ubicación y el nombre exactos de cada archivo deben mantenerse sincronizados con los existentes en el repositorio.

El archivo `docs/README.md` funciona como índice de la documentación técnica. El `README.md` ubicado en la raíz presenta el proyecto de manera general.

---

# 📑 Índice de documentos

## 📝 [`01_Bitacora.md`](01_Bitacora.md)

**Registro histórico del desarrollo del proyecto.**

Contiene:

* Avances realizados.
* Fechas y sesiones de trabajo.
* Actividades desarrolladas.
* Cambios relevantes.
* Evolución del proyecto.
* Progreso del diseño UI/UX.
* Avances de implementación.
* Problemas identificados y actividades pendientes.

**Estado:** 🟡 Actualizada según los avances documentados; debe seguir incorporando las nuevas actividades relevantes.

---

## 📋 [`02_Planificacion.md`](02_Planificacion.md)

**Organización general del trabajo y gestión del proyecto.**

Incluye:

* Descripción del proyecto.
* Objetivos generales y específicos.
* Alcance.
* Tecnologías.
* Metodología.
* Fases del proyecto.
* Estado del desarrollo.
* Plan de trabajo.
* Entregables.
* Criterios de finalización.
* Avances y actividades pendientes.

**Estado:** 🟡 Documento de planificación actualizado; sujeto a revisiones conforme evolucione la implementación.

---

## 📌 [`03_Requisitos.md`](03_Requisitos.md)

**Definición de las necesidades y funcionalidades del sistema.**

Incluye:

* Requisitos funcionales.
* Requisitos no funcionales.
* Requisitos de seguridad.
* Rendimiento y compatibilidad.
* Diseño y experiencia de usuario.
* Sistema de diseño.
* Criterios responsive.
* Interfaces diseñadas.
* Criterios de aceptación.
* Estado de implementación de los requisitos.

**Estado:** 🟡 Documento de referencia para el desarrollo y la validación de funcionalidades.

> Los requisitos describen las necesidades del sistema. Su documentación no implica que todas las funcionalidades correspondientes estén implementadas o verificadas.

---

## 🏗️ [`04_Arquitectura.md`](04_Arquitectura.md)

**Descripción técnica de la estructura del sistema.**

Incluye:

* Arquitectura general.
* Organización del sistema.
* Tecnologías utilizadas.
* Estructura del proyecto.
* Separación de responsabilidades.
* Módulos principales.
* Relación entre diseño e implementación.
* Estructura del frontend.
* Organización de los estilos CSS.
* Componentes PHP reutilizables.
* Integración con la base de datos.
* Consideraciones técnicas de seguridad y mantenimiento.

**Estado:** 🟡 Documento de referencia técnica que debe mantenerse sincronizado con la arquitectura realmente implementada.

---

## ⚙️ [`manual-tecnico.md`](manual-tecnico.md)

**Guía técnica destinada a desarrolladores y responsables del mantenimiento.**

Incluye:

* Requisitos del entorno (Hardware, Software, extensiones PHP).
* Arquitectura en capas y patrones de diseño (Front Controller, Singleton PDO).
* Estructura completa de archivos y responsabilidades.
* Modelo de datos relacional y catálogo de las 11 tablas con DDL e índices.
* Instalación y migración automatizada con `database/setup.php`.
* Servicio de correo electrónico con PHPMailer y SMTP autenticado.
* Mecanismos de seguridad (BCRYPT, tokens criptográficos uniuso, CSRF, Prepared Statements, XSS).
* Especificación completa de los endpoints REST/JSON (`api/`).
* Frontend, estilos modulares y componentes interactivos JavaScript.
* Guía de mantenimiento y solución de problemas comunes.

**Estado:** 🟢 Elaborado y disponible (`v0.2.1`). Documentación técnica oficial del sistema.

---

## 👤 [`manual-usuario.md`](manual-usuario.md)

**Guía destinada a los usuarios finales de la plataforma (egresados, docentes, directivos y administradores).**

Incluye:

* Bienvenida y propósito institucional.
* Requisitos de acceso y compatibilidad multidispositivo.
* Navegación por el portal público y menús responsive.
* Registro de nuevos egresados paso a paso.
* Inicio y cierre de sesión seguro.
* Flujo guiado de recuperación de contraseña por correo electrónico institucional.
* Estructura y widgets del Dashboard personal.
* Gestión y edición integral del perfil (académico, laboral y habilidades).
* Directorio de egresados, búsqueda, filtros y solicitudes de conexión.
* Sistema de mensajería privada y chat entre contactos.
* Catálogo y publicación de proyectos comunitarios.
* Consulta de noticias y eventos institucionales.
* Módulo de PQRS con adjuntos interactivos (dropzone).
* Panel de administración institucional (gestión de usuarios, moderación y PQRS).
* Preguntas frecuentes (FAQ) y resolución de dudas comunes.

**Estado:** 🟢 Elaborado y disponible (`v0.2.1`). Guía oficial de navegación y uso de la plataforma.

---

# 📚 Documentos complementarios

Los siguientes archivos complementan la documentación de `docs/` y se encuentran previstos en la raíz del repositorio.

## 🗺️ [`ROADMAP.md`](ROADMAP.md)

**Plan de evolución y seguimiento de las fases del proyecto.**

Incluye:

* Fases del proyecto.
* Actividades completadas.
* Actividades en desarrollo.
* Tareas pendientes.
* Próximos objetivos.
* Estado general del proyecto.

**Estado:** 🟡 Actualizado al 17 de octubre de 2026 (v0.2.1).

---

## ⚖️ [`decisiones.md`](decisiones.md)

**Registro de decisiones importantes tomadas durante el desarrollo.**

Incluye:

* Decisiones técnicas y de arquitectura (ADR DEC-001 a DEC-037).
* Decisiones de diseño y usabilidad.
* Motivos y justificaciones.
* Alternativas consideradas.
* Criterios adoptados.
* Evolución de las decisiones del proyecto.

**Estado:** 📌 Documento de referencia con 37 decisiones registradas.

---

## 📖 [`glosario.md`](glosario.md)

**Definición de los términos utilizados durante el desarrollo.**

Incluye conceptos relacionados con:

* Proyecto e identidad institucional.
* Desarrollo web y arquitectura modular.
* Diseño UI/UX y Design System.
* Componentes reutilizables y estados de interacción.
* Criterios responsive.
* Tecnologías (PHP, PDO, PHPMailer, MySQL).
* Criptografía y seguridad (Tokens SHA-256, BCRYPT, CSRF).
* Bases de datos relacionales y semillas (`setup.php`).
* Git y GitHub.

**Estado:** 📌 Documento de referencia terminológica consolidado.

---

## 📝 [`CHANGELOG.md`](CHANGELOG.md)

**Registro de cambios y evolución de las versiones del proyecto.**

Permite consultar:

* Nuevas funcionalidades.
* Cambios realizados.
* Correcciones.
* Mejoras.
* Evolución del diseño.
* Actualizaciones importantes.
* Cambios entre versiones (v0.1.0 hasta v0.2.1).

**Estado:** 📌 Mantenido al día con la versión v0.2.1.

---

# 📚 Orden de lectura recomendado

Para comprender el proyecto de manera progresiva, se recomienda el siguiente orden:

### 1. Introducción y contexto

📄 [`README.md`](../README.md)

Presenta la descripción general, los objetivos, las tecnologías principales y el estado del proyecto.

### 2. Organización y planificación

📄 [`02_Planificacion.md`](02_Planificacion.md)

Explica la organización del proyecto, sus objetivos, las fases, la metodología y el plan de trabajo.

### 3. Requisitos del sistema

📄 [`03_Requisitos.md`](03_Requisitos.md)

Describe las funcionalidades esperadas, los requisitos no funcionales y los criterios de aceptación.

### 4. Arquitectura

📄 [`04_Arquitectura.md`](04_Arquitectura.md)

Explica la organización técnica, las tecnologías, los módulos y la estructura del sistema.

### 5. Historial del desarrollo

📄 [`01_Bitacora.md`](01_Bitacora.md)

Permite consultar las actividades realizadas, los avances, las sesiones de trabajo y los cambios importantes.

### 6. Decisiones del proyecto

📄 [`decisiones.md`](../decisiones.md)

Documenta las decisiones relevantes y sus justificaciones.

### 7. Historial de cambios

📄 [`CHANGELOG.md`](../CHANGELOG.md)

Permite revisar la evolución de las versiones y los cambios registrados.

### 8. Roadmap

📄 [`ROADMAP.md`](../ROADMAP.md)

Presenta las fases, las actividades pendientes y los objetivos futuros.

### 9. Manual Técnico

📄 [`manual-tecnico.md`](manual-tecnico.md)

Documento destinado a la instalación, configuración, arquitectura técnica, endpoints REST, seguridad y mantenimiento de la plataforma.

**Estado:** 🟢 Elaborado y disponible (`v0.2.1`).

### 10. Manual de Usuario

📄 [`manual-usuario.md`](manual-usuario.md)

Documento destinado a explicar el uso integral de la plataforma a egresados y administradores.

**Estado:** 🟢 Elaborado y disponible (`v0.2.1`).

### 11. Glosario

📄 [`glosario.md`](glosario.md)

Permite consultar los términos técnicos y conceptuales utilizados en el proyecto.

---

# 🧩 Relación entre los documentos

La documentación se relaciona de forma progresiva para facilitar la trazabilidad entre las necesidades del sistema y su implementación.

```text
Planificación
     │
     ▼
Requisitos
     │
     ▼
Arquitectura
     │
     ▼
Diseño UI/UX
     │
     ▼
Implementación
     │
     ├── Frontend
     ├── Backend
     └── Base de datos
             │
             ▼
         Integración
             │
             ▼
           Pruebas
             │
             ▼
     Documentación final
             │
             ▼
         Despliegue
```

La Bitácora, el `CHANGELOG.md` y `decisiones.md` acompañan transversalmente las diferentes etapas del proyecto.

El glosario proporciona definiciones comunes para mantener una terminología coherente entre los documentos.

El diseño UI/UX ya está cerrado. Por lo tanto, en la etapa actual, Figma se utiliza como referencia para implementar y validar las interfaces, no como una fase de diseño todavía abierta.

---

# 🔄 Flujo de actualización documental

Cada cambio importante del proyecto debe reflejarse en los documentos correspondientes.

El flujo recomendado es:

```text
Cambio importante
       │
       ▼
Actualizar el documento relacionado
       │
       ▼
Registrar el cambio en CHANGELOG.md
       │
       ▼
Actualizar la Bitácora si corresponde
       │
       ▼
Revisar documentos relacionados
       │
       ▼
Verificar la coherencia documental
       │
       ▼
Realizar el commit en Git
```

No todos los cambios requieren modificar todos los documentos. Las actualizaciones deben realizarse según su alcance e impacto.

Cuando un cambio modifique la arquitectura, los requisitos, las rutas o la estructura de archivos, también deberán revisarse las referencias relacionadas.

---

# 📌 Buenas prácticas de documentación

Para mantener la calidad y la coherencia documental se aplican las siguientes prácticas:

* Mantener los documentos actualizados.
* Registrar las decisiones relevantes.
* Utilizar nombres claros y consistentes para los archivos.
* Mantener la documentación alineada con el diseño y el código.
* Actualizar el `CHANGELOG.md` después de cambios importantes.
* Registrar los avances relevantes en la Bitácora.
* Revisar los documentos relacionados cuando cambie la arquitectura.
* Utilizar Git para controlar las modificaciones.
* Evitar información desactualizada sobre el estado real del proyecto.
* Distinguir las funcionalidades planificadas de las implementadas.
* No declarar una funcionalidad como terminada sin verificar su funcionamiento.
* Mantener las referencias y los enlaces internos actualizados.
* Verificar los nombres y las rutas de los archivos antes de documentarlos como definitivos.
* No registrar datos simulados como información real de la aplicación.
* Separar las herramientas temporales de desarrollo de las funcionalidades destinadas a producción.

---

# 🔗 Referencias cruzadas

| Documento         | Propósito                                                             |
| ----------------- | --------------------------------------------------------------------- |
| Bitácora          | Seguimiento histórico del desarrollo.                                 |
| Planificación     | Organización de las fases y actividades.                              |
| Requisitos        | Define qué debe cumplir el sistema.                                   |
| Arquitectura      | Describe cómo se organiza técnicamente el sistema.                    |
| Roadmap           | Registra la evolución prevista del proyecto.                          |
| Decisiones        | Documenta decisiones importantes y sus justificaciones.               |
| Glosario          | Define los términos utilizados.                                       |
| Changelog         | Registra los cambios y las versiones.                                 |
| Manual Técnico    | Explica la instalación, configuración y mantenimiento.                |
| Manual de Usuario | Explica el funcionamiento de la plataforma para los usuarios finales. |

---

# 📦 Control de versiones

La documentación utiliza **Git y GitHub** para controlar los cambios realizados durante el desarrollo.

Las actualizaciones importantes deben contemplar, según corresponda:

* Un mensaje de commit descriptivo.
* La actualización del `CHANGELOG.md`.
* La actualización de la Bitácora.
* La modificación de la versión documental cuando corresponda.
* La revisión de los documentos relacionados.
* La verificación de los enlaces internos y la estructura documental.

Ejemplo de commit:

```bash
git add docs/README.md
git commit -m "docs: actualizar indice de documentacion"
git push
```

El mensaje es un ejemplo de referencia; debe ajustarse a los archivos incluidos en cada commit.

---

# 📅 Control de cambios

| Versión | Fecha      | Descripción                                                                                                                                                                                                             | Autor            |
| ------- | ---------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------- |
| v0.1.0  | 09/07/2026 | Creación inicial del índice documental y organización de los documentos del proyecto. El proyecto había comenzado en 2025.                                                                                              | Santiago Emitola |
| v0.1.0  | 20/07/2026 | Actualización de la documentación y sincronización con los avances del diseño UI/UX.                                                                                                                                    | Santiago Emitola |
| v0.1.0  | 05/09/2026 | Actualización del índice documental según los avances del Design System, Components & Responsive, los estados de los componentes y la estructura de navegación.                                                         | Santiago Emitola |
| v0.1.1  | 07/10/2026 | Actualización general del índice, revisión de referencias documentales y consolidación del estado de la fase UI/UX y la transición a implementación.                                                                    | Santiago Emitola |
| v0.1.1  | 08/10/2026 | Actualización del índice según los avances iniciales del frontend, los estilos CSS modulares, los componentes PHP reutilizables y la navegación responsive.                                                             | Santiago Emitola |
| v0.1.1  | 09/10/2026 | Incorporación de los avances reportados en autenticación, registro y recuperación de contraseña; actualización de las referencias al estado actual del proyecto y sus actividades pendientes.                           | Santiago Emitola |
| v0.1.1  | 13/10/2026 | Actualización del estado general, consolidación de la referencia a las 34 pantallas diseñadas y registro de los avances reportados en Dashboard, navegación interna y tareas pendientes de implementación y validación. | Santiago Emitola |
| v0.2.1  | 17/10/2026 | Actualización a versión v0.2.1; formalización del esquema relacional en MySQL (11 tablas con setup.php), PHPMailer transaccional, flujo seguro de restablecimiento con tokens SHA-256 y unificación en pages/.          | Santiago Emitola |

> El proyecto comenzó en 2025. Las fechas de esta tabla corresponden a registros de creación y actualización documental, no a la fecha de inicio del proyecto.

---

# 🚀 Estado actual del proyecto

Raíces Alzate se encuentra en la **Fase 4 — Implementación e integración funcional** (v0.2.1).

## Avances registrados

* [x] Planificación del proyecto.
* [x] Definición de requisitos funcionales y no funcionales.
* [x] Documentación de la arquitectura inicial y técnica.
* [x] Consolidación de la documentación base (Bitácora, Planificación, Requisitos, Arquitectura, Decisiones, Glosario, Roadmap).
* [x] Definición de la identidad visual y paleta institucional (#D32F2F, #1565C0, #212121).
* [x] Tipografía Inter y sistema de espaciado.
* [x] Elaboración del Design System completo en Figma.
* [x] Diseño y refinamiento de 34 pantallas (17 Desktop y 17 Mobile).
* [x] Finalización formal de la Fase 3 — Diseño UI/UX.
* [x] Implementación frontend de Inicio (Desktop y Mobile).
* [x] Componentes PHP reutilizables (Header, Footer, Sidebar, Topbar, Loader, Mailer, Helpers, CSRF).
* [x] Arquitectura de estilos CSS modulares y responsivos.
* [x] Interfaces de autenticación: Login, Registro, Solicitud y Restablecimiento (`pages/reset-password.php`).
* [x] Hashing seguro de contraseñas mediante `password_hash(PASSWORD_DEFAULT)`.
* [x] Flujo de recuperación seguro con tokens criptográficos SHA-256 temporales (60 min) de un solo uso.
* [x] Integración de PHPMailer nativo con SMTP autenticado y plantilla HTML responsiva con logotipo embebido.
* [x] Directorio y detalle de egresados con filtros en tiempo real, botones condicionales y toast.
* [x] Módulo de Perfil y formulario modular de edición (`pages/editar-perfil.php`).
* [x] Catálogo y detalle de proyectos con cuadrícula 16:9 y paginación incremental asíncrona.
* [x] Módulo de noticias institucionales con selector de categorías temáticas.
* [x] Formulario de PQRS y soporte con dropzone para capturas e ícono SVG blindado en sidebar.
* [x] Módulo oficial de Administración (Figma 08) con KPI Cards y modales de moderación interactivos.
* [x] Módulo de mensajería y red de conexiones con endpoints API JSON (`api/`).
* [x] Esquema relacional estructurado en MySQL (`database/schema.sql` con 11 tablas).
* [x] Instalador y verificador automatizado `database/setup.php` (CLI y navegador).
* [x] Centralización de rutas y controladores bajo el directorio `pages/`.
* [x] Elaboración y publicación formal del Manual Técnico (`docs/manual-tecnico.md`) v0.2.1.
* [x] Elaboración y publicación formal del Manual de Usuario (`docs/manual-usuario.md`) v0.2.1.

## Etapa actual

* 🟡 Consolidación de la persistencia de datos mediante PDO en todos los módulos.
* 🟡 Pruebas de integración de extremo a extremo (Autenticación, Recuperación, Mensajería, PQRS, Administración).
* 🟡 Pruebas de compatibilidad responsive en las 34 pantallas de Figma.

## Próximos pasos

* Ejecutar pruebas integrales con bases de datos pobladas mediante `database/setup.php`.
* Validar el ciclo de envío de correos en entornos SMTP reales.
* Auditar la seguridad de roles y permisos en rutas administrativas.
* Preparar el proyecto para la Fase 5 (Pruebas sistemáticas) y Fase 6 (Despliegue y Cierre).

---

# 🌱 Raíces Alzate

**Construyendo una conexión permanente entre la institución y sus egresados.**

> Las raíces del pasado fortalecen los proyectos del futuro.
