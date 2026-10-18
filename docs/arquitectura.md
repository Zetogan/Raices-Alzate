# 🌱 Raíces Alzate

> Plataforma web para la gestión, el seguimiento y el fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 🏗️ Arquitectura del Proyecto

## Información general

| Campo                            | Información                                    |
| -------------------------------- | ---------------------------------------------- |
| Proyecto                         | Raíces Alzate                                  |
| Institución                      | Institución Educativa Gilberto Alzate Avendaño |
| Desarrollador                    | Santiago Emitola                               |
| Versión                          | v0.2.1                                         |
| Estado                           | 🟡 En desarrollo                               |
| Etapa actual                     | Implementación e integración funcional         |
| Inicio del proyecto              | 2025                                           |
| Última actualización documentada | 17 de octubre de 2026                          |

---

# 1. Introducción

Este documento describe la arquitectura general de **Raíces Alzate**, incluyendo las tecnologías utilizadas, la separación de responsabilidades, los módulos principales, la organización de archivos, los flujos de navegación y los mecanismos para procesar y almacenar información.

La arquitectura busca facilitar el mantenimiento, la reutilización de componentes, la seguridad, la trazabilidad y la evolución progresiva de la plataforma.

El proyecto comenzó en **2025**. Durante sus primeras etapas se trabajaron la planificación, la documentación base, los requerimientos y el diseño UI/UX.

La Fase 3 — Diseño UI/UX se encuentra finalizada. Como resultado, se definieron el sistema visual, los componentes reutilizables, los estados de interacción, los flujos de navegación y las interfaces de escritorio y dispositivos móviles.

Posteriormente comenzó la implementación de las interfaces y funcionalidades. En la versión **v0.2.1**, el desarrollo avanzó desde la construcción de interfaces hacia la integración de módulos, la implementación de la base de datos y el fortalecimiento de la autenticación.

Entre los avances reportados se encuentran:

* Implementación de la página de Inicio.
* Desarrollo de interfaces y componentes del Dashboard.
* Organización de vistas y componentes PHP compartidos.
* Estandarización de la cabecera pública y la navegación autenticada.
* Implementación de un menú lateral colapsable.
* Adaptación de la navegación a dispositivos móviles.
* Incorporación y ajuste de un indicador de carga.
* Desarrollo de interfaces de perfil, egresados, proyectos, noticias y administración.
* Implementación de operaciones relacionadas con proyectos y noticias.
* Incorporación de funcionalidades de mensajería y conexiones entre egresados.
* Desarrollo de funcionalidades relacionadas con PQRS.
* Creación del esquema de la base de datos y configuración de la conexión mediante PDO.
* Desarrollo de procesos de registro e inicio de sesión.
* Implementación del flujo de recuperación y restablecimiento de contraseñas.
* Integración de PHPMailer para el envío de correos electrónicos.
* Ajustes en las rutas, la gestión de sesiones y los componentes compartidos.
* Actualización progresiva de la documentación técnica.

La implementación continúa de manera incremental. La existencia de una interfaz o de un archivo de procesamiento no implica que todas las funcionalidades asociadas estén terminadas, correctamente integradas o preparadas para producción.

La versión actual representa un avance importante en la construcción de la plataforma, pero todavía requiere completar y verificar operaciones, controles de acceso, persistencia de datos, pruebas funcionales y medidas de seguridad.

---

# 2. Arquitectura general

Raíces Alzate utiliza una arquitectura web basada en el modelo **cliente-servidor**, con separación entre la presentación, el procesamiento de solicitudes y el almacenamiento de información.

```text
                         USUARIO
                            │
                            ▼
                      NAVEGADOR WEB
                            │
                            ▼
                    CAPA DE PRESENTACIÓN
                 HTML5 · CSS3 · JavaScript
                            │
                            ▼
                   PUNTO DE ENTRADA
                       index.php
                            │
                            ▼
                  RUTAS, VISTAS Y CONTROLADORES
                            │
                            ▼
                   LÓGICA DE APLICACIÓN
              Validaciones, sesiones y procesos
                            │
                            ▼
                    CAPA DE ACCESO A DATOS
                           PDO
                            │
                            ▼
                    BASE DE DATOS RELACIONAL
                       MySQL / MariaDB

                            │
                            ▼
                 SERVICIOS COMPLEMENTARIOS
                   PHPMailer · SMTP
```

El navegador presenta las interfaces y permite la interacción del usuario. Las solicitudes que requieren procesamiento en el servidor son atendidas por PHP.

Cuando una funcionalidad necesita consultar o modificar información persistente, el backend utiliza PDO para comunicarse con la base de datos.

PHPMailer participa en los procesos que requieren envío de correos electrónicos, como la recuperación de contraseñas, mediante el servicio de correo configurado.

El diagrama representa la organización general de la aplicación. Los flujos concretos pueden variar según el módulo y la ruta utilizada.

## 2.1. Enfoque arquitectónico

La implementación sigue estos criterios:

* PHP nativo para el procesamiento del lado del servidor.
* HTML5 semántico para estructurar las interfaces.
* CSS3 modular para la presentación visual.
* JavaScript nativo para la interactividad del navegador.
* Organización modular de vistas y recursos.
* Uso de `index.php` como punto de entrada para las rutas que administra.
* Controladores PHP independientes para determinadas operaciones.
* Vistas organizadas principalmente en `pages/`.
* Componentes compartidos para elementos reutilizables.
* Separación del procesamiento de formularios mediante archivos especializados.
* Acceso a datos mediante PDO.
* Almacenamiento persistente en MySQL o MariaDB.
* Uso de PHPMailer para el envío de correos electrónicos.
* Uso de Git y GitHub para el control de versiones.

La arquitectura puede evolucionar conforme se incorporen nuevos módulos, manteniendo la separación de responsabilidades.

---

# 3. Tecnologías utilizadas

## 3.1. Frontend

| Tecnología        | Responsabilidad                                       |
| ----------------- | ----------------------------------------------------- |
| HTML5             | Estructura semántica de las interfaces.               |
| CSS3              | Presentación visual, componentes y diseño responsive. |
| JavaScript nativo | Interactividad y comportamiento del navegador.        |
| Inter             | Tipografía principal de las interfaces.               |
| Figma             | Referencia visual del diseño y los componentes.       |

El frontend se desarrolla principalmente con tecnologías nativas. La implementación debe respetar los diseños aprobados en Figma y mantener una presentación consistente entre los módulos.

## 3.2. Backend

| Tecnología o mecanismo | Responsabilidad                                                 |
| ---------------------- | --------------------------------------------------------------- |
| PHP                    | Procesamiento de solicitudes y lógica de aplicación.            |
| Controladores PHP      | Gestión de operaciones y formularios.                           |
| Sesiones PHP           | Mantenimiento del estado de autenticación.                      |
| PDO                    | Acceso a la base de datos.                                      |
| `password_hash()`      | Generación de hashes de contraseñas.                            |
| `password_verify()`    | Verificación de contraseñas almacenadas.                        |
| Tokens de seguridad    | Protección de operaciones específicas, según su implementación. |
| PHPMailer              | Envío de correos electrónicos mediante SMTP.                    |

Los mecanismos de autenticación, autorización y gestión de sesiones deben verificarse por funcionalidad. La presencia de estas herramientas en el proyecto no implica que todas las operaciones estén completamente auditadas.

## 3.3. Base de datos

| Tecnología o recurso  | Responsabilidad                               |
| --------------------- | --------------------------------------------- |
| MySQL / MariaDB       | Almacenamiento persistente de la información. |
| SQL                   | Definición de tablas y operaciones de datos.  |
| PDO                   | Comunicación entre PHP y la base de datos.    |
| `config/database.php` | Configuración y obtención de la conexión.     |
| `database/schema.sql` | Definición del esquema de la base de datos.   |
| `database/setup.php`  | Inicialización y comprobación del esquema.    |

La versión `v0.2.1` incorpora un esquema de base de datos y un mecanismo de conexión centralizada. La integración de cada módulo debe comprobarse mediante pruebas de sus operaciones reales.

## 3.4. Herramientas de desarrollo

* Visual Studio Code para la edición del código.
* Git para el control de versiones.
* GitHub para el alojamiento del repositorio privado y la gestión de cambios.
* Figma para el diseño UI/UX.
* XAMPP para el entorno local de desarrollo.
* Antigravity y Gemini como herramientas de apoyo para la implementación y revisión del código.
* Navegador web y terminal para realizar verificaciones técnicas.

---

# 4. Arquitectura por capas

## 4.1. Capa de presentación

La capa de presentación muestra la información y permite que los usuarios interactúen con la plataforma.

Está compuesta principalmente por HTML5, CSS3, JavaScript y las vistas PHP.

Sus responsabilidades incluyen:

* Representar las interfaces diseñadas en Figma.
* Presentar formularios y controles.
* Mostrar mensajes de validación y alertas.
* Gestionar los estados visuales de los componentes.
* Adaptar las interfaces a escritorio y dispositivos móviles.
* Reutilizar elementos comunes.
* Facilitar la navegación.
* Proporcionar retroalimentación visual durante las operaciones.

### Sistema visual

La implementación utiliza como referencia el Design System definido durante la etapa de diseño UI/UX.

Este sistema establece:

* Identidad visual.
* Paleta de colores.
* Tipografía.
* Escala de espaciado.
* Estilos de botones.
* Campos de formulario.
* Checkbox.
* Radio Button.
* Switch.
* Tablas.
* Estados de interacción.
* Criterios de diseño responsive.

Las principales referencias de diseño son:

* **Desktop:** ancho de referencia de 1440 px.
* **Mobile:** ancho de referencia de 390 px.

Estas dimensiones no representan las únicas resoluciones compatibles. La implementación debe adaptarse también a tamaños intermedios.

### Organización de estilos CSS

Los estilos se distribuyen en hojas de estilos según su responsabilidad.

Entre los archivos identificados en los avances se encuentran:

```text
assets/
├── css/
│   ├── main.css
│   ├── fonts.css
│   ├── header.css
│   ├── footer.css
│   ├── auth.css
│   ├── portal.css
│   ├── dashboard.css
│   ├── perfil.css
│   ├── egresados.css
│   ├── proyectos.css
│   ├── noticias.css
│   ├── oportunidades.css
│   ├── administracion.css
│   ├── mensajes.css
│   ├── pqr.css
│   └── loader.css
└── js/
    ├── main.js
    ├── auth.js
    ├── dashboard.js
    ├── perfil.js
    ├── egresados.js
    ├── proyectos.js
    ├── noticias.js
    ├── oportunidades.js
    ├── mensajes.js
    ├── pqr.js
    └── loader.js
```

Esta representación es orientativa y reúne nombres de recursos identificados durante los avances. La existencia y ubicación definitiva de cada archivo deben mantenerse alineadas con el repositorio.

### `main.css`

Contiene estilos generales del proyecto y reglas visuales compartidas.

Sus responsabilidades pueden incluir:

* Reglas base.
* Colores generales.
* Estilos comunes de enlaces y botones.
* Alertas y mensajes de estado.
* Elementos visuales reutilizables.

Las reglas globales deben evitar interferir accidentalmente con componentes específicos.

### `header.css`

Contiene los estilos específicos de la cabecera pública.

Los avances reportados incluyen:

* Normalización de la altura.
* Alineación de la marca y la navegación.
* Fondo sólido.
* Borde inferior.
* Comportamiento sticky.
* Ajustes responsive.
* Correcciones en la distribución de acciones y botones.

Los informes de implementación registraron diferentes valores para el ancho máximo del contenedor. El valor definitivo debe mantenerse de acuerdo con el código consolidado.

### `footer.css`

Contiene los estilos del pie de página compartido.

El diseño de referencia contempla:

* Identidad visual de Raíces Alzate.
* Enlaces de navegación.
* Información institucional.
* Separador visual del bloque de copyright.
* Adaptación a pantallas móviles.

### `auth.css`

Contiene los estilos relacionados con las interfaces de autenticación:

* Formularios.
* Campos de entrada.
* Botones.
* Estados de validación.
* Mensajes de error.
* Alertas.
* Espaciado y alineación.
* Adaptación responsive.

### `dashboard.css`

Contiene estilos de la interfaz autenticada del Dashboard.

Los comportamientos documentados incluyen:

* Distribución de la cabecera autenticada.
* Barra superior responsive.
* Menú lateral.
* Sidebar colapsable en escritorio.
* Sidebar adaptado a móviles.
* Estados visuales de navegación.
* Transiciones del menú.
* Ajustes de botones y acciones.
* Adaptación de tarjetas y contenido.

En escritorio, el sidebar contempla un ancho de referencia de 240 px expandido y 76 px contraído.

### `loader.css`

Contiene los estilos del indicador de carga.

Los ajustes reportados incluyen:

* Fondo semitransparente.
* Desenfoque del contenido de fondo.
* Spinner institucional.
* Uso de los colores de marca.
* Presentación del logotipo.
* Estados para ocultar el indicador y evitar que bloquee la interacción.

Los valores exactos deben verificarse en la versión consolidada de los archivos.

### JavaScript

Los archivos JavaScript organizan los comportamientos interactivos de los módulos.

Entre sus responsabilidades se encuentran:

* Validaciones iniciales de formularios.
* Gestión de eventos.
* Presentación de mensajes.
* Actualización de elementos de la interfaz.
* Solicitudes asíncronas a endpoints cuando corresponde.
* Interacción con los controles de navegación.
* Gestión visual de estados.
* Comportamiento del indicador de carga.

Las validaciones del navegador mejoran la experiencia de uso, pero no sustituyen las validaciones obligatorias del servidor.

---

## 4.2. Capa de enrutamiento y controladores

La capa de enrutamiento determina qué interfaz o funcionalidad debe atender una solicitud.

`index.php` actúa como punto de entrada para las rutas que administra. El proyecto también contiene páginas de acceso directo y archivos de procesamiento especializados.

Entre los recursos identificados se encuentran:

* `index.php`
* `pages/`
* `auth/`
* `api/`
* `admin/`
* `usuario/`

La existencia de estos directorios no implica que todas las solicitudes pasen por un único controlador centralizado.

### Responsabilidades

* Preparar los datos necesarios para una vista.
* Determinar qué contenido debe mostrarse.
* Coordinar el procesamiento de solicitudes.
* Gestionar respuestas y redirecciones.
* Comprobar sesiones cuando corresponde.
* Aplicar controles de acceso a rutas protegidas.
* Delegar la presentación a las vistas.
* Evitar concentrar toda la lógica visual y de negocio en un único archivo.

### Autenticación y cierre de sesión

El proyecto incluye archivos destinados a procesar el inicio de sesión, el registro, la recuperación de contraseñas y el cierre de sesión.

Los informes de implementación también registran correcciones relacionadas con la inicialización de sesiones PHP y el envío de cabeceras HTTP.

El comportamiento de cada operación debe verificarse de extremo a extremo, incluyendo el acceso a la base de datos, las respuestas del servidor y la gestión segura de sesiones.

### Acceso a rutas protegidas

Las interfaces privadas y administrativas deben comprobar la identidad y los permisos del usuario en el servidor.

Ocultar enlaces o botones en la interfaz no es suficiente para impedir accesos no autorizados.

---

## 4.3. Capa de vistas

Las vistas se organizan principalmente dentro del directorio `pages/`.

Esta capa contiene el contenido de las interfaces y la estructura HTML generada mediante PHP.

Entre las vistas identificadas se encuentran:

* `pages/inicio.php`
* `pages/login.php`
* `pages/registro.php`
* `pages/dashboard.php`
* `pages/perfil.php`
* `pages/egresados.php`
* `pages/proyectos.php`
* `pages/noticias.php`
* `pages/oportunidades.php`
* `pages/administracion.php`
* `pages/mensajes.php`
* `pages/pqr.php`

La lista es orientativa y no sustituye el inventario real del repositorio.

### Responsabilidades

* Construir la estructura semántica de cada interfaz.
* Presentar campos y controles.
* Incorporar componentes compartidos.
* Mostrar alertas y mensajes.
* Mantener la jerarquía visual definida en Figma.
* Cargar los estilos y scripts correspondientes.
* Presentar los datos preparados por la lógica de aplicación.

Las vistas no deben confiar en los valores enviados por el navegador ni contener operaciones complejas de acceso a datos.

---

## 4.4. Componentes PHP compartidos

El proyecto utiliza componentes reutilizables para evitar duplicaciones en la estructura de las páginas.

Entre los archivos identificados en los avances se encuentran:

```text
includes/
├── header.php
├── footer.php
├── topbar.php
├── sidebar.php
├── loader.php
├── auth.php
├── csrf.php
├── helpers.php
└── mailer.php
```

La estructura exacta debe comprobarse en el repositorio vigente.

### `includes/header.php`

Centraliza elementos de la cabecera pública y la estructura inicial del documento HTML según el contexto de la página.

### `includes/footer.php`

Centraliza el pie de página compartido y la carga de los recursos JavaScript necesarios.

### `includes/topbar.php`

Contiene elementos de la barra superior de la interfaz autenticada.

Los avances reportados incluyen:

* Avatar.
* Nombre del usuario.
* Información complementaria.
* Distribución adaptada a móviles.
* Botón de menú hamburguesa en pantallas pequeñas.

### `includes/sidebar.php`

Contiene la navegación lateral del Dashboard.

Los avances reportados incluyen:

* Enlaces a secciones autenticadas.
* Botón para contraer o expandir el menú.
* Iconos y etiquetas.
* Estado colapsado.
* Adaptación a navegación móvil.
* Indicadores visuales y accesibles.

El estado colapsado del sidebar utiliza almacenamiento local del navegador según los informes de implementación. Esto permite conservar la preferencia visual, pero no constituye un mecanismo de autenticación o autorización.

### `includes/loader.php`

Contiene el indicador de carga compartido.

Su comportamiento debe evitar que el indicador permanezca visible indefinidamente o bloquee innecesariamente las interfaces.

### `includes/auth.php`

Se relaciona con funciones de autenticación y comprobación de acceso compartidas.

Las reglas concretas deben verificarse en el código vigente.

### `includes/csrf.php`

Se relaciona con los mecanismos de protección frente a solicitudes falsificadas entre sitios.

La cobertura de esta protección debe comprobarse en los formularios y operaciones que modifican información.

### `includes/helpers.php`

Agrupa funciones auxiliares compartidas por las diferentes partes de la aplicación.

### `includes/mailer.php`

Se utiliza para centralizar funciones relacionadas con el envío de correos electrónicos, según la organización implementada.

### Directorio `components/`

El proyecto también contempla componentes visuales reutilizables en `components/`.

Cuando existan archivos con responsabilidades equivalentes en `components/` e `includes/`, debe mantenerse clara la fuente de mantenimiento para evitar duplicaciones y comportamientos divergentes.

---

## 4.5. Capa de lógica de aplicación

La lógica de aplicación se desarrolla mediante PHP nativo.

Esta capa procesa las solicitudes, aplica las reglas correspondientes y coordina las operaciones que requieren interacción con la base de datos.

Las funcionalidades desarrolladas o en proceso de integración incluyen:

* Autenticación.
* Registro de usuarios.
* Recuperación de contraseñas.
* Gestión de perfiles.
* Consulta de egresados.
* Gestión de conexiones entre usuarios.
* Mensajería.
* Gestión de proyectos.
* Gestión de noticias.
* Gestión de oportunidades.
* Administración.
* Procesamiento de PQRS.
* Validación de información.
* Gestión de roles y permisos.
* Persistencia de datos.

La implementación de cada funcionalidad debe evaluarse de manera individual.

### Procesamiento de autenticación

El proyecto incluye archivos de procesamiento dentro de `auth/`, entre ellos recursos destinados al inicio de sesión, registro y recuperación de contraseñas.

#### Inicio de sesión

El proceso debe verificar las credenciales contra información persistente, comprobar la contraseña mediante los mecanismos apropiados, establecer una sesión segura y aplicar los controles de acceso necesarios.

#### Registro

El proceso debe validar los datos en el servidor, comprobar las restricciones correspondientes, proteger la contraseña mediante hashing y almacenar la información de manera consistente.

#### Recuperación de contraseñas

El flujo contempla el procesamiento de solicitudes, la generación y validación de tokens y el envío de instrucciones mediante correo electrónico.

La operación completa requiere verificar el almacenamiento seguro del token, su expiración, su utilización única y el restablecimiento final de la contraseña.

---

## 4.6. Capa de acceso a datos

La capa de acceso a datos conecta la lógica de aplicación con MySQL o MariaDB mediante PDO.

El proyecto dispone de una configuración centralizada en `config/database.php`.

Sus responsabilidades incluyen:

* Establecer conexiones.
* Ejecutar consultas SQL.
* Recuperar información.
* Insertar registros.
* Actualizar datos.
* Eliminar registros cuando corresponda.
* Gestionar transacciones.
* Tratar errores de acceso a datos.

Las consultas que incorporen datos proporcionados por usuarios deben utilizar sentencias preparadas cuando corresponda.

Las credenciales de conexión deben mantenerse fuera de los archivos públicos y no deben exponerse en mensajes de error.

La conexión centralizada está implementada. La integración de todas las operaciones de los módulos debe comprobarse mediante pruebas funcionales.

---

## 4.7. Capa de datos

MySQL o MariaDB está destinado a almacenar la información persistente de Raíces Alzate.

La versión `v0.2.1` incorpora un esquema de base de datos con las siguientes tablas:

| Tabla                | Responsabilidad general                                                         |
| -------------------- | ------------------------------------------------------------------------------- |
| `roles`              | Catálogo de roles de la plataforma.                                             |
| `usuarios`           | Información de las cuentas de usuario y autenticación.                          |
| `perfiles_egresados` | Información personal, académica y profesional de los perfiles.                  |
| `habilidades`        | Catálogo de habilidades.                                                        |
| `perfil_habilidades` | Relación entre perfiles y habilidades.                                          |
| `proyectos`          | Información de los proyectos.                                                   |
| `noticias`           | Información de las noticias.                                                    |
| `oportunidades`      | Registro de oportunidades.                                                      |
| `password_resets`    | Información relacionada con las solicitudes de restablecimiento de contraseñas. |
| `conexiones`         | Solicitudes y relaciones entre usuarios.                                        |
| `mensajes`           | Mensajes intercambiados entre usuarios.                                         |
| `pqrs`               | Peticiones, quejas, reclamos y sugerencias.                                     |

La estructura exacta, los campos y las relaciones deben mantenerse alineados con `database/schema.sql`.

El esquema se complementa con claves, restricciones e índices definidos en el archivo SQL. Su funcionamiento y consistencia deben comprobarse en el motor de base de datos utilizado.

### Inicialización del esquema

`database/setup.php` permite inicializar la base de datos y comprobar la presencia de tablas requeridas.

La inicialización debe ejecutarse únicamente en un entorno controlado. El instalador no debe permanecer expuesto en un entorno de producción sin controles de acceso apropiados.

La creación de tablas existentes no sustituye un sistema de migraciones capaz de modificar de forma controlada los esquemas ya instalados.

---

# 5. Arquitectura del diseño

La arquitectura visual de Raíces Alzate se definió durante la Fase 3 — Diseño UI/UX.

Esta fase se encuentra finalizada y sus resultados constituyen la referencia visual para la implementación frontend.

## 5.1. Design System

El Design System establece las bases visuales compartidas por las interfaces.

Incluye:

* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Estilos de botones.
* Campos de entrada.
* Componentes de selección.
* Estados de interacción.
* Criterios de accesibilidad visual.
* Diseño responsive.

### Paleta principal

| Elemento         | Valor     |
| ---------------- | --------- |
| Primary          | `#D32F2F` |
| Primary Hover    | `#B71C1C` |
| Secondary        | `#1565C0` |
| Texto principal  | `#212121` |
| Texto secundario | `#757575` |
| Fondo claro      | `#F5F5F5` |
| Blanco           | `#FFFFFF` |
| Borde            | `#E0E0E0` |

### Tipografía

La tipografía principal es **Inter**, con los siguientes pesos de referencia:

* 400 — Regular.
* 500 — Medium.
* 600 — Semibold.
* 700 — Bold.

### Sistema de espaciado

La escala principal es:

`4 / 8 / 12 / 16 / 24 / 32 / 48 / 64 px`

La implementación debe conservar la consistencia entre márgenes, espaciado interno y separación entre secciones.

## 5.2. Components & Responsive

La página `Components & Responsive` de Figma contiene los componentes reutilizables, sus estados y los criterios de adaptación a diferentes tamaños de pantalla.

Los componentes definidos incluyen:

* Botones.
* Inputs.
* Checkbox.
* Radio Button.
* Switch.
* Tablas.

Los estados contemplados incluyen:

* Normal.
* Focus.
* Selected.
* Disabled.
* Error.
* On / Off, cuando corresponde.

Los componentes deben conservar los indicadores visuales y el comportamiento definido en el sistema de diseño.

---

# 6. Interfaces del sistema

Durante la etapa de diseño UI/UX se definieron **17 interfaces principales para Desktop y sus correspondientes adaptaciones Mobile, para un total de 34 diseños**.

La implementación se desarrolla progresivamente.

## 6.1. Interfaces Desktop

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

## 6.2. Interfaces Mobile

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

Las interfaces móviles deben mantener la jerarquía visual y la identidad de las versiones Desktop, adaptando la distribución, la navegación y los controles al espacio disponible.

La existencia de un diseño aprobado en Figma no significa que su implementación funcional esté terminada.

---

# 7. Navegación del sistema

El flujo conceptual de navegación se definió durante el diseño UI/UX.

```text
Inicio
  │
  ▼
Autenticación
  │
  ▼
Dashboard
  ├── Perfil
  ├── Egresados
  ├── Proyectos
  ├── Noticias
  ├── Oportunidades
  ├── Mensajes
  ├── PQRS
  └── Administración
```

Este diagrama representa la navegación general prevista. Las rutas efectivamente disponibles y sus permisos dependen de la implementación de cada módulo.

## 7.1. Flujo de proyectos

```text
Proyectos
  ├── Crear proyecto
  ├── Detalle de proyecto
  └── Editar proyecto
```

## 7.2. Flujo de noticias

```text
Noticias
  ├── Detalle de noticia
  ├── Crear noticia
  └── Editar noticia
```

## 7.3. Flujo de perfil y egresados

```text
Perfil
  └── Editar perfil

Egresados
  ├── Consultar información autorizada
  └── Gestionar conexiones entre usuarios
```

## 7.4. Flujo de mensajería

```text
Mensajes
  ├── Consultar conversaciones
  ├── Obtener mensajes
  └── Enviar mensajes
```

## 7.5. Flujo administrativo

```text
Administración
  ├── Gestión de usuarios y egresados
  ├── Gestión de proyectos
  ├── Gestión de noticias
  ├── Gestión de oportunidades
  └── Gestión de PQRS
```

La navegación debe complementarse con controles de autenticación y autorización del lado del servidor para proteger las operaciones privadas y administrativas.

---

# 8. Estructura del proyecto

El repositorio se organiza por responsabilidades. La siguiente estructura resume los directorios principales y los recursos identificados en los avances.

```text
raices-alzate/
├── .github/
├── admin/
├── api/
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
│   ├── config.php
│   ├── database.php
│   └── mail.php
├── database/
│   ├── schema.sql
│   └── setup.php
├── docs/
├── includes/
│   ├── auth.php
│   ├── csrf.php
│   ├── helpers.php
│   ├── mailer.php
│   ├── header.php
│   ├── footer.php
│   ├── topbar.php
│   ├── sidebar.php
│   └── loader.php
├── pages/
├── usuario/
├── uploads/
├── index.php
├── .gitignore
└── README.md
```

Esta representación es una referencia arquitectónica, no un inventario exhaustivo. La lista definitiva debe actualizarse cuando se consoliden los archivos y las rutas del repositorio.

## 8.1. Responsabilidad de los directorios

| Directorio o archivo | Responsabilidad                                                                          |
| -------------------- | ---------------------------------------------------------------------------------------- |
| `.github/`           | Configuraciones y recursos relacionados con GitHub.                                      |
| `admin/`             | Recursos relacionados con funciones administrativas, según la organización implementada. |
| `api/`               | Endpoints para operaciones que requieren respuestas estructuradas, como JSON.            |
| `assets/css/`        | Hojas de estilos.                                                                        |
| `assets/js/`         | Scripts JavaScript.                                                                      |
| `assets/img/`        | Recursos gráficos.                                                                       |
| `assets/icons/`      | Iconos y recursos gráficos pequeños.                                                     |
| `assets/fonts/`      | Recursos tipográficos locales.                                                           |
| `assets/videos/`     | Recursos audiovisuales.                                                                  |
| `auth/`              | Procesamiento de autenticación y recuperación de contraseñas.                            |
| `components/`        | Componentes visuales reutilizables.                                                      |
| `config/`            | Configuración de la aplicación, conexión y correo.                                       |
| `database/`          | Esquema SQL e inicialización de la base de datos.                                        |
| `docs/`              | Documentación técnica y seguimiento del proyecto.                                        |
| `includes/`          | Helpers, seguridad, autenticación y componentes compartidos.                             |
| `pages/`             | Vistas de las interfaces.                                                                |
| `usuario/`           | Recursos relacionados con la experiencia del usuario, según la estructura vigente.       |
| `uploads/`           | Espacio destinado a archivos cargados, sujeto a controles de seguridad.                  |
| `index.php`          | Punto de entrada para las rutas que administra.                                          |
| `README.md`          | Documentación principal del repositorio.                                                 |

## 8.2. Criterios de organización

* Mantener las vistas separadas de la lógica de aplicación.
* Centralizar los componentes compartidos.
* Separar los estilos globales de los estilos específicos.
* Evitar implementaciones duplicadas.
* Mantener las credenciales fuera de los archivos públicos.
* Proteger los archivos cargados por los usuarios.
* Incorporar nuevos módulos respetando la organización existente.
* Actualizar la documentación cuando se consoliden o reubiquen archivos.

---

# 9. Módulos del sistema

## 9.1. Inicio

Presenta la plataforma y comunica su propósito, sus beneficios y su propuesta para la comunidad de egresados.

La interfaz contempla:

* Header.
* Hero.
* Sección informativa sobre Raíces Alzate.
* Sección de beneficios.
* Footer.

**Estado:** interfaz implementada visualmente, con adaptaciones Desktop y Mobile y ajustes de componentes reportados.

## 9.2. Dashboard

Constituye la interfaz principal del usuario autenticado.

Los avances reportados incluyen:

* Estructura general del Dashboard.
* Tarjetas de resumen.
* Actividad reciente.
* Próximas oportunidades.
* Acciones rápidas.
* Saludo personalizado a partir de los datos disponibles.
* Topbar adaptada a dispositivos móviles.
* Sidebar colapsable en escritorio.
* Navegación lateral adaptada a móviles.
* Persistencia local del estado colapsado del sidebar.
* Ajustes de estilos y botones.
* Mejoras de navegación y del loader.

**Estado:** interfaz implementada con avances de navegación y comportamiento responsive.

Las tarjetas y los elementos que dependen de información dinámica deben utilizar los datos persistentes correspondientes. Los datos simulados no deben presentarse como información real almacenada.

## 9.3. Autenticación

Agrupa las funcionalidades de acceso a la plataforma.

Incluye:

* Inicio de sesión.
* Registro.
* Recuperación de contraseña.
* Restablecimiento de contraseña.

Los avances de la versión `v0.2.1` incluyen procesos de autenticación y la integración de PHPMailer para la recuperación de contraseñas.

**Estado:** implementación funcional en desarrollo; requiere pruebas integrales de credenciales, sesiones, correo, tokens, expiración y autorización.

## 9.4. Perfil

Está destinado a la consulta y gestión de la información personal, académica y profesional del usuario.

Sus responsabilidades incluyen:

* Visualización del perfil.
* Consulta de información personal.
* Actualización de datos.
* Gestión de información académica y profesional.

**Estado:** se han reportado avances de implementación e interacción. Debe verificarse la persistencia de las actualizaciones y su reflejo en las demás áreas de la plataforma.

## 9.5. Egresados

Permite consultar y visualizar información de los egresados que forman parte de la comunidad.

Los avances relacionados con el módulo incluyen funcionalidades de conexiones entre usuarios.

El módulo debe respetar los permisos correspondientes y limitar la exposición de información personal.

**Estado:** implementación en desarrollo; las operaciones de consulta, conexión y visualización requieren pruebas con datos reales.

## 9.6. Proyectos

Permite consultar y gestionar proyectos relacionados con la comunidad.

Las interfaces contempladas incluyen:

* Listado de proyectos.
* Creación de proyectos.
* Detalle de proyecto.
* Edición de proyecto.

Se han reportado avances de implementación visual y funcional de las interfaces y sus operaciones.

**Estado:** implementación en desarrollo; deben verificarse las operaciones CRUD, la persistencia, las validaciones y los permisos.

## 9.7. Noticias

Permite consultar y gestionar noticias relacionadas con la institución y la comunidad de egresados.

Las interfaces contempladas incluyen:

* Listado de noticias.
* Detalle de noticia.
* Creación de noticias.
* Edición de noticias.

Se han reportado avances de implementación de las interfaces y sus operaciones.

**Estado:** implementación en desarrollo; deben verificarse las operaciones, la persistencia y los permisos correspondientes.

## 9.8. Oportunidades

Está destinado a presentar oportunidades relevantes para la comunidad de egresados.

El proyecto dispone de recursos relacionados con este módulo, pero el alcance funcional definitivo y la integración de sus operaciones deben comprobarse en el repositorio.

**Estado:** en desarrollo.

## 9.9. Mensajería y conexiones

El proyecto incluye endpoints y recursos relacionados con las conexiones entre egresados y el intercambio de mensajes.

Las funcionalidades contempladas incluyen:

* Solicitar conexiones.
* Responder solicitudes.
* Consultar mensajes.
* Enviar mensajes.
* Obtener conversaciones.

**Estado:** implementación en desarrollo; deben verificarse las restricciones de acceso, la consistencia de las relaciones y las operaciones con información persistente.

## 9.10. PQRS

El módulo está destinado a gestionar peticiones, quejas, reclamos y sugerencias.

Su estructura de datos contempla registros de PQRS y campos relacionados con su clasificación, estado y respuesta.

**Estado:** existen avances de implementación; el flujo completo de registro, consulta, gestión y respuesta debe verificarse.

## 9.11. Administración

Agrupa funciones administrativas de la plataforma.

Entre sus responsabilidades previstas se encuentran la gestión de usuarios, proyectos, noticias, oportunidades y PQRS.

**Estado:** implementación en desarrollo.

El acceso a este módulo debe estar protegido mediante controles de autorización del lado del servidor.

---

# 10. Flujos de procesamiento

## 10.1. Flujo general de una solicitud

El flujo general de las operaciones que requieren procesamiento en el servidor es:

1. El usuario interactúa con una interfaz.
2. El navegador envía una solicitud.
3. La solicitud llega al controlador o punto de procesamiento correspondiente.
4. PHP valida los datos recibidos.
5. Se ejecutan las reglas de negocio aplicables.
6. Cuando corresponde, se consulta o modifica la base de datos.
7. El servidor construye una respuesta o realiza una redirección.
8. La interfaz presenta el resultado al usuario.

Las validaciones del navegador apoyan la experiencia de uso, pero las reglas de seguridad deben ejecutarse en el servidor.

## 10.2. Flujo de inicio de sesión

```text
Formulario de inicio de sesión
            │
            ▼
Validación inicial en el navegador
            │
            ▼
Procesamiento en PHP
            │
            ▼
Validación del servidor
            │
            ▼
Consulta de la cuenta
            │
            ▼
Verificación de contraseña
            │
            ▼
Creación segura de sesión
            │
            ▼
Respuesta o redirección
```

El flujo debe comprobarse de extremo a extremo, incluyendo la validación de credenciales, la creación de sesión y el acceso posterior a las rutas protegidas.

## 10.3. Flujo de registro

```text
Formulario de registro
          │
          ▼
Validación en el navegador
          │
          ▼
Procesamiento en PHP
          │
          ▼
Validación del servidor
          │
          ▼
Comprobación de restricciones
          │
          ▼
Hash de la contraseña
          │
          ▼
Persistencia mediante PDO
          │
          ▼
Respuesta al usuario
```

El proceso debe comprobar las restricciones de datos y mantener la consistencia entre el registro y el modelo de la base de datos.

## 10.4. Flujo de recuperación de contraseña

```text
Formulario de recuperación
            │
            ▼
Validación de la solicitud
            │
            ▼
Procesamiento en PHP
            │
            ▼
Respuesta genérica
            │
            ▼
Generación segura del token
            │
            ▼
Almacenamiento y expiración
            │
            ▼
Envío del correo mediante SMTP
            │
            ▼
Validación del token
            │
            ▼
Restablecimiento de contraseña
```

PHPMailer participa en el envío del correo electrónico.

El flujo completo requiere comprobar el almacenamiento seguro del token, su expiración, su utilización única y la actualización de la contraseña.

## 10.5. Flujo de operaciones con la base de datos

```text
Solicitud del usuario
         │
         ▼
Validación del servidor
         │
         ▼
Reglas de negocio
         │
         ▼
Operación mediante PDO
         │
         ▼
Consulta o modificación SQL
         │
         ▼
Resultado de la operación
         │
         ▼
Respuesta al usuario
```

Las operaciones deben respetar las relaciones, restricciones y permisos definidos para cada entidad.

---

# 11. Seguridad y validación

La seguridad es un criterio transversal de la arquitectura.

Las medidas deben implementarse en cada módulo y verificarse antes de considerar una funcionalidad lista para su uso.

## 11.1. Validación de datos

* Validar en el servidor los datos recibidos.
* Comprobar los formatos requeridos.
* Aplicar restricciones de longitud y contenido.
* Validar las operaciones antes de acceder a la base de datos.
* No confiar exclusivamente en JavaScript para aceptar o rechazar información.

## 11.2. Contraseñas

* Utilizar funciones seguras de hashing, como `password_hash()`.
* Verificar contraseñas mediante `password_verify()`.
* No almacenar contraseñas en texto plano.
* Evitar incluir credenciales en mensajes de error o registros públicos.
* Gestionar de manera segura el restablecimiento de contraseñas.

## 11.3. Acceso a la base de datos

* Utilizar sentencias preparadas mediante PDO para consultas con datos variables.
* Mantener separadas las credenciales de conexión.
* Controlar los errores sin exponer detalles internos al usuario.
* Utilizar transacciones cuando una operación requiera mantener la consistencia de varios cambios relacionados.
* Verificar las restricciones de integridad definidas en el esquema.

## 11.4. Autenticación y autorización

* Verificar las credenciales en el servidor.
* Gestionar las sesiones de manera segura.
* Comprobar los permisos antes de ejecutar operaciones protegidas.
* Restringir las funciones administrativas a los usuarios autorizados.
* No depender únicamente de los elementos visibles en la interfaz para controlar el acceso.
* Mantener las herramientas de prueba local fuera del entorno de producción.

## 11.5. Recuperación de contraseña

* Utilizar tokens generados de forma criptográficamente segura.
* Evitar revelar si un correo está registrado.
* Definir una expiración adecuada.
* Almacenar los tokens de forma segura.
* Evitar su reutilización después de completar la operación.
* Validar el token antes de permitir el cambio de contraseña.
* Proteger las credenciales SMTP y la configuración de correo.

## 11.6. Formularios, sesiones y archivos

A medida que se completen los módulos, deben verificarse las medidas aplicables frente a:

* Solicitudes CSRF.
* Manejo incorrecto de sesiones.
* Exposición de información.
* Inyección SQL.
* Acceso no autorizado a recursos.
* Carga de archivos inseguros.
* Exposición de errores internos.
* Reutilización de tokens de recuperación.

La presencia de algunas medidas de seguridad no implica que toda la aplicación haya sido auditada o esté preparada para producción.

---

# 12. Recursos institucionales

La interfaz utiliza recursos gráficos de Raíces Alzate almacenados dentro del proyecto.

El logotipo se incorpora en componentes de marca, como la cabecera, el pie de página y el favicon, según corresponda.

Los recursos gráficos deben cargarse mediante rutas consistentes con la estructura del proyecto.

La configuración centralizada de URLs permite construir rutas para páginas y recursos. Su comportamiento debe verificarse en las diferentes ubicaciones desde las que se accede a la aplicación.

---

# 13. Pruebas y verificación técnica

La implementación requiere verificaciones continuas para comprobar que las modificaciones no afecten las funcionalidades existentes.

Los informes de desarrollo registran verificaciones de sintaxis PHP, rutas y respuestas HTTP para determinados archivos durante las jornadas de trabajo.

Estas verificaciones constituyen evidencia de pruebas realizadas en esos momentos, pero no sustituyen una prueba integral de todos los módulos.

## 13.1. Validaciones técnicas

Las verificaciones aplicables incluyen:

* Comprobación de sintaxis PHP.
* Revisión de rutas.
* Comprobación de carga de CSS y JavaScript.
* Verificación de recursos gráficos y tipográficos.
* Pruebas de formularios.
* Validaciones en el navegador.
* Validaciones en el servidor.
* Verificación de consultas y operaciones de base de datos.
* Pruebas de navegación Desktop y Mobile.
* Comprobación de regresiones.
* Pruebas de sesiones y cierre de sesión.
* Comprobación de acceso a rutas protegidas.
* Pruebas del flujo de recuperación de contraseña.
* Verificación del envío de correos.
* Revisión del funcionamiento del loader.

## 13.2. Fidelidad visual

La implementación se calibra tomando como referencia las interfaces aprobadas en Figma.

Las verificaciones visuales deben considerar:

* Dimensiones y distribución de componentes.
* Tipografía y jerarquía visual.
* Colores.
* Espaciado.
* Estados de interacción.
* Comportamiento responsive.
* Consistencia de los componentes compartidos.

Las diferencias entre el diseño y la implementación deben corregirse sin incorporar secciones o comportamientos que no correspondan a los requerimientos aprobados.

---

# 14. Flujo de desarrollo del proyecto

El desarrollo de Raíces Alzate se organiza de manera incremental.

Las etapas generales comprenden:

1. Planificación.
2. Documentación.
3. Definición de requerimientos.
4. Diseño UI/UX.
5. Definición de flujos de información.
6. Implementación frontend.
7. Implementación backend.
8. Diseño e implementación de la base de datos.
9. Integración con MySQL.
10. Pruebas funcionales y técnicas.
11. Corrección de errores.
12. Control de versiones.
13. Actualización de la documentación.

Las etapas iniciales de planificación, documentación base y diseño UI/UX se han desarrollado.

La etapa actual corresponde a la implementación e integración funcional. Durante esta etapa se han construido interfaces, organizado componentes, incorporado operaciones backend y preparado el almacenamiento persistente.

El trabajo continuará con la integración de los módulos, la verificación de las operaciones y la consolidación de los controles de seguridad.

La documentación deberá actualizarse conforme cambie la arquitectura o se incorporen nuevas funcionalidades.

---

# 15. Principios de desarrollo

Durante la implementación se aplicarán los siguientes principios:

* Código organizado y mantenible.
* Arquitectura modular.
* Separación de responsabilidades.
* Componentes reutilizables.
* Consistencia visual mediante el Design System.
* Diseño responsive.
* Validación de datos.
* Seguridad desde el desarrollo.
* Control de versiones mediante Git y GitHub.
* Documentación continua.
* Reutilización de recursos.
* Reducción de duplicaciones.
* Pruebas después de modificaciones relevantes.
* Evolución incremental de los módulos.
* Coherencia entre el diseño aprobado y la interfaz implementada.

La incorporación de nuevas funcionalidades deberá respetar estos principios y mantener la compatibilidad con los módulos existentes.

---

# 16. Estado actual de la arquitectura

## 16.1. Diseño UI/UX

La Fase 3 — Diseño UI/UX se encuentra finalizada.

Los resultados incluyen:

* Design System en Figma.
* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Componentes reutilizables.
* Estados de interacción.
* Página `Components & Responsive`.
* Criterios de diseño responsive.
* Flujo de navegación.
* 17 diseños Desktop.
* 17 adaptaciones Mobile.

El diseño aprobado constituye la referencia visual de la implementación.

## 16.2. Implementación de interfaces y módulos

| Módulo         | Estado general                                                                  |
| -------------- | ------------------------------------------------------------------------------- |
| Inicio         | Interfaz implementada visualmente.                                              |
| Dashboard      | Interfaz implementada con ajustes de navegación y comportamiento.               |
| Autenticación  | Procesos de acceso, registro y recuperación en desarrollo e integración.        |
| Perfil         | Avances de interfaz y funcionalidad; persistencia por verificar.                |
| Egresados      | Avances de interfaz y conexiones; operaciones por verificar.                    |
| Proyectos      | Interfaces y operaciones implementadas parcialmente; integración por verificar. |
| Noticias       | Interfaces y operaciones implementadas parcialmente; integración por verificar. |
| Oportunidades  | En desarrollo.                                                                  |
| Mensajería     | Endpoints y recursos implementados; pruebas integrales pendientes.              |
| PQRS           | Avances de interfaz y procesamiento; flujo completo por verificar.              |
| Administración | En desarrollo; deben verificarse las operaciones y los permisos.                |

## 16.3. Componentes y navegación compartidos

Los informes de implementación registran avances en:

* Normalización de la cabecera pública.
* Comportamiento sticky de la cabecera.
* Ajustes de estilos compartidos.
* Corrección de dimensiones de botones.
* Organización de la barra superior del Dashboard.
* Sidebar colapsable en escritorio.
* Navegación lateral adaptada a móviles.
* Persistencia local del estado del sidebar.
* Ajustes de accesibilidad de los controles de navegación.
* Rediseño del loader.
* Correcciones de rutas y gestión de sesiones.

Los componentes deben seguir verificándose conforme se incorporen nuevos módulos.

## 16.4. Autenticación y sesiones

La versión `v0.2.1` incluye avances en:

* Inicio de sesión.
* Registro.
* Recuperación de contraseña.
* Restablecimiento de contraseña.
* Procesamiento de formularios.
* Configuración de correo.
* Integración de PHPMailer.
* Ajustes de inicialización de sesiones.
* Correcciones relacionadas con el envío de cabeceras HTTP.
* Gestión de rutas de autenticación.

El funcionamiento completo debe comprobarse con pruebas que abarquen credenciales, sesiones, tokens, correo electrónico y controles de acceso.

## 16.5. Base de datos

La versión actual incorpora:

* Esquema SQL para las entidades principales.
* Configuración centralizada de la conexión.
* Acceso a datos mediante PDO.
* Script de inicialización.
* Comprobación de las tablas requeridas.

Continúa siendo necesario verificar:

* Consistencia entre el esquema y las consultas del backend.
* Integridad de las relaciones.
* Persistencia de las operaciones de cada módulo.
* Tratamiento de errores de conexión.
* Comportamiento de las restricciones.
* Evolución del esquema mediante migraciones.
* Sustitución de los datos simulados por consultas reales cuando corresponda.

## 16.6. Pendientes técnicos

Los principales pendientes identificados son:

* Completar las pruebas de autenticación.
* Verificar el flujo completo de recuperación de contraseñas.
* Comprobar el envío de correos en el entorno de desarrollo.
* Verificar el almacenamiento, la expiración y la utilización de tokens.
* Consolidar los componentes compartidos.
* Completar la persistencia de perfiles.
* Verificar las operaciones de conexiones entre egresados.
* Completar las operaciones de proyectos y noticias.
* Consolidar las funcionalidades de oportunidades.
* Verificar el flujo completo de mensajería.
* Completar el procesamiento de PQRS.
* Verificar los controles de autorización.
* Ampliar las pruebas funcionales, visuales y de seguridad.
* Revisar la configuración del instalador de base de datos.
* Mantener actualizados los documentos técnicos.

---

# 17. Próxima etapa

La implementación continuará con la consolidación progresiva de los módulos y sus operaciones.

Las prioridades incluyen:

1. Verificar el funcionamiento integral de la autenticación.
2. Completar y probar el flujo de recuperación de contraseñas.
3. Verificar la consistencia del esquema de la base de datos con el backend.
4. Completar las operaciones de perfil y egresados.
5. Consolidar las operaciones de proyectos y noticias.
6. Completar las funcionalidades de oportunidades.
7. Verificar la mensajería y las conexiones entre usuarios.
8. Consolidar el módulo de PQRS.
9. Completar el módulo administrativo y sus controles de acceso.
10. Sustituir los datos simulados por información persistente cuando corresponda.
11. Realizar pruebas funcionales, visuales y técnicas.
12. Actualizar la documentación conforme se consoliden los avances.

El orden de ejecución podrá ajustarse según las dependencias técnicas.

Cada módulo deberá incorporar progresivamente:

* La vista correspondiente al diseño aprobado.
* Los estilos y componentes reutilizables.
* Los comportamientos necesarios en JavaScript.
* El procesamiento requerido en PHP.
* La validación de los datos.
* La integración con la base de datos.
* Los controles de seguridad aplicables.
* Las pruebas funcionales y visuales.
* La actualización de la documentación.

Las interfaces y funcionalidades se considerarán terminadas únicamente cuando se hayan verificado sus criterios funcionales y técnicos.

La documentación de arquitectura se mantendrá como referencia viva del proyecto, reflejando las decisiones y los cambios que realmente se incorporen al repositorio.

---

**Raíces Alzate — Institución Educativa Gilberto Alzate Avendaño**

*Arquitectura del proyecto · v0.2.1 · Implementación e integración funcional*
