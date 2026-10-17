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

* Gestión de información de egresados.
* Registro y actualización de perfiles.
* Gestión de información personal, académica y profesional.
* Consulta, búsqueda y filtrado de egresados.
* Administración de usuarios.
* Gestión de proyectos.
* Publicación y gestión de noticias.
* Sistema de autenticación.
* Gestión de roles y permisos.
* Dashboard para los usuarios.
* Recuperación de contraseña.
* Herramientas para fortalecer la comunicación y la colaboración entre los egresados.

### Características técnicas y de diseño

* Arquitectura modular basada en PHP.
* Componentes PHP reutilizables.
* HTML5 semántico.
* CSS modularizado.
* JavaScript vanilla.
* Integración con MySQL mediante PHP y PDO.
* Diseño UI/UX desarrollado en Figma.
* Design System y componentes reutilizables.
* 17 interfaces principales diseñadas para Desktop.
* 17 adaptaciones de las interfaces para Mobile.
* Criterios de diseño responsive.
* Documentación técnica organizada.
* Control de versiones mediante Git y GitHub.

> **Nota:** Las funcionalidades descritas representan el alcance previsto del sistema. Algunas cuentan con avances de implementación, mientras que otras continúan pendientes de desarrollo, integración o validación funcional.

---

## 🚀 Tecnologías

| Tecnología         | Uso                                                   |
| ------------------ | ----------------------------------------------------- |
| HTML5              | Estructura semántica de las interfaces.               |
| CSS3               | Diseño visual, estilos y adaptación responsive.       |
| JavaScript         | Interactividad y validaciones del lado del cliente.   |
| PHP                | Procesamiento del servidor y lógica de la aplicación. |
| MySQL              | Gestión y almacenamiento de datos.                    |
| PDO                | Acceso a la base de datos mediante PHP.               |
| Git                | Control de versiones.                                 |
| GitHub             | Alojamiento y gestión del repositorio.                |
| Figma              | Diseño UI/UX, prototipos y Design System.             |
| Visual Studio Code | Entorno de desarrollo.                                |
| XAMPP              | Entorno de desarrollo local.                          |

El frontend se desarrolla sin frameworks, utilizando **PHP, HTML, CSS y JavaScript vanilla**. La estructura se organiza de manera modular para facilitar el mantenimiento y la evolución del proyecto.

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
├── config/               # Configuración global y singleton PDO
│   ├── config.php        # Constantes, base URL dinámica y helpers de URLs
│   └── database.php      # Conexión PDO con reintentos y singleton getDBConnection()
├── database/             # Esquema SQL y scripts de migración
│   ├── schema.sql        # Esquema DDL con 11 tablas, FK, índices y semillas
│   └── setup.php         # Script instalador/migrador (CLI y navegador)
├── docs/                 # Documentación técnica y bitácora del proyecto
├── includes/             # Lógica modular, helpers comunes, auth, CSRF, layout
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
├── main.css
├── fonts.css
├── header.css
├── footer.css
├── auth.css
├── dashboard.css
├── loader.css
└── portal.css
```

La lista puede ampliarse conforme se implementen nuevas interfaces y componentes.

### Componentes y recursos compartidos

El proyecto utiliza componentes PHP reutilizables para elementos comunes de la interfaz, como el encabezado, el pie de página, la navegación lateral y la barra superior del dashboard.

Los archivos compartidos se organizan entre `components/` e `includes/`, según su responsabilidad dentro de la arquitectura.

---

## 📚 Documentación

La documentación del proyecto se encuentra organizada principalmente dentro de `docs/` y en archivos complementarios ubicados en la raíz del repositorio.

| Documento                                                | Descripción                                      |
| -------------------------------------------------------- | ------------------------------------------------ |
| [`docs/README.md`](docs/README.md)                       | Índice y guía de navegación de la documentación. |
| [`docs/01_Bitacora.md`](docs/01_Bitacora.md)             | Registro histórico del desarrollo.               |
| [`docs/02_Planificacion.md`](docs/02_Planificacion.md)   | Planificación general del proyecto.              |
| [`docs/03_Requisitos.md`](docs/03_Requisitos.md)         | Requisitos funcionales y no funcionales.         |
| [`docs/04_Arquitectura.md`](docs/04_Arquitectura.md)     | Arquitectura y organización técnica del sistema. |
| [`docs/05_Manual_Tecnico.md`](docs/05_Manual_Tecnico.md) | Guía técnica para desarrolladores.               |
| [`docs/06_Manual_Usuario.md`](docs/06_Manual_Usuario.md) | Guía de uso para los usuarios finales.           |
| [`CHANGELOG.md`](CHANGELOG.md)                           | Historial de cambios y versiones.                |
| [`ROADMAP.md`](ROADMAP.md)                               | Hoja de ruta y seguimiento de las fases.         |
| [`decisiones.md`](decisiones.md)                         | Registro de decisiones del proyecto.             |
| [`glosario.md`](glosario.md)                             | Glosario de términos técnicos y conceptuales.    |

La documentación se actualiza durante el ciclo de desarrollo para mantener la trazabilidad de las decisiones, los requisitos y los cambios realizados.

Los manuales Técnico y de Usuario se completarán progresivamente cuando existan funcionalidades suficientemente consolidadas para documentar su configuración y utilización.

---

## 🚧 Estado del proyecto

* **Versión actual:** `v0.1.1`
* **Estado:** 🟡 En desarrollo
* **Inicio del proyecto:** 2025
* **Fase actual:** Fase 4 — Implementación
* **Última actualización documental:** 13 de octubre de 2026

Las fases de preparación, planificación, documentación base y diseño UI/UX se encuentran consolidadas. La implementación del sistema está en curso.

### Avances registrados

* [x] Preparación inicial del repositorio y configuración de Git y GitHub.
* [x] Planificación general del proyecto.
* [x] Definición de los requisitos funcionales y no funcionales.
* [x] Elaboración de la documentación base.
* [x] Definición de la identidad visual.
* [x] Creación del Design System.
* [x] Diseño de componentes y estados de interacción.
* [x] Definición de la estructura de navegación.
* [x] Diseño de 17 interfaces Desktop.
* [x] Diseño de 17 adaptaciones Mobile.
* [x] Cierre de la fase de diseño UI/UX.
* [x] Preparación de la estructura inicial del frontend.
* [x] Desarrollo inicial de componentes PHP reutilizables.
* [x] Organización modular de los estilos CSS.
* [x] Implementación inicial de la interfaz de Inicio para Desktop y Mobile.
* [x] Desarrollo inicial del encabezado y los elementos de navegación responsive.
* [x] Integración del isotipo y el favicon.
* [x] Desarrollo inicial de la estructura del dashboard y sus elementos de navegación.
* [x] Implementación visual inicial de las interfaces de autenticación, registro y recuperación de contraseña.
* [x] Verificación inicial de la conexión entre PHP y MySQL.

### Actividades pendientes

* [ ] Revisar visualmente Inicio Desktop frente a Figma.
* [ ] Revisar visualmente Inicio Mobile frente a Figma.
* [ ] Corregir las diferencias identificadas.
* [ ] Continuar con la implementación de las interfaces restantes.
* [ ] Verificar funcionalmente el registro y el inicio de sesión.
* [ ] Completar y probar el flujo de recuperación de contraseña.
* [ ] Configurar y verificar el envío de correos electrónicos para la recuperación.
* [ ] Diseñar e implementar el modelo definitivo de la base de datos.
* [ ] Completar las funcionalidades del backend.
* [ ] Implementar los módulos de usuarios y egresados.
* [ ] Implementar los módulos de proyectos y noticias.
* [ ] Implementar y verificar los roles y permisos.
* [ ] Integrar los módulos con la base de datos.
* [ ] Realizar pruebas funcionales, de integración, responsive y de seguridad.
* [ ] Preparar el despliegue de la plataforma.

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

**Frontend**

* Estructura inicial PHP.
* Componentes reutilizables.
* Encabezado y pie de página.
* Navegación responsive.
* Menú móvil.
* Isotipo y favicon.
* Estilos CSS modulares.
* Interfaz de Inicio para Desktop y Mobile.
* Estructura inicial del dashboard.
* Interfaces iniciales de autenticación y formularios.

**Autenticación y formularios**

* Estructura inicial de inicio de sesión.
* Desarrollo inicial del registro.
* Validaciones visuales de formularios.
* Alertas reutilizables.
* Estructura inicial de recuperación de contraseña.
* Avances en la generación de tokens de recuperación.

**Pendiente**

* Revisar Inicio frente a Figma.
* Verificar los flujos de autenticación y registro.
* Completar y probar la recuperación de contraseña.
* Configurar el envío de correos.
* Implementar las demás interfaces.
* Completar las funcionalidades del backend.
* Diseñar e implementar la estructura definitiva de la base de datos.
* Integrar los módulos.

**Estado:** 🟡 En progreso.

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

### 5. Consultar la documentación técnica

Para conocer los procedimientos definitivos de instalación y configuración, consulta [`docs/05_Manual_Tecnico.md`](docs/05_Manual_Tecnico.md) cuando su contenido se encuentre completado.

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

* Revisar Inicio Desktop y Mobile frente a Figma.
* Corregir diferencias visuales y estructurales.
* Implementar progresivamente las interfaces restantes.
* Consolidar los componentes reutilizables.
* Mantener la coherencia con el Design System.
* Implementar y verificar los comportamientos responsive.

### Backend

* Consolidar la estructura PHP.
* Completar los procesos de autenticación y registro.
* Completar la recuperación de contraseña.
* Implementar la gestión de usuarios y perfiles.
* Implementar la gestión de egresados.
* Implementar la gestión de proyectos y noticias.
* Desarrollar los roles, permisos y validaciones.
* Integrar los procesos con el frontend.

### Base de datos

* Diseñar el modelo entidad-relación.
* Definir las tablas y los atributos.
* Establecer las relaciones y las claves.
* Crear las estructuras definitivas.
* Implementar las consultas necesarias.
* Integrar las operaciones de datos con PHP.

### Integración, pruebas y despliegue

* Integrar frontend, backend y base de datos.
* Realizar pruebas funcionales y de integración.
* Validar la compatibilidad responsive.
* Verificar la seguridad de la aplicación.
* Corregir errores.
* Preparar una versión estable.
* Completar la documentación técnica y de usuario.
* Preparar el despliegue.

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

**Versión actual:** `v0.1.1`

Desarrollado con ❤️ por el equipo de Raíces Alzate.

</div>
