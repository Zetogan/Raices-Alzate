# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 🗺️ ROADMAP

## Estado General del Proyecto

**Versión actual:** v0.1.1

**Estado:** 🟡 En desarrollo

**Fase actual:** Fase 4 — Implementación

**Última actualización:** 08 de octubre de 2026

---

# Fase 0 — Preparación

* [x] Crear repositorio en GitHub.
* [x] Configurar Git y GitHub.
* [x] Clonar el proyecto.
* [x] Crear la estructura inicial del proyecto.
* [x] Organizar la estructura de carpetas.
* [x] Configurar el control de versiones.
* [x] Crear la documentación base.
* [x] Definir los estándares de documentación.

**Estado:** ✅ Completada

---

# Fase 1 — Planificación

* [x] Definir el objetivo del proyecto.
* [x] Definir el alcance inicial.
* [x] Identificar las funcionalidades principales.
* [x] Definir las tecnologías principales.
* [x] Establecer la metodología de trabajo.
* [x] Establecer las etapas generales del proyecto.
* [x] Elaborar la planificación inicial.

**Estado:** ✅ Completada

---

# Fase 2 — Documentación

* [x] Crear la Bitácora del proyecto.
* [x] Elaborar la planificación.
* [x] Definir los requisitos funcionales y no funcionales.
* [x] Documentar la arquitectura del sistema.
* [x] Crear el Roadmap.
* [x] Elaborar el README principal.
* [x] Crear el índice de documentación.
* [x] Registrar decisiones del proyecto.
* [x] Elaborar el glosario.
* [x] Definir la estructura documental del proyecto.
* [ ] Completar el Manual Técnico.
* [ ] Completar el Manual de Usuario.

**Estado:** ✅ Completada

> Los manuales Técnico y de Usuario permanecerán pendientes hasta que exista una versión funcional que permita documentar correctamente el desarrollo y uso de la plataforma.

---

# Fase 3 — Diseño UI/UX

## Design System

* [x] Definir la identidad visual.
* [x] Definir la paleta de colores.
* [x] Definir la tipografía.
* [x] Definir el sistema de espaciado.
* [x] Diseñar los botones reutilizables.
* [x] Diseñar Inputs.
* [x] Diseñar Checkbox.
* [x] Diseñar Radio Button.
* [x] Diseñar Switch.
* [x] Diseñar Tables.
* [x] Definir estados de los componentes.
* [x] Definir criterios responsive.
* [x] Finalizar la página **Components & Responsive**.

### Estados de componentes

* [x] Normal.
* [x] Hover.
* [x] Focus.
* [x] Selected.
* [x] Disabled.
* [x] Error.
* [x] Off.
* [x] On.

---

## Flujo y estructura

* [x] Definir el flujo general de información.
* [x] Definir los recorridos principales de los usuarios.
* [x] Definir la estructura de navegación.
* [x] Relacionar los módulos principales del sistema.
* [x] Consolidar la navegación entre las principales áreas de la plataforma.

### Estructura principal de navegación

* Inicio → Autenticación → Dashboard.
* Dashboard → Perfil.
* Dashboard → Proyectos.
* Dashboard → Administración.
* Perfil → Egresados.
* Proyectos → Noticias.

---

## Interfaces Desktop

Se consolidaron las principales interfaces de escritorio:

* [x] Inicio.
* [x] Autenticación.
* [x] Dashboard.
* [x] Perfil.
* [x] Egresados.
* [x] Proyectos.
* [x] Noticias.
* [x] Administración.
* [x] Crear proyecto.
* [x] Detalle de proyecto.
* [x] Editar perfil.
* [x] Recuperar contraseña.
* [x] Registro.
* [x] Editar proyecto.
* [x] Crear noticia.
* [x] Detalle de noticia.
* [x] Editar noticia.

---

## Adaptaciones Mobile

Se trabajaron adaptaciones Mobile de las principales interfaces:

* [x] Inicio Mobile.
* [x] Autenticación Mobile.
* [x] Dashboard Mobile.
* [x] Mi perfil.
* [x] Mis proyectos.
* [x] Oportunidades.

> Las adaptaciones Mobile trabajadas se realizaron tomando como referencia los criterios responsive definidos en el Design System.

---

## Revisión y consolidación

* [x] Revisar consistencia visual.
* [x] Revisar estructura de las interfaces.
* [x] Revisar componentes reutilizables.
* [x] Revisar estados de interacción.
* [x] Revisar criterios responsive.
* [x] Consolidar las interfaces diseñadas.
* [x] Preparar Figma como referencia para la implementación.

**Estado:** ✅ Completada

---

# Fase 4 — Implementación

La implementación corresponde a la etapa actual del proyecto.

Las interfaces, componentes, estados, recorridos y criterios responsive definidos en Figma sirven como referencia para el desarrollo del sistema.

## Frontend

* [x] Preparar la estructura base del frontend.
* [x] Preparar la estructura HTML/PHP inicial.
* [x] Implementar la estructura base de estilos CSS.
* [x] Implementar JavaScript base.
* [x] Establecer componentes PHP reutilizables.
* [x] Implementar la interfaz de Inicio.
* [x] Implementar Header reutilizable.
* [x] Implementar Footer reutilizable.
* [x] Implementar navegación responsive.
* [x] Implementar menú móvil.
* [x] Integrar el isotipo del proyecto.
* [x] Integrar el favicon.
* [x] Establecer la estructura CSS modular.
* [x] Implementar la referencia Desktop de Inicio a 1440 px.
* [x] Implementar la referencia Mobile de Inicio a 390 px.
* [ ] Revisar visualmente Inicio contra Figma.
* [ ] Implementar las interfaces restantes.
* [ ] Implementar las interacciones específicas de cada interfaz.
* [ ] Integrar las interfaces con el backend.

### Estructura CSS actual

```text
assets/css/
├── main.css
├── fonts.css
├── header.css
└── footer.css
```

### Componentes PHP reutilizables

```text
components/
├── header.php
└── footer.php
```

**Estado:** 🟡 En progreso

---

## Backend

* [ ] Configurar la estructura PHP definitiva.
* [x] Verificar la conexión con MySQL.
* [ ] Implementar el sistema de autenticación.
* [ ] Implementar el registro de usuarios.
* [ ] Implementar la gestión de perfiles.
* [ ] Implementar la gestión de egresados.
* [ ] Implementar la gestión de proyectos.
* [ ] Implementar la gestión de noticias.
* [ ] Implementar roles y permisos.
* [ ] Implementar validaciones del sistema.
* [ ] Integrar el backend con el frontend.

**Estado:** ⏳ Pendiente de desarrollo funcional

> La conexión PHP → MySQL ya fue verificada correctamente. La lógica de negocio y las funcionalidades backend todavía no han sido implementadas.

---

## Base de datos

* [ ] Diseñar la estructura definitiva de la base de datos.
* [ ] Definir las entidades necesarias.
* [ ] Definir las tablas necesarias.
* [ ] Definir las relaciones entre las entidades.
* [ ] Definir claves primarias y foráneas.
* [ ] Definir restricciones necesarias.
* [ ] Crear las tablas de la base de datos.
* [ ] Crear las consultas necesarias.
* [ ] Integrar la base de datos con PHP.

**Estado:** ⏳ Pendiente

> La conexión con MySQL se encuentra verificada, pero la estructura definitiva de tablas, relaciones y datos todavía no ha sido implementada.

---

## Integración

* [ ] Integrar frontend, backend y base de datos.
* [ ] Verificar el funcionamiento de los módulos.
* [ ] Implementar los flujos principales.
* [ ] Validar autenticación y permisos.
* [ ] Verificar el manejo de información.
* [ ] Realizar ajustes derivados de la integración.
* [ ] Validar la comunicación entre las diferentes capas del sistema.

**Estado:** ⏳ Pendiente

---

# Fase 5 — Pruebas

* [ ] Realizar pruebas funcionales.
* [ ] Realizar pruebas de integración.
* [ ] Realizar pruebas de interfaz.
* [ ] Realizar pruebas responsive.
* [ ] Realizar pruebas de compatibilidad entre navegadores.
* [ ] Validar el sistema de autenticación.
* [ ] Validar permisos y roles.
* [ ] Realizar validaciones de seguridad.
* [ ] Detectar y corregir errores.
* [ ] Optimizar el sistema.
* [ ] Validar las funcionalidades principales.
* [ ] Realizar validación final del sistema.

**Estado:** ⏳ Pendiente

---

# Fase 6 — Despliegue y Cierre

* [ ] Actualizar la documentación final.
* [ ] Completar el Manual Técnico.
* [ ] Completar el Manual de Usuario.
* [ ] Preparar la versión estable.
* [ ] Configurar el entorno de producción.
* [ ] Publicar la versión estable.
* [ ] Verificar el funcionamiento en producción.
* [ ] Realizar la revisión final del proyecto.
* [ ] Registrar los cambios finales.
* [ ] Realizar el cierre del proyecto.

**Estado:** ⏳ Pendiente

---

# 📊 Resumen del progreso

| Fase                         | Estado         |
| ---------------------------- | -------------- |
| Fase 0 — Preparación         | ✅ Completada   |
| Fase 1 — Planificación       | ✅ Completada   |
| Fase 2 — Documentación       | ✅ Completada   |
| Fase 3 — Diseño UI/UX        | ✅ Completada   |
| Fase 4 — Implementación      | 🟡 En progreso |
| Fase 5 — Pruebas             | ⏳ Pendiente    |
| Fase 6 — Despliegue y Cierre | ⏳ Pendiente    |

---

# 🎯 Próximo objetivo

El siguiente objetivo del proyecto es continuar con la **Fase 4 — Implementación**, comenzando por la revisión y consolidación de la interfaz de **Inicio** frente al diseño aprobado en Figma.

Las actividades inmediatas son:

* Revisar la implementación Desktop de Inicio contra Figma.
* Revisar la implementación Mobile de Inicio contra Figma.
* Corregir diferencias visuales o estructurales encontradas.
* Consolidar los estilos y componentes reutilizables.
* Continuar con la implementación progresiva de las interfaces restantes.
* Mantener la arquitectura frontend modular.
* Preparar posteriormente la implementación backend.
* Definir la estructura definitiva de la base de datos a partir de las necesidades reales de las interfaces.
* Integrar progresivamente frontend, backend y base de datos.

Una vez finalizada la implementación funcional, se continuará con las pruebas correspondientes.

---

# 📌 Nota sobre la planificación

El proyecto se desarrolla de manera progresiva, manteniendo una relación entre planificación, documentación, diseño, implementación y pruebas.

La fase de Diseño UI/UX fue completada antes de iniciar la implementación, permitiendo utilizar las interfaces de Figma como referencia para el desarrollo.

La implementación frontend ya se encuentra iniciada. La interfaz de **Inicio** constituye actualmente la primera interfaz trasladada del diseño a código.

La estructura frontend se mantiene basada en **PHP, HTML, CSS y JavaScript vanilla**, sin incorporar frameworks frontend.

Los estilos se organizan de manera modular para facilitar el mantenimiento y evolución del proyecto. Los elementos compartidos de la interfaz se implementan mediante componentes PHP reutilizables.

La conexión PHP → MySQL ya fue verificada, pero la definición e implementación de la estructura definitiva de la base de datos se realizará posteriormente, tomando como referencia las necesidades reales de las interfaces y funcionalidades implementadas.

La documentación se actualizará conforme avance cada etapa para mantener sincronizados los requisitos, la arquitectura, el diseño y el desarrollo del sistema.

Los manuales Técnico y de Usuario permanecerán pendientes hasta las etapas finales del proyecto, cuando exista una versión funcional que permita documentar correctamente el funcionamiento y uso de la plataforma.

---

# 📜 Historial de cambios

| Versión | Fecha      | Cambio                                                                                                                                                                                                                             |
| ------- | ---------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| v0.1.0  | 09/07/2026 | Creación inicial del Roadmap del proyecto.                                                                                                                                                                                         |
| v0.1.0  | 05/09/2026 | Actualización del roadmap según el avance del proyecto, incluyendo el Design System, Components & Responsive y las etapas de diseño UI/UX.                                                                                         |
| v0.1.1  | 07/10/2026 | Actualización general del Roadmap, consolidación de las fases de Planificación, Documentación y Diseño UI/UX, incorporación de las interfaces Desktop y adaptaciones Mobile trabajadas, y transición a la Fase 4 — Implementación. |
| v0.1.1  | 08/10/2026 | Actualización de la Fase 4 con los avances reales de implementación frontend: Inicio Desktop y Mobile, componentes PHP reutilizables, CSS modular, navegación responsive, isotipo, favicon y conexión PHP → MySQL verificada.      |

---

# 📍 Estado actual

**Versión:** v0.1.1

**Estado:** 🟡 En desarrollo

**Fase actual:** Fase 4 — Implementación

**Última actualización:** 08 de octubre de 2026
