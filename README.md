# 🌱 Raíces Alzate

> Plataforma digital para el seguimiento, acompañamiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 📖 Descripción

**Raíces Alzate** es una plataforma web desarrollada con el propósito de fortalecer el vínculo entre la Institución Educativa Gilberto Alzate Avendaño y su comunidad de egresados.

El proyecto busca centralizar información académica, profesional y de contacto de los exalumnos, facilitando el seguimiento institucional, la creación de oportunidades de networking y la consolidación de una comunidad activa que impulse el crecimiento académico, profesional y personal de sus integrantes.

La plataforma también contempla la publicación de proyectos y noticias, la gestión de perfiles, la consulta de egresados y diferentes funciones administrativas, buscando fortalecer el sentido de pertenencia con la institución mediante herramientas digitales organizadas y de fácil acceso.

---

# 🎯 Objetivo

Desarrollar una plataforma web que permita gestionar la información de los egresados, fortalecer la comunicación entre la institución y sus exalumnos y generar oportunidades de desarrollo académico, profesional y colaborativo para toda la comunidad Alzate.

---

# ✨ Características

* Gestión de información de egresados.
* Registro y actualización de perfiles.
* Gestión de información personal y profesional.
* Consulta y búsqueda de egresados.
* Filtrado de información.
* Administración de usuarios.
* Gestión de proyectos.
* Publicación y gestión de noticias.
* Sistema de autenticación.
* Gestión de roles y permisos.
* Dashboard para los usuarios.
* Arquitectura modular.
* Design System desarrollado en Figma.
* Componentes PHP reutilizables.
* Interfaces Desktop y adaptaciones Mobile diseñadas.
* Implementación responsive.
* CSS modularizado.
* Documentación técnica organizada.
* Desarrollo basado en buenas prácticas.

---

# 🚀 Tecnologías

| Tecnología         | Uso                          |
| ------------------ | ---------------------------- |
| HTML5              | Estructura de la aplicación  |
| CSS3               | Diseño y estilos responsive  |
| JavaScript         | Interactividad               |
| PHP                | Lógica del servidor          |
| MySQL              | Base de datos                |
| Git                | Control de versiones         |
| GitHub             | Gestión del repositorio      |
| Figma              | Diseño UI/UX y Design System |
| Visual Studio Code | Desarrollo del proyecto      |
| XAMPP              | Entorno de desarrollo local  |

Actualmente no se utiliza ningún framework frontend. La implementación se realiza con **PHP, HTML, CSS y JavaScript vanilla**, manteniendo una estructura modular y reutilizable.

---

# 📂 Estructura del proyecto

```text
raices-alzate/

├── .github/
├── admin/
├── assets/
│   ├── css/
│   ├── js/
│   ├── img/
│   ├── icons/
│   ├── fonts/
│   └── videos/
├── components/
├── config/
├── database/
├── docs/
├── includes/
├── pages/
├── usuario/
├── uploads/
├── .gitignore
├── README.md
└── index.php
```

La estructura del proyecto está organizada de forma modular para facilitar el mantenimiento, la escalabilidad y el trabajo colaborativo durante las diferentes fases del desarrollo.

La estructura frontend actual incluye componentes PHP reutilizables y una organización modular de estilos:

```text
components/
├── header.php
└── footer.php

includes/
├── header.php
└── footer.php

assets/css/
├── main.css
├── fonts.css
├── header.css
└── footer.css
```

---

# 📚 Documentación

Toda la documentación principal se encuentra organizada dentro de la carpeta **`docs/`**.

| Documento                   | Descripción                                     |
| --------------------------- | ----------------------------------------------- |
| `docs/README.md`            | Índice y guía de la documentación.              |
| `docs/01_Bitacora.md`       | Registro histórico del desarrollo del proyecto. |
| `docs/02_Planificacion.md`  | Planificación general del proyecto.             |
| `docs/03_Requisitos.md`     | Requisitos funcionales y no funcionales.        |
| `docs/04_Arquitectura.md`   | Arquitectura general del sistema.               |
| `docs/05_Manual_Tecnico.md` | Manual dirigido a desarrolladores.              |
| `docs/06_Manual_Usuario.md` | Manual de uso de la plataforma.                 |
| `CHANGELOG.md`              | Historial de cambios y versiones.               |
| `ROADMAP.md`                | Hoja de ruta del proyecto.                      |
| `decisiones.md`             | Registro de decisiones importantes.             |
| `glosario.md`               | Glosario de términos del proyecto.              |

La documentación se mantiene actualizada durante el ciclo de desarrollo para garantizar la trazabilidad del proyecto y facilitar su mantenimiento.

---

# 🚧 Estado del proyecto

**Versión actual:** `v0.1.1`

**Estado:** 🟡 En desarrollo

**Fase actual:** **Fase 4 — Implementación**

Raíces Alzate se encuentra actualmente en la etapa de implementación, después de haber completado las fases de planificación, documentación y diseño UI/UX.

La etapa de Diseño UI/UX dejó definidas las principales estructuras visuales, componentes, estados, criterios responsive, navegación e interfaces que sirven como referencia para el desarrollo frontend.

La implementación frontend ya comenzó con la interfaz **Inicio**, incluyendo su estructura Desktop y adaptación Mobile, además de la creación de componentes PHP reutilizables y una estructura CSS modular.

La conexión entre PHP y MySQL también fue verificada correctamente. La estructura definitiva de tablas y relaciones de la base de datos se definirá posteriormente de acuerdo con las necesidades reales de las interfaces implementadas.

---

# 📊 Estado del proyecto

| Etapa                  | Progreso                      |
| ---------------------- | ----------------------------- |
| 📋 Planificación       | 🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩 **100%** |
| 📚 Documentación       | 🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩 **100%** |
| 🎨 Diseño UI/UX        | 🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩 **100%** |
| 💻 Desarrollo Frontend | 🟩🟩⬜⬜⬜⬜⬜⬜⬜⬜ **20%**          |
| ⚙️ Desarrollo Backend  | ⬜⬜⬜⬜⬜⬜⬜⬜⬜⬜ **0%**             |
| 🗄️ Base de datos      | 🟩⬜⬜⬜⬜⬜⬜⬜⬜⬜ **10%**           |
| 🧪 Pruebas             | ⬜⬜⬜⬜⬜⬜⬜⬜⬜⬜ **0%**             |
| 🚀 Despliegue          | ⬜⬜⬜⬜⬜⬜⬜⬜⬜⬜ **0%**             |

> Los porcentajes representan una estimación del estado de cada etapa general del proyecto y no corresponden directamente al porcentaje de funcionalidades individuales implementadas.

> La base de datos se encuentra en una etapa inicial debido a que la conexión PHP–MySQL ya fue verificada, pero las tablas, relaciones y estructura funcional todavía están pendientes.

---

# 🗺️ Roadmap

## ✅ Fase 0 — Preparación

* Configuración del repositorio.
* Configuración de Git y GitHub.
* Organización de la estructura del proyecto.
* Creación de la documentación base.

**Estado:** Completada.

---

## ✅ Fase 1 — Planificación

* Definición del objetivo.
* Definición del alcance.
* Identificación de funcionalidades.
* Definición de tecnologías.
* Metodología de trabajo.
* Planificación general.

**Estado:** Completada.

---

## ✅ Fase 2 — Documentación

* Bitácora.
* Planificación.
* Requisitos.
* Arquitectura.
* Roadmap.
* README.
* Changelog.
* Decisiones.
* Glosario.
* Índice de documentación.

Los manuales Técnico y de Usuario permanecerán pendientes hasta las etapas posteriores del desarrollo.

**Estado:** Completada.

---

## ✅ Fase 3 — Diseño UI/UX

### Design System

* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Botones.
* Inputs.
* Checkbox.
* Radio Button.
* Switch.
* Tablas.
* Estados de componentes.
* Criterios responsive.
* Components & Responsive.

### Estructura y navegación

* Flujo general de información.
* Recorridos principales.
* Estructura de navegación.
* Relación entre módulos.

### Interfaces Desktop

Se consolidaron las principales interfaces:

* Inicio.
* Autenticación.
* Dashboard.
* Perfil.
* Egresados.
* Proyectos.
* Noticias.
* Administración.
* Crear proyecto.
* Detalle de proyecto.
* Editar perfil.
* Recuperar contraseña.
* Registro.
* Editar proyecto.
* Crear noticia.
* Detalle de noticia.
* Editar noticia.

### Adaptaciones Mobile

Se trabajaron adaptaciones para:

* Inicio.
* Autenticación.
* Dashboard.
* Mi perfil.
* Mis proyectos.
* Oportunidades.

**Estado:** Completada.

---

## 🔄 Fase 4 — Implementación

Actualmente en desarrollo.

### Frontend

**Completado inicialmente:**

* Estructura HTML/PHP base.
* Componentes PHP reutilizables.
* Header.
* Footer.
* Navegación responsive.
* Menú mobile.
* Isotipo institucional.
* Favicon.
* Sistema base de estilos.
* Modularización de CSS.
* Tipografía institucional Inter.
* Interfaz Inicio Desktop.
* Adaptación Inicio Mobile.

**Pendiente:**

* Revisión visual de Inicio frente a Figma.
* Implementación de las demás interfaces.
* Interacciones específicas.
* Integración con backend.
* Integración con base de datos.

### Backend

* PHP.
* Autenticación.
* Registro.
* Gestión de usuarios.
* Perfiles.
* Egresados.
* Proyectos.
* Noticias.
* Roles y permisos.
* Validaciones.

### Base de datos

* Diseño de la estructura.
* Tablas.
* Relaciones.
* Claves primarias y foráneas.
* Consultas.
* Integración con PHP.

La conexión PHP–MySQL ya fue verificada correctamente. La estructura funcional de la base de datos se definirá posteriormente con base en las necesidades reales de las interfaces.

### Integración

* Frontend.
* Backend.
* Base de datos.
* Módulos.
* Autenticación.
* Permisos.

**Estado:** 🟡 En progreso.

---

## ⏳ Fase 5 — Pruebas

* Pruebas funcionales.
* Pruebas de integración.
* Pruebas de interfaz.
* Pruebas responsive.
* Pruebas de compatibilidad.
* Validación de seguridad.
* Corrección de errores.
* Optimización.
* Validación final.

**Estado:** Pendiente.

---

## ⏳ Fase 6 — Despliegue y Cierre

* Actualización final de documentación.
* Manual Técnico.
* Manual de Usuario.
* Preparación de versión estable.
* Configuración de producción.
* Publicación.
* Verificación en producción.
* Revisión final.
* Cierre del proyecto.

**Estado:** Pendiente.

---

# ⚙️ Instalación

## Requisitos previos

Para la etapa actual de implementación se deberá contar con:

* PHP 8.0 o superior.
* MySQL 8.0 o superior.
* Servidor local compatible con PHP, como XAMPP, Laragon, WAMP o similar.
* Git.
* Visual Studio Code, recomendado.

> La configuración definitiva de la base de datos se realizará durante la etapa de implementación.

---

## Clonar el repositorio

```bash
git clone https://github.com/Zetogan/Raices-Alzate.git
```

---

## Ingresar al proyecto

```bash
cd Raices-Alzate
```

---

## Configurar el entorno

1. Configurar un servidor local compatible con PHP.
2. Ubicar el proyecto dentro del directorio correspondiente del servidor local.
3. Iniciar Apache y MySQL desde el entorno local.
4. Configurar los parámetros de conexión a MySQL.
5. Abrir el proyecto desde el navegador.

La información definitiva de instalación y configuración será ampliada en el **Manual Técnico** a medida que avance la implementación.

---

# ▶️ Uso

Actualmente el proyecto se encuentra en desarrollo.

La implementación de la plataforma contempla progresivamente funcionalidades como:

* Registro de usuarios.
* Inicio de sesión.
* Recuperación de contraseña.
* Gestión de perfiles.
* Consulta de egresados.
* Búsqueda y filtrado de egresados.
* Gestión de proyectos.
* Gestión de noticias.
* Administración de usuarios.
* Gestión de permisos.
* Panel administrativo.

Actualmente se encuentra implementada inicialmente la interfaz **Inicio**, junto con la estructura base de navegación, Header, Footer y comportamiento responsive.

Las demás funcionalidades estarán disponibles progresivamente a medida que avance la implementación.

---

# 📅 Próximos objetivos

Las siguientes actividades corresponden a la **Fase 4 — Implementación**.

## Desarrollo Frontend

* Revisar visualmente la interfaz Inicio frente al diseño aprobado en Figma.
* Implementar las demás interfaces definidas en Figma.
* Crear los componentes reutilizables necesarios.
* Mantener los estilos establecidos en el Design System.
* Implementar el comportamiento responsive.
* Desarrollar las interacciones necesarias.

## Desarrollo Backend

* Configurar la lógica PHP.
* Mantener la conexión con MySQL.
* Implementar autenticación.
* Implementar gestión de usuarios.
* Implementar perfiles.
* Implementar egresados.
* Implementar proyectos.
* Implementar noticias.
* Implementar roles y permisos.

## Base de datos

* Diseñar las tablas.
* Establecer relaciones.
* Definir claves primarias y foráneas.
* Crear consultas.
* Integrar la base de datos con el backend.

## Posteriormente

* Integración completa.
* Pruebas funcionales.
* Pruebas de compatibilidad.
* Validación de seguridad.
* Corrección de errores.
* Optimización.
* Preparación del despliegue.

---

# 👥 Equipo de desarrollo

Proyecto desarrollado por estudiantes de la **Institución Educativa Gilberto Alzate Avendaño**.

## Integrantes

* Emanuel Cadavid
* Harold Montoya
* Santiago Delgado Martínez
* Santiago Sánchez Emitola

---

# 🤝 Contribuciones

Actualmente el desarrollo del proyecto está a cargo del equipo responsable de **Raíces Alzate**.

En futuras versiones se podrá establecer una guía de contribución que incluya:

* Flujo de trabajo con Git.
* Convenciones de código.
* Estándares de documentación.
* Proceso para reportar errores.
* Proceso para proponer nuevas funcionalidades.

---

# 📄 Licencia

Este proyecto se distribuye bajo la licencia **MIT**.

Consulta el archivo `LICENSE` para obtener información detallada sobre los términos de uso y distribución del proyecto.

---

# 💙 Agradecimientos

Agradecemos a la **Institución Educativa Gilberto Alzate Avendaño**, a los docentes, directivos y a todas las personas que hacen posible el desarrollo de este proyecto.

Su acompañamiento fortalece la construcción de una plataforma que busca mantener un vínculo permanente entre la institución y su comunidad de egresados.

---

<div align="center">

# 🌱 Raíces Alzate

### Conectando generaciones, fortaleciendo el futuro.

**Versión actual:** v0.1.1

Desarrollado con ❤️ por el equipo de **Raíces Alzate**.

</div>
