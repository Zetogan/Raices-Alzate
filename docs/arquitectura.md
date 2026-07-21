# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 🏗️ Arquitectura del Proyecto

## Información General

| Campo                | Información         |
| -------------------- | ------------------- |
| Proyecto             | Raíces Alzate       |
| Versión              | v0.1.0              |
| Estado               | 🟡 En desarrollo    |
| Última actualización | 20 de julio de 2026 |

---

# Introducción

Este documento describe la arquitectura general de **Raíces Alzate**, incluyendo la organización del proyecto, las tecnologías utilizadas, la estructura por capas y la relación entre los diferentes módulos que conformarán la plataforma.

La arquitectura ha sido diseñada para facilitar el mantenimiento, la escalabilidad, la reutilización de componentes y el desarrollo incremental del sistema.

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

---

# Arquitectura por Capas

## Capa de Presentación

Responsable de la interacción entre el usuario y la plataforma.

Tecnologías:

* HTML5
* CSS3
* JavaScript

Durante esta capa se utilizará un **Design System** desarrollado en Figma para mantener una interfaz consistente, reutilizable y responsive.

---

## Capa de Lógica

Responsable del procesamiento de la información y las reglas de negocio.

Tecnología:

* PHP

Funciones principales:

* Autenticación.
* Gestión de usuarios.
* Gestión de egresados.
* Gestión de proyectos.
* Administración del sistema.

---

## Capa de Datos

Responsable del almacenamiento y consulta de la información.

Tecnología:

* MySQL

Almacenará la información relacionada con:

* Usuarios.
* Egresados.
* Proyectos.
* Roles.
* Configuración del sistema.

---

# Arquitectura del Diseño

Antes del desarrollo del sistema se construye un **Design System** en Figma que servirá como guía para toda la interfaz gráfica.

Actualmente contempla:

* Identidad visual.
* Paleta de colores institucional.
* Tipografía.
* Sistema de espaciado.
* Botones reutilizables.
* Componentes reutilizables (en desarrollo).

Esta arquitectura permitirá mantener uniformidad visual durante todo el desarrollo del proyecto.

---

# Estructura del Proyecto

```text
raices-alzate/
│
├── .github/
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

---

# Módulos del Sistema

La plataforma estará compuesta por los siguientes módulos principales:

* Inicio.
* Autenticación.
* Usuarios.
* Egresados.
* Proyectos.
* Panel administrativo.

---

# Flujo General del Sistema

1. El usuario accede a la plataforma.
2. Navega por la página principal.
3. Inicia sesión.
4. El sistema valida las credenciales.
5. El backend procesa la solicitud.
6. Se consulta la base de datos.
7. Se determina el rol del usuario.
8. Se concede acceso a los módulos correspondientes.
9. El usuario interactúa con las funcionalidades del sistema.

---

# Flujo de Desarrollo del Proyecto

El desarrollo de **Raíces Alzate** sigue una metodología incremental organizada en las siguientes etapas:

1. Planificación.
2. Documentación.
3. Diseño UI/UX.
4. Desarrollo Frontend.
5. Desarrollo Backend.
6. Pruebas.
7. Control de versiones.
8. Actualización continua de la documentación.

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

---

# Estado Actual

Actualmente el proyecto se encuentra en la **Fase 3 — Diseño UI/UX**, donde se desarrolla el Design System que servirá como base para el desarrollo frontend de la plataforma.
