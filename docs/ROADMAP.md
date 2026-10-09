# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 🗺️ ROADMAP

## Estado general del proyecto

* **Versión actual:** v0.1.1
* **Estado:** 🟡 En desarrollo
* **Fase actual:** Fase 4 — Implementación
* **Inicio del proyecto:** 2025
* **Última actualización:** 9 de octubre de 2026

---

# Fase 0 — Preparación

* [x] Crear el repositorio en GitHub.
* [x] Configurar Git y GitHub.
* [x] Clonar el proyecto.
* [x] Crear la estructura inicial del proyecto.
* [x] Organizar la estructura de carpetas.
* [x] Configurar el control de versiones.
* [x] Crear la documentación base.
* [x] Definir los estándares de documentación.

**Estado:** ✅ Completada.

---

# Fase 1 — Planificación

* [x] Definir el objetivo del proyecto.
* [x] Definir el alcance inicial.
* [x] Identificar las funcionalidades principales.
* [x] Definir las tecnologías principales.
* [x] Establecer la metodología de trabajo.
* [x] Establecer las etapas generales del proyecto.
* [x] Elaborar la planificación inicial.

**Estado:** ✅ Completada.

---

# Fase 2 — Documentación

* [x] Crear la Bitácora del proyecto.
* [x] Elaborar la planificación.
* [x] Definir los requisitos funcionales y no funcionales.
* [x] Documentar la arquitectura inicial del sistema.
* [x] Crear el Roadmap.
* [x] Elaborar el README principal.
* [x] Crear el índice de documentación.
* [x] Registrar las decisiones del proyecto.
* [x] Elaborar el glosario.
* [x] Definir la estructura documental del proyecto.
* [ ] Completar el Manual Técnico.
* [ ] Completar el Manual de Usuario.

**Estado:** ✅ Documentación base completada.

> Los manuales Técnico y de Usuario permanecerán pendientes hasta que exista una versión funcional que permita documentar adecuadamente la implementación, configuración y utilización de la plataforma.

La documentación se seguirá actualizando durante las siguientes fases para reflejar los cambios reales del proyecto.

---

# Fase 3 — Diseño UI/UX

La fase de diseño se completó a finales de septiembre de 2026. Las interfaces y los componentes definidos en Figma sirven como referencia para la implementación.

## 3.1. Design System

* [x] Definir la identidad visual.
* [x] Definir la paleta de colores.
* [x] Definir la tipografía.
* [x] Definir el sistema de espaciado.
* [x] Diseñar los botones reutilizables.
* [x] Diseñar los campos de entrada (*Inputs*).
* [x] Diseñar los componentes Checkbox.
* [x] Diseñar los componentes Radio Button.
* [x] Diseñar los componentes Switch.
* [x] Diseñar las tablas.
* [x] Definir los estados de los componentes.
* [x] Establecer los criterios responsive.
* [x] Finalizar la página `Components & Responsive`.

### Estados de los componentes

* [x] Normal.
* [x] Hover.
* [x] Focus.
* [x] Selected.
* [x] Disabled.
* [x] Error.
* [x] Off.
* [x] On.

## 3.2. Flujo y estructura

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

## 3.3. Interfaces Desktop

Se diseñaron y consolidaron las siguientes interfaces en Figma:

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

**Total:** 17 interfaces Desktop.

## 3.4. Adaptaciones Mobile

Se trabajaron las siguientes adaptaciones para dispositivos móviles:

* [x] Inicio Mobile.
* [x] Autenticación Mobile.
* [x] Dashboard Mobile.
* [x] Mi perfil.
* [x] Mis proyectos.
* [x] Oportunidades.

> Esta lista representa las adaptaciones trabajadas en Figma. No implica que todas las interfaces Mobile estén completamente diseñadas ni que ya se encuentren implementadas en código.

## 3.5. Revisión y consolidación

* [x] Revisar la consistencia visual.
* [x] Revisar la estructura de las interfaces.
* [x] Revisar los componentes reutilizables.
* [x] Revisar los estados de interacción.
* [x] Definir los criterios responsive.
* [x] Consolidar las interfaces diseñadas.
* [x] Preparar Figma como referencia para la implementación.

**Estado:** ✅ Completada.

---

# Fase 4 — Implementación

La implementación es la fase actual del proyecto.

El objetivo es trasladar progresivamente los diseños de Figma a una aplicación funcional, manteniendo una estructura modular y separando las responsabilidades de presentación, procesamiento y acceso a datos.

## 4.1. Frontend

### Estructura y configuración

* [x] Preparar la estructura base del frontend.
* [x] Preparar la estructura inicial HTML/PHP.
* [x] Implementar la estructura base de estilos CSS.
* [x] Implementar JavaScript base.
* [x] Establecer componentes PHP reutilizables.
* [x] Organizar los estilos mediante archivos CSS modulares.
* [x] Integrar el isotipo del proyecto.
* [x] Integrar el favicon.

### Interfaz de Inicio

* [x] Implementar la interfaz de Inicio en Desktop.
* [x] Implementar la estructura general del encabezado (*Header*).
* [x] Implementar el pie de página (*Footer*).
* [x] Implementar la navegación responsive.
* [x] Implementar el menú móvil.
* [x] Establecer la referencia Desktop de Inicio a 1440 px.
* [x] Establecer la referencia Mobile de Inicio a 390 px.
* [ ] Revisar visualmente Inicio Desktop frente a Figma.
* [ ] Revisar visualmente Inicio Mobile frente a Figma.
* [ ] Corregir las diferencias visuales identificadas.

### Autenticación y formularios

* [x] Crear la estructura inicial de la pantalla de inicio de sesión.
* [x] Organizar la pantalla de autenticación mediante PHP.
* [x] Implementar estilos específicos para autenticación.
* [x] Implementar validaciones de interfaz para el inicio de sesión.
* [x] Incorporar alertas reutilizables para los mensajes del sistema.
* [x] Definir estados visuales para Inputs y Radio Button.
* [x] Implementar validación visual reactiva de campos.
* [x] Crear la estructura de la pantalla de registro.
* [x] Desarrollar el procesamiento inicial del registro de usuarios.
* [x] Crear la estructura de recuperación de contraseña.
* [x] Desarrollar el procesamiento inicial de solicitudes de recuperación.
* [x] Incorporar una respuesta genérica para evitar revelar si un correo está registrado.
* [ ] Verificar los flujos completos de inicio de sesión y registro.
* [ ] Verificar las validaciones del lado del servidor.
* [ ] Verificar el almacenamiento y la gestión segura de las contraseñas.
* [ ] Completar y probar el flujo de recuperación de contraseña.
* [ ] Configurar el servicio de correo electrónico para la recuperación.
* [ ] Verificar la expiración, el almacenamiento y la validación de los tokens de recuperación.
* [ ] Revisar las pantallas implementadas frente a sus diseños aprobados en Figma.

> Los elementos marcados como completados corresponden a avances de estructura, interfaz o procesamiento reportados durante el desarrollo. No significan que todos los flujos de autenticación estén validados de extremo a extremo.

### Interfaces restantes

* [ ] Implementar las interfaces restantes de acuerdo con la planificación.
* [ ] Implementar las interacciones específicas de cada interfaz.
* [ ] Desarrollar los estados de carga, error, éxito y ausencia de información que correspondan.
* [ ] Adaptar progresivamente las interfaces a dispositivos móviles.
* [ ] Verificar la consistencia visual entre las interfaces implementadas.
* [ ] Integrar progresivamente las interfaces con el backend.

### Estructura CSS reportada

```text
assets/css/
├── main.css
├── fonts.css
├── header.css
├── footer.css
└── auth.css
```

### Archivos JavaScript reportados

```text
assets/js/
└── auth.js
```

### Componentes PHP reutilizables

Se han reportado componentes compartidos para el encabezado y el pie de página. La ubicación definitiva de estos archivos deberá mantenerse sincronizada con la estructura real del repositorio.

**Estado:** 🟡 En progreso.

---

## 4.2. Backend

Se han desarrollado estructuras iniciales de procesamiento para formularios de autenticación, registro y recuperación de contraseña. Los módulos restantes de negocio todavía requieren implementación.

### Configuración y estructura

* [ ] Consolidar la estructura definitiva del backend.
* [x] Verificar la conexión entre PHP y MySQL.
* [x] Crear estructuras iniciales para procesar formularios de autenticación.
* [x] Incorporar consultas preparadas en los procesos reportados.
* [x] Incorporar el uso de `password_hash()` en el procesamiento del registro reportado.
* [ ] Verificar integralmente la autenticación mediante sesiones.
* [ ] Completar las validaciones de seguridad del backend.
* [ ] Verificar los procesos de autorización y control de acceso.

### Módulos funcionales

* [ ] Completar el sistema de autenticación.
* [ ] Completar el registro de usuarios.
* [ ] Implementar la gestión de perfiles.
* [ ] Implementar la gestión de egresados.
* [ ] Implementar la gestión de proyectos.
* [ ] Implementar la gestión de noticias.
* [ ] Implementar la gestión administrativa.
* [ ] Definir e implementar los roles y permisos.
* [ ] Integrar los módulos con la base de datos.
* [ ] Integrar los procesos del backend con las interfaces.
* [ ] Gestionar adecuadamente los errores y las respuestas del sistema.

### Recuperación de contraseña

* [x] Crear la estructura inicial del formulario.
* [x] Implementar la validación inicial del correo electrónico.
* [x] Desarrollar la generación inicial de tokens mediante `random_bytes()`.
* [x] Incorporar una respuesta genérica para las solicitudes.
* [ ] Verificar el almacenamiento seguro de los tokens.
* [ ] Definir y verificar su tiempo de expiración.
* [ ] Implementar y probar la validación del token.
* [ ] Implementar el proceso para establecer una nueva contraseña.
* [ ] Configurar el envío de instrucciones mediante correo electrónico.
* [ ] Validar el flujo completo de recuperación.

> La generación inicial de tokens está reportada, pero todavía se debe verificar la implementación integral de su almacenamiento, expiración, validación y utilización. El envío de correos requiere configurar el servicio correspondiente.

**Estado:** 🟡 En desarrollo inicial.

---

## 4.3. Base de datos

La conexión entre PHP y MySQL se verificó correctamente. La estructura definitiva de la base de datos y sus módulos de información todavía deben definirse e implementarse.

* [ ] Diseñar el modelo entidad-relación definitivo.
* [ ] Identificar las entidades necesarias.
* [ ] Definir las tablas de la base de datos.
* [ ] Definir los atributos de cada entidad.
* [ ] Establecer las claves primarias.
* [ ] Establecer las claves foráneas.
* [ ] Definir las relaciones entre las entidades.
* [ ] Establecer las restricciones de integridad.
* [ ] Diseñar las tablas relacionadas con usuarios y perfiles.
* [ ] Definir la estructura de información de egresados.
* [ ] Definir la estructura de proyectos.
* [ ] Definir la estructura de noticias.
* [ ] Definir las tablas adicionales requeridas por los roles y permisos.
* [ ] Crear las tablas definitivas.
* [ ] Implementar las consultas necesarias.
* [ ] Integrar las operaciones de base de datos con PHP.
* [ ] Verificar la integridad y consistencia de los datos.

**Estado:** ⏳ Pendiente de diseño e implementación.

> La conexión PHP → MySQL está verificada, pero esto no significa que el modelo de datos definitivo ni todos los procesos de persistencia estén terminados.

---

## 4.4. Integración

La integración se realizará progresivamente a medida que se completen las interfaces, los procesos del backend y la estructura de la base de datos.

* [ ] Integrar frontend, backend y base de datos.
* [ ] Verificar el funcionamiento de cada módulo.
* [ ] Implementar y comprobar los flujos principales.
* [ ] Validar la autenticación y los permisos.
* [ ] Verificar el procesamiento de la información.
* [ ] Comprobar las operaciones de consulta, creación, edición y eliminación que correspondan.
* [ ] Verificar la comunicación entre las diferentes capas.
* [ ] Corregir los errores encontrados durante la integración.
* [ ] Verificar los mensajes de respuesta y los estados de las interfaces.
* [ ] Consolidar la integración de los módulos funcionales.

**Estado:** ⏳ Pendiente de integración funcional completa.

---

## 4.5. Seguridad

La seguridad se verificará durante el desarrollo y las pruebas de cada módulo.

* [ ] Verificar el uso de consultas preparadas en las operaciones de base de datos.
* [ ] Verificar el almacenamiento seguro de las contraseñas.
* [ ] Verificar la autenticación mediante sesiones.
* [ ] Implementar y validar los controles de autorización.
* [ ] Validar los datos recibidos en el servidor.
* [ ] Revisar la protección frente a inyección SQL.
* [ ] Revisar la protección frente a vulnerabilidades relacionadas con formularios y sesiones.
* [ ] Verificar el tratamiento de los errores.
* [ ] Verificar la protección de los tokens de recuperación.
* [ ] Evitar la exposición innecesaria de información sensible.
* [ ] Validar el acceso a las funciones administrativas.

**Estado:** ⏳ Pendiente de validación integral.

---

# Fase 5 — Pruebas

Esta fase comenzará cuando existan módulos funcionales que puedan verificarse de forma sistemática.

* [ ] Realizar pruebas funcionales.
* [ ] Realizar pruebas de integración.
* [ ] Realizar pruebas de interfaz.
* [ ] Realizar pruebas responsive.
* [ ] Realizar pruebas de compatibilidad entre navegadores.
* [ ] Validar el sistema de autenticación.
* [ ] Validar el registro de usuarios.
* [ ] Validar la recuperación de contraseña.
* [ ] Validar los roles y permisos.
* [ ] Verificar las operaciones sobre la base de datos.
* [ ] Realizar pruebas de seguridad.
* [ ] Detectar y corregir errores.
* [ ] Evaluar el rendimiento.
* [ ] Validar las funcionalidades principales.
* [ ] Realizar una revisión integral del sistema.
* [ ] Documentar los resultados de las pruebas.

**Estado:** ⏳ Pendiente.

---

# Fase 6 — Despliegue y cierre

Esta fase comprende la preparación, publicación y documentación final de la plataforma.

* [ ] Actualizar la documentación definitiva.
* [ ] Completar el Manual Técnico.
* [ ] Completar el Manual de Usuario.
* [ ] Preparar una versión estable.
* [ ] Configurar el entorno de producción.
* [ ] Preparar la configuración de la base de datos de producción.
* [ ] Configurar las variables y credenciales del entorno de producción.
* [ ] Configurar los servicios necesarios para el funcionamiento de la plataforma.
* [ ] Publicar la versión estable.
* [ ] Verificar el funcionamiento en producción.
* [ ] Realizar una revisión final del proyecto.
* [ ] Registrar los cambios finales.
* [ ] Consolidar la entrega del proyecto.
* [ ] Realizar el cierre del proyecto.

**Estado:** ⏳ Pendiente.

---

# 📊 Resumen del progreso

| Fase                         | Estado         |
| ---------------------------- | -------------- |
| Fase 0 — Preparación         | ✅ Completada   |
| Fase 1 — Planificación       | ✅ Completada   |
| Fase 2 — Documentación base  | ✅ Completada   |
| Fase 3 — Diseño UI/UX        | ✅ Completada   |
| Fase 4 — Implementación      | 🟡 En progreso |
| Fase 5 — Pruebas             | ⏳ Pendiente    |
| Fase 6 — Despliegue y cierre | ⏳ Pendiente    |

> La finalización de una fase no implica que todas las tareas futuras relacionadas con ella hayan desaparecido. La documentación, por ejemplo, seguirá actualizándose durante la implementación y el cierre.

---

# 🎯 Próximo objetivo

El siguiente objetivo es continuar con la **Fase 4 — Implementación**, consolidando los avances existentes y verificando que las interfaces desarrolladas correspondan a los diseños aprobados en Figma.

Las actividades prioritarias son:

1. Revisar la interfaz de Inicio en Desktop frente a Figma.
2. Revisar la interfaz de Inicio en Mobile frente a Figma.
3. Corregir las diferencias visuales y estructurales identificadas.
4. Consolidar los componentes PHP reutilizables y los estilos compartidos.
5. Verificar las pantallas y los procesos iniciales de autenticación, registro y recuperación de contraseña.
6. Confirmar la estructura real de los archivos y mantener la documentación sincronizada con el repositorio.
7. Continuar con la implementación progresiva de las interfaces restantes.
8. Diseñar el modelo entidad-relación y definir la estructura de la base de datos.
9. Integrar progresivamente las interfaces, los procesos PHP y las operaciones de base de datos.
10. Preparar los módulos para sus respectivas pruebas funcionales y de seguridad.

Una vez consolidados los primeros módulos, se continuará con las siguientes interfaces y funcionalidades, manteniendo la arquitectura modular y los criterios de diseño definidos.

---

# 📌 Nota sobre la planificación

Raíces Alzate se desarrolla de manera progresiva, manteniendo la relación entre planificación, documentación, diseño, implementación, integración y pruebas.

El proyecto comenzó en 2025. Las fechas registradas en las actualizaciones de 2026 corresponden al avance de sus distintas etapas.

La fase de Diseño UI/UX se completó a finales de septiembre de 2026. Las interfaces diseñadas en Figma constituyen la referencia visual y estructural para la implementación.

La implementación frontend ya está iniciada. La interfaz de Inicio es la primera pantalla trasladada al código, y se han reportado avances adicionales en autenticación, registro y recuperación de contraseña.

La arquitectura prevista se basa en **PHP, HTML, CSS y JavaScript vanilla**, sin incorporar frameworks frontend. Los estilos se organizan mediante archivos CSS modulares, y los elementos compartidos se desarrollan mediante componentes PHP reutilizables.

La conexión entre PHP y MySQL fue verificada. Sin embargo, el modelo de datos definitivo, las tablas y las relaciones todavía deben diseñarse e implementarse.

Los avances de autenticación y recuperación de contraseña deben verificarse mediante pruebas funcionales antes de considerarse terminados. En particular, la recuperación requiere comprobar el ciclo completo del token y configurar el servicio de envío de correos electrónicos.

La documentación se actualizará conforme avance cada etapa, procurando que los requisitos, la arquitectura, el diseño y el código representen el mismo estado del proyecto.

Los manuales Técnico y de Usuario permanecerán pendientes hasta que exista una versión funcional que permita documentar adecuadamente la configuración, el mantenimiento y el uso de la plataforma.

---

# 📜 Historial de cambios

| Versión | Fecha      | Cambio                                                                                                                                                                                              |
| ------- | ---------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| v0.1.0  | 09/07/2026 | Registro inicial del Roadmap en la documentación del proyecto. El proyecto había comenzado en 2025.                                                                                                 |
| v0.1.0  | 05/09/2026 | Actualización del Roadmap según los avances del Design System, Components & Responsive y las etapas de diseño UI/UX.                                                                                |
| v0.1.1  | 07/10/2026 | Actualización general de las fases de planificación, documentación y diseño; consolidación de las interfaces Desktop y las adaptaciones Mobile trabajadas; transición a la Fase 4 — Implementación. |
| v0.1.1  | 08/10/2026 | Actualización de los avances de Inicio Desktop y Mobile, los componentes reutilizables, los estilos CSS modulares, la navegación responsive, el isotipo, el favicon y la conexión PHP → MySQL.      |
| v0.1.1  | 09/10/2026 | Consolidación de los avances reportados en autenticación, registro y recuperación de contraseña; actualización de las tareas pendientes de validación, seguridad, integración y base de datos.      |

---

# 📍 Estado actual

* **Versión:** v0.1.1
* **Estado:** 🟡 En desarrollo
* **Fase actual:** Fase 4 — Implementación
* **Inicio del proyecto:** 2025
* **Última actualización:** 9 de octubre de 2026
