# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 📑 Requisitos del Proyecto

## Información General

| Campo                | Información                                    |
| -------------------- | ---------------------------------------------- |
| Proyecto             | Raíces Alzate                                  |
| Institución          | Institución Educativa Gilberto Alzate Avendaño |
| Versión              | v0.1.0                                         |
| Estado               | 🟡 En desarrollo                               |
| Última actualización | 05 de septiembre de 2026                       |

---

# Introducción

Este documento reúne los requisitos funcionales y no funcionales que definirán el comportamiento, las características y las condiciones de funcionamiento de la plataforma **Raíces Alzate**.

Los requisitos establecidos servirán como referencia durante las etapas de diseño, desarrollo y pruebas del sistema, permitiendo mantener una relación clara entre las necesidades del proyecto y las funcionalidades que serán implementadas.

Actualmente, el proyecto se encuentra en la **Fase 3 — Diseño UI/UX**, donde se están definiendo la identidad visual, los componentes reutilizables, los estados de interacción, la estructura responsive y posteriormente el flujo de información del sistema.

---

# Requisitos Funcionales

## Módulo de Inicio

* Mostrar la página principal del proyecto.
* Presentar información institucional.
* Mostrar acceso al inicio de sesión.
* Mostrar acceso al registro de usuarios.
* Presentar de forma clara los principales contenidos y accesos de la plataforma.

---

## Módulo de Autenticación

* Iniciar sesión.
* Cerrar sesión.
* Recuperar contraseña.
* Cambiar contraseña.
* Mantener la sesión iniciada.
* Validar las credenciales proporcionadas por el usuario.
* Restringir el acceso a funcionalidades según el tipo de usuario.

---

## Módulo de Usuarios

* Registrar usuarios.
* Editar perfil.
* Cambiar fotografía.
* Actualizar información personal.
* Consultar la información asociada al perfil.
* Gestionar la información de la cuenta.

---

## Módulo de Egresados

* Registrar egresados.
* Consultar información de los egresados.
* Buscar egresados.
* Filtrar resultados.
* Visualizar perfiles.
* Mantener actualizada la información de los egresados.

---

## Módulo de Proyectos

* Publicar proyectos.
* Editar proyectos.
* Eliminar proyectos.
* Consultar proyectos publicados.
* Visualizar la información de los proyectos.
* Asociar proyectos con sus respectivos egresados.

---

## Módulo Administrativo

* Gestionar usuarios.
* Gestionar egresados.
* Gestionar proyectos.
* Administrar permisos.
* Visualizar estadísticas.
* Controlar el acceso a las funciones administrativas.

---

# Requisitos No Funcionales

## Rendimiento

* La plataforma debe cargar de manera rápida y eficiente.
* Optimizar imágenes y recursos utilizados por la plataforma.
* Evitar recursos innecesarios que afecten el rendimiento.
* Mantener tiempos de respuesta adecuados durante la navegación.

---

## Seguridad

* Las contraseñas deben almacenarse de forma segura mediante mecanismos de cifrado o hash adecuados.
* Validar los datos ingresados en los formularios.
* Proteger las sesiones de los usuarios.
* Implementar control de permisos y acceso.
* Evitar accesos no autorizados a información o funcionalidades.
* Validar la información recibida antes de procesarla o almacenarla.

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
* La interfaz debe mantener una identidad visual institucional.
* Los componentes deben mantener consistencia visual.
* Los componentes interactivos deben contemplar sus estados correspondientes.
* La interfaz debe considerar criterios básicos de accesibilidad.
* El diseño debe ser moderno, claro y organizado.
* La plataforma debe mantener una jerarquía visual coherente.
* Los elementos de navegación deben ser claros y fáciles de identificar.

---

## Sistema de Diseño

La interfaz deberá basarse en un sistema de diseño previamente establecido para garantizar consistencia durante el desarrollo.

El sistema contempla:

* Paleta de colores institucional.
* Tipografía definida.
* Sistema de espaciado.
* Componentes reutilizables.
* Estados de interacción.
* Criterios responsive.

Entre los componentes trabajados se encuentran:

* Botones.
* Inputs.
* Checkbox.
* Radio Button.
* Switch.
* Tablas.
* Otros componentes reutilizables definidos en el Design System.

Los componentes deberán contemplar los estados necesarios según su función, incluyendo:

* Normal.
* Hover.
* Focus.
* Selected.
* Disabled.
* Error.
* Off.
* On.

---

## Responsive

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

---

## Mantenibilidad

* El código debe estar documentado.
* La estructura del proyecto debe mantenerse organizada.
* Se debe utilizar una estructura modular para facilitar futuras modificaciones.
* Se deben utilizar componentes reutilizables cuando sea necesario.
* Se utilizará Git para el control de versiones.
* Se utilizará GitHub para almacenar y gestionar el repositorio.
* La documentación deberá mantenerse actualizada conforme avance el proyecto.

---

# Requisitos de Diseño UI/UX

Antes de iniciar la implementación de las interfaces, se deberá establecer la estructura visual y funcional de la plataforma mediante Figma.

El proceso de diseño contempla:

1. Definición de identidad visual.
2. Creación del Design System.
3. Definición de componentes reutilizables.
4. Definición de estados de interacción.
5. Definición de criterios responsive.
6. Diseño del flujo de información.
7. Definición de recorridos principales de los usuarios.
8. Elaboración de wireframes.
9. Diseño de mockups de alta fidelidad.
10. Construcción de un prototipo navegable.

---

# Estado de los Requisitos

## ✅ Requisitos definidos

* Requisitos funcionales principales.
* Requisitos no funcionales.
* Requisitos de seguridad.
* Requisitos de compatibilidad.
* Requisitos de rendimiento.
* Requisitos de diseño.
* Criterios responsive.
* Componentes reutilizables.
* Estados de interacción.

## 🟡 En desarrollo

* Flujo de información del sistema.
* Recorridos principales de los usuarios.
* Definición detallada de las interfaces.

## ⏳ Pendiente

* Wireframes.
* Mockups de alta fidelidad.
* Prototipo navegable.
* Implementación frontend.
* Implementación backend.
* Pruebas del sistema.

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
* La documentación deberá actualizarse conforme avance el desarrollo.

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

---

# Estado actual del proyecto

El proyecto se encuentra actualmente en la **Fase 3 — Diseño UI/UX**.

Se encuentran completados:

* Design System.
* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Componentes reutilizables.
* Estados de componentes.
* Página Components & Responsive.
* Criterios de diseño responsive.

El siguiente paso corresponde a la definición del **flujo de información del sistema**, seguido de los recorridos principales de los usuarios, wireframes, mockups y prototipo navegable.

La implementación frontend y backend se realizará posteriormente, tomando como referencia las interfaces y estructuras definidas durante la fase de diseño.

---

# Historial de cambios

| Versión | Fecha      | Cambio                                                                                                                                                     |
| ------- | ---------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------- |
| v0.1.0  | 09/07/2026 | Creación inicial de los requisitos del proyecto.                                                                                                           |
| v0.1.0  | 05/09/2026 | Actualización de requisitos funcionales y no funcionales, incorporación de criterios UI/UX, componentes, estados, responsive y estado actual del proyecto. |
