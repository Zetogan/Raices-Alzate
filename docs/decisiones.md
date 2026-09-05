# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 📘 Registro de Decisiones

## Información General

| Campo           | Información                                    |
| --------------- | ---------------------------------------------- |
| Proyecto        | Raíces Alzate                                  |
| Institución     | Institución Educativa Gilberto Alzate Avendaño |
| Fecha de inicio | 09 de julio de 2026                            |
| Versión         | v0.1.0                                         |

---

# Introducción

Este documento registra las decisiones técnicas, funcionales y de diseño tomadas durante el desarrollo de Raíces Alzate.

Su propósito es dejar evidencia de por qué se eligieron determinadas tecnologías, metodologías o soluciones, facilitando el mantenimiento y la evolución del proyecto.

---

# Decisiones

## DEC-001 | Uso de PHP como lenguaje backend

**Fecha:** 09 de julio de 2026

**Decisión**

El backend del proyecto será desarrollado utilizando PHP.

**Justificación**

* Hace parte de las tecnologías aprendidas durante la formación.
* Tiene una integración sencilla con MySQL.
* Es ampliamente utilizado en aplicaciones web.
* Facilita el despliegue en servidores compartidos.

---

## DEC-002 | Control de versiones con Git y GitHub

**Fecha:** 09 de julio de 2026

**Decisión**

Todo el desarrollo del proyecto será gestionado mediante Git y alojado en GitHub.

**Justificación**

* Mantener un historial de cambios.
* Facilitar la recuperación de versiones.
* Llevar un desarrollo organizado.
* Aplicar buenas prácticas profesionales.

---

## DEC-003 | Documentación en Markdown

**Fecha:** 09 de julio de 2026

**Decisión**

Toda la documentación técnica del proyecto será escrita en archivos Markdown (`.md`).

**Justificación**

* Compatible con GitHub.
* Fácil de leer y mantener.
* Permite documentar el proyecto junto con el código.

---

## DEC-004 | Diseño previo al desarrollo

**Fecha:** 09 de julio de 2026

**Decisión**

Antes de programar cualquier módulo se realizará su diseño y planificación.

**Justificación**

* Reduce retrabajos.
* Permite detectar problemas antes de programar.
* Mantiene una interfaz consistente.
* Facilita la organización del proyecto.

---

## DEC-005 | Metodología de trabajo

**Fecha:** 09 de julio de 2026

**Decisión**

Cada módulo seguirá el siguiente flujo de trabajo:

1. Planificación.
2. Documentación.
3. Diseño.
4. Desarrollo.
5. Pruebas.
6. Documentación de cambios.
7. Commit y Push.

**Justificación**

Esta metodología permitirá mantener un desarrollo ordenado, documentado y fácil de mantener.

---

## DEC-006 | Uso de Figma para el diseño UI/UX

**Fecha:** 10 de julio de 2026

**Decisión**

El diseño de la interfaz de usuario y la experiencia de usuario del proyecto se realizará utilizando Figma.

**Justificación**

* Permite organizar y documentar el diseño antes del desarrollo.
* Facilita la creación de componentes reutilizables.
* Permite establecer una guía visual para la implementación frontend.
* Facilita la planificación de las interfaces y sus diferentes estados.

---

## DEC-007 | Implementación de un Design System

**Fecha:** 10 de julio de 2026

**Decisión**

El proyecto utilizará un **Design System** como base para definir y mantener la identidad visual de la plataforma.

**Justificación**

* Mantener consistencia visual.
* Centralizar los criterios de diseño.
* Facilitar la reutilización de elementos.
* Establecer una referencia común para el desarrollo frontend.
* Reducir inconsistencias entre las diferentes pantallas.

El Design System contempla elementos como:

* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Botones.
* Componentes reutilizables.

---

## DEC-008 | Separación de fundamentos y componentes en Figma

**Fecha:** 20 de julio de 2026

**Decisión**

El archivo de Figma se organizará separando los fundamentos visuales del sistema de diseño y los componentes reutilizables.

La estructura contempla:

1. **Design System**
2. **Components & Responsive**

**Justificación**

* Evitar sobrecargar una sola página del archivo.
* Mantener una organización clara.
* Facilitar la consulta de los componentes.
* Separar los estilos base de los componentes de interfaz.

---

## DEC-009 | Diseño responsive

**Fecha:** 05 de septiembre de 2026

**Decisión**

Los componentes y posteriormente las interfaces de Raíces Alzate se diseñarán teniendo en cuenta diferentes tamaños de pantalla.

**Justificación**

* Garantizar una experiencia adecuada en diferentes dispositivos.
* Facilitar la adaptación de las interfaces durante el desarrollo frontend.
* Mantener la consistencia visual independientemente del tamaño de pantalla.
* Preparar el sistema de diseño para la construcción de interfaces adaptables.

---

## DEC-010 | Uso de componentes reutilizables y estados

**Fecha:** 05 de septiembre de 2026

**Decisión**

Los componentes de interfaz serán diseñados de forma reutilizable y contemplarán los estados necesarios para representar diferentes situaciones de interacción.

Entre los estados trabajados se encuentran:

* Normal.
* Selected.
* Hover.
* Focus.
* Disabled.
* Error.

**Justificación**

* Mantener consistencia entre las interfaces.
* Evitar diseñar repetidamente los mismos elementos.
* Representar correctamente los diferentes estados de interacción.
* Facilitar la posterior implementación de los componentes en frontend.

---

## DEC-011 | No utilizar Auto Layout durante esta etapa de Figma

**Fecha:** 20 de julio de 2026

**Decisión**

Durante esta etapa del diseño en Figma no se utilizará **Auto Layout** para la construcción de los componentes y las páginas del Design System.

**Justificación**

* Mantener un proceso de construcción manual y controlado.
* Comprender la estructura y proporciones de los componentes antes de incorporar herramientas avanzadas.
* Adaptar el proceso a las condiciones y limitaciones actuales del archivo de diseño.
* Mantener el control directo sobre la distribución visual de los elementos.

Esta decisión corresponde a la etapa actual del proyecto y podrá revisarse posteriormente si las necesidades del desarrollo lo requieren.

---

## DEC-012 | Definición del flujo de información antes del diseño de pantallas

**Fecha:** 05 de septiembre de 2026

**Decisión**

Antes de comenzar el diseño de las pantallas finales de la plataforma se definirá el **flujo de información** y los recorridos principales del sistema.

**Justificación**

* Permitir comprender cómo se relacionan los módulos.
* Definir los recorridos principales de los usuarios.
* Identificar las pantallas necesarias.
* Evitar diseñar interfaces sin conocer previamente su relación dentro del sistema.
* Utilizar el flujo como base para el posterior diseño de las pantallas.

---

# Estado de las decisiones

Las decisiones registradas hasta el momento establecen las principales bases técnicas, metodológicas y de diseño del proyecto.

Actualmente, el proyecto ha completado la definición del **Design System** y la página **Components & Responsive** en Figma.

El siguiente paso corresponde a la elaboración del **flujo de información**, antes de comenzar el diseño de las pantallas de la plataforma.

Las decisiones podrán actualizarse o complementarse conforme avance el proyecto.
