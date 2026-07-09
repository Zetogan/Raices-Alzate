# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 🏗️ Arquitectura del Proyecto

## Información General

| Campo | Información |
|--------|-------------|
| Proyecto | Raíces Alzate |
| Versión | v0.1.0 |
| Estado | En desarrollo |
| Última actualización | 09 de julio de 2026 |

---

# Introducción

Este documento describe la arquitectura general de la plataforma Raíces Alzate, incluyendo la organización del proyecto, las tecnologías utilizadas y la relación entre sus diferentes módulos.

---

# Arquitectura General

La plataforma seguirá una arquitectura basada en cliente-servidor.

```text
Usuario
    │
    ▼
 Navegador Web
    │
    ▼
Frontend
(HTML, CSS, JavaScript)
    │
    ▼
Backend
(PHP)
    │
    ▼
Base de Datos
(MySQL)
```

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

# Arquitectura por Capas

## Capa de Presentación

Responsable de la interfaz que interactúa con el usuario.

Tecnologías:

- HTML5
- CSS3
- JavaScript

---

## Capa de Lógica

Gestiona toda la lógica del sistema.

Tecnología:

- PHP

---

## Capa de Datos

Almacena toda la información del sistema.

Tecnología:

- MySQL

---

# Módulos del Sistema

- Inicio
- Autenticación
- Usuarios
- Egresados
- Proyectos
- Administración

---

# Flujo General del Sistema

1. El usuario accede a la plataforma.
2. Navega por la página principal.
3. Inicia sesión.
4. El sistema valida las credenciales.
5. Se consulta la base de datos.
6. Se concede acceso según el rol del usuario.
7. El usuario interactúa con los diferentes módulos.

---

# Principios de Desarrollo

Durante el desarrollo de Raíces Alzate se aplicarán los siguientes principios:

- Código organizado.
- Componentes reutilizables.
- Documentación continua.
- Control de versiones con Git.
- Diseño responsive.
- Seguridad desde el desarrollo.
- Escalabilidad.