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
| Versión actual         | v0.1.1                                                                                         |
| Estado                 | 🟡 En desarrollo                                                                               |
| Fase actual            | Fase 4 — Implementación                                                                        |
| Última actualización   | 9 de octubre de 2026                                                                           |
| Responsable documental | Santiago Emitola                                                                               |

---

# 📖 Sobre esta documentación

Este directorio contiene la documentación del proyecto **Raíces Alzate**, en la que se registran la planificación, los requisitos, la arquitectura, las decisiones técnicas, la evolución y los avances de desarrollo de la plataforma.

Su objetivo es mantener organizada la información del proyecto, facilitar la comprensión de su estructura y proporcionar una referencia para las distintas etapas de implementación y mantenimiento.

Actualmente, el proyecto se encuentra en la **Fase 4 — Implementación**.

Las etapas de planificación y diseño UI/UX se encuentran consolidadas. La implementación ya cuenta con una estructura inicial de frontend, la interfaz de Inicio y avances reportados en autenticación, registro y recuperación de contraseña.

La conexión entre PHP y MySQL también fue verificada. Sin embargo, todavía se deben completar las funcionalidades del sistema, definir la estructura definitiva de la base de datos y verificar integralmente los flujos desarrollados.

La documentación se actualizará progresivamente para mantenerla alineada con el estado real del repositorio.

---

# 📂 Estructura de la documentación

La documentación se organiza en archivos ubicados dentro de `docs/` y documentos complementarios situados en la raíz del repositorio.

```text
raices-alzate/
│
├── README.md
├── CHANGELOG.md
├── ROADMAP.md
├── decisiones.md
├── glosario.md
│
└── docs/
    ├── README.md
    ├── 01_Bitacora.md
    ├── 02_Planificacion.md
    ├── 03_Requisitos.md
    ├── 04_Arquitectura.md
    ├── 05_Manual_Tecnico.md
    └── 06_Manual_Usuario.md
```

> Esta estructura representa la organización documental prevista. La ubicación y el nombre exactos de cada archivo deben mantenerse sincronizados con los existentes en el repositorio.

El archivo `docs/README.md` funciona como índice de la documentación técnica. El `README.md` ubicado en la raíz presenta el proyecto de manera general.

---

# 📑 Índice de documentos

## 📝 `01_Bitacora.md`

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

**Estado:** ✅ Actualizada según los avances documentados.

---

## 📋 `02_Planificacion.md`

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

**Estado:** ✅ Actualizada.

---

## 📌 `03_Requisitos.md`

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

**Estado:** ✅ Actualizada.

> Los requisitos describen las necesidades del sistema. Su documentación no implica que todas las funcionalidades correspondientes estén implementadas o verificadas.

---

## 🏗️ `04_Arquitectura.md`

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

**Estado:** 📌 Documento de referencia técnica que debe mantenerse sincronizado con la arquitectura realmente implementada.

---

## ⚙️ `05_Manual_Tecnico.md`

**Guía técnica destinada a desarrolladores y responsables del mantenimiento.**

Incluirá progresivamente:

* Requisitos del entorno.
* Instalación y configuración del proyecto.
* Estructura del código.
* Configuración de PHP.
* Conexión con MySQL.
* Estructura de la base de datos.
* Procesos internos.
* Autenticación y autorización.
* Configuración de servicios externos.
* Mantenimiento.
* Solución de problemas.

**Estado:** ⏳ Pendiente de completar.

> Se ampliará conforme se consoliden la arquitectura, los módulos funcionales y la configuración definitiva del sistema.

---

## 👤 `06_Manual_Usuario.md`

**Guía destinada a los usuarios finales de la plataforma.**

Incluirá:

* Acceso al sistema.
* Registro.
* Inicio y cierre de sesión.
* Recuperación de contraseña.
* Navegación.
* Gestión del perfil.
* Uso de las funcionalidades.
* Funciones disponibles según el tipo de usuario.
* Preguntas frecuentes.

**Estado:** ⏳ Pendiente de completar.

> Se elaborará cuando exista una versión funcional que permita documentar los procedimientos reales de uso.

---

# 📚 Documentos complementarios

Los siguientes archivos se encuentran previstos en la raíz del repositorio y complementan la documentación de `docs/`.

## 🗺️ `../ROADMAP.md`

**Plan de evolución y seguimiento de las fases del proyecto.**

Incluye:

* Fases del proyecto.
* Actividades completadas.
* Actividades en desarrollo.
* Tareas pendientes.
* Próximos objetivos.
* Estado general del proyecto.

**Estado:** ✅ Actualizado.

---

## ⚖️ `../decisiones.md`

**Registro de decisiones importantes tomadas durante el desarrollo.**

Incluye:

* Decisiones técnicas.
* Decisiones de diseño.
* Motivos y justificaciones.
* Alternativas consideradas.
* Criterios adoptados.
* Evolución de las decisiones del proyecto.

**Estado:** ✅ Actualizado.

---

## 📖 `../glosario.md`

**Definición de los términos utilizados durante el desarrollo.**

Incluye conceptos relacionados con:

* Proyecto.
* Desarrollo web.
* Diseño UI/UX.
* Figma.
* Design System.
* Components & Responsive.
* Componentes reutilizables.
* Estados de interacción.
* Diseño responsive.
* Tecnologías.
* Bases de datos.
* Usuarios y roles.
* Git y GitHub.
* Implementación.

**Estado:** ✅ Actualizado.

---

## 📝 `../CHANGELOG.md`

**Registro de cambios y evolución de las versiones del proyecto.**

Permite consultar:

* Nuevas funcionalidades.
* Cambios realizados.
* Correcciones.
* Mejoras.
* Evolución del diseño.
* Actualizaciones importantes.
* Cambios entre versiones.
* Avances de implementación.

**Estado:** ✅ Actualizado.

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

📄 [`05_Manual_Tecnico.md`](05_Manual_Tecnico.md)

Documento destinado a la instalación, configuración, comprensión técnica y mantenimiento de la plataforma.

**Estado:** ⏳ Pendiente de completar.

### 10. Manual de Usuario

📄 [`06_Manual_Usuario.md`](06_Manual_Usuario.md)

Documento destinado a explicar el uso de la plataforma a los usuarios finales.

**Estado:** ⏳ Pendiente de completar.

### 11. Glosario

📄 [`glosario.md`](../glosario.md)

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
git commit -m "docs: consolidar indice de documentacion"
git push
```

El mensaje es un ejemplo de referencia; debe ajustarse a los archivos incluidos en cada commit.

---

# 📅 Control de cambios

| Versión | Fecha      | Descripción                                                                                                                                                                                   | Autor            |
| ------- | ---------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------- |
| v0.1.0  | 09/07/2026 | Creación inicial del índice documental y organización de los documentos del proyecto.                                                                                                         | Santiago Emitola |
| v0.1.0  | 20/07/2026 | Actualización de la documentación y sincronización con los avances del diseño UI/UX.                                                                                                          | Santiago Emitola |
| v0.1.0  | 05/09/2026 | Actualización del índice documental según los avances del Design System, Components & Responsive, los estados de los componentes y la estructura de navegación.                               | Santiago Emitola |
| v0.1.1  | 07/10/2026 | Actualización general del índice, revisión de referencias documentales y consolidación del estado de la fase UI/UX y la transición a implementación.                                          | Santiago Emitola |
| v0.1.1  | 08/10/2026 | Actualización del índice según los avances iniciales del frontend, los estilos CSS modulares, los componentes PHP reutilizables y la navegación responsive.                                   | Santiago Emitola |
| v0.1.1  | 09/10/2026 | Incorporación de los avances reportados en autenticación, registro y recuperación de contraseña; actualización de las referencias al estado actual del proyecto y sus actividades pendientes. | Santiago Emitola |

> El proyecto comenzó en 2025. Las fechas de esta tabla corresponden a registros de creación y actualización documental, no a la fecha de inicio del proyecto.

---

# 🚀 Estado actual del proyecto

Raíces Alzate se encuentra en la **Fase 4 — Implementación**.

## Avances registrados

* [x] Planificación del proyecto.
* [x] Definición de requisitos funcionales y no funcionales.
* [x] Documentación de la arquitectura inicial.
* [x] Consolidación de la documentación base.
* [x] Definición de la identidad visual.
* [x] Definición de la paleta de colores.
* [x] Definición de la tipografía.
* [x] Definición del sistema de espaciado.
* [x] Elaboración del Design System.
* [x] Diseño de componentes reutilizables.
* [x] Definición de estados de interacción.
* [x] Consolidación de la página Components & Responsive.
* [x] Definición de la estructura de navegación.
* [x] Diseño de las 17 interfaces Desktop.
* [x] Trabajo en las adaptaciones Mobile indicadas en la documentación de diseño.
* [x] Finalización de la fase de diseño UI/UX.
* [x] Preparación de la estructura base del frontend.
* [x] Desarrollo inicial de componentes PHP reutilizables para Header y Footer.
* [x] Organización inicial de los estilos CSS.
* [x] Implementación inicial de la interfaz de Inicio Desktop.
* [x] Implementación inicial de la interfaz de Inicio Mobile.
* [x] Implementación de la navegación responsive.
* [x] Integración del isotipo y el favicon.
* [x] Verificación de la conexión entre PHP y MySQL.
* [x] Desarrollo inicial de las pantallas y procesos de autenticación, registro y recuperación de contraseña, según los avances reportados.

> Estos elementos representan avances documentados. La marca de verificación no sustituye las pruebas funcionales necesarias para confirmar que cada módulo está completamente operativo.

## Etapa actual

* 🟡 Consolidación e implementación del frontend.
* 🟡 Desarrollo inicial de los procesos de autenticación y formularios.
* ⏳ Verificación funcional de los flujos de registro e inicio de sesión.
* ⏳ Finalización y validación del proceso de recuperación de contraseña.
* ⏳ Diseño e implementación del modelo definitivo de la base de datos.
* ⏳ Desarrollo de los módulos funcionales del backend.
* ⏳ Integración completa entre frontend, backend y base de datos.

## Próximos pasos

* Revisar Inicio Desktop frente a Figma.
* Revisar Inicio Mobile frente a Figma.
* Corregir las diferencias visuales y estructurales identificadas.
* Consolidar los componentes y estilos reutilizables.
* Verificar los formularios y procesos iniciales de autenticación.
* Continuar con la implementación de las interfaces restantes.
* Definir el modelo entidad-relación y la estructura definitiva de la base de datos.
* Implementar las funcionalidades del backend.
* Completar la autenticación y el control de permisos.
* Integrar los módulos con la base de datos.
* Realizar pruebas funcionales, de integración, responsive y de seguridad.
* Completar los manuales Técnico y de Usuario cuando exista una versión funcional que permita documentarlos adecuadamente.

---

# 🌱 Raíces Alzate

**Construyendo una conexión permanente entre la institución y sus egresados.**

> Las raíces del pasado fortalecen los proyectos del futuro.
