# 🌱 Raíces Alzate

> Plataforma para la gestión, el seguimiento y el fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 🏗️ Arquitectura del Proyecto

## Información General

| Campo                | Información             |
| -------------------- | ----------------------- |
| Proyecto             | Raíces Alzate           |
| Versión              | v0.1.1                  |
| Estado               | 🟡 En desarrollo        |
| Fase actual          | Fase 4 — Implementación |
| Inicio del proyecto  | 2025                    |
| Última actualización | 9 de octubre de 2026    |

---

# 1. Introducción

Este documento describe la arquitectura general de **Raíces Alzate**, incluyendo la organización del proyecto, las tecnologías utilizadas, la separación de responsabilidades, los módulos principales, la estructura de archivos y los flujos de navegación y procesamiento de información.

La arquitectura busca facilitar el mantenimiento, la reutilización de componentes, la seguridad, la trazabilidad y la evolución progresiva de la plataforma.

El proyecto se encuentra en desarrollo desde **2025**. Durante las etapas iniciales se trabajaron la planificación, la documentación base y la definición de los requerimientos. Posteriormente se desarrolló la Fase 3 — Diseño UI/UX, completada a finales de septiembre de 2026.

Como resultado de la etapa de diseño, se establecieron el sistema visual, los componentes reutilizables, los estados de interacción, los flujos de navegación y las interfaces Desktop y Mobile que sirven como referencia para la implementación.

Actualmente, Raíces Alzate se encuentra en la **Fase 4 — Implementación**, durante la cual se está trasladando progresivamente el diseño aprobado en Figma a una aplicación web funcional.

Los avances de esta fase incluyen:

* Implementación modular de la página de Inicio.
* Construcción de las interfaces de autenticación.
* Implementación de los formularios de inicio de sesión, registro y recuperación de contraseña.
* Desarrollo de validaciones de formularios en JavaScript.
* Implementación inicial del procesamiento de formularios mediante PHP.
* Preparación de la conexión con la base de datos MySQL y utilización de PDO.
* Organización de los estilos y recursos compartidos.
* Adaptación responsive de la cabecera y de las interfaces implementadas.

La implementación continúa de manera incremental. Por lo tanto, la existencia de una interfaz o de un controlador no implica que todas las funcionalidades asociadas estén terminadas o disponibles en producción.

---

# 2. Arquitectura General

Raíces Alzate utiliza una arquitectura web basada en el modelo **cliente-servidor**, con separación entre la presentación, el procesamiento de solicitudes y el almacenamiento de información.

```text
                       USUARIO
                          │
                          ▼
                    NAVEGADOR WEB
                          │
                          ▼
              PRESENTACIÓN E INTERFAZ
                HTML5 · CSS · JavaScript
                          │
                          ▼
                 ENTRADA AL SISTEMA
                       index.php
                          │
                          ▼
               CONTROLADORES Y RUTAS
                   PHP nativo
                          │
                          ▼
                LÓGICA DE APLICACIÓN
             Validaciones y procesamiento
                          │
                          ▼
                  ACCESO A DATOS
                       PDO
                          │
                          ▼
                    BASE DE DATOS
                        MySQL
```

El navegador presenta las interfaces y permite la interacción del usuario. Las solicitudes que requieren procesamiento en el servidor son atendidas por PHP, que ejecuta las validaciones y operaciones correspondientes.

Cuando una funcionalidad necesita consultar o modificar información persistente, el backend utiliza la capa de acceso a datos para comunicarse con MySQL.

La implementación debe mantener separadas las responsabilidades de cada parte, evitando concentrar toda la presentación, la lógica de negocio y el acceso a datos en un único archivo.

## 2.1. Enfoque arquitectónico

La implementación se basa en los siguientes criterios:

* PHP nativo, sin depender de frameworks de aplicación externos.
* Organización modular de las interfaces y los recursos.
* Uso de `index.php` como punto de entrada y enrutamiento de la aplicación cuando corresponde.
* Controladores independientes para las operaciones y las rutas que requieren procesamiento.
* Vistas organizadas dentro de `pages/`.
* Componentes compartidos para elementos de interfaz reutilizables.
* Procesamiento de formularios mediante archivos específicos dentro de `auth/`.
* Estilos organizados según su responsabilidad.
* Separación entre presentación, procesamiento y persistencia.
* Integración progresiva de las funcionalidades.
* Uso de Git y GitHub para el control de versiones.

La arquitectura se irá ajustando conforme se incorporen nuevos módulos, sin perder la separación de responsabilidades establecida.

---

# 3. Tecnologías Utilizadas

## 3.1. Frontend

| Tecnología        | Responsabilidad                                                         |
| ----------------- | ----------------------------------------------------------------------- |
| HTML5             | Estructura semántica de las interfaces.                                 |
| CSS3              | Presentación visual, distribución, componentes y adaptación responsive. |
| JavaScript nativo | Interactividad, validación reactiva y comportamiento de componentes.    |
| Inter             | Tipografía principal de las interfaces.                                 |
| Figma             | Referencia del diseño visual y de los componentes.                      |

El frontend se desarrolla utilizando tecnologías nativas, sin incorporar frameworks externos para la construcción de las interfaces.

La implementación debe respetar los criterios visuales establecidos en Figma y mantener una presentación consistente entre los diferentes módulos.

## 3.2. Backend

| Tecnología                    | Responsabilidad                                                                    |
| ----------------------------- | ---------------------------------------------------------------------------------- |
| PHP nativo                    | Procesamiento de solicitudes y lógica de aplicación.                               |
| PDO                           | Acceso a la base de datos mediante una interfaz de conexión de PHP.                |
| Sesiones PHP                  | Mecanismo previsto para mantener el estado de autenticación.                       |
| Funciones de seguridad de PHP | Validación de datos, generación de contraseñas hash y generación segura de tokens. |

Los mecanismos de autenticación, autorización y gestión de sesiones deben implementarse y verificarse conforme se desarrollen los módulos correspondientes.

La utilización de una función o mecanismo de seguridad en una funcionalidad no implica que todos los controles de seguridad de la plataforma estén terminados.

## 3.3. Base de datos

| Tecnología | Responsabilidad                               |
| ---------- | --------------------------------------------- |
| MySQL      | Almacenamiento persistente de la información. |
| PDO        | Comunicación entre PHP y la base de datos.    |
| SQL        | Consultas y operaciones de persistencia.      |

La conexión entre PHP y MySQL fue verificada durante la preparación de la implementación.

La estructura definitiva de las tablas, las relaciones, las restricciones y las consultas debe documentarse conforme se definan e implementen los módulos de datos.

## 3.4. Herramientas de desarrollo

* Visual Studio Code para la edición del código.
* Git para el control de versiones.
* GitHub para el alojamiento del repositorio y la colaboración.
* Figma para el diseño UI/UX.
* Herramientas de desarrollo y validación para comprobar el funcionamiento de las interfaces y los archivos PHP.

---

# 4. Arquitectura por Capas

## 4.1. Capa de presentación

La capa de presentación es responsable de mostrar la información y permitir que los usuarios interactúen con la plataforma.

Está compuesta principalmente por HTML5, CSS3, JavaScript y las vistas PHP.

Sus responsabilidades incluyen:

* Representar las interfaces diseñadas en Figma.
* Presentar formularios y sus campos.
* Mostrar mensajes de validación y alertas.
* Gestionar los estados visuales de los componentes.
* Adaptar la interfaz a Desktop y Mobile.
* Reutilizar elementos comunes entre las diferentes páginas.
* Facilitar la navegación entre las secciones del sistema.

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
* Criterios responsive.

Las interfaces consideran principalmente dos referencias de diseño:

* **Desktop:** ancho de referencia de 1440 px.
* **Mobile:** ancho de referencia de 390 px.

Estas dimensiones corresponden a las referencias de diseño y no representan las únicas resoluciones compatibles. La implementación debe adaptarse también a los tamaños intermedios pertinentes.

### Organización de estilos CSS

Los estilos se distribuyen en archivos específicos para evitar concentrar todas las reglas en una única hoja de estilos.

```text
assets/
├── css/
│   ├── main.css
│   ├── fonts.css
│   ├── header.css
│   ├── footer.css
│   └── auth.css
└── js/
    └── auth.js
```

Esta representación incluye los archivos identificados en los informes de implementación. La estructura definitiva puede incorporar hojas de estilos y scripts adicionales a medida que se desarrollen otros módulos.

#### `main.css`

Contiene los estilos generales del proyecto y los componentes visuales compartidos.

Entre sus responsabilidades se encuentran:

* Reglas base y normalización de estilos.
* Colores generales.
* Estilos comunes de enlaces.
* Estilos de botones compartidos.
* Alertas y mensajes de estado.
* Reglas generales de presentación.
* Elementos visuales reutilizables.

Las clases compartidas deben mantenerse centralizadas cuando su responsabilidad sea global, evitando duplicar reglas entre las hojas de estilos de cada módulo.

#### `fonts.css`

Centraliza la declaración y configuración de la tipografía Inter, utilizando los recursos tipográficos locales del proyecto.

```text
assets/fonts/
```

Los pesos considerados en la implementación son:

* 400 — Regular.
* 500 — Medium.
* 600 — Semibold.
* 700 — Bold.

#### `header.css`

Contiene los estilos específicos de la cabecera.

La referencia visual de la cabecera establece una altura de 72 px.

En Desktop contempla:

* Identidad visual a la izquierda.
* Navegación en la zona central.
* Acciones de usuario a la derecha.

En Mobile contempla:

* Identidad visual.
* Botón de menú hamburguesa.
* Navegación adaptada al espacio disponible.
* Acciones de autenticación dentro del menú desplegable o lateral, según la implementación del componente.

El comportamiento responsive oculta las acciones de cabecera que no deben mostrarse directamente en la barra superior y permite acceder a ellas desde la navegación Mobile.

#### `footer.css`

Contiene los estilos específicos del pie de página.

La estructura visual establecida contempla:

* Fondo `#212121`.
* Distribución en tres columnas en Desktop.
* Identidad de Raíces Alzate.
* Enlaces de navegación.
* Información institucional.
* Separador visual del bloque de copyright.
* Adaptación a pantallas Mobile.

La estructura se mantiene como componente compartido para las páginas que requieren el pie de página institucional.

#### `auth.css`

Contiene los estilos relacionados con las interfaces de autenticación.

Sus responsabilidades incluyen:

* Tarjetas de autenticación.
* Distribución de formularios.
* Campos de entrada.
* Estados de validación.
* Mensajes de error.
* Botones de autenticación.
* Espaciado y alineación de los elementos.
* Adaptación responsive de las pantallas de autenticación.

Las reglas deben mantener coherencia con los estilos globales y con los estados definidos en el Design System.

### JavaScript de autenticación

El archivo `assets/js/auth.js` centraliza los comportamientos de los formularios de autenticación que se han implementado hasta el momento.

Entre las funciones documentadas se encuentran:

* Validación reactiva de campos.
* Limpieza de errores cuando el usuario corrige una entrada.
* Validación del formato del correo electrónico.
* Presentación de mensajes de retroalimentación.
* Prevención de envíos repetidos del formulario de inicio de sesión.
* Inicialización de la validación del formulario de registro.
* Gestión de estados de error en los campos.

Las validaciones de JavaScript mejoran la experiencia de uso, pero no sustituyen las validaciones obligatorias que debe realizar el servidor.

---

## 4.2. Capa de enrutamiento y controladores

La capa de enrutamiento determina qué interfaz o funcionalidad debe atender una solicitud.

El archivo `index.php` actúa como punto de entrada y contiene rutas para acceder a determinadas vistas del sistema.

Entre las rutas documentadas se encuentran las relacionadas con la página principal y la recuperación de contraseña.

La aplicación también dispone de controladores PHP independientes en la raíz del proyecto para algunas interfaces.

Entre ellos se encuentran:

* `login.php`
* `registro.php`
* `recuperar-password.php`

Estos archivos organizan el acceso a las interfaces correspondientes y permiten separar la entrada de una página de su contenido visual.

La arquitectura no obliga a que todas las operaciones pasen por una única ruta: las rutas existentes deben mantenerse coherentes mientras se consolida la organización general de la aplicación.

### Responsabilidades de los controladores

* Preparar los datos necesarios para presentar una vista.
* Determinar qué contenido debe mostrarse.
* Coordinar el procesamiento de las solicitudes.
* Invocar la lógica correspondiente cuando sea necesario.
* Gestionar las respuestas y redirecciones.
* Evitar concentrar la estructura visual completa dentro del controlador.

Los controladores deben mantenerse ligeros y delegar el contenido visual a las vistas y el procesamiento especializado a los archivos correspondientes.

---

## 4.3. Capa de vistas

Las vistas se encuentran organizadas dentro del directorio `pages/`.

Esta capa contiene el contenido de las interfaces y la estructura HTML generada mediante PHP.

Entre las vistas implementadas se encuentran:

```text
pages/
├── login.php
├── registro.php
└── recuperar-password.php
```

La vista de Inicio también forma parte de la organización del proyecto, aunque su nombre y ubicación definitivos deben mantenerse alineados con la estructura real del repositorio.

### Responsabilidades de las vistas

* Construir la estructura semántica de cada interfaz.
* Presentar los campos y controles del usuario.
* Incorporar los componentes compartidos.
* Mostrar alertas y mensajes de retroalimentación.
* Conservar la jerarquía visual definida en Figma.
* Utilizar las hojas de estilos y scripts correspondientes.

Las vistas no deben contener operaciones complejas de acceso a datos ni asumir que los valores enviados por el navegador son confiables.

---

## 4.4. Componentes PHP compartidos

El proyecto mantiene componentes reutilizables para evitar duplicaciones en la estructura de las páginas.

La implementación documentada utiliza:

```text
includes/
├── header.php
└── footer.php
```

Estos componentes centralizan elementos compartidos de la interfaz.

### `includes/header.php`

Es responsable de incorporar la estructura compartida de la cabecera y los elementos iniciales del documento HTML.

Entre sus responsabilidades se encuentran:

* Declaración inicial del documento.
* Metadatos generales.
* Inclusión de los estilos correspondientes.
* Incorporación del favicon.
* Presentación de la marca.
* Navegación principal.
* Acciones de autenticación.
* Adaptación de la navegación a dispositivos Mobile.

También contempla la carga condicional de recursos específicos para las interfaces de autenticación cuando corresponde.

### `includes/footer.php`

Es responsable de incorporar el pie de página compartido y los recursos JavaScript necesarios antes del cierre del documento.

La carga de scripts específicos depende de la interfaz que se esté presentando.

### Directorio `components/`

La estructura original también contempla:

```text
components/
├── header.php
└── footer.php
```

No obstante, los informes de implementación identifican `includes/header.php` e `includes/footer.php` como los componentes que actualmente participan en la construcción de las páginas.

Por ello, la existencia de archivos con nombres equivalentes en `components/` no debe interpretarse automáticamente como una segunda implementación activa de los mismos componentes.

La estructura deberá revisarse para determinar si `components/` contiene implementaciones adicionales necesarias o si corresponde consolidar los componentes compartidos en una única ubicación.

El objetivo es evitar duplicaciones y establecer una única fuente de mantenimiento para cada componente.

---

## 4.5. Capa de lógica de aplicación

La lógica de aplicación se desarrolla principalmente mediante PHP nativo.

Esta capa procesa las solicitudes, aplica las reglas correspondientes y coordina las operaciones que requieren interacción con la base de datos.

Las funcionalidades previstas para esta capa incluyen:

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

### Organización del procesamiento de autenticación

Los informes de implementación identifican los siguientes archivos:

```text
auth/
├── login-process.php
├── registro-process.php
└── recuperar-process.php
```

Estos archivos se encargan del procesamiento de las operaciones relacionadas con autenticación y recuperación de contraseña.

#### `auth/login-process.php`

Es el punto de procesamiento del formulario de inicio de sesión.

Su responsabilidad corresponde a recibir y procesar la solicitud de autenticación. La verificación definitiva de credenciales, la creación de la sesión y la autorización de acceso deben comprobarse de acuerdo con la implementación efectiva del archivo.

#### `auth/registro-process.php`

Procesa el formulario de registro.

Los informes indican que incorpora validaciones del lado del servidor, comprobación de contraseñas coincidentes, verificación de correo electrónico único, utilización de `password_hash()` y operaciones mediante PDO.

También se reporta el uso de control transaccional para las operaciones de base de datos correspondientes.

La definición definitiva de los campos de registro debe mantenerse alineada con la vista y con el modelo de datos implementado.

#### `auth/recuperar-process.php`

Procesa las solicitudes de recuperación de contraseña.

La implementación documentada contempla la validación del correo electrónico y la generación de tokens mediante `random_bytes(32)`.

La respuesta se mantiene genérica para no revelar si una dirección de correo corresponde a una cuenta existente.

El envío real de mensajes electrónicos todavía depende de configurar e integrar un servicio de correo, por ejemplo mediante SMTP.

La generación de un token, por sí sola, no significa que todo el proceso de recuperación se encuentre terminado. También deben verificarse su almacenamiento seguro, expiración, utilización única, validación y el proceso final de restablecimiento de contraseña.

---

## 4.6. Capa de acceso a datos

La capa de acceso a datos conecta la lógica de aplicación con la base de datos MySQL.

El proyecto utiliza PDO como mecanismo de acceso desde PHP.

Sus responsabilidades incluyen:

* Establecer conexiones con la base de datos.
* Ejecutar consultas SQL.
* Recuperar información.
* Insertar nuevos registros.
* Actualizar datos existentes.
* Eliminar registros cuando la funcionalidad lo permita.
* Gestionar transacciones.
* Tratar los errores de acceso a datos.

Las consultas que incorporan valores proporcionados por los usuarios deben utilizar sentencias preparadas cuando corresponda.

La configuración de conexión debe mantenerse separada de las vistas y no debe exponer credenciales en el código público del repositorio.

La organización definitiva de esta capa deberá documentarse cuando se consoliden los archivos de configuración, las funciones de acceso a datos y los modelos o módulos de persistencia.

---

## 4.7. Capa de datos

La base de datos MySQL está destinada a almacenar la información persistente de la plataforma.

Las entidades previstas incluyen:

* Usuarios.
* Perfiles.
* Egresados.
* Proyectos.
* Noticias.
* Roles y permisos.
* Información complementaria de la plataforma.

La conexión entre PHP y MySQL fue verificada satisfactoriamente durante la preparación de la implementación.

Sin embargo, la verificación de conectividad no implica que todas las tablas, relaciones y operaciones de persistencia estén terminadas.

La estructura definitiva deberá responder a los requerimientos funcionales de cada módulo y documentarse a medida que avance la integración.

---

# 5. Arquitectura del Diseño

La arquitectura visual de Raíces Alzate se definió durante la Fase 3 — Diseño UI/UX.

El diseño de las interfaces se considera completado y constituye la referencia visual para la implementación frontend.

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
* Adaptación responsive.

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

La tipografía utilizada en las interfaces es **Inter**.

Los pesos contemplados son 400, 500, 600 y 700, de acuerdo con la jerarquía y la función de cada elemento.

### Sistema de espaciado

La escala de espaciado se basa principalmente en:

`4 / 8 / 12 / 16 / 24 / 32 / 48 / 64 px`

La implementación debe conservar la consistencia entre los márgenes, el espaciado interno de los componentes y la separación entre secciones.

## 5.2. Components & Responsive

La página `Components & Responsive` contiene los componentes reutilizables, sus estados y los criterios de adaptación a distintos tamaños de pantalla.

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
* On / Off en componentes que lo requieren.

Los estados implementados en el código deben conservar los colores, los indicadores y el comportamiento definidos en el sistema visual.

Por ejemplo, los campos de entrada deben permitir distinguir entre el estado normal, el enfoque, el error y la deshabilitación. Los errores no deben comunicarse únicamente mediante un cambio de color cuando sea posible acompañarlos de un mensaje textual.

La página de componentes se utiliza como referencia durante la construcción y calibración de las interfaces.

---

# 6. Interfaces del Sistema

Como resultado de la etapa de diseño UI/UX se definieron 17 interfaces principales para Desktop y sus correspondientes diseños Mobile.

La implementación de las interfaces continúa de manera progresiva.

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

La existencia de un diseño Mobile no implica que su implementación se encuentre completada.

---

# 7. Navegación del Sistema

El flujo general de navegación se definió durante el diseño UI/UX.

```text
Inicio
  │
  ▼
Autenticación
  │
  ▼
Dashboard
  ├── Perfil
  │     └── Egresados
  │
  ├── Proyectos
  │     └── Noticias
  │
  └── Administración
```

Este diagrama representa el flujo conceptual de navegación. Las rutas efectivamente disponibles dependen de la implementación de cada módulo.

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

## 7.3. Flujo de perfil

```text
Perfil
  ├── Editar perfil
  └── Egresados
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

# 8. Estructura del Proyecto

La estructura del repositorio se organiza por responsabilidades.

La siguiente representación combina los archivos y directorios identificados en la estructura documentada con los archivos incorporados durante la implementación de autenticación. Debe entenderse como una estructura de referencia, no como una verificación exhaustiva del estado actual de cada archivo.

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
│   │   └── auth.css
│   ├── js/
│   │   └── auth.js
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
│   └── footer.php
├── pages/
│   ├── login.php
│   ├── registro.php
│   └── recuperar-password.php
├── uploads/
├── index.php
├── login.php
├── registro.php
├── recuperar-password.php
├── .gitignore
└── README.md
```

### Responsabilidad de los directorios

| Directorio o archivo     | Responsabilidad                                                                          |
| ------------------------ | ---------------------------------------------------------------------------------------- |
| `.github/`               | Configuraciones y recursos relacionados con GitHub.                                      |
| `admin/`                 | Organización prevista para funcionalidades administrativas.                              |
| `usuario/`               | Organización prevista para funcionalidades relacionadas con usuarios.                    |
| `assets/css/`            | Hojas de estilos.                                                                        |
| `assets/js/`             | Scripts JavaScript.                                                                      |
| `assets/img/`            | Recursos gráficos.                                                                       |
| `assets/icons/`          | Iconos y recursos gráficos pequeños.                                                     |
| `assets/fonts/`          | Recursos tipográficos locales.                                                           |
| `assets/videos/`         | Recursos audiovisuales.                                                                  |
| `auth/`                  | Procesamiento de operaciones de autenticación.                                           |
| `components/`            | Espacio previsto para componentes reutilizables, sujeto a consolidación con `includes/`. |
| `config/`                | Configuración de la aplicación y sus servicios.                                          |
| `database/`              | Recursos relacionados con la base de datos.                                              |
| `docs/`                  | Documentación técnica y de seguimiento.                                                  |
| `includes/`              | Componentes PHP compartidos.                                                             |
| `pages/`                 | Vistas de las interfaces.                                                                |
| `uploads/`               | Espacio destinado a archivos cargados por los usuarios, sujeto a controles de seguridad. |
| `index.php`              | Punto de entrada y enrutamiento de las rutas que gestiona.                               |
| `login.php`              | Entrada a la interfaz de inicio de sesión.                                               |
| `registro.php`           | Entrada a la interfaz de registro.                                                       |
| `recuperar-password.php` | Entrada a la interfaz de recuperación de contraseña.                                     |

### Criterios de organización

* Mantener las vistas separadas de los controladores.
* Centralizar los componentes compartidos.
* Separar los estilos globales de los estilos específicos de cada módulo.
* Evitar duplicar implementaciones de componentes.
* Mantener las credenciales fuera de los archivos públicos.
* No incorporar archivos estáticos redundantes en la raíz cuando exista una versión modular equivalente.
* Incorporar los nuevos módulos respetando la organización existente.
* Actualizar esta estructura cuando se consoliden o reubiquen archivos.

---

# 9. Módulos del Sistema

## 9.1. Inicio

Presenta la plataforma y comunica su propósito, sus beneficios y su propuesta para la comunidad de egresados.

La interfaz diseñada contempla:

* Header.
* Hero.
* Sección informativa sobre Raíces Alzate.
* Sección de beneficios.
* Footer.

La implementación inicial de Inicio fue desarrollada para Desktop y Mobile, estableciendo las bases de los componentes compartidos.

## 9.2. Autenticación

Agrupa las funcionalidades de acceso a la plataforma.

Incluye:

* Inicio de sesión.
* Registro.
* Recuperación de contraseña.

Durante la implementación se desarrollaron las vistas, los estilos y parte del procesamiento asociado a estas funcionalidades.

El funcionamiento completo de la autenticación debe evaluarse considerando tanto el procesamiento del servidor como la integración con la base de datos, la gestión de sesiones y los controles de autorización.

## 9.3. Usuarios y perfil

Está destinado a la consulta y gestión de la información de los usuarios.

Entre sus responsabilidades previstas se encuentran:

* Visualización del perfil.
* Consulta de información personal.
* Actualización de datos.
* Gestión de información académica y profesional.

La implementación de estas operaciones se realizará de acuerdo con los requerimientos y las interfaces correspondientes.

## 9.4. Egresados

Permitirá consultar y visualizar información de los egresados que forman parte de la comunidad.

El módulo deberá respetar los permisos correspondientes y limitar la exposición de información personal a los datos que sea apropiado mostrar.

## 9.5. Proyectos

Permitirá consultar y gestionar los proyectos publicados por la comunidad.

Las interfaces contempladas incluyen:

* Listado de proyectos.
* Creación de proyectos.
* Detalle de proyecto.
* Edición de proyecto.

## 9.6. Noticias

Permitirá consultar y gestionar noticias relacionadas con la institución y la comunidad de egresados.

Las interfaces contempladas incluyen:

* Listado de noticias.
* Detalle de noticia.
* Creación de noticias.
* Edición de noticias.

## 9.7. Administración

Agrupará las funciones administrativas de la plataforma.

Entre sus responsabilidades previstas se encuentran la gestión de egresados, proyectos, noticias y otros recursos del sistema.

El acceso a este módulo deberá estar protegido mediante controles de autorización del lado del servidor. Ocultar enlaces o botones en la interfaz no es suficiente para impedir el acceso no autorizado.

---

# 10. Flujos de Procesamiento

## 10.1. Flujo general de una solicitud

El flujo previsto para las operaciones que requieren procesamiento en el servidor es:

1. El usuario interactúa con una interfaz.
2. El navegador envía una solicitud al servidor.
3. La solicitud llega al controlador o al punto de procesamiento correspondiente.
4. PHP valida los datos recibidos.
5. Se ejecutan las reglas de negocio aplicables.
6. Cuando es necesario, se consulta o modifica la base de datos.
7. El servidor construye una respuesta o realiza una redirección.
8. La interfaz presenta el resultado al usuario.

Las validaciones del navegador sirven como apoyo a la experiencia de uso, pero las reglas de seguridad y las validaciones obligatorias deben ejecutarse en el servidor.

## 10.2. Flujo de inicio de sesión

```text
Formulario de inicio de sesión
            │
            ▼
Validación inicial en el navegador
            │
            ▼
auth/login-process.php
            │
            ▼
Validación del lado del servidor
            │
            ▼
Verificación de credenciales
            │
            ▼
Gestión de la sesión
            │
            ▼
Respuesta o redirección
```

Este diagrama representa el flujo esperado de autenticación. Los pasos concretos deben verificarse en el código antes de considerar terminada la funcionalidad.

La contraseña debe verificarse mediante el mecanismo de hashing correspondiente y nunca compararse como texto plano con una contraseña almacenada.

## 10.3. Flujo de registro

```text
Formulario de registro
          │
          ▼
Validación reactiva en JavaScript
          │
          ▼
auth/registro-process.php
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

Los informes de implementación indican que el procesamiento de registro contempla validación del servidor, comprobación de contraseñas coincidentes, verificación de correo único y uso de `password_hash()`.

Los campos definitivos deben corresponder a los definidos en la vista y en el modelo de datos.

## 10.4. Flujo de recuperación de contraseña

```text
Formulario de recuperación
            │
            ▼
Validación del correo
            │
            ▼
auth/recuperar-process.php
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

Los informes documentan la validación del correo, la respuesta genérica y la generación de tokens.

El almacenamiento, la expiración, el envío de instrucciones y el restablecimiento final deben verificarse individualmente. El envío real de correos requiere completar la integración con un servicio de correo.

---

# 11. Seguridad y Validación

La seguridad constituye un criterio transversal de la arquitectura.

Las medidas deben implementarse en cada módulo y verificarse antes de considerar una funcionalidad lista para su utilización.

## 11.1. Validación de datos

* Validar en el servidor los datos recibidos.
* Comprobar los formatos requeridos.
* Aplicar restricciones de longitud y contenido cuando corresponda.
* Validar las operaciones antes de acceder a la base de datos.
* No confiar exclusivamente en JavaScript para aceptar o rechazar información.

## 11.2. Contraseñas

* Utilizar funciones seguras de hashing de contraseñas, como `password_hash()`.
* Verificar contraseñas con los mecanismos apropiados de PHP.
* No almacenar contraseñas en texto plano.
* Evitar incluir credenciales en mensajes de error o registros públicos.

## 11.3. Acceso a la base de datos

* Utilizar sentencias preparadas mediante PDO para consultas con datos variables.
* Mantener separadas las credenciales de conexión.
* Controlar los errores de base de datos sin exponer detalles internos al usuario.
* Utilizar transacciones cuando una operación requiera mantener la consistencia de varios cambios relacionados.

## 11.4. Autenticación y autorización

* Verificar las credenciales en el servidor.
* Gestionar de manera segura las sesiones.
* Comprobar los permisos antes de ejecutar operaciones protegidas.
* Restringir las funciones administrativas a los usuarios autorizados.
* No depender únicamente de los elementos visibles en la interfaz para controlar el acceso.

## 11.5. Recuperación de contraseña

* Utilizar tokens generados de forma criptográficamente segura.
* Evitar revelar si un correo está registrado.
* Definir una expiración adecuada.
* Almacenar los tokens de forma segura.
* Evitar su reutilización después de completar la operación.
* Validar el token antes de permitir el cambio de contraseña.

## 11.6. Formularios y sesiones

A medida que se completen los módulos, deberán verificarse también las medidas aplicables frente a solicitudes no autorizadas, ataques CSRF, manejo incorrecto de sesiones, exposición de información y carga de archivos inseguros.

La presencia de algunas medidas de seguridad no implica que toda la aplicación haya sido auditada o que se encuentre preparada para producción.

---

# 12. Recursos Institucionales

La interfaz utiliza recursos gráficos de Raíces Alzate almacenados dentro del proyecto.

El isotipo se incorpora en los componentes de marca, como la cabecera, el pie de página y el favicon.

El favicon se declara en el documento HTML mediante una referencia al recurso almacenado localmente.

La ruta actualmente documentada es:

```html
<link rel="icon" type="image/x-icon" href="assets/icons/logo sin fondo raices alzate.ico">
```

La ruta relativa definitiva debe verificarse según la ubicación de la página que genera el documento y la forma en que se gestionan las rutas de los recursos.

Cuando corresponda, se recomienda centralizar la construcción de las rutas para evitar que los recursos dejen de cargarse al acceder a páginas ubicadas en distintas rutas.

---

# 13. Pruebas y Verificación Técnica

La implementación requiere verificaciones continuas para comprobar que las modificaciones no afecten las funcionalidades existentes.

Los informes de implementación registran verificaciones de sintaxis PHP y respuestas HTTP satisfactorias para los archivos evaluados durante las sesiones correspondientes.

Estas verificaciones constituyen evidencia de pruebas realizadas en ese momento, pero no sustituyen una prueba integral de todos los módulos.

## 13.1. Validaciones técnicas

Las verificaciones aplicables incluyen:

* Comprobación de sintaxis PHP.
* Revisión de las rutas.
* Comprobación de carga de CSS y JavaScript.
* Verificación de recursos gráficos y tipográficos.
* Pruebas de formularios.
* Pruebas de validación en el navegador.
* Pruebas de validación en el servidor.
* Verificación de consultas y operaciones de base de datos.
* Pruebas de navegación Desktop y Mobile.
* Comprobación de regresiones en las interfaces previamente implementadas.

## 13.2. Fidelidad visual

La implementación se calibra tomando como referencia las interfaces aprobadas en Figma.

Las verificaciones visuales deben considerar:

* Dimensiones y distribución de los componentes.
* Tipografía y jerarquía visual.
* Colores.
* Espaciado.
* Estados de interacción.
* Comportamiento responsive.
* Consistencia de los componentes compartidos.

Las diferencias entre el diseño y la implementación deben corregirse sin incorporar secciones o comportamientos que no correspondan a los requerimientos aprobados.

---

# 14. Flujo de Desarrollo del Proyecto

El desarrollo de Raíces Alzate se organiza de manera incremental.

Las etapas generales comprenden:

1. Planificación.
2. Documentación.
3. Definición de requerimientos.
4. Diseño UI/UX.
5. Definición de flujos de información.
6. Implementación frontend.
7. Implementación backend.
8. Integración con la base de datos.
9. Pruebas funcionales y técnicas.
10. Corrección de errores.
11. Control de versiones.
12. Actualización de la documentación.

Las etapas iniciales de planificación, documentación base y diseño UI/UX se han desarrollado.

La etapa actual corresponde a la implementación. En esta fase se han construido las primeras interfaces y se han incorporado partes del procesamiento de autenticación.

El trabajo continuará por módulos, integrando progresivamente las vistas, la lógica de aplicación y las operaciones de persistencia.

La documentación técnica deberá actualizarse conforme cambie la arquitectura o se incorporen funcionalidades.

---

# 15. Principios de Desarrollo

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

# 16. Estado Actual de la Arquitectura

## 16.1. Diseño UI/UX

La Fase 3 — Diseño UI/UX se considera completada a finales de septiembre de 2026.

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
* Definición de las interfaces Desktop.
* Diseño de las adaptaciones Mobile.

El diseño aprobado constituye la referencia visual de la implementación.

## 16.2. Implementación frontend

La implementación comenzó con la página de Inicio y los componentes compartidos.

Durante las jornadas de implementación se realizaron ajustes en:

* Estructura HTML.
* Cabecera.
* Navegación Desktop y Mobile.
* Menú Mobile.
* Pie de página.
* Recursos institucionales.
* Favicon.
* Tipografía local.
* Organización de las hojas de estilos.
* Botones compartidos.
* Alertas globales.
* Estados visuales de los campos y componentes.

Los avances se incorporan de manera incremental para facilitar su revisión y evitar regresiones.

## 16.3. Implementación de autenticación

Los informes de implementación identifican los siguientes avances:

* Creación de `login.php` y `pages/login.php`.
* Creación de `auth/login-process.php`.
* Creación de `registro.php` y `pages/registro.php`.
* Creación de `auth/registro-process.php`.
* Creación de `recuperar-password.php` y `pages/recuperar-password.php`.
* Creación de `auth/recuperar-process.php`.
* Incorporación de estilos de autenticación en `assets/css/auth.css`.
* Incorporación de validaciones y comportamientos en `assets/js/auth.js`.
* Validaciones reactivas de campos.
* Mensajes de retroalimentación.
* Validación de correo electrónico.
* Hash de contraseñas en el procesamiento de registro.
* Generación de tokens para el proceso de recuperación.
* Respuestas genéricas para evitar revelar si una cuenta existe.
* Carga condicional de recursos según la interfaz.

Los informes también registran pruebas de sintaxis y respuestas HTTP satisfactorias durante las jornadas correspondientes.

Estos avances deben entenderse como implementación documentada de los módulos de autenticación, no como confirmación de que todas las operaciones de producción estén terminadas.

## 16.4. Estado de la base de datos

La conexión entre PHP y MySQL fue verificada durante la preparación del proyecto.

La integración debe continuar con la consolidación de los modelos de datos, las consultas y las operaciones requeridas por cada módulo.

La arquitectura debe distinguir entre conectividad verificada y funcionalidades que ya realizan operaciones completas sobre datos persistentes.

## 16.5. Pendientes técnicos identificados

Entre los aspectos que requieren continuidad o verificación se encuentran:

* Completar y verificar el flujo de autenticación.
* Comprobar la gestión segura de sesiones.
* Completar la recuperación de contraseña.
* Configurar el servicio de correo para el envío real de instrucciones.
* Verificar el almacenamiento, la expiración y la utilización de tokens.
* Consolidar la estructura de componentes compartidos.
* Continuar la implementación de las interfaces diseñadas.
* Integrar progresivamente los módulos con MySQL.
* Implementar y comprobar los controles de autorización.
* Ampliar las pruebas funcionales y de seguridad.
* Mantener actualizados los documentos técnicos.

---

# 17. Próxima Etapa

La implementación continuará con el desarrollo incremental de los módulos de la plataforma.

Cada módulo deberá incorporar progresivamente:

1. La vista correspondiente al diseño aprobado.
2. Los estilos y componentes reutilizables.
3. Los comportamientos necesarios en JavaScript.
4. El procesamiento requerido en PHP.
5. La validación de los datos.
6. La integración con la base de datos, cuando corresponda.
7. Los controles de seguridad aplicables.
8. Las pruebas funcionales y visuales.
9. La actualización de la documentación.

Las interfaces y los módulos se considerarán terminados únicamente cuando se hayan verificado los criterios funcionales y técnicos correspondientes.

La documentación de arquitectura se mantendrá como referencia viva del proyecto, reflejando las decisiones y los cambios que realmente se incorporen al repositorio.

---

**Raíces Alzate — Institución Educativa Gilberto Alzate Avendaño**

*Arquitectura del proyecto · v0.1.1 · Fase 4 — Implementación*
