# 🌱 Raíces Alzate

> Plataforma web para la gestión, el seguimiento y el fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 🏗️ Arquitectura del Proyecto

## Información general

| Campo                            | Información                                        |
| -------------------------------- | -------------------------------------------------- |
| Proyecto                         | Raíces Alzate                                      |
| Institución                      | Institución Educativa Gilberto Alzate Avendaño     |
| Desarrollador                    | Santiago Emitola                                   |
| Versión                          | v0.1.1                                             |
| Estado                           | 🟡 En desarrollo                                   |
| Fase actual                      | Fase 4 — Implementación y refinamiento de interfaz |
| Inicio del proyecto              | 2025                                               |
| Última actualización documentada | 13 de octubre de 2026                              |

---

# 1. Introducción

Este documento describe la arquitectura general de **Raíces Alzate**, incluyendo las tecnologías utilizadas, la separación de responsabilidades, los módulos principales, la organización de archivos, los flujos de navegación y los mecanismos previstos para procesar y almacenar información.

La arquitectura busca facilitar el mantenimiento, la reutilización de componentes, la seguridad, la trazabilidad y la evolución progresiva de la plataforma.

El proyecto comenzó en **2025**. Durante sus primeras etapas se trabajaron la planificación, la documentación base y la definición de requerimientos. Posteriormente se desarrolló la Fase 3 — Diseño UI/UX, que se encuentra finalizada.

Como resultado del diseño se definieron el sistema visual, los componentes reutilizables, los estados de interacción, los flujos de navegación y las interfaces de escritorio y dispositivos móviles.

La etapa actual corresponde a la **Fase 4 — Implementación**, durante la cual se está trasladando progresivamente el diseño aprobado en Figma a una aplicación web funcional.

Los avances documentados incluyen:

* Implementación de la página de Inicio.
* Implementación visual del Dashboard.
* Organización de componentes PHP compartidos.
* Estandarización y adaptación responsive de la cabecera pública.
* Implementación de una navegación autenticada con barra superior y menú lateral.
* Incorporación de un sidebar colapsable en escritorio.
* Ajustes de navegación para dispositivos móviles.
* Desarrollo de un sistema de carga visual mediante un loader.
* Correcciones relacionadas con las sesiones PHP y el envío de cabeceras HTTP.
* Incorporación de un mecanismo local para facilitar las pruebas visuales del Dashboard.
* Ajustes en las rutas de acceso a las interfaces.
* Actualización de documentación técnica y registro de avances.

La implementación continúa de manera incremental.

**La existencia de una interfaz, un controlador o un componente no implica que toda la funcionalidad asociada esté terminada, conectada a la base de datos o preparada para producción.**

Actualmente, el Dashboard cuenta con avances de implementación visual y de navegación. Los módulos de Perfil, Egresados, Proyectos, Noticias y Administración todavía requieren desarrollo. También está pendiente completar la estructura de la base de datos e integrar las funcionalidades con información persistente.

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
                 PUNTO DE ENTRADA Y RUTAS
                         index.php
                            │
                            ▼
                 CONTROLADORES Y VISTAS PHP
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
                     BASE DE DATOS
                          MySQL
```

El navegador presenta las interfaces y permite la interacción del usuario. Las solicitudes que requieren procesamiento en el servidor son atendidas por PHP.

Cuando una funcionalidad necesita consultar o modificar información persistente, el backend utiliza los mecanismos de acceso a datos para comunicarse con MySQL.

La implementación debe mantener separadas las responsabilidades de cada capa, evitando concentrar toda la presentación, la lógica de negocio y el acceso a datos en un único archivo.

El diagrama representa la arquitectura objetivo. No significa que todos los módulos ya se encuentren conectados entre sí ni que la persistencia esté implementada en todas las funcionalidades.

## 2.1. Enfoque arquitectónico

La implementación sigue estos criterios:

* PHP nativo, sin frameworks de aplicación pesados.
* HTML5 semántico para estructurar las interfaces.
* CSS3 modular para la presentación visual.
* JavaScript nativo para la interactividad del navegador.
* Organización modular de vistas y recursos.
* Uso de `index.php` como punto de entrada y enrutamiento para las rutas que gestiona.
* Controladores PHP independientes para determinadas operaciones.
* Vistas organizadas en `pages/`.
* Componentes compartidos para elementos reutilizables.
* Separación del procesamiento de formularios mediante archivos especializados cuando corresponde.
* Organización de estilos según su responsabilidad.
* Preparación de una capa de acceso a datos mediante PDO.
* Integración progresiva con MySQL.
* Uso de Git y GitHub para el control de versiones.

La arquitectura podrá evolucionar conforme se incorporen nuevos módulos, manteniendo la separación de responsabilidades.

---

# 3. Tecnologías utilizadas

## 3.1. Frontend

| Tecnología        | Responsabilidad                                                     |
| ----------------- | ------------------------------------------------------------------- |
| HTML5             | Estructura semántica de las interfaces.                             |
| CSS3              | Presentación visual, distribución, componentes y diseño responsive. |
| JavaScript nativo | Interactividad y comportamiento de los componentes.                 |
| Inter             | Tipografía principal de las interfaces.                             |
| Figma             | Referencia visual del diseño y los componentes.                     |

El frontend se desarrolla con tecnologías nativas, sin depender de frameworks pesados para construir las interfaces.

La implementación debe respetar los diseños aprobados en Figma y mantener una presentación consistente entre los módulos.

## 3.2. Backend

| Tecnología o mecanismo        | Responsabilidad                                            | Estado                                                         |
| ----------------------------- | ---------------------------------------------------------- | -------------------------------------------------------------- |
| PHP nativo                    | Procesamiento de solicitudes y lógica de aplicación.       | En implementación.                                             |
| Controladores PHP             | Gestión de determinadas rutas y operaciones.               | Implementación parcial.                                        |
| Sesiones PHP                  | Mantenimiento del estado de sesión.                        | Gestión inicial y pruebas; integración pendiente de verificar. |
| PDO                           | Acceso a la base de datos desde PHP.                       | Preparado para la integración; uso integral pendiente.         |
| Funciones de seguridad de PHP | Validación, hashing de contraseñas y generación de tokens. | Debe verificarse por funcionalidad.                            |

Los mecanismos de autenticación, autorización y gestión de sesiones deben implementarse y comprobarse conforme se desarrollen los módulos.

La presencia de una sesión de prueba no equivale a disponer de un sistema de autenticación completo.

## 3.3. Base de datos

| Tecnología | Responsabilidad                               | Estado                              |
| ---------- | --------------------------------------------- | ----------------------------------- |
| MySQL      | Almacenamiento persistente de la información. | Estructura definitiva pendiente.    |
| PDO        | Comunicación entre PHP y MySQL.               | Integración por completar.          |
| SQL        | Consultas y operaciones de persistencia.      | Pendiente de consolidar por módulo. |

La conexión entre PHP y MySQL fue probada durante la preparación de la implementación.

Sin embargo, todavía deben definirse e implementarse el modelo entidad-relación, las tablas, las relaciones, las restricciones y las consultas necesarias para los módulos.

**La conectividad comprobada no significa que la base de datos funcional esté terminada.**

## 3.4. Herramientas de desarrollo

* Visual Studio Code para la edición del código.
* Git para el control de versiones.
* GitHub para el alojamiento del repositorio y la gestión de cambios.
* Figma para el diseño UI/UX.
* XAMPP para el entorno local de desarrollo con Apache, PHP y MySQL.
* Antigravity y Gemini como herramientas de apoyo para la implementación y revisión del código.
* Herramientas de navegador y terminal para realizar verificaciones técnicas.

---

# 4. Arquitectura por capas

## 4.1. Capa de presentación

La capa de presentación es responsable de mostrar la información y permitir que los usuarios interactúen con la plataforma.

Está compuesta principalmente por HTML5, CSS3, JavaScript y las vistas PHP.

Sus responsabilidades incluyen:

* Representar las interfaces diseñadas en Figma.
* Presentar formularios y controles.
* Mostrar mensajes de validación y alertas.
* Gestionar los estados visuales de los componentes.
* Adaptar las interfaces a escritorio y dispositivos móviles.
* Reutilizar elementos comunes.
* Facilitar la navegación entre las secciones del sistema.
* Proporcionar retroalimentación visual durante las operaciones.

### Sistema visual

La implementación utiliza como referencia el Design System establecido durante la etapa de diseño UI/UX.

Este sistema define:

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

Entre los archivos identificados en los informes de implementación se encuentran:

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
│   └── loader.css
└── js/
    ├── auth.js
    ├── main.js
    └── loader.js
```

Esta representación reúne los archivos mencionados en los informes disponibles. La existencia y ubicación definitiva de cada archivo deben mantenerse alineadas con el repositorio.

### `main.css`

Centraliza los estilos generales del proyecto y los componentes visuales compartidos.

Sus responsabilidades incluyen:

* Reglas base.
* Colores generales.
* Estilos comunes de enlaces y botones.
* Alertas y mensajes de estado.
* Reglas generales de presentación.
* Elementos visuales reutilizables.

Las reglas globales deben evitar interferir accidentalmente con los componentes específicos de otros módulos.

### `fonts.css`

Centraliza la configuración de la tipografía Inter y la referencia a los recursos tipográficos locales, cuando corresponda.

Los pesos definidos por el sistema visual son:

* 400 — Regular.
* 500 — Medium.
* 600 — Semibold.
* 700 — Bold.

### `header.css`

Contiene los estilos específicos de la cabecera pública.

La referencia visual establece una altura de 72 px.

Los ajustes reportados incluyen:

* Alineación de la marca y la navegación.
* Normalización de las dimensiones de la cabecera.
* Fondo sólido.
* Borde inferior.
* Comportamiento sticky.
* Ajustes responsive.
* Correcciones de la distribución de acciones y botones.

El ancho máximo definitivo del contenedor debe mantenerse de acuerdo con el valor presente en el código consolidado. Los informes recibidos mencionan tanto 1200 px como 1400 px, por lo que este dato requiere verificación en el repositorio.

### `footer.css`

Contiene los estilos del pie de página compartido.

El diseño de referencia contempla:

* Fondo oscuro.
* Identidad visual de Raíces Alzate.
* Enlaces de navegación.
* Información institucional.
* Separador visual del bloque de copyright.
* Adaptación a pantallas móviles.

La estructura visual aprobada se reutiliza en las páginas que requieren el pie de página institucional.

### `auth.css`

Contiene los estilos relacionados con las interfaces de autenticación.

Sus responsabilidades incluyen:

* Tarjetas de autenticación.
* Distribución de formularios.
* Campos de entrada.
* Estados de validación.
* Mensajes de error.
* Botones.
* Espaciado y alineación.
* Adaptación responsive.

La finalización visual de las pantallas de autenticación debe verificarse frente a Figma.

### `portal.css`

Contiene estilos relacionados con elementos compartidos del portal.

Entre las correcciones reportadas se encuentra el aislamiento de una regla de ancho fijo para botones, que afectaba a elementos del Dashboard.

Los estilos específicos deben aplicarse mediante selectores suficientemente acotados para evitar efectos secundarios en otros módulos.

### `dashboard.css`

Contiene estilos de la interfaz autenticada del Dashboard.

Entre los comportamientos documentados se encuentran:

* Distribución de la cabecera autenticada.
* Barra superior responsive.
* Menú lateral.
* Sidebar colapsable en escritorio.
* Sidebar adaptado a móviles.
* Estados visuales de navegación.
* Transiciones del menú.
* Ajustes de botones y acciones.
* Adaptación de las tarjetas y del contenido.

La versión de escritorio contempla un sidebar de 240 px expandido y 76 px contraído. La implementación debe mantener este comportamiento sin comprometer la navegación ni la accesibilidad.

### `loader.css`

Contiene los estilos del indicador de carga.

Los ajustes reportados incluyen:

* Fondo blanco semitransparente.
* Desenfoque del contenido de fondo.
* Spinner institucional.
* Uso de los colores de marca.
* Presentación del logotipo.
* Estados para ocultar el indicador y evitar que bloquee la interacción.

El valor exacto de opacidad del fondo debe verificarse en la versión consolidada del archivo, porque los informes de implementación registran valores diferentes.

### JavaScript de autenticación

El archivo `assets/js/auth.js` reúne comportamientos de los formularios de autenticación que se han desarrollado.

Los informes identifican funciones como:

* Validación reactiva de campos.
* Limpieza de errores.
* Validación del formato del correo electrónico.
* Presentación de mensajes.
* Prevención de envíos repetidos.
* Inicialización de la validación del registro.
* Gestión visual de errores.

Estas funciones deben comprobarse en el código vigente antes de considerarlas terminadas.

Las validaciones de JavaScript mejoran la experiencia de uso, pero no sustituyen las validaciones obligatorias del servidor.

### JavaScript general y loader

`assets/js/main.js` se utiliza como recurso JavaScript general del proyecto según la estructura reportada.

`assets/js/loader.js` contiene lógica relacionada con el comportamiento del indicador de carga.

Se reportaron ajustes para evitar que el loader permanezca visible indefinidamente y bloquee las interfaces.

También se incorporaron mecanismos de ocultación asociados a la carga de la página y un tiempo de respaldo. La interacción entre el script externo y los scripts incluidos directamente en los archivos PHP debe mantenerse revisada para evitar duplicaciones o conflictos.

---

## 4.2. Capa de enrutamiento y controladores

La capa de enrutamiento determina qué interfaz o funcionalidad debe atender una solicitud.

`index.php` actúa como punto de entrada para las rutas que administra. También existen controladores PHP independientes en la raíz del proyecto.

Entre los archivos identificados se encuentran:

* `index.php`
* `inicio.php`
* `login.php`
* `registro.php`
* `recuperar-password.php`
* `recuperar-contrasena.php`
* `dashboard.php`
* `logout.php`

La lista corresponde a los archivos mencionados en los informes de implementación y debe mantenerse alineada con el repositorio.

### Responsabilidades de los controladores

* Preparar los datos necesarios para una vista.
* Determinar qué contenido debe mostrarse.
* Coordinar el procesamiento de las solicitudes.
* Gestionar las respuestas y redirecciones.
* Iniciar o comprobar las sesiones cuando corresponda.
* Aplicar controles de acceso a las rutas protegidas.
* Delegar la presentación a las vistas.
* Evitar concentrar toda la lógica visual y de negocio en un solo archivo.

### Controlador del Dashboard

`dashboard.php` corresponde a la entrada de la interfaz autenticada del Dashboard.

Los informes indican que verifica la presencia de sesión activa autenticada para controlar el acceso.

Esta protección se mantiene estrictamente en el servidor validando la identidad contra la base de datos MySQL con contraseñas hasheadas.

### Cierre de sesión

`logout.php` está destinado a gestionar el cierre de sesión seguro.

La implementación contempla la limpieza de la sesión, la destrucción de sus datos, la invalidación de cookies y la redirección al inicio de sesión.

### Autenticación segura y eliminación de sesiones simuladas

El archivo auxiliar `dev-login.php` fue completamente eliminado del proyecto para garantizar la máxima seguridad de la base de datos. Ningún usuario puede eludir los mecanismos formales de autenticación: el acceso requiere credenciales verificadas contra la base de datos MySQL mediante `password_verify()` y tokens CSRF.

### Rutas de recuperación de contraseña

Se reportaron ajustes para permitir el acceso mediante diferentes nombres de ruta relacionados con la recuperación de contraseña.

Los alias y sus redirecciones deben mantenerse coherentes para evitar errores de acceso y rutas relativas incorrectas.

---

## 4.3. Capa de vistas

Las vistas se organizan principalmente dentro del directorio `pages/`.

Esta capa contiene el contenido de las interfaces y la estructura HTML generada mediante PHP.

Entre las vistas identificadas se encuentran:

```text
pages/
├── inicio.php
├── login.php
├── registro.php
├── recuperar-password.php
└── dashboard.php
```

Esta representación es orientativa y debe verificarse contra la estructura actual del repositorio. No se considera una lista exhaustiva de todos los archivos existentes.

### Responsabilidades de las vistas

* Construir la estructura semántica de cada interfaz.
* Presentar los campos y controles.
* Incorporar los componentes compartidos.
* Mostrar alertas y mensajes de retroalimentación.
* Mantener la jerarquía visual definida en Figma.
* Utilizar los estilos y scripts correspondientes.
* Presentar los datos preparados por la lógica de aplicación.

Las vistas no deben contener operaciones complejas de acceso a datos ni asumir que los valores enviados por el navegador son confiables.

---

## 4.4. Componentes PHP compartidos

El proyecto utiliza componentes reutilizables para evitar duplicaciones en la estructura de las páginas.

Los archivos reportados incluyen:

```text
includes/
├── header.php
├── footer.php
├── topbar.php
├── sidebar.php
└── loader.php
```

### `includes/header.php`

Centraliza la cabecera pública y los elementos iniciales del documento HTML según el contexto de cada página.

Entre sus responsabilidades se encuentran:

* Metadatos generales.
* Inclusión de estilos.
* Favicon.
* Identidad visual.
* Navegación principal.
* Acciones de autenticación.
* Adaptación responsive.

Los informes registran ajustes de altura, alineación, ancho del contenedor y comportamiento sticky.

### `includes/footer.php`

Centraliza el pie de página compartido y la carga de los recursos JavaScript necesarios antes del cierre del documento.

La carga de scripts específicos depende de la interfaz que se presenta.

### `includes/topbar.php`

Contiene elementos de la barra superior de la interfaz autenticada.

Los avances reportados incluyen:

* Presentación del avatar.
* Nombre del usuario.
* Rol o información complementaria.
* Distribución adaptada a móviles.
* Botón de menú hamburguesa en pantallas pequeñas.

El contenido personalizado depende de los datos de sesión disponibles.

### `includes/sidebar.php`

Contiene la navegación lateral del Dashboard.

Los ajustes reportados incluyen:

* Enlaces a las secciones autenticadas.
* Retiro de un enlace redundante a Inicio.
* Botón para contraer o expandir el menú.
* Iconos y etiquetas.
* Estado colapsado.
* Adaptación a navegación móvil.
* Indicadores accesibles para el control del menú.

En escritorio, el sidebar contempla 240 px expandido y 76 px contraído.

En móviles, se adapta a un menú lateral desplegable con un fondo de respaldo que permite distinguirlo del contenido principal.

### `includes/loader.php`

Contiene el indicador de carga compartido.

Los ajustes reportados sustituyen el skeleton visual inicial por un indicador más sencillo, con fondo semitransparente, desenfoque, spinner y logotipo institucional.

Se incorporaron mecanismos para ocultarlo al finalizar la carga o cuando se cumple un tiempo de respaldo. Debe comprobarse que el comportamiento no interfiera con las interfaces públicas ni con los formularios.

### Directorio `components/`

La estructura del proyecto también contempla archivos reutilizables en `components/`.

Los informes identifican componentes de cabecera y pie de página en este directorio, pero también señalan que `includes/header.php` e `includes/footer.php` participan en la construcción de las páginas.

Por ello, la existencia de archivos con nombres equivalentes no implica automáticamente que ambos conjuntos sean utilizados simultáneamente.

La estructura debe revisarse para determinar qué archivos se mantienen activos y evitar implementaciones duplicadas.

El objetivo es establecer una fuente de mantenimiento clara para cada componente compartido.

---

## 4.5. Capa de lógica de aplicación

La lógica de aplicación se desarrolla mediante PHP nativo.

Esta capa procesa las solicitudes, aplica las reglas correspondientes y coordina las operaciones que requieren interacción con la base de datos.

Las funcionalidades previstas incluyen:

* Autenticación.
* Registro de usuarios.
* Recuperación de contraseña.
* Gestión de perfiles.
* Consulta de egresados.
* Gestión de proyectos.
* Gestión de noticias.
* Administración de la plataforma.
* Validación de información.
* Gestión de roles y permisos.
* Procesamiento de formularios.
* Persistencia de datos.

No todas estas funcionalidades se encuentran implementadas. Su incorporación se realizará progresivamente.

### Procesamiento de autenticación

Los informes identifican los siguientes archivos:

```text
auth/
├── login-process.php
├── registro-process.php
└── recuperar-process.php
```

Su existencia y comportamiento actual deben comprobarse contra el repositorio antes de dar por terminada cada operación.

#### `auth/login-process.php`

Está destinado a procesar el formulario de inicio de sesión.

La autenticación real requiere verificar las credenciales contra información persistente, comprobar la contraseña mediante los mecanismos apropiados, establecer una sesión segura y aplicar los controles de acceso necesarios.

Las sesiones simuladas utilizadas para maquetación no reemplazan este flujo.

#### `auth/registro-process.php`

Está destinado a procesar el formulario de registro.

Los informes anteriores mencionan validaciones del servidor, comprobación de contraseñas coincidentes, verificación de correo único, utilización de `password_hash()` y operaciones mediante PDO.

Estos comportamientos deben verificarse en el código y en la base de datos antes de considerarlos parte de una funcionalidad completamente operativa.

La definición de los campos debe coincidir con los requerimientos y con el modelo de datos que se implemente.

#### `auth/recuperar-process.php`

Está destinado a procesar las solicitudes de recuperación de contraseña.

Los informes mencionan la validación del correo electrónico, la respuesta genérica para no revelar si una cuenta existe y la generación de tokens mediante `random_bytes(32)`.

El proceso completo también requiere verificar el almacenamiento seguro del token, su expiración, su utilización única, el envío de las instrucciones y el restablecimiento final de la contraseña.

La generación de un token por sí sola no significa que la recuperación de contraseña esté terminada.

---

## 4.6. Capa de acceso a datos

La capa de acceso a datos conectará la lógica de aplicación con MySQL mediante PDO.

Sus responsabilidades previstas incluyen:

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

La conexión PHP–MySQL fue probada durante la preparación de la implementación. Sin embargo, la capa de persistencia todavía debe consolidarse e integrarse progresivamente con los módulos.

---

## 4.7. Capa de datos

MySQL está destinado a almacenar la información persistente de Raíces Alzate.

Las entidades previstas incluyen:

* Usuarios.
* Perfiles.
* Egresados.
* Proyectos.
* Noticias.
* Roles y permisos.
* Información complementaria de la plataforma.

La estructura definitiva deberá definirse mediante el modelo entidad-relación y el diseño de las tablas, considerando las relaciones, restricciones e índices necesarios.

También deberán definirse las consultas y operaciones que cada módulo utilizará para crear, consultar, actualizar o eliminar información.

**Estado actual:** la conectividad ha sido probada, pero el modelo de datos y la integración completa con las funcionalidades continúan pendientes.

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
| Primary Active   | `#8E0000` |
| Secondary        | `#1565C0` |
| Texto principal  | `#212121` |
| Texto secundario | `#757575` |
| Fondo claro      | `#F5F5F5` |
| Blanco           | `#FFFFFF` |
| Borde            | `#E0E0E0` |

### Tipografía

La tipografía principal es **Inter**, con pesos 400, 500, 600 y 700.

### Sistema de espaciado

La escala principal de espaciado es:

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

Los errores no deben comunicarse únicamente mediante cambios de color cuando sea posible acompañarlos de un mensaje textual.

---

# 6. Interfaces del sistema

Durante la etapa de diseño UI/UX se definieron 17 interfaces principales para Desktop y sus correspondientes adaptaciones Mobile, para un total de **34 diseños**.

La implementación de estas interfaces continúa de manera progresiva.

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

Las interfaces Mobile deben mantener la jerarquía visual y la identidad de las versiones Desktop, adaptando la distribución, la navegación y los controles al espacio disponible.

La existencia de un diseño aprobado en Figma no significa que su implementación frontend esté terminada.

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
  └── Administración
```

Este diagrama representa la navegación prevista. Las rutas efectivamente disponibles dependen de la implementación de cada módulo.

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
  └── Consultar información autorizada
```

## 7.4. Flujo administrativo

```text
Administración
  ├── Gestión de egresados
  ├── Gestión de proyectos
  ├── Gestión de noticias
  ├── Crear proyecto
  ├── Editar proyecto
  ├── Crear noticia
  └── Editar noticia
```

La navegación deberá complementarse con controles de autenticación y autorización del lado del servidor cuando se implementen las funcionalidades privadas y administrativas.

---

# 8. Estructura del proyecto

La estructura del repositorio se organiza por responsabilidades.

La siguiente representación reúne archivos y directorios identificados en la documentación y en los informes de implementación. No constituye una verificación exhaustiva del repositorio ni implica que todos los módulos representados estén terminados.

```text
raices-alzate/
├── .github/
├── admin/
├── usuario/
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   ├── fonts.css
│   │   ├── header.css
│   │   ├── footer.css
│   │   ├── auth.css
│   │   ├── portal.css
│   │   ├── dashboard.css
│   │   └── loader.css
│   ├── js/
│   │   ├── main.js
│   │   ├── auth.js
│   │   └── loader.js
│   ├── img/
│   ├── icons/
│   ├── fonts/
│   └── videos/
├── auth/
│   ├── login-process.php
│   ├── registro-process.php
│   └── recuperar-process.php
├── components/
├── config/
├── database/
├── docs/
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── topbar.php
│   ├── sidebar.php
│   └── loader.php
├── pages/
│   ├── inicio.php
│   ├── login.php
│   ├── registro.php
│   ├── recuperar-password.php
│   └── dashboard.php
├── uploads/
├── index.php
├── inicio.php
├── login.php
├── registro.php
├── recuperar-password.php
├── recuperar-contrasena.php
├── dashboard.php
├── logout.php
├── .gitignore
└── README.md
```

Los archivos concretos deben mantenerse alineados con el repositorio vigente. Cuando se consoliden, eliminen o reubiquen archivos, esta representación deberá actualizarse.

## 8.1. Responsabilidad de los directorios

| Directorio o archivo | Responsabilidad                                                              |
| -------------------- | ---------------------------------------------------------------------------- |
| `.github/`           | Configuraciones y recursos relacionados con GitHub.                          |
| `api/`               | Endpoints REST JSON seguros para mensajería y red de egresados.              |
| `assets/css/`        | Hojas de estilos y fuentes locales.                                          |
| `assets/js/`         | Scripts JavaScript modulares.                                                |
| `assets/img/`        | Recursos gráficos.                                                           |
| `assets/icons/`      | Iconos y recursos gráficos pequeños.                                         |
| `assets/fonts/`      | Recursos tipográficos locales (Inter).                                        |
| `assets/videos/`     | Recursos audiovisuales.                                                      |
| `auth/`              | Procesamiento backend de autenticación, registro y recuperación segura.       |
| `components/`        | Componentes visuales reutilizables (header, footer).                         |
| `config/`            | Configuración de la aplicación, constantes y singleton PDO.                   |
| `database/`          | Esquema DDL con 11 tablas relacionales y script de migración/setup.          |
| `docs/`              | Documentación técnica y seguimiento del proyecto.                            |
| `includes/`          | Helpers comunes, layout compartido, auth y CSRF.                             |
| `pages/`             | Vistas de las interfaces dentro del Front Controller.                        |
| `uploads/`           | Espacio destinado a archivos cargados, sujeto a controles de seguridad.      |
| `index.php`          | Front Controller único en la raíz para todo el enrutamiento.                 |
| `README.md`          | Documentación principal del repositorio en la raíz.                          |

## 8.2. Criterios de organización

* Mantener las vistas separadas de los controladores.
* Centralizar los componentes compartidos.
* Separar los estilos globales de los estilos específicos.
* Evitar duplicar implementaciones.
* Mantener las credenciales fuera de los archivos públicos.
* Evitar archivos redundantes cuando exista una implementación modular equivalente.
* Incorporar nuevos módulos respetando la organización existente.
* Actualizar esta estructura cuando se consoliden o reubiquen archivos.

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

**Estado:** interfaz implementada con adaptaciones Desktop y Mobile y ajustes visuales reportados.

## 9.2. Dashboard

Constituye la interfaz principal del usuario autenticado.

Los avances reportados incluyen:

* Estructura general del Dashboard.
* Tarjetas de resumen.
* Sección de actividad reciente.
* Próximas oportunidades.
* Acciones rápidas.
* Saludo personalizado a partir de los datos de sesión disponibles.
* Topbar adaptada a dispositivos móviles.
* Sidebar colapsable en escritorio.
* Navegación lateral adaptada a móviles.
* Persistencia local del estado colapsado del sidebar.
* Correcciones de estilos y dimensiones de botones.
* Ajustes del loader y de la navegación.

**Estado:** interfaz maquetada e implementada visualmente con ajustes de navegación y comportamiento responsive.

Las tarjetas y los elementos que dependen de información dinámica deben conectarse a MySQL. Los datos simulados no deben presentarse como información persistente real.

La autenticación definitiva y los controles de acceso deben verificarse de manera independiente.

## 9.3. Autenticación

Agrupa las funcionalidades de acceso a la plataforma.

Incluye:

* Inicio de sesión.
* Registro.
* Recuperación de contraseña.

Los informes identifican vistas, archivos de procesamiento, validaciones y ajustes técnicos relacionados con estas operaciones.

**Estado:** existen avances de implementación, pero la maquetación, la autenticación real, la integración con MySQL y la recuperación completa de contraseña requieren completar y verificar el trabajo pendiente.

Las sesiones simuladas utilizadas para facilitar pruebas visuales no constituyen un sistema de autenticación real.

## 9.4. Perfil

Está destinado a la consulta y gestión de la información personal, académica y profesional del usuario.

Entre sus responsabilidades previstas se encuentran:

* Visualización del perfil.
* Consulta de información personal.
* Actualización de datos.
* Gestión de información académica y profesional.

**Estado:** diseño UI/UX finalizado en Figma; implementación pendiente.

## 9.5. Egresados

Permitirá consultar y visualizar información de los egresados que forman parte de la comunidad.

El módulo deberá respetar los permisos correspondientes y limitar la exposición de información personal.

**Estado:** diseño UI/UX finalizado en Figma; implementación pendiente.

## 9.6. Proyectos

Permitirá consultar y gestionar proyectos relacionados con la comunidad.

Las interfaces contempladas incluyen:

* Listado de proyectos.
* Creación de proyectos.
* Detalle de proyecto.
* Edición de proyecto.

**Estado:** diseño UI/UX finalizado en Figma; implementación pendiente.

## 9.7. Noticias

Permitirá consultar y gestionar noticias relacionadas con la institución y la comunidad de egresados.

Las interfaces contempladas incluyen:

* Listado de noticias.
* Detalle de noticia.
* Creación de noticias.
* Edición de noticias.

**Estado:** diseño UI/UX finalizado en Figma; implementación pendiente.

## 9.8. Administración

Agrupará las funciones administrativas de la plataforma.

Entre sus responsabilidades previstas se encuentran la gestión de egresados, proyectos, noticias y otros recursos del sistema.

**Estado:** diseño UI/UX finalizado en Figma; implementación pendiente.

El acceso a este módulo deberá estar protegido mediante controles de autorización del lado del servidor. Ocultar enlaces o botones en la interfaz no es suficiente para impedir accesos no autorizados.

---

# 10. Flujos de procesamiento

## 10.1. Flujo general de una solicitud

El flujo previsto para las operaciones que requieren procesamiento en el servidor es:

1. El usuario interactúa con una interfaz.
2. El navegador envía una solicitud.
3. La solicitud llega al controlador o al punto de procesamiento correspondiente.
4. PHP valida los datos recibidos.
5. Se ejecutan las reglas de negocio aplicables.
6. Cuando corresponde, se consulta o modifica la base de datos.
7. El servidor construye una respuesta o realiza una redirección.
8. La interfaz presenta el resultado al usuario.

Las validaciones del navegador apoyan la experiencia de uso, pero las reglas de seguridad deben ejecutarse en el servidor.

## 10.2. Flujo previsto de inicio de sesión

```text
Formulario de inicio de sesión
            │
            ▼
Validación inicial en el navegador
            │
            ▼
Controlador de procesamiento
            │
            ▼
Validación del lado del servidor
            │
            ▼
Verificación de credenciales
            │
            ▼
Creación segura de la sesión
            │
            ▼
Respuesta o redirección
```

Este diagrama representa el flujo esperado. Los pasos concretos deben verificarse en el código antes de considerar terminada la funcionalidad.

La contraseña debe verificarse mediante los mecanismos apropiados y nunca compararse como texto plano con una contraseña almacenada.

## 10.3. Flujo previsto de registro

```text
Formulario de registro
          │
          ▼
Validación en el navegador
          │
          ▼
Controlador de procesamiento
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

Los informes anteriores mencionan validaciones del servidor, comprobación de contraseñas coincidentes, verificación de correo único y uso de `password_hash()`.

Estos pasos deben verificarse frente al código y el modelo de datos definitivo.

## 10.4. Flujo previsto de recuperación de contraseña

```text
Formulario de recuperación
            │
            ▼
Validación del correo
            │
            ▼
Procesamiento de la solicitud
            │
            ▼
Respuesta genérica
            │
            ▼
Generación segura de token
            │
            ▼
Almacenamiento y expiración
            │
            ▼
Envío de instrucciones
            │
            ▼
Validación del token
            │
            ▼
Restablecimiento de contraseña
```

Los informes identifican avances en la validación del correo, las respuestas genéricas y la generación de tokens.

El almacenamiento, la expiración, el envío de instrucciones y el restablecimiento final deben verificarse individualmente.

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
* Verificar contraseñas mediante los mecanismos apropiados de PHP.
* No almacenar contraseñas en texto plano.
* Evitar incluir credenciales en mensajes de error o registros públicos.

## 11.3. Acceso a la base de datos

* Utilizar sentencias preparadas mediante PDO para consultas con datos variables.
* Mantener separadas las credenciales de conexión.
* Controlar los errores sin exponer detalles internos al usuario.
* Utilizar transacciones cuando una operación requiera mantener la consistencia de varios cambios relacionados.

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

## 11.6. Formularios y sesiones

A medida que se completen los módulos, deberán verificarse también las medidas aplicables frente a solicitudes no autorizadas, ataques CSRF, manejo incorrecto de sesiones, exposición de información y carga de archivos inseguros.

La presencia de algunas medidas de seguridad no implica que toda la aplicación haya sido auditada o esté preparada para producción.

---

# 12. Recursos institucionales

La interfaz utiliza recursos gráficos de Raíces Alzate almacenados dentro del proyecto.

El logotipo se incorpora en componentes de marca, como la cabecera, el pie de página y el favicon, según corresponda.

Los informes de implementación también identifican el siguiente recurso para el loader:

```text
assets/icons/logo sin fondo raices alzate.ico
```

La ruta definitiva debe verificarse según la ubicación de la página y la forma en que se construyen las rutas de los recursos.

Cuando corresponda, se recomienda centralizar la construcción de las rutas para evitar errores al acceder a páginas ubicadas en distintos niveles o rutas.

---

# 13. Pruebas y verificación técnica

La implementación requiere verificaciones continuas para comprobar que las modificaciones no afecten las funcionalidades existentes.

Los informes de Antigravity y Gemini registran verificaciones de sintaxis PHP y respuestas HTTP satisfactorias para determinados archivos durante las jornadas de trabajo.

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

La etapa actual corresponde a la implementación y el refinamiento de la interfaz. Se han trabajado Inicio, el Dashboard y componentes compartidos, además de ajustes técnicos en las rutas, las sesiones y el loader.

El trabajo continuará con la implementación de los módulos restantes y la consolidación de la autenticación y la base de datos.

La documentación deberá actualizarse conforme cambie la arquitectura o se incorporen funcionalidades.

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

## 16.2. Implementación de interfaces

| Módulo         | Estado                                                                                    |
| -------------- | ----------------------------------------------------------------------------------------- |
| Inicio         | Implementado visualmente; ajustes responsive y de componentes realizados.                 |
| Dashboard      | Interfaz implementada visualmente; ajustes de navegación y comportamiento reportados.     |
| Perfil         | Diseño finalizado; implementación pendiente.                                              |
| Egresados      | Diseño finalizado; implementación pendiente.                                              |
| Proyectos      | Diseño finalizado; implementación pendiente.                                              |
| Noticias       | Diseño finalizado; implementación pendiente.                                              |
| Administración | Diseño finalizado; implementación pendiente.                                              |
| Autenticación  | Vistas y procesamiento con avances; falta completar y verificar la integración funcional. |

## 16.3. Componentes y navegación compartidos

Los informes de implementación registran los siguientes avances:

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
* Correcciones de rutas y de gestión de sesiones.

La existencia de estos avances no implica que todos los componentes estén definitivamente consolidados. Deben seguir verificándose conforme se incorporen nuevos módulos.

## 16.4. Autenticación y sesiones

Los informes identifican avances en:

* Vistas de inicio de sesión.
* Vista de registro.
* Vista de recuperación de contraseña.
* Controladores de acceso a determinadas interfaces.
* Archivos de procesamiento de formularios.
* Ajustes de inicialización de sesiones.
* Correcciones relacionadas con el envío de cabeceras HTTP.
* Utilidad local para crear una sesión simulada.
* Controlador de cierre de sesión.

La autenticación real debe comprobarse de extremo a extremo con la base de datos y las contraseñas almacenadas.

También deben verificarse las restricciones de acceso, la gestión segura de sesiones y el proceso completo de recuperación de contraseña.

## 16.5. Base de datos

La conexión entre PHP y MySQL fue probada durante la preparación de la implementación.

Continúan pendientes:

* Definición del modelo entidad-relación.
* Diseño definitivo de las tablas.
* Establecimiento de claves primarias y foráneas.
* Definición de restricciones e índices.
* Consolidación de la configuración de conexión.
* Implementación de consultas y operaciones de persistencia.
* Integración de los módulos con datos reales.
* Sustitución de los datos simulados del Dashboard.
* Pruebas de integridad y consistencia de la información.

## 16.6. Pendientes técnicos

Los principales pendientes identificados son:

* Completar y verificar la autenticación real.
* Terminar la maquetación y los ajustes de las interfaces de autenticación.
* Completar el flujo de recuperación de contraseña.
* Configurar el envío real de correos cuando corresponda.
* Verificar el almacenamiento, la expiración y la utilización de tokens.
* Consolidar la estructura de componentes compartidos.
* Implementar Perfil.
* Implementar Egresados.
* Implementar Proyectos.
* Implementar Noticias.
* Implementar el Panel administrativo.
* Diseñar e implementar el modelo de datos.
* Completar la integración con MySQL.
* Reemplazar los datos simulados por consultas reales.
* Implementar y comprobar los controles de autorización.
* Ampliar las pruebas funcionales, visuales y de seguridad.
* Mantener actualizados los documentos técnicos.

---

# 17. Próxima etapa

La implementación continuará con el desarrollo incremental de los módulos de la plataforma.

La continuidad del proyecto deberá contemplar:

1. Completar y verificar las pantallas de autenticación.
2. Definir el modelo entidad-relación y las tablas de MySQL.
3. Consolidar la conexión y la capa de acceso a datos mediante PDO.
4. Implementar el módulo de Perfil.
5. Implementar el módulo de Egresados.
6. Implementar el módulo de Proyectos.
7. Implementar el módulo de Noticias.
8. Implementar el Panel administrativo.
9. Integrar las funcionalidades con datos persistentes.
10. Implementar y verificar los controles de autorización.
11. Realizar pruebas funcionales, visuales y técnicas.
12. Actualizar la documentación conforme se consoliden los avances.

El orden de ejecución podrá ajustarse según las dependencias técnicas. En particular, el diseño de la base de datos debe coordinarse con los requerimientos de autenticación, usuarios, perfiles y los demás módulos.

Cada módulo deberá incorporar progresivamente:

* La vista correspondiente al diseño aprobado.
* Los estilos y componentes reutilizables.
* Los comportamientos necesarios en JavaScript.
* El procesamiento requerido en PHP.
* La validación de los datos.
* La integración con la base de datos cuando corresponda.
* Los controles de seguridad aplicables.
* Las pruebas funcionales y visuales.
* La actualización de la documentación.

Las interfaces y funcionalidades se considerarán terminadas únicamente cuando se hayan verificado sus criterios funcionales y técnicos.

La documentación de arquitectura se mantendrá como referencia viva del proyecto, reflejando las decisiones y los cambios que realmente se incorporen al repositorio.

---

**Raíces Alzate — Institución Educativa Gilberto Alzate Avendaño**

*Arquitectura del proyecto · v0.1.1 · Fase 4 — Implementación*
