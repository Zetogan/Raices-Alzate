# 🌱 Raíces Alzate

> Plataforma digital para el seguimiento, acompañamiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

## 📖 1. Descripción del proyecto

**Raíces Alzate** es una plataforma web desarrollada con el propósito de fortalecer el vínculo entre la Institución Educativa Gilberto Alzate Avendaño y su comunidad de egresados.

El proyecto busca centralizar la información académica, profesional y de contacto de los exalumnos, facilitando el seguimiento institucional, la creación de oportunidades de networking y la consolidación de una comunidad activa que impulse el crecimiento académico, profesional y personal de sus integrantes.

La plataforma también permitirá compartir proyectos desarrollados por egresados, fomentar la colaboración y fortalecer el sentido de pertenencia con la institución mediante herramientas digitales modernas, escalables y de fácil acceso.

El proyecto se encuentra en desarrollo desde 2025. Su construcción se organiza por fases, desde la planificación y documentación inicial hasta el diseño UI/UX, la implementación, las pruebas y el despliegue.

Actualmente, el proyecto se encuentra en la **Fase 4 — Implementación**. La fase de diseño UI/UX está finalizada y sus diseños constituyen la referencia visual para el desarrollo de las interfaces.

---

## 🎯 2. Objetivo general

Desarrollar una plataforma web que permita gestionar la información de los egresados, fortalecer la comunicación entre la institución y sus exalumnos y generar oportunidades de desarrollo académico, profesional y colaborativo para toda la comunidad Alzate.

### 2.1. Objetivos específicos

* Centralizar la información académica, profesional y de contacto de los egresados.
* Facilitar el registro, la autenticación y la administración de usuarios.
* Permitir la consulta y actualización de perfiles.
* Fortalecer la comunicación entre la institución y su comunidad de egresados.
* Facilitar la publicación y consulta de proyectos desarrollados por los integrantes de la comunidad.
* Gestionar noticias e información institucional.
* Promover el networking, la colaboración y las oportunidades de desarrollo.
* Construir una interfaz accesible, intuitiva y adaptable a diferentes dispositivos.
* Mantener una arquitectura modular que facilite el mantenimiento y la evolución del sistema.
* Aplicar buenas prácticas de programación, protección de datos y seguridad durante la implementación.

---

## ✨ 3. Características principales

Las funcionalidades y características previstas para la plataforma incluyen:

### 3.1. Funcionalidades de usuario

* Registro de usuarios.
* Inicio y cierre de sesión.
* Recuperación de contraseña.
* Consulta y actualización de perfiles.
* Consulta de información de egresados.
* Publicación y consulta de proyectos.
* Consulta de noticias e información institucional.
* Acceso a las secciones habilitadas según el rol del usuario.

### 3.2. Funcionalidades administrativas

* Administración de usuarios.
* Gestión de roles y permisos.
* Gestión de información de egresados.
* Gestión de proyectos.
* Gestión y publicación de noticias.
* Acceso a un panel administrativo.
* Control de las operaciones permitidas para cada tipo de usuario.

### 3.3. Características técnicas y visuales

* Componentes de interfaz reutilizables.
* Design System desarrollado en Figma.
* Estados de interacción para los componentes.
* Diseño responsive.
* Interfaces Desktop y Mobile.
* Arquitectura modular.
* Separación de responsabilidades entre presentación, procesamiento y configuración.
* Documentación técnica organizada.
* Control de versiones mediante Git y GitHub.
* Desarrollo basado en buenas prácticas de programación y seguridad.

Estas características representan el alcance funcional previsto. Su disponibilidad definitiva dependerá de la implementación, integración y validación de cada módulo.

---

## 🛠️ 4. Tecnologías y herramientas

| Tecnología o herramienta | Uso previsto                                        |
| ------------------------ | --------------------------------------------------- |
| HTML5                    | Estructura semántica de las interfaces.             |
| CSS3                     | Diseño visual, estilos y adaptación responsive.     |
| JavaScript               | Interactividad y validaciones del lado del cliente. |
| PHP                      | Lógica del servidor y procesamiento de solicitudes. |
| MySQL                    | Almacenamiento y gestión de datos.                  |
| PDO                      | Acceso a la base de datos desde PHP.                |
| Git                      | Control de versiones.                               |
| GitHub                   | Alojamiento y gestión del repositorio.              |
| Figma                    | Diseño UI/UX, prototipos y Design System.           |
| Visual Studio Code       | Entorno de edición y desarrollo.                    |
| XAMPP                    | Entorno de desarrollo local.                        |

### 4.1. Criterios tecnológicos

* Utilizar PHP nativo con una organización modular.
* Emplear HTML5 semántico.
* Desarrollar los estilos con CSS vanilla.
* Utilizar JavaScript vanilla para la interactividad.
* Gestionar la base de datos MySQL mediante PDO y consultas preparadas.
* Evitar la incorporación de frameworks frontend que no formen parte del alcance tecnológico definido.
* Mantener una estructura clara que facilite el mantenimiento y la integración de nuevos módulos.
* Separar la configuración del sistema de las vistas y los componentes de presentación.
* Validar los datos tanto en el cliente como en el servidor cuando corresponda.
* Evitar la exposición de credenciales, información sensible y detalles internos de los errores.
* Mantener la compatibilidad con el entorno local y comprobar las versiones de las tecnologías antes del despliegue.

---

## 📂 5. Estructura general del proyecto

La estructura de referencia del repositorio contempla la siguiente organización:

```text
raices-alzate/
├── .github/
├── admin/
├── usuario/
├── assets/
│   ├── css/
│   ├── js/
│   ├── img/
│   ├── icons/
│   ├── fonts/
│   └── videos/
├── auth/
├── components/
├── config/
├── database/
├── docs/
├── includes/
├── pages/
├── uploads/
├── .gitignore
├── README.md
└── index.php
```

Esta estructura es una referencia de organización. La presencia, ubicación y responsabilidad definitiva de cada archivo o directorio deben corresponder al estado real del repositorio.

Durante la implementación, los componentes compartidos, las vistas, los estilos, los scripts y los archivos de configuración se organizan progresivamente para favorecer la separación de responsabilidades.

La organización modular busca facilitar:

* La separación de responsabilidades.
* La reutilización de componentes.
* El mantenimiento del código.
* La incorporación progresiva de funcionalidades.
* La integración entre las interfaces, la lógica del servidor y la base de datos.
* El trabajo colaborativo y el control de versiones.
* La identificación y corrección de errores.
* La evolución del sistema sin duplicar innecesariamente código.

La estructura definitiva deberá documentarse en la arquitectura técnica cuando se hayan consolidado las ubicaciones y responsabilidades de los archivos.

---

## 📚 6. Organización de la documentación

La documentación oficial se organiza principalmente dentro de la carpeta `docs/`, junto con archivos de seguimiento general ubicados en la raíz del repositorio.

| Documento                   | Descripción                                         |
| --------------------------- | --------------------------------------------------- |
| `README.md`                 | Presentación general del proyecto y guía de inicio. |
| `docs/README.md`            | Índice de la documentación técnica.                 |
| `docs/01_Bitacora.md`       | Registro cronológico del desarrollo.                |
| `docs/02_Planificacion.md`  | Planificación general del proyecto.                 |
| `docs/03_Requisitos.md`     | Requisitos funcionales y no funcionales.            |
| `docs/04_Arquitectura.md`   | Arquitectura general del sistema.                   |
| `docs/05_Manual_Tecnico.md` | Manual dirigido a desarrolladores.                  |
| `docs/06_Manual_Usuario.md` | Manual dirigido a usuarios finales.                 |
| `docs/glosario.md`          | Definición de términos utilizados en el proyecto.   |
| `docs/decisiones.md`        | Registro de decisiones importantes.                 |
| `CHANGELOG.md`              | Historial de cambios del proyecto.                  |
| `ROADMAP.md`                | Hoja de ruta y evolución del proyecto.              |

Los manuales técnico y de usuario se completarán progresivamente, a medida que las funcionalidades estén implementadas y verificadas.

Su contenido deberá describir el comportamiento real de la aplicación y no únicamente las funcionalidades previstas.

La documentación debe mantenerse actualizada durante todo el ciclo de desarrollo para garantizar la trazabilidad de las decisiones, los avances y los cambios realizados.

---

## 🚧 7. Estado actual del proyecto

**Versión documental:** `v0.1.1`

**Estado general:** 🟡 En desarrollo

**Fase actual:** Fase 4 — Implementación

**Inicio del proyecto:** 2025

**Última actualización de esta planificación:** 13 de octubre de 2026

### 7.1. Diseño UI/UX

La Fase 3 — Diseño UI/UX se encuentra finalizada desde finales de septiembre de 2026.

Durante esta etapa se definieron la identidad visual, el sistema de diseño, los componentes reutilizables, el flujo de información, la estructura de navegación y las principales interfaces de la plataforma.

El trabajo de diseño contempla **34 pantallas en Figma: 17 versiones Desktop y 17 adaptaciones Mobile**.

El diseño en Figma constituye la referencia visual para la implementación frontend. Las diferencias que se detecten durante el desarrollo deberán corregirse mediante revisiones y validaciones frente a los diseños aprobados.

### 7.2. Implementación frontend

La implementación frontend comenzó en octubre de 2026. Los primeros trabajos se centraron en la página de Inicio y en la estructura compartida de la aplicación.

Posteriormente, se avanzó en las interfaces de autenticación, registro y recuperación de contraseña, así como en elementos de navegación y en la estructura inicial del Dashboard.

Entre los avances reportados se encuentran:

* Implementación inicial de la página de Inicio.
* Creación e integración de componentes compartidos para el encabezado y el pie de página.
* Organización modular de los estilos CSS.
* Incorporación de la navegación responsive y del menú móvil.
* Adaptación inicial de Inicio para dispositivos móviles.
* Desarrollo inicial de las interfaces de inicio de sesión, registro y recuperación de contraseña.
* Ajustes en la navegación y en las rutas de acceso a las vistas.
* Desarrollo inicial de la estructura visual del Dashboard.
* Organización de la barra lateral y de la barra superior del panel.
* Incorporación de estados visuales para campos, formularios y mensajes de retroalimentación.
* Trabajo inicial en elementos de carga y retroalimentación de la interfaz.
* Ajustes relacionados con el funcionamiento de las sesiones y el acceso a las vistas durante las pruebas.

Estos avances no significan que todas las interfaces estén finalizadas ni que los flujos estén listos para producción. Es necesario completar la revisión visual, la adaptación responsive, la integración funcional y las pruebas correspondientes.

### 7.3. Backend

Se han iniciado tareas de procesamiento de formularios y autenticación mediante PHP.

También se han reportado ajustes en el manejo de sesiones, la navegación entre vistas y el acceso al Dashboard durante el desarrollo.

La implementación del backend deberá continuar con la consolidación de la autenticación, los permisos, los perfiles, los egresados, los proyectos, las noticias y las funcionalidades administrativas.

Los mecanismos auxiliares de acceso utilizados para probar las interfaces deben permanecer restringidos al entorno de desarrollo y no deben habilitarse como mecanismos de acceso en producción.

### 7.4. Base de datos

La conexión entre PHP y MySQL se ha reportado como verificada en el entorno de desarrollo.

Sin embargo, la estructura definitiva de la base de datos, sus relaciones y la integración completa de las operaciones de cada módulo deben documentarse y validarse durante la implementación.

La verificación de conectividad no implica que todas las tablas, relaciones y operaciones CRUD estén terminadas.

La siguiente etapa de trabajo deberá definir el modelo de datos, establecer las relaciones entre entidades y conectar progresivamente los módulos de la aplicación con las operaciones correspondientes de la base de datos.

### 7.5. Integración y pruebas

Se han reportado comprobaciones iniciales de sintaxis PHP y respuestas HTTP de algunas rutas durante el desarrollo.

Estas comprobaciones constituyen verificaciones preliminares, pero no sustituyen las pruebas funcionales, de integración, de seguridad, de accesibilidad y de comportamiento responsive.

La validación integral deberá comprobar que los formularios, las sesiones, los permisos, las consultas a la base de datos y las interfaces funcionen de manera coherente.

---

## 🎨 8. Diseño UI/UX completado

### 8.1. Design System

Se definieron los fundamentos visuales de la plataforma:

* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Jerarquía tipográfica.
* Botones reutilizables.
* Estados de interacción de los componentes.
* Criterios generales de consistencia visual.

La implementación deberá conservar la coherencia con el sistema visual aprobado en Figma.

### 8.2. Components & Responsive

Se trabajaron los siguientes componentes:

* Inputs.
* Checkbox.
* Radio Button.
* Switch.
* Tablas.
* Estados normales.
* Estados seleccionados.
* Estados de foco.
* Estados deshabilitados.
* Estados de error.
* Criterios de adaptación responsive.

Estos componentes sirven como referencia para mantener la consistencia de las interfaces y reducir las diferencias visuales entre los módulos.

### 8.3. Flujo de información y navegación

Se definieron el flujo general de información, la estructura de navegación y las relaciones principales entre las pantallas de la plataforma.

La estructura contempla los accesos a Inicio, Autenticación, Dashboard, Perfil, Egresados, Proyectos, Noticias y Administración, además de las pantallas destinadas a crear, consultar y editar contenidos.

La navegación implementada deberá respetar los permisos y las condiciones de acceso correspondientes a cada módulo.

### 8.4. Interfaces diseñadas

Se completó el diseño de las siguientes 17 interfaces Desktop:

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

También se diseñaron las 17 adaptaciones Mobile correspondientes.

**Estado de la fase:** Finalizada.

**Nota:** El cierre del diseño UI/UX no significa que todas las adaptaciones responsive estén implementadas ni que todas las pantallas hayan sido verificadas en código. Esa validación corresponde a la fase de implementación.

---

## 📊 9. Estado general de las etapas

| Etapa                    | Estado                                                                  |
| ------------------------ | ----------------------------------------------------------------------- |
| Preparación del proyecto | 🟩 Completada                                                           |
| Planificación inicial    | 🟩 Completada                                                           |
| Documentación base       | 🟩 Completada, con actualización continua                               |
| Diseño UI/UX             | 🟩 Completado                                                           |
| Desarrollo frontend      | 🟨 En progreso                                                          |
| Desarrollo backend       | 🟨 En desarrollo inicial                                                |
| Base de datos            | 🟨 Conectividad reportada como verificada; esquema definitivo pendiente |
| Integración de módulos   | ⬜ Pendiente de completar                                                |
| Pruebas integrales       | ⬜ Pendientes de ejecución integral                                      |
| Despliegue               | ⬜ Pendiente                                                             |

> Los estados representan el avance general de las etapas. Deben actualizarse conforme se implementen y verifiquen las funcionalidades. No equivalen a porcentajes exactos de avance.

---

# 🗺️ 10. Plan de trabajo por fases

## ✅ Fase 0 — Preparación

**Objetivo:** establecer las condiciones iniciales para organizar y desarrollar el proyecto.

Actividades:

* Configurar el repositorio.
* Configurar Git y GitHub.
* Organizar la estructura inicial del proyecto.
* Preparar el entorno de trabajo.
* Crear la documentación base.

**Estado:** Completada.

---

## ✅ Fase 1 — Planificación

**Objetivo:** definir el propósito, el alcance inicial y la organización del proyecto.

Actividades:

* Definir el propósito del proyecto.
* Establecer el objetivo general.
* Identificar las características principales.
* Definir las tecnologías de desarrollo.
* Organizar la estructura inicial.
* Establecer el flujo general de trabajo.

**Estado:** Completada.

---

## ✅ Fase 2 — Documentación

**Objetivo:** establecer una base documental que facilite el seguimiento y la comprensión del proyecto.

Actividades:

* Elaborar la bitácora.
* Desarrollar la planificación.
* Documentar los requisitos.
* Definir la arquitectura inicial.
* Preparar los archivos README.
* Crear el roadmap.
* Crear el changelog.
* Registrar decisiones importantes.
* Elaborar el glosario.
* Organizar el índice de documentación.

Los manuales técnico y de usuario se completarán conforme existan funcionalidades verificadas que permitan documentarlas con precisión.

**Estado:** Documentación base completada; actualización continua durante las siguientes fases.

---

## ✅ Fase 3 — Diseño UI/UX

**Objetivo:** establecer la estructura visual, los componentes y las interfaces que servirán como referencia para la implementación.

### 3.1. Design System

* Definir la identidad visual.
* Establecer la paleta de colores.
* Definir la tipografía.
* Establecer el sistema de espaciado.
* Diseñar los botones.
* Definir los estados de los componentes.

### 3.2. Components & Responsive

* Diseñar inputs.
* Diseñar checkbox.
* Diseñar radio buttons.
* Diseñar switches.
* Diseñar tablas.
* Definir estados de interacción.
* Establecer criterios responsive.

### 3.3. Estructura y flujo

* Definir el flujo de información.
* Diseñar la estructura de navegación.
* Establecer los recorridos principales de los usuarios.

### 3.4. Interfaces

* Diseñar las principales interfaces Desktop.
* Diseñar las adaptaciones Mobile.
* Consolidar visualmente las pantallas.
* Revisar la coherencia general del sistema de diseño.

**Estado:** Finalizada a finales de septiembre de 2026.

---

## 🔄 Fase 4 — Implementación

**Objetivo:** convertir los diseños y las especificaciones definidas durante las fases anteriores en una aplicación web funcional.

### 4.1. Frontend

#### 4.1.1. Inicio y estructura compartida

* [x] Implementación inicial de la estructura de Inicio.
* [x] Implementación inicial del encabezado compartido.
* [x] Implementación inicial del pie de página compartido.
* [x] Organización inicial de los estilos CSS.
* [x] Incorporación de la navegación responsive.
* [x] Implementación inicial del menú móvil.
* [x] Incorporación de elementos de identidad visual.
* [x] Adaptación inicial de Inicio Mobile.
* [ ] Revisión visual completa de Inicio frente a Figma.
* [ ] Verificación final de accesibilidad y comportamiento responsive.
* [ ] Validación de enlaces y navegación.
* [ ] Pruebas en distintos tamaños de pantalla.

#### 4.1.2. Dashboard y navegación interna

* [x] Desarrollo inicial de la estructura visual del Dashboard.
* [x] Organización inicial de la barra lateral.
* [x] Incorporación de la barra superior del panel.
* [x] Trabajo inicial en el comportamiento de expansión y contracción de la barra lateral.
* [x] Ajustes iniciales de navegación para pantallas pequeñas.
* [x] Ajustes visuales de elementos de acción del Dashboard.
* [ ] Verificar todos los enlaces y destinos de navegación.
* [ ] Completar la integración funcional de las secciones del panel.
* [ ] Validar la persistencia de las preferencias de navegación.
* [ ] Completar la adaptación responsive.
* [ ] Comparar el Dashboard implementado con Figma.
* [ ] Verificar los permisos de acceso a cada sección.
* [ ] Sustituir los datos simulados por datos reales cuando corresponda.

Las casillas marcadas indican avances reportados durante el desarrollo inicial. No significan que el Dashboard esté terminado ni que todas sus funciones estén integradas.

#### 4.1.3. Autenticación, registro y recuperación

* [x] Desarrollo inicial de la interfaz de inicio de sesión.
* [x] Desarrollo inicial de la interfaz de registro.
* [x] Desarrollo inicial de la interfaz de recuperación de contraseña.
* [x] Incorporación de validaciones y estados visuales reportados.
* [x] Ajustes iniciales de las rutas de acceso a las vistas.
* [ ] Verificar el funcionamiento integral de los tres flujos.
* [ ] Comprobar la integración con la base de datos.
* [ ] Verificar el manejo de sesiones y las respuestas del servidor.
* [ ] Validar los casos de error.
* [ ] Verificar los controles de seguridad.
* [ ] Completar las adaptaciones responsive.
* [ ] Comparar las interfaces implementadas con los diseños de Figma.
* [ ] Comprobar el cierre de sesión y la protección de las rutas privadas.
* [ ] Verificar que los mecanismos de prueba no estén disponibles en producción.

> Las casillas marcadas indican avances de implementación reportados. Los flujos de autenticación, registro y recuperación no deben considerarse terminados hasta que sus operaciones se integren y se validen de extremo a extremo.

#### 4.1.4. Interfaces restantes

Las siguientes pantallas forman parte del diseño aprobado y deberán implementarse e integrarse progresivamente:

* [ ] Perfil.
* [ ] Egresados.
* [ ] Proyectos.
* [ ] Noticias.
* [ ] Administración.
* [ ] Crear proyecto.
* [ ] Detalle de proyecto.
* [ ] Editar perfil.
* [ ] Editar proyecto.
* [ ] Crear noticia.
* [ ] Detalle de noticia.
* [ ] Editar noticia.

La implementación de estas interfaces deberá contemplar sus respectivos estados, formularios, permisos, datos y comportamientos responsive.

#### 4.1.5. Responsive

* [ ] Completar las adaptaciones Mobile pendientes.
* [ ] Validar la navegación responsive.
* [ ] Revisar las interfaces en diferentes tamaños de pantalla.
* [ ] Comparar las interfaces implementadas con los diseños aprobados.
* [ ] Comprobar la usabilidad en dispositivos móviles.
* [ ] Corregir desbordamientos y problemas de distribución.
* [ ] Verificar la legibilidad de textos y controles.
* [ ] Comprobar la interacción con formularios, menús y componentes.
* [ ] Validar los estados de foco y la navegación mediante teclado.

### 4.2. Backend

Actividades previstas:

* [ ] Consolidar la estructura de procesamiento del servidor.
* [ ] Implementar y verificar la autenticación.
* [ ] Implementar la gestión de sesiones.
* [ ] Definir y aplicar los roles y permisos.
* [ ] Completar la gestión de usuarios.
* [ ] Implementar la gestión de perfiles.
* [ ] Implementar la gestión de egresados.
* [ ] Implementar la gestión de proyectos.
* [ ] Implementar la gestión de noticias.
* [ ] Desarrollar las funcionalidades administrativas.
* [ ] Validar los datos recibidos desde los formularios.
* [ ] Aplicar controles de seguridad en las operaciones del servidor.
* [ ] Implementar respuestas de error coherentes.
* [ ] Comprobar que las rutas privadas exijan autenticación y autorización.
* [ ] Evitar que datos simulados se confundan con información persistida.

El avance inicial de los procesos de autenticación no significa que el backend completo se encuentre terminado.

### 4.3. Base de datos

Actividades previstas:

* [ ] Definir el modelo de datos definitivo.
* [ ] Diseñar la estructura de la base de datos.
* [ ] Crear las tablas necesarias.
* [ ] Definir claves primarias y foráneas.
* [ ] Establecer las relaciones entre entidades.
* [ ] Configurar restricciones de integridad.
* [ ] Integrar los módulos PHP con MySQL.
* [ ] Implementar operaciones CRUD.
* [ ] Validar consultas y transacciones.
* [ ] Verificar el tratamiento de errores de conexión y operaciones.
* [ ] Comprobar la persistencia y consistencia de los datos.
* [ ] Documentar el esquema definitivo.
* [ ] Preparar un procedimiento controlado para inicializar la base de datos.

**Estado conocido:** la conexión entre PHP y MySQL se ha reportado como verificada. El diseño definitivo del esquema y la integración completa de los módulos continúan pendientes.

### 4.4. Seguridad

Actividades previstas:

* [ ] Verificar el uso de consultas preparadas.
* [ ] Comprobar el almacenamiento seguro de contraseñas.
* [ ] Verificar la gestión de sesiones.
* [ ] Implementar y comprobar controles de autorización.
* [ ] Validar las entradas recibidas desde el cliente.
* [ ] Revisar los mensajes de error para evitar la exposición de información sensible.
* [ ] Completar el flujo de recuperación de contraseña.
* [ ] Verificar la generación, el almacenamiento y la expiración de los tokens de recuperación.
* [ ] Garantizar que los tokens de recuperación sean de un solo uso.
* [ ] Configurar el envío de correos cuando se habilite el servicio SMTP.
* [ ] Revisar la configuración del entorno antes del despliegue.
* [ ] Proteger las credenciales de conexión y demás datos sensibles.
* [ ] Deshabilitar los mecanismos auxiliares de acceso utilizados durante las pruebas.
* [ ] Revisar la protección de las operaciones que modifican información.
* [ ] Comprobar el manejo de errores sin revelar detalles internos.

### 4.5. Integración

* [ ] Integrar frontend y backend.
* [ ] Integrar las operaciones con MySQL.
* [ ] Validar los formularios de extremo a extremo.
* [ ] Comprobar la gestión de sesiones.
* [ ] Verificar permisos y roles.
* [ ] Integrar progresivamente los módulos.
* [ ] Comprobar el tratamiento de errores.
* [ ] Validar la persistencia de los datos.
* [ ] Comprobar las rutas y los enlaces entre pantallas.
* [ ] Verificar que los datos mostrados correspondan a la información almacenada.
* [ ] Revisar la integración entre componentes compartidos y vistas.

**Criterio de cierre de la fase:** los módulos previstos deberán estar implementados, integrados y preparados para las pruebas funcionales, de seguridad y de usabilidad.

---

## ⏳ Fase 5 — Pruebas y validación

**Objetivo:** comprobar que la aplicación cumple los requisitos definidos y que sus módulos funcionan de manera coherente.

Actividades:

* [ ] Ejecutar pruebas funcionales.
* [ ] Probar los formularios.
* [ ] Verificar el registro y el inicio de sesión.
* [ ] Probar la recuperación de contraseña.
* [ ] Validar las sesiones.
* [ ] Comprobar los permisos y roles.
* [ ] Probar las operaciones CRUD.
* [ ] Verificar la persistencia de los datos.
* [ ] Ejecutar pruebas responsive.
* [ ] Revisar accesibilidad y navegación por teclado.
* [ ] Corregir errores detectados.
* [ ] Comprobar la integración entre módulos.
* [ ] Comparar las interfaces con los diseños de Figma.
* [ ] Ejecutar pruebas de regresión.
* [ ] Verificar el manejo de errores.
* [ ] Comprobar el comportamiento en distintos navegadores.
* [ ] Documentar los resultados y los errores encontrados.
* [ ] Repetir las pruebas después de corregir errores relevantes.

**Estado:** Pendiente de ejecución integral.

Las comprobaciones preliminares realizadas durante la implementación no sustituyen esta fase.

---

## ⏳ Fase 6 — Despliegue y cierre

**Objetivo:** preparar una versión estable de la aplicación y completar su documentación final.

Actividades:

* [ ] Preparar el entorno de producción.
* [ ] Configurar el servidor.
* [ ] Configurar la base de datos de producción.
* [ ] Definir y proteger las variables de configuración.
* [ ] Configurar los servicios externos necesarios.
* [ ] Desplegar la aplicación.
* [ ] Ejecutar pruebas en el entorno de producción.
* [ ] Elaborar el Manual Técnico.
* [ ] Elaborar el Manual de Usuario.
* [ ] Actualizar la documentación.
* [ ] Revisar los requisitos de seguridad.
* [ ] Comprobar que no existan mecanismos de prueba habilitados en producción.
* [ ] Preparar la versión estable del proyecto.
* [ ] Documentar el procedimiento de mantenimiento y actualización.

**Estado:** Pendiente.

---

## ⚙️ 11. Preparación del entorno de desarrollo

### 11.1. Requisitos previos

Para preparar el entorno local se recomienda contar con:

* PHP 8.0 o superior, sujeto a compatibilidad con la configuración real del proyecto.
* MySQL 8.0 o una versión compatible.
* Un servidor local compatible con PHP, como XAMPP.
* Git.
* Visual Studio Code u otro editor compatible.
* Un navegador web actualizado.
* Acceso al repositorio de GitHub.

Las versiones definitivas deberán verificarse frente a la configuración real del proyecto antes de su despliegue.

### 11.2. Clonar el repositorio

```bash
git clone https://github.com/Zetogan/Raices-Alzate.git
```

### 11.3. Ingresar al proyecto

```bash
cd Raices-Alzate
```

### 11.4. Configurar el entorno

1. Instalar y configurar un servidor local compatible con PHP.
2. Ubicar el proyecto en el directorio correspondiente del servidor local.
3. Configurar los parámetros de conexión a la base de datos de acuerdo con los archivos reales del proyecto.
4. Crear o importar la base de datos cuando exista un esquema definido y disponible.
5. Configurar las variables de entorno o los parámetros locales que requiera la aplicación.
6. Iniciar el servidor local y el servicio de base de datos.
7. Abrir la aplicación desde el navegador utilizando la ruta correspondiente.
8. Comprobar que las rutas, los recursos CSS y los scripts JavaScript se carguen correctamente.
9. Revisar los mensajes de error del servidor y del navegador durante las primeras pruebas.

> Los pasos específicos de creación e importación de la base de datos deberán completarse cuando el esquema definitivo esté documentado. No se deben inventar nombres de bases de datos, tablas ni credenciales.

> Las instrucciones definitivas de instalación deberán contrastarse con la configuración real del repositorio y con el entorno de desarrollo utilizado por el equipo.

---

## ▶️ 12. Uso previsto de la plataforma

Raíces Alzate se encuentra en desarrollo y todavía no cuenta con una versión final de producción.

Los módulos de Inicio, autenticación, registro y recuperación de contraseña tienen avances iniciales de implementación. También se ha trabajado en la estructura visual del Dashboard y en elementos compartidos de navegación.

Su funcionamiento integral debe comprobarse antes de considerar que la plataforma está lista para un uso general.

Las funcionalidades previstas incluyen:

* Registro de usuarios.
* Inicio y cierre de sesión.
* Recuperación de contraseña.
* Consulta y edición de perfiles.
* Gestión de egresados.
* Administración de usuarios.
* Publicación y consulta de proyectos.
* Publicación y gestión de noticias.
* Consulta de información institucional.
* Panel administrativo.

Estas funcionalidades se incorporarán progresivamente durante las fases de implementación e integración.

La documentación de uso se ampliará cuando cada módulo disponga de un comportamiento estable y verificable.

---

## 📅 13. Próximos objetivos

Los próximos objetivos corresponden a continuar con la Fase 4 — Implementación.

### 13.1. Frontend

* Revisar la correspondencia visual de Inicio con Figma.
* Verificar los flujos iniciales de autenticación, registro y recuperación.
* Continuar la implementación de las pantallas diseñadas.
* Consolidar la estructura visual del Dashboard.
* Implementar los componentes definidos en el Design System.
* Completar las adaptaciones responsive.
* Incorporar la interactividad necesaria mediante JavaScript.
* Mantener la coherencia visual entre las diferentes interfaces.
* Verificar los enlaces y las rutas de navegación.
* Revisar los estados de carga, error y retroalimentación.
* Comprobar la accesibilidad de los elementos interactivos.

### 13.2. Backend

* Verificar y consolidar los procesos iniciales de autenticación.
* Completar la gestión de sesiones.
* Implementar los roles y permisos.
* Desarrollar las funcionalidades principales.
* Validar los procesos de registro y recuperación de contraseña.
* Aplicar y comprobar las medidas de seguridad necesarias.
* Verificar que las rutas privadas estén protegidas.
* Integrar progresivamente las operaciones con la base de datos.
* Eliminar o restringir los mecanismos auxiliares utilizados exclusivamente durante las pruebas.

### 13.3. Base de datos

* Definir el esquema definitivo.
* Crear las tablas necesarias.
* Establecer relaciones e integridad referencial.
* Implementar operaciones CRUD.
* Integrar la base de datos con los módulos PHP.
* Probar las consultas y el manejo de errores.
* Documentar el modelo de datos.
* Verificar la persistencia de la información.

### 13.4. Integración y pruebas

* Verificar los flujos completos desde la interfaz hasta la base de datos.
* Validar las respuestas de los formularios.
* Comprobar los permisos y la gestión de sesiones.
* Ejecutar pruebas funcionales y responsive.
* Comparar las interfaces implementadas con los diseños aprobados.
* Corregir errores y documentar los resultados.
* Actualizar la planificación y la bitácora según los avances verificados.

---

## 👥 14. Equipo de desarrollo

Proyecto desarrollado por estudiantes de la Institución Educativa Gilberto Alzate Avendaño.

### 14.1. Integrantes

* Emanuel Cadavid.
* Harold Montoya.
* Santiago Delgado Martínez.
* Santiago Sánchez Emitola.

**Responsable de la documentación del proyecto:** Santiago Emitola.

---

## 🤝 15. Contribuciones

Actualmente, el desarrollo de Raíces Alzate está a cargo del equipo responsable del proyecto.

En futuras versiones se documentará una guía de contribución que incluya:

* Flujo de trabajo con Git.
* Convenciones de nombres y commits.
* Estándares de programación.
* Criterios de organización del código.
* Estándares de documentación.
* Procedimiento para reportar errores.
* Procedimiento para proponer nuevas funcionalidades.
* Revisión y validación de cambios.
* Buenas prácticas para el manejo de ramas y solicitudes de integración.

Los cambios deberán revisarse antes de incorporarse a la rama principal, de acuerdo con el flujo de trabajo establecido por el equipo.

---

## 📄 16. Licencia

La licencia del proyecto deberá corresponder al archivo de licencia oficial incluido en el repositorio.

Si el proyecto se distribuye bajo la licencia MIT, el archivo `LICENSE` deberá estar presente y contener los términos correspondientes.

Antes de publicar o distribuir el proyecto, se debe verificar que la licencia declarada en este documento coincida con la licencia real del repositorio.

Mientras no se haya confirmado la licencia definitiva, no debe afirmarse que el proyecto se distribuye bajo una licencia específica.

---

## 💙 17. Agradecimientos

Agradecemos a la Institución Educativa Gilberto Alzate Avendaño, a los docentes, directivos y a todas las personas que hacen posible el desarrollo de este proyecto.

Su acompañamiento fortalece la construcción de una plataforma que busca mantener un vínculo permanente entre la institución y su comunidad de egresados.

---

<div align="center">

# 🌱 Raíces Alzate

### Conectando generaciones, fortaleciendo el futuro.

**Versión documental:** `v0.1.1`

Desarrollado con ❤️ por el equipo de Raíces Alzate.

</div>
