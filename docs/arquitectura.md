# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

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
| Última actualización | 8 de octubre de 2026    |

---

# Introducción

Este documento describe la arquitectura general de **Raíces Alzate**, incluyendo la organización del proyecto, las tecnologías utilizadas, la estructura por capas, los módulos principales, el flujo general del sistema y la relación entre las diferentes interfaces que conformarán la plataforma.

La arquitectura ha sido planteada para facilitar el mantenimiento, la escalabilidad, la reutilización de componentes y el desarrollo incremental del sistema.

El proyecto se encuentra en desarrollo desde **2025**. La **Fase 3 — Diseño UI/UX** fue completada, dejando definidos el sistema visual, los componentes reutilizables, el flujo de navegación, la estructura de las interfaces y los criterios responsive.

Actualmente el proyecto se encuentra en la **Fase 4 — Implementación**, durante la cual se está trasladando progresivamente el diseño aprobado en Figma al código frontend.

Como primer avance de esta etapa se implementó la pantalla **Inicio**, tanto en su adaptación Desktop como Mobile, junto con la estructura modular de cabecera, pie de página, estilos y recursos tipográficos.

---

# Arquitectura General

La plataforma seguirá una arquitectura basada en el modelo **Cliente – Servidor**, donde cada capa tendrá responsabilidades claramente definidas.

```text
                Usuario
                   │
                   ▼
             Navegador Web
                   │
                   ▼
      Frontend (HTML • CSS • JS)
                   │
                   ▼
           Backend (PHP)
                   │
                   ▼
        Base de Datos (MySQL)
```

Esta estructura permite separar la presentación, la lógica de negocio y la gestión de datos, facilitando el mantenimiento y la evolución del sistema.

La implementación actual se encuentra principalmente en la **capa de presentación**, mientras que la integración completa con backend y base de datos se desarrollará progresivamente.

---

# Arquitectura por Capas

## Capa de Presentación

Es responsable de la interacción entre el usuario y la plataforma.

Tecnologías utilizadas:

* HTML5
* CSS3
* JavaScript
* PHP para la generación y organización de las vistas

La interfaz se implementa tomando como referencia el **Design System desarrollado en Figma**, con el objetivo de mantener una experiencia visual consistente, reutilizable y responsive.

El sistema de diseño contempla:

* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Componentes reutilizables.
* Estados de los componentes.
* Diseño responsive.
* Estructura y navegación de las interfaces.

Las interfaces consideran principalmente dos adaptaciones:

* Desktop: `1440 px`
* Mobile: `390 px`

La implementación debe conservar la jerarquía visual y los criterios definidos durante la etapa de diseño.

### Implementación frontend actual

Durante la implementación de la pantalla **Inicio** se estableció una estructura modular para evitar la concentración de todos los estilos y componentes en un único archivo.

La capa CSS se organiza actualmente de la siguiente manera:

```text
assets/
└── css/
    ├── main.css
    ├── fonts.css
    ├── header.css
    └── footer.css
```

### `main.css`

Contiene los estilos generales del proyecto y los tokens derivados del Design System, incluyendo:

* Colores institucionales.
* Escala de espaciado.
* Estilos base.
* Reset y normalización de elementos.
* Reglas generales de layout.
* Estilos compartidos por diferentes componentes.

También contiene el restablecimiento global de los estilos de enlaces para evitar subrayados y bordes inferiores no deseados.

### `fonts.css`

Centraliza la configuración de la tipografía **Inter** utilizada por la interfaz y su relación con los recursos tipográficos almacenados localmente en:

```text
assets/fonts/
```

Se contemplan los pesos utilizados actualmente:

* 400
* 500
* 600
* 700

### `header.css`

Contiene exclusivamente los estilos correspondientes a la cabecera del sistema.

La cabecera utiliza una altura de:

```text
72 px
```

En Desktop organiza:

* Identidad visual a la izquierda.
* Navegación en la zona central.
* Acciones del usuario a la derecha.

En Mobile incorpora:

* Botón de menú hamburguesa.
* Enlace **Iniciar sesión** visible en la barra superior.
* Menú lateral tipo drawer para las opciones adicionales.
* Adaptación de la navegación al ancho reducido de la pantalla.

El drawer se encuentra oculto fuera del área visible y se muestra mediante el estado correspondiente del componente.

### `footer.css`

Contiene los estilos específicos del pie de página.

El footer utiliza:

* Fondo `#212121`.
* Distribución en tres columnas en Desktop.
* Espaciado vertical basado en el sistema definido en Figma.
* Lista de enlaces sin viñetas.
* Separador visual superior del bloque de copyright.
* Adaptación responsive para Mobile.

La estructura visual contempla:

1. Identidad de Raíces Alzate.
2. Navegación.
3. Información institucional.

---

## Componentes PHP

La estructura de presentación utiliza componentes reutilizables para evitar duplicación de código.

Actualmente se utilizan componentes para:

```text
components/
├── header.php
└── footer.php
```

Estos componentes son incluidos desde:

```text
includes/
├── header.php
└── footer.php
```

`includes/header.php` se encarga además de construir la estructura inicial del documento HTML e incorporar los recursos necesarios, incluyendo la hoja de estilos y el favicon.

`includes/footer.php` incorpora el componente de pie de página y los recursos JavaScript antes del cierre del documento.

La separación permite mantener diferenciadas las responsabilidades de estructura general e implementación visual de los componentes.

---

## Página de Inicio

La primera interfaz implementada corresponde a:

```text
01 - Inicio - Desktop
01 - Inicio - Mobile
```

La referencia Desktop utilizada en Figma tiene una dimensión de:

```text
1440 × 1472 px
```

La adaptación Mobile utiliza como referencia:

```text
390 px
```

La implementación de Inicio contempla:

* Header.
* Hero.
* Sección informativa sobre Raíces Alzate.
* Sección de beneficios.
* Footer.

La implementación se realizó respetando la estructura definida durante la etapa de diseño UI/UX y evitando agregar secciones que no formen parte del diseño aprobado.

---

## Recursos institucionales

La interfaz utiliza recursos gráficos institucionales almacenados dentro del proyecto.

El isotipo de Raíces Alzate se integra actualmente en:

* Header.
* Footer.
* Favicon del sitio.

El favicon se declara desde el documento HTML mediante:

```html
<link rel="icon" type="image/x-icon" href="assets/icons/logo sin fondo raices alzate.ico">
```

Los recursos se mantienen dentro de la estructura de `assets/` para facilitar su reutilización.

---

## Capa de Lógica

Es responsable del procesamiento de la información y las reglas de negocio.

Tecnología utilizada:

* PHP

Entre sus funciones principales se encuentran:

* Autenticación.
* Registro de usuarios.
* Gestión de perfiles.
* Gestión de egresados.
* Gestión de proyectos.
* Gestión de noticias.
* Administración del sistema.
* Validación de información.
* Gestión de roles y permisos.
* Procesamiento de solicitudes.
* Comunicación con la base de datos.

La implementación completa de estas funcionalidades se realizará progresivamente durante las siguientes etapas de desarrollo.

---

## Capa de Datos

Es responsable del almacenamiento, consulta y gestión de la información.

Tecnología prevista:

* MySQL

La base de datos almacenará información relacionada con:

* Usuarios.
* Egresados.
* Perfiles.
* Proyectos.
* Noticias.
* Roles.
* Configuración del sistema.

La conexión entre PHP y MySQL ya fue verificada satisfactoriamente durante la preparación de la implementación.

La estructura definitiva de tablas, relaciones y consultas será desarrollada posteriormente a partir de las necesidades reales de las interfaces y funcionalidades implementadas.

---

# Arquitectura del Diseño

Antes de iniciar la implementación se construyó un **Design System en Figma** que establece las bases visuales para la construcción de la interfaz gráfica.

La etapa de diseño UI/UX se encuentra finalizada y sirve como referencia para la implementación frontend.

## Design System

Esta sección establece las bases visuales del proyecto:

* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Estilos base.
* Botones y elementos de interacción.

La tipografía principal utilizada en las interfaces es **Inter**.

### Paleta principal

| Elemento         | Valor     |
| ---------------- | --------- |
| Primary          | `#D32F2F` |
| Secondary        | `#1565C0` |
| Texto principal  | `#212121` |
| Texto secundario | `#757575` |
| Fondo claro      | `#F5F5F5` |
| Blanco           | `#FFFFFF` |
| Borde            | `#E0E0E0` |

### Sistema de espaciado

Se utiliza una escala basada principalmente en:

`4 / 8 / 12 / 16 / 24 / 32 / 48 / 64 px`

---

# Components & Responsive

Esta sección contiene los componentes reutilizables de la interfaz, sus diferentes estados y los criterios utilizados para adaptar las interfaces a diferentes tamaños de pantalla.

Los componentes definidos incluyen:

* Botones.
* Inputs.
* Checkbox.
* Radio Button.
* Switch.
* Tablas.

Los componentes contemplan los estados correspondientes según su función, entre ellos:

* Normal.
* Focus.
* Selected.
* Disabled.
* Error.
* On / Off en componentes que lo requieren.

El diseño responsive se desarrolló considerando principalmente:

* Desktop: `1440 px` de ancho.
* Mobile: `390 px` de ancho.

La adaptación responsive mantiene la jerarquía visual, los espacios, los tamaños de interacción y la legibilidad de los contenidos.

Esta sección se utiliza actualmente como referencia durante la implementación de los componentes frontend.

---

# Interfaces del Sistema

Como resultado de la Fase 3 — Diseño UI/UX, se definieron las principales interfaces necesarias para la plataforma y sus respectivas adaptaciones responsive.

## Interfaces Desktop

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

## Interfaces Mobile

Las interfaces principales también fueron consideradas dentro del diseño responsive, tomando como referencia un ancho de `390 px`.

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

Las interfaces fueron diseñadas manteniendo la identidad visual, los componentes y los criterios establecidos en el Design System.

Actualmente la implementación frontend se encuentra iniciada con la interfaz **Inicio**, mientras que las demás interfaces serán trasladadas progresivamente desde Figma al código.

---

# Navegación del Sistema

El flujo principal de navegación se estructura de la siguiente manera:

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

A partir de estos módulos se accede a las diferentes acciones y vistas del sistema.

## Flujo de proyectos

```text
Proyectos
  ├── Crear proyecto
  ├── Detalle de proyecto
  └── Editar proyecto
```

## Flujo de noticias

```text
Noticias
  ├── Detalle de noticia
  ├── Crear noticia
  └── Editar noticia
```

## Flujo de perfil

```text
Perfil
  ├── Editar perfil
  └── Egresados
```

## Flujo administrativo

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

---

# Estructura del Proyecto

La estructura base actual del proyecto se organiza de la siguiente manera:

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
│   │   └── footer.css
│   ├── js/
│   ├── img/
│   ├── icons/
│   ├── fonts/
│   └── videos/

├── components/
│   ├── header.php
│   └── footer.php

├── config/

├── database/

├── docs/

├── includes/
│   ├── header.php
│   └── footer.php

├── pages/
│   └── inicio.php

├── uploads/

├── .gitignore

├── README.md

└── index.php
```

Esta estructura permite separar los recursos visuales, componentes, configuración, documentación, páginas y archivos relacionados con la aplicación.

La estructura podrá continuar ajustándose durante la implementación a medida que se incorporen los diferentes módulos del sistema.

---

# Módulos del Sistema

La plataforma estará compuesta por los siguientes módulos principales:

## Inicio

Presenta la plataforma y comunica su propósito, beneficios y propuesta para la comunidad de egresados.

La implementación frontend de este módulo ya fue iniciada y cuenta con adaptación Desktop y Mobile.

## Autenticación

Gestiona el acceso de los usuarios mediante:

* Inicio de sesión.
* Registro.
* Recuperación de contraseña.

## Usuarios y Perfil

Permite gestionar la información personal y profesional de los usuarios.

## Egresados

Permite consultar y visualizar información de los egresados que forman parte de la comunidad.

## Proyectos

Permite consultar, crear, visualizar y editar proyectos publicados por la comunidad.

## Noticias

Permite consultar, crear, visualizar y editar noticias relacionadas con la comunidad.

## Administración

Permite gestionar información y contenido de la plataforma según los permisos correspondientes.

---

# Flujo General del Sistema

El flujo general previsto para el funcionamiento de la plataforma es:

1. El usuario accede a la plataforma.
2. Navega por la página principal.
3. Selecciona la opción correspondiente.
4. Inicia sesión o crea una cuenta.
5. El sistema valida las credenciales.
6. El backend procesa la solicitud.
7. Se consulta o modifica la información correspondiente en la base de datos.
8. El sistema determina el rol y los permisos del usuario.
9. Se concede acceso a los módulos correspondientes.
10. El usuario interactúa con las funcionalidades disponibles.
11. El sistema procesa y almacena los cambios realizados.
12. El sistema devuelve la respuesta correspondiente a la interfaz.

---

# Flujo de Desarrollo del Proyecto

El desarrollo de **Raíces Alzate** sigue una metodología incremental organizada en las siguientes etapas:

1. Planificación.
2. Documentación.
3. Diseño UI/UX.
4. Definición del flujo de información.
5. Diseño de pantallas.
6. Desarrollo Frontend.
7. Desarrollo Backend.
8. Integración con la base de datos.
9. Pruebas.
10. Control de versiones.
11. Actualización continua de la documentación.

Las etapas de planificación, documentación base y diseño UI/UX han sido desarrolladas.

Actualmente se encuentra en ejecución la etapa de **Desarrollo Frontend**, iniciando con la implementación de la pantalla Inicio y sus componentes compartidos.

La integración completa entre frontend, backend y base de datos se realizará progresivamente después de completar las interfaces correspondientes.

---

# Principios de Desarrollo

Durante el desarrollo del proyecto se aplicarán los siguientes principios:

* Código organizado y mantenible.
* Componentes reutilizables.
* Arquitectura modular.
* Documentación continua.
* Control de versiones mediante Git y GitHub.
* Diseño responsive.
* Seguridad desde el desarrollo.
* Escalabilidad.
* Consistencia visual mediante un Design System.
* Separación de responsabilidades entre las diferentes capas del sistema.
* Validación de datos.
* Reutilización de recursos y componentes.
* Separación de estilos por responsabilidades cuando la complejidad del frontend lo requiera.

---

# Estado Actual

Actualmente el proyecto se encuentra en la **Fase 4 — Implementación**.

La **Fase 3 — Diseño UI/UX** ha sido completada y dejó establecidos los fundamentos visuales y estructurales necesarios para el desarrollo del sistema.

Durante la fase de diseño se completaron:

* Design System en Figma.
* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Componentes reutilizables.
* Estados de los componentes.
* Página **Components & Responsive**.
* Criterios de diseño responsive.
* Flujo de navegación.
* Estructura de las interfaces.
* Interfaces principales para Desktop.
* Adaptaciones responsive para Mobile.

El diseño realizado en Figma constituye la referencia visual para la implementación frontend.

## Avance de implementación frontend

Durante la sesión del **8 de octubre de 2026** se avanzó en la implementación de la primera pantalla del sistema.

Se realizaron las siguientes actividades:

* Implementación y corrección de la pantalla **Inicio**.
* Adaptación de Inicio para Desktop y Mobile.
* Corrección de la estructura HTML de la navegación.
* Reestructuración de las acciones de usuario para responsive.
* Implementación del comportamiento del menú lateral Mobile.
* Integración del isotipo institucional en Header y Footer.
* Incorporación del favicon institucional.
* Implementación del Footer en tres columnas.
* Ajuste del bloque de copyright.
* Modularización de los estilos CSS.
* Creación de `fonts.css`.
* Creación de `header.css`.
* Creación de `footer.css`.
* Corrección del reset global de enlaces.
* Eliminación de reglas CSS obsoletas.
* Organización de los estilos según responsabilidades.

La primera pantalla implementada establece además la base para la reutilización de Header, Footer, tipografía y estilos generales en las siguientes interfaces.

## Siguiente etapa

La implementación continuará trasladando progresivamente las demás interfaces diseñadas en Figma al frontend.

El desarrollo deberá respetar:

* La arquitectura definida.
* El Design System.
* Los componentes diseñados.
* Los flujos de navegación.
* Las interfaces aprobadas.
* La estructura de módulos.
* Los criterios responsive.
* La separación de responsabilidades del código.

Posteriormente se continuará con la implementación de la lógica backend y la integración definitiva con la base de datos.

La documentación será actualizada continuamente conforme avance el desarrollo.
