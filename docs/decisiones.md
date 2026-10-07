# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 📘 Registro de Decisiones

## Información General

| Campo           | Información                                    |
| --------------- | ---------------------------------------------- |
| Proyecto        | Raíces Alzate                                  |
| Institución     | Institución Educativa Gilberto Alzate Avendaño |
| Fecha de inicio | 2025                                           |
| Versión         | v0.1.1                                         |
| Estado          | 🟡 En desarrollo                               |
| Fase actual     | Fase 4 — Implementación                        |

---

# Introducción

Este documento registra las decisiones técnicas, funcionales, metodológicas y de diseño tomadas durante el desarrollo de Raíces Alzate.

Su propósito es dejar evidencia de por qué se eligieron determinadas tecnologías, metodologías, estructuras o soluciones, facilitando el mantenimiento, la evolución y la implementación del proyecto.

Las decisiones se conservan como parte del historial del proyecto y pueden ser revisadas o complementadas cuando las necesidades del desarrollo lo requieran.

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
* Aplicar buenas prácticas de control de versiones.

---

## DEC-003 | Documentación en Markdown

**Fecha:** 09 de julio de 2026

**Decisión**

La documentación técnica y de seguimiento del proyecto será escrita principalmente en archivos Markdown (`.md`).

**Justificación**

* Es compatible con GitHub.
* Es fácil de leer y mantener.
* Permite documentar el proyecto junto con el código.
* Facilita el control de cambios mediante Git.

---

## DEC-004 | Diseño previo al desarrollo

**Fecha:** 09 de julio de 2026

**Decisión**

Antes de implementar los módulos principales se realizará su planificación, definición y diseño previo.

**Justificación**

* Reduce retrabajos.
* Permite detectar problemas antes de programar.
* Mantiene una interfaz consistente.
* Facilita la organización del proyecto.
* Permite utilizar el diseño como referencia durante la implementación.

---

## DEC-005 | Metodología de trabajo

**Fecha:** 09 de julio de 2026

**Decisión**

El desarrollo del proyecto seguirá un flujo organizado por etapas:

1. Planificación.
2. Documentación.
3. Diseño.
4. Desarrollo.
5. Pruebas.
6. Documentación de cambios.
7. Commit y Push.

**Justificación**

Esta metodología permite mantener un desarrollo ordenado, documentado y fácil de mantener, además de conservar un historial claro de los cambios realizados.

---

## DEC-006 | Uso de Figma para el diseño UI/UX

**Fecha:** 10 de julio de 2026

**Decisión**

El diseño de la interfaz de usuario y la experiencia de usuario del proyecto se realizará utilizando Figma.

**Justificación**

* Permite organizar y documentar el diseño antes del desarrollo.
* Facilita la creación y definición de componentes reutilizables.
* Permite establecer una guía visual para la implementación frontend.
* Facilita la planificación de las interfaces y sus diferentes estados.
* Permite definir previamente las dimensiones y distribución de las pantallas.

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
* Estados de componentes.
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
* Mantener una estructura compatible con las limitaciones del plan utilizado.

---

## DEC-009 | Diseño responsive

**Fecha:** 05 de septiembre de 2026

**Decisión**

Los componentes y las interfaces de Raíces Alzate se diseñarán teniendo en cuenta diferentes tamaños de pantalla.

**Justificación**

* Garantizar una experiencia adecuada en diferentes dispositivos.
* Facilitar la adaptación de las interfaces durante el desarrollo frontend.
* Mantener la consistencia visual independientemente del tamaño de pantalla.
* Preparar el sistema de diseño para la construcción de interfaces adaptables.
* Permitir definir versiones Desktop y Mobile de las interfaces principales.

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

Esta decisión corresponde a la etapa de diseño realizada y podrá revisarse posteriormente si las necesidades de implementación lo requieren.

---

## DEC-012 | Definición del flujo de información antes del diseño de pantallas

**Fecha:** Septiembre de 2026

**Decisión**

Antes de consolidar el diseño de las pantallas finales de la plataforma se definió el **flujo de información** y los recorridos principales del sistema.

**Justificación**

* Permitir comprender cómo se relacionan los módulos.
* Definir los recorridos principales de los usuarios.
* Identificar las pantallas necesarias.
* Evitar diseñar interfaces sin conocer previamente su relación dentro del sistema.
* Utilizar el flujo como base para el diseño de las pantallas.

El flujo establecido contempla relaciones principales como:

* Inicio → Autenticación → Dashboard.
* Dashboard → Perfil.
* Dashboard → Proyectos.
* Dashboard → Administración.
* Perfil → Egresados.
* Proyectos → Noticias.

---

## DEC-013 | Definición de interfaces Desktop y Mobile

**Fecha:** Septiembre de 2026

**Decisión**

Las interfaces principales de Raíces Alzate se diseñarán considerando versiones para **Desktop y Mobile**, utilizando como referencia las dimensiones y criterios definidos durante el proceso de UI/UX.

**Justificación**

* Garantizar una experiencia coherente en diferentes dispositivos.
* Facilitar la posterior implementación responsive.
* Identificar desde la etapa de diseño los cambios necesarios entre resoluciones.
* Mantener una estructura visual consistente entre las diferentes versiones.

Las interfaces Desktop desarrolladas abarcan los principales módulos y flujos de la plataforma, mientras que las adaptaciones Mobile se realizaron sobre las interfaces priorizadas durante la etapa de diseño.

---

## DEC-014 | Uso de Inter como tipografía de las interfaces

**Fecha:** Septiembre de 2026

**Decisión**

La tipografía utilizada en las interfaces de la plataforma será **Inter**, manteniendo una jerarquía visual clara para títulos, textos y elementos de interacción.

**Justificación**

* Favorecer la legibilidad en interfaces digitales.
* Mantener una apariencia limpia y moderna.
* Facilitar la lectura en diferentes tamaños de pantalla.
* Mantener consistencia tipográfica entre las diferentes interfaces.

La tipografía inicialmente definida dentro del Design System fue Poppins. Posteriormente, durante el desarrollo de las interfaces, se adoptó Inter como tipografía principal de la interfaz.

---

## DEC-015 | Uso de una estructura visual simple y consistente

**Fecha:** Septiembre de 2026

**Decisión**

Las interfaces de Raíces Alzate seguirán una estructura visual limpia, organizada y con suficiente espacio entre los elementos, evitando la sobrecarga de información.

**Justificación**

* Facilitar la comprensión de las interfaces.
* Mejorar la lectura y navegación.
* Evitar interfaces visualmente saturadas.
* Mantener una jerarquía clara de la información.
* Facilitar la implementación posterior del diseño.

---

## DEC-016 | Diseño de las interfaces como referencia para la implementación

**Fecha:** Septiembre de 2026

**Decisión**

Las interfaces consolidadas en Figma serán utilizadas como referencia visual para la etapa de implementación frontend.

**Justificación**

* Mantener correspondencia entre diseño y desarrollo.
* Reducir diferencias visuales durante la programación.
* Facilitar la construcción de componentes HTML, CSS y JavaScript.
* Utilizar las medidas, estilos, estados y estructuras definidas durante la etapa UI/UX.
* Servir como guía para la implementación responsive.

---

# Estado de las decisiones

Las decisiones registradas establecen las principales bases técnicas, metodológicas y de diseño de Raíces Alzate.

Durante la etapa de diseño UI/UX se consolidaron el **Design System**, la página **Components & Responsive**, el flujo de información y las principales interfaces de la plataforma para Desktop y las adaptaciones Mobile trabajadas.

La **Fase 3 — Diseño UI/UX** se encuentra finalizada y sus resultados serán utilizados como referencia para la siguiente etapa.

Actualmente, el proyecto se encuentra en la **Fase 4 — Implementación**, en la cual se continuará con:

* Construcción del frontend.
* Implementación de componentes.
* Desarrollo de la estructura backend.
* Configuración e integración de la base de datos.
* Implementación de autenticación y roles.
* Integración entre frontend, backend y MySQL.
* Pruebas funcionales y de interfaz.
* Documentación de los avances y cambios.

Las decisiones podrán actualizarse o complementarse conforme avance el proyecto, manteniendo el registro histórico de las decisiones tomadas durante cada etapa.
