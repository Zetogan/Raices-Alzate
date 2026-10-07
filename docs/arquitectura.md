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
| Última actualización | 7 de octubre de 2026    |

---

# Introducción

Este documento describe la arquitectura general de **Raíces Alzate**, incluyendo la organización del proyecto, las tecnologías previstas, la estructura por capas, los módulos principales, el flujo general del sistema y la relación entre las diferentes interfaces que conformarán la plataforma.

La arquitectura ha sido planteada para facilitar el mantenimiento, la escalabilidad, la reutilización de componentes y el desarrollo incremental del sistema.

El proyecto se encuentra en desarrollo desde **2025**. La **Fase 3 — Diseño UI/UX** ha sido completada, dejando definidos el sistema visual, los componentes reutilizables, el flujo de navegación, la estructura de las interfaces y los criterios responsive.

A partir de esta etapa, el proyecto entra en la fase de **implementación**, tomando como referencia la arquitectura definida y los diseños realizados en Figma.

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

Esta estructura permitirá separar la presentación, la lógica de negocio y la gestión de datos, facilitando el mantenimiento y la evolución del sistema.

---

# Arquitectura por Capas

## Capa de Presentación

Responsable de la interacción entre el usuario y la plataforma.

Tecnologías previstas:

* HTML5
* CSS3
* JavaScript

La interfaz se implementará tomando como referencia el **Design System desarrollado en Figma**, con el objetivo de mantener una experiencia visual consistente, reutilizable y responsive.

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

La implementación deberá conservar la jerarquía visual y los criterios definidos durante la etapa de diseño.

---

## Capa de Lógica

Responsable del procesamiento de la información y las reglas de negocio.

Tecnología prevista:

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

---

## Capa de Datos

Responsable del almacenamiento, consulta y gestión de la información.

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

La estructura definitiva de la base de datos será desarrollada y documentada durante la etapa de implementación.

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

## Components & Responsive

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

Esta sección se utilizará como referencia durante la implementación de los componentes frontend.

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
  │    └── Egresados
  │
  ├── Proyectos
  │    └── Noticias
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

La organización base del proyecto se plantea de la siguiente manera:

```text
raices-alzate/

│
├── .github/
│
├── assets/
│   ├── css/
│   ├── js/
│   ├── img/
│   ├── icons/
│   ├── fonts/
│   └── videos/
│
├── components/
├── config/
├── database/
├── docs/
├── includes/
├── pages/
├── uploads/
│
├── .gitignore
├── README.md
└── index.php
```

Esta estructura permite separar los recursos visuales, componentes, configuración, documentación, páginas y archivos relacionados con la aplicación.

La estructura podrá ajustarse durante la implementación cuando se definan de manera concreta los archivos y responsabilidades de cada módulo.

---

# Módulos del Sistema

La plataforma estará compuesta por los siguientes módulos principales:

## Inicio

Presenta la plataforma y comunica su propósito, beneficios y propuesta para la comunidad de egresados.

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

La siguiente etapa corresponde a la **implementación del sistema**, comenzando por la construcción frontend y posteriormente integrando la lógica backend y la base de datos de acuerdo con la arquitectura definida.

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

---

# Estado Actual

Actualmente el proyecto se encuentra en la **Fase 4 — Implementación**.

La **Fase 3 — Diseño UI/UX** ha sido completada y dejó establecidos los fundamentos visuales y estructurales necesarios para comenzar el desarrollo del sistema.

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

## Siguiente etapa

La siguiente etapa corresponde a la **implementación del sistema**, tomando como base:

* La arquitectura definida.
* El Design System.
* Los componentes diseñados.
* Los flujos de navegación.
* Las interfaces desarrolladas.
* La estructura de módulos.
* Los criterios responsive.

La implementación deberá respetar las decisiones establecidas durante las etapas anteriores y permitir una integración progresiva entre frontend, backend y base de datos.

La documentación será actualizada continuamente conforme avance el desarrollo.
