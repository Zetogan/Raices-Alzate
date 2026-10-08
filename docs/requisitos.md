# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 📑 Requisitos del Proyecto

## Información General

| Campo                | Información                                    |
| -------------------- | ---------------------------------------------- |
| Proyecto             | Raíces Alzate                                  |
| Institución          | Institución Educativa Gilberto Alzate Avendaño |
| Versión              | v0.1.1                                         |
| Estado               | 🟡 En desarrollo                               |
| Fase actual          | Fase 4 — Implementación                        |
| Última actualización | 08 de octubre de 2026                          |

---

# Introducción

Este documento reúne los requisitos funcionales y no funcionales que definen el comportamiento, las características y las condiciones de funcionamiento de la plataforma **Raíces Alzate**.

Los requisitos establecidos sirven como referencia durante las etapas de diseño, desarrollo y pruebas del sistema, permitiendo mantener una relación clara entre las necesidades del proyecto y las funcionalidades que serán implementadas.

Las etapas de planificación, documentación y diseño UI/UX se encuentran consolidadas. La fase de diseño UI/UX finalizó con la definición del sistema de diseño, componentes, criterios responsive, estructura de navegación y las principales interfaces Desktop y Mobile.

Actualmente, el proyecto se encuentra en la **Fase 4 — Implementación**. La implementación frontend ya inició tomando como referencia los diseños establecidos en Figma. La interfaz de **Inicio** constituye actualmente la primera pantalla trasladada del diseño a código.

---

# Requisitos Funcionales

## Módulo de Inicio

* Mostrar la página principal del proyecto.
* Presentar información institucional.
* Mostrar acceso al inicio de sesión.
* Mostrar acceso al registro de usuarios.
* Presentar de forma clara los principales contenidos y accesos de la plataforma.
* Presentar los beneficios y propósito de la plataforma.
* Mantener una estructura visual coherente con el diseño definido en Figma.
* Adaptar la navegación y los contenidos a diferentes tamaños de pantalla.

**Estado de implementación:**

* 🟢 Estructura frontend implementada.
* 🟢 Header implementado.
* 🟢 Footer implementado.
* 🟢 Navegación responsive implementada.
* 🟢 Versión Desktop implementada como referencia de 1440 px.
* 🟢 Versión Mobile implementada como referencia de 390 px.
* 🟢 Isotipo institucional integrado.
* 🟢 Favicon integrado.
* 🟡 Revisión visual final pendiente.

---

## Módulo de Autenticación

* Registrar usuarios.
* Iniciar sesión.
* Cerrar sesión.
* Recuperar contraseña.
* Cambiar contraseña.
* Mantener la sesión iniciada cuando corresponda.
* Validar las credenciales proporcionadas por el usuario.
* Validar los datos ingresados durante el registro.
* Restringir el acceso a funcionalidades según el tipo de usuario.
* Controlar el acceso a las funciones administrativas.

**Estado:** ⏳ Pendiente de implementación funcional.

---

## Módulo de Usuarios y Perfil

* Consultar la información asociada al perfil.
* Editar información del perfil.
* Actualizar información personal.
* Cambiar fotografía de perfil.
* Gestionar la información de la cuenta.
* Mantener actualizada la información personal y profesional del usuario.
* Permitir que el usuario consulte su información desde el dashboard.

**Estado:** ⏳ Pendiente de implementación funcional.

---

## Módulo de Egresados

* Registrar información de egresados.
* Consultar información de los egresados.
* Buscar egresados.
* Filtrar resultados.
* Visualizar perfiles.
* Mantener actualizada la información de los egresados.
* Facilitar la consulta de información relevante de la comunidad de egresados.

**Estado:** ⏳ Pendiente de implementación.

---

## Módulo de Proyectos

* Publicar proyectos.
* Crear proyectos.
* Consultar proyectos publicados.
* Visualizar la información de los proyectos.
* Editar proyectos.
* Eliminar proyectos.
* Asociar proyectos con sus respectivos egresados.
* Gestionar la información relacionada con los proyectos.

**Estado:** ⏳ Pendiente de implementación.

---

## Módulo de Noticias

* Publicar noticias.
* Crear noticias.
* Consultar noticias publicadas.
* Visualizar el detalle de una noticia.
* Editar noticias.
* Eliminar noticias.
* Gestionar la información de las publicaciones.

**Estado:** ⏳ Pendiente de implementación.

---

## Módulo Administrativo

* Gestionar usuarios.
* Gestionar egresados.
* Gestionar proyectos.
* Gestionar noticias.
* Administrar permisos.
* Controlar el acceso a las funciones administrativas.
* Visualizar información administrativa disponible.
* Gestionar las operaciones correspondientes a los permisos del sistema.

**Estado:** ⏳ Pendiente de implementación.

---

# Requisitos No Funcionales

## Rendimiento

* La plataforma debe cargar de manera rápida y eficiente.
* Se deben optimizar las imágenes y recursos utilizados por la plataforma.
* Se deben evitar recursos innecesarios que afecten el rendimiento.
* Se deben mantener tiempos de respuesta adecuados durante la navegación.
* La estructura del código deberá evitar procesos innecesarios que afecten el funcionamiento del sistema.
* Los recursos frontend deberán mantenerse organizados para facilitar su mantenimiento y optimización.

---

## Seguridad

* Las contraseñas deben almacenarse de forma segura mediante mecanismos de hash adecuados.
* Se deben validar los datos ingresados en los formularios.
* Se deben proteger las sesiones de los usuarios.
* Se debe implementar control de permisos y acceso.
* Se deben evitar accesos no autorizados a información o funcionalidades.
* Se debe validar la información recibida antes de procesarla o almacenarla.
* Las funcionalidades administrativas deberán estar restringidas a los usuarios autorizados.
* Se deberán aplicar validaciones tanto en el frontend como en el backend cuando corresponda.
* Las operaciones relacionadas con la base de datos deberán utilizar mecanismos seguros de consulta y procesamiento.

---

## Compatibilidad

La plataforma deberá ser compatible con los principales navegadores web:

* Google Chrome.
* Microsoft Edge.
* Mozilla Firefox.

El sistema deberá mantener un funcionamiento adecuado en diferentes tamaños de pantalla y dispositivos.

---

## Diseño y Experiencia de Usuario

* El diseño debe ser responsive.
* La interfaz debe ser intuitiva y fácil de utilizar.
* La interfaz debe mantener una identidad visual coherente con el proyecto.
* Los componentes deben mantener consistencia visual.
* Los componentes interactivos deben contemplar sus estados correspondientes.
* La interfaz debe considerar criterios básicos de accesibilidad.
* El diseño debe ser moderno, claro y organizado.
* La plataforma debe mantener una jerarquía visual coherente.
* Los elementos de navegación deben ser claros y fáciles de identificar.
* Las diferentes pantallas deben mantener una estructura visual consistente.
* La experiencia de navegación debe ser coherente entre las diferentes funcionalidades.
* Las interfaces implementadas deberán mantener correspondencia con los diseños aprobados en Figma.

---

# Sistema de Diseño

La interfaz deberá basarse en el sistema de diseño establecido durante la fase UI/UX para garantizar consistencia durante la implementación.

El sistema contempla:

* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Componentes reutilizables.
* Estados de interacción.
* Criterios responsive.
* Estructura visual consistente.

Entre los componentes definidos se encuentran:

* Botones.
* Inputs.
* Checkbox.
* Radio Button.
* Switch.
* Tablas.

Los componentes deberán contemplar los estados necesarios según su función, incluyendo:

* Normal.
* Hover.
* Focus.
* Selected.
* Disabled.
* Error.
* Off.
* On.

El sistema de diseño se encuentra documentado en Figma y sirve como referencia visual para la implementación frontend.

Los principales valores definidos incluyen:

* Color primario: `#D32F2F`.
* Color secundario: `#1565C0`.
* Texto principal: `#212121`.
* Texto secundario: `#757575`.
* Fondo claro: `#F5F5F5`.
* Blanco: `#FFFFFF`.
* Borde: `#E0E0E0`.

El sistema de espaciado contempla valores de:

`4px`, `8px`, `12px`, `16px`, `24px`, `32px`, `48px` y `64px`.

---

# Responsive

La plataforma deberá adaptarse correctamente a diferentes tamaños de pantalla.

El diseño responsive deberá considerar:

* Dispositivos móviles.
* Tablets.
* Computadores de escritorio.
* Reorganización de los elementos según el espacio disponible.
* Legibilidad de textos.
* Accesibilidad de botones y controles.
* Correcta visualización de componentes.
* Navegación funcional en diferentes resoluciones.
* Adaptación de las interfaces según el dispositivo.

Durante la fase UI/UX se definieron interfaces Desktop y se trabajaron adaptaciones Mobile para las pantallas principales, tomando como referencia los criterios establecidos en **Components & Responsive**.

Durante la implementación frontend se está utilizando como referencia:

* Desktop: `1440px`.
* Mobile: `390px`.

La primera implementación responsive corresponde actualmente a la interfaz de **Inicio**, incluyendo la adaptación de la navegación mediante menú móvil.

---

# Mantenibilidad

* El código debe estar documentado.
* La estructura del proyecto debe mantenerse organizada.
* Se debe utilizar una estructura modular para facilitar futuras modificaciones.
* Se deben utilizar componentes reutilizables cuando sea necesario.
* Se utilizará Git para el control de versiones.
* Se utilizará GitHub para almacenar y gestionar el repositorio.
* La documentación deberá mantenerse actualizada conforme avance el proyecto.
* Las funcionalidades deberán mantenerse organizadas de acuerdo con la arquitectura definida.
* Los cambios relevantes deberán registrarse en la documentación y el control de versiones.
* Los estilos deberán mantenerse organizados por responsabilidades.
* Los elementos compartidos de la interfaz deberán utilizar componentes PHP reutilizables cuando corresponda.

Actualmente, la estructura CSS del frontend se encuentra modularizada de la siguiente manera:

```text
assets/css/
├── main.css
├── fonts.css
├── header.css
└── footer.css
```

Las responsabilidades principales son:

* `main.css`: estilos generales, variables, tokens, estructura base y elementos compartidos.
* `fonts.css`: configuración y tipografía institucional.
* `header.css`: estilos correspondientes al encabezado y navegación.
* `footer.css`: estilos correspondientes al pie de página.

Los elementos compartidos del sistema se gestionan mediante componentes PHP reutilizables:

```text
components/
├── header.php
└── footer.php
```

Estos componentes son integrados mediante la estructura ubicada en `includes/`.

---

# Requisitos de Diseño UI/UX

Antes de iniciar la implementación se estableció la estructura visual y funcional de la plataforma mediante Figma.

El proceso de diseño contempló:

1. Definición de identidad visual.
2. Creación del Design System.
3. Definición de componentes reutilizables.
4. Definición de estados de interacción.
5. Definición de criterios responsive.
6. Definición del flujo de información.
7. Definición de recorridos principales de los usuarios.
8. Definición de la estructura de navegación.
9. Diseño de interfaces Desktop.
10. Adaptación de interfaces principales a Mobile.
11. Revisión y consolidación de las interfaces.
12. Preparación de las interfaces como referencia para la implementación.

La fase de diseño UI/UX se considera completada y sus resultados sirven como referencia para la etapa actual de implementación.

---

# Interfaces Diseñadas

Durante la fase UI/UX se consolidaron las siguientes interfaces principales para Desktop:

1. Inicio.
2. Autenticación.
3. Dashboard.
4. Perfil.
5. Egresados.
6. Proyectos.
7. Noticias.
8. Administración.
9. Crear proyecto.
10. Detalle de proyecto.
11. Editar perfil.
12. Recuperar contraseña.
13. Registro.
14. Editar proyecto.
15. Crear noticia.
16. Detalle de noticia.
17. Editar noticia.

También se trabajaron adaptaciones Mobile para:

* Inicio.
* Autenticación.
* Dashboard.
* Mi perfil.
* Mis proyectos.
* Oportunidades.

Las interfaces definidas en Figma constituyen la referencia visual para la implementación frontend.

---

# Estado de los Requisitos

## ✅ Requisitos definidos y consolidados

* Requisitos funcionales principales.
* Requisitos no funcionales.
* Requisitos de seguridad.
* Requisitos de compatibilidad.
* Requisitos de rendimiento.
* Requisitos de diseño.
* Criterios responsive.
* Componentes reutilizables.
* Estados de interacción.
* Sistema de diseño.
* Estructura de navegación.
* Flujo general de información.
* Interfaces principales Desktop.
* Adaptaciones Mobile trabajadas.
* Criterios visuales para la implementación.

## 🟢 Fase de diseño UI/UX completada

* Design System.
* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Componentes reutilizables.
* Estados de componentes.
* Página **Components & Responsive**.
* Criterios responsive.
* Estructura de navegación.
* Definición de interfaces principales.
* Revisión y consolidación de las interfaces.

## 🟢 Implementado actualmente

* Estructura base del frontend.
* Interfaz de Inicio.
* Header reutilizable.
* Footer reutilizable.
* Navegación responsive.
* Menú móvil.
* Integración del isotipo.
* Integración del favicon.
* Estructura CSS modular.
* Tipografía institucional mediante `fonts.css`.
* Referencia Desktop de 1440 px.
* Referencia Mobile de 390 px.
* Conexión PHP con MySQL verificada previamente.

## 🟡 En implementación

* Implementación progresiva de las interfaces restantes.
* Desarrollo frontend de los módulos definidos.
* Desarrollo backend.
* Integración entre frontend y backend.
* Implementación de autenticación y permisos.
* Implementación de los módulos funcionales.
* Definición e implementación de las tablas definitivas de la base de datos.

## ⏳ Pendiente

* Implementación completa de la base de datos.
* Integración completa entre frontend, backend y base de datos.
* Pruebas funcionales.
* Pruebas de integración.
* Validación completa del sistema.
* Corrección de errores detectados durante las pruebas.
* Pruebas de compatibilidad.
* Despliegue de la plataforma.

---

# Criterios de aceptación generales

La plataforma deberá cumplir con los siguientes criterios:

* Las funcionalidades principales deberán responder correctamente a las acciones del usuario.
* Los usuarios deberán poder autenticarse de forma segura.
* La información deberá validarse antes de ser almacenada.
* Los permisos deberán controlar el acceso a las diferentes funcionalidades.
* La interfaz deberá ser responsive.
* Los componentes deberán mantener consistencia visual.
* La plataforma deberá funcionar correctamente en los navegadores definidos.
* El sistema deberá mantener una estructura organizada y fácil de mantener.
* Las interfaces implementadas deberán mantener correspondencia con los diseños definidos en Figma.
* La información deberá mostrarse de manera clara y organizada.
* Las funcionalidades administrativas deberán estar protegidas mediante permisos.
* La documentación deberá actualizarse conforme avance el desarrollo.
* Las pruebas deberán validar el funcionamiento de las funcionalidades implementadas antes del despliegue.
* Los componentes compartidos deberán mantener un comportamiento consistente entre las diferentes interfaces.
* La implementación frontend deberá respetar el sistema de diseño establecido.
* Las adaptaciones responsive deberán mantener la funcionalidad y jerarquía visual definida en Figma.

---

# Tecnologías relacionadas

## Frontend

* HTML5.
* CSS3.
* JavaScript.

## Backend

* PHP.

## Base de datos

* MySQL.

## Diseño UI/UX

* Figma.

## Control de versiones

* Git.
* GitHub.

## Entorno de desarrollo

* XAMPP.
* Apache.

---

# Estado actual del proyecto

El proyecto se encuentra actualmente en la **Fase 4 — Implementación**.

Las fases de **Planificación**, **Documentación** y **Diseño UI/UX** se encuentran completadas.

Durante la fase de Diseño UI/UX se consolidaron el Design System, los componentes reutilizables, los estados de interacción, los criterios responsive, la estructura de navegación y las principales interfaces Desktop y Mobile trabajadas.

La implementación frontend ya inició tomando como referencia los diseños aprobados en Figma.

Actualmente, la interfaz de **Inicio** constituye la primera pantalla implementada en código. Esta implementación incluye:

* Header reutilizable.
* Footer reutilizable.
* Navegación responsive.
* Menú móvil.
* Isotipo.
* Favicon.
* Estructura CSS modular.
* Tipografía institucional.
* Adaptación Desktop.
* Adaptación Mobile.

La conexión entre PHP y MySQL también fue verificada previamente. Sin embargo, la estructura definitiva de tablas, relaciones y datos de la plataforma todavía no ha sido implementada.

El desarrollo continuará de manera progresiva, implementando las interfaces restantes y posteriormente las funcionalidades backend, la base de datos y la integración completa del sistema.

---

# Historial de cambios

| Versión | Fecha      | Cambio                                                                                                                                                                                                                                               |
| ------- | ---------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| v0.1.0  | 09/07/2026 | Creación inicial de los requisitos del proyecto.                                                                                                                                                                                                     |
| v0.1.0  | 05/09/2026 | Actualización de requisitos funcionales y no funcionales, incorporación de criterios UI/UX, componentes, estados, responsive y estado del proyecto.                                                                                                  |
| v0.1.1  | 07/10/2026 | Actualización general de requisitos, consolidación de la fase UI/UX, incorporación de las interfaces diseñadas, actualización del estado a Fase 4 — Implementación y preparación de los requisitos para la etapa de desarrollo.                      |
| v0.1.1  | 08/10/2026 | Actualización del estado de implementación frontend, incorporación de los avances de la interfaz Inicio, estructura CSS modular, componentes PHP reutilizables, navegación responsive, isotipo, favicon y conexión previamente verificada con MySQL. |

---

# Versión del documento

**Versión:** v0.1.1

**Estado:** 🟡 En desarrollo

**Fase:** Fase 4 — Implementación

**Última actualización:** 08 de octubre de 2026
