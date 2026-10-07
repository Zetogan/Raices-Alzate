# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 📖 Glosario

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

El presente documento reúne los términos técnicos, académicos y funcionales utilizados durante el desarrollo del proyecto **Raíces Alzate**.

Su propósito es establecer un lenguaje común entre los integrantes del proyecto, docentes y futuros colaboradores, facilitando la comprensión de la documentación, el código fuente, el diseño y el funcionamiento general de la plataforma.

Este glosario será actualizado de forma continua conforme evolucionen las funcionalidades, tecnologías, procesos y decisiones del proyecto.

---

# Índice

* [Conceptos del Proyecto](#conceptos-del-proyecto)
* [Desarrollo Web](#desarrollo-web)
* [Diseño UI/UX](#diseño-uiux)
* [Tecnologías](#tecnologías)
* [Control de Versiones](#control-de-versiones)
* [Usuarios y Roles](#usuarios-y-roles)
* [Base de Datos](#base-de-datos)
* [Implementación](#implementación)
* [Notas](#notas)
* [Control de Cambios](#control-de-cambios)

---

# Conceptos del Proyecto

| Término                  | Definición                                                                                            | Documento relacionado                        |
| ------------------------ | ----------------------------------------------------------------------------------------------------- | -------------------------------------------- |
| **Bitácora**             | Documento donde se registran cronológicamente las actividades, avances y novedades del proyecto.      | [`01_Bitacora.md`](./01_Bitacora.md)         |
| **Changelog**            | Documento que registra los cambios importantes realizados durante el desarrollo del proyecto.         | [`CHANGELOG.md`](./CHANGELOG.md)             |
| **Documentación**        | Conjunto de documentos técnicos, funcionales y organizativos del proyecto.                            | [`README.md`](../README.md)                  |
| **Flujo de Información** | Representación de cómo circula la información entre usuarios, módulos y componentes del sistema.      | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Módulo**               | Conjunto de funcionalidades relacionadas que cumplen un objetivo específico dentro de la plataforma.  | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Plataforma**           | Sistema web desarrollado para fortalecer el vínculo entre la institución y sus egresados.             | [`03_Requisitos.md`](./03_Requisitos.md)     |
| **Sistema**              | Conjunto de componentes que trabajan de manera integrada para ofrecer los servicios de la plataforma. | [`04_Arquitectura.md`](./04_Arquitectura.md) |

---

# Desarrollo Web

| Término        | Definición                                                                                                         | Documento relacionado                        |
| -------------- | ------------------------------------------------------------------------------------------------------------------ | -------------------------------------------- |
| **Backend**    | Parte del sistema encargada del procesamiento de datos, reglas de negocio y comunicación con la base de datos.     | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Cliente**    | Navegador desde el cual un usuario accede e interactúa con la plataforma.                                          | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **CRUD**       | Conjunto de operaciones básicas para Crear, Leer, Actualizar y Eliminar información.                               | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Dashboard**  | Panel principal desde el cual un usuario puede consultar y acceder a las funcionalidades disponibles según su rol. | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Frontend**   | Parte visual del sistema con la que interactúa directamente el usuario.                                            | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Interfaz**   | Medio visual mediante el cual el usuario interactúa con las funcionalidades del sistema.                           | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Responsive** | Capacidad de una interfaz para adaptarse a diferentes tamaños de pantalla y dispositivos.                          | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Servidor**   | Equipo o servicio que ejecuta la aplicación y responde a las solicitudes realizadas por los clientes.              | [`04_Arquitectura.md`](./04_Arquitectura.md) |

---

# Diseño UI/UX

| Término                                | Definición                                                                                                                                  | Documento relacionado                        |
| -------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------- |
| **Componente**                         | Elemento reutilizable de la interfaz que cumple una función específica dentro del sistema.                                                  | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Components & Responsive**            | Página del archivo de Figma destinada a organizar los componentes reutilizables, sus estados y criterios de adaptación responsive.          | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Design System**                      | Conjunto organizado de reglas, estilos, criterios y componentes que establecen la identidad visual y los principios de diseño del proyecto. | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Estado**                             | Representación visual de una condición o situación de interacción de un componente.                                                         | [`decisiones.md`](./decisiones.md)           |
| **Figma**                              | Herramienta utilizada para diseñar, organizar y documentar la interfaz de usuario y el Design System del proyecto.                          | [`01_Bitacora.md`](./01_Bitacora.md)         |
| **Focus**                              | Estado visual que indica que un elemento de la interfaz ha recibido el foco y está preparado para una interacción.                          | [`decisiones.md`](./decisiones.md)           |
| **Hover**                              | Estado visual que aparece cuando el cursor se encuentra sobre un elemento interactivo.                                                      | [`decisiones.md`](./decisiones.md)           |
| **Normal**                             | Estado visual predeterminado de un componente cuando no presenta una interacción o condición especial.                                      | [`decisiones.md`](./decisiones.md)           |
| **Radio Button**                       | Componente de selección que permite elegir una única opción dentro de un conjunto de alternativas.                                          | [`01_Bitacora.md`](./01_Bitacora.md)         |
| **Selected**                           | Estado visual que indica que una opción o componente ha sido seleccionado.                                                                  | [`decisiones.md`](./decisiones.md)           |
| **Switch**                             | Componente de interfaz utilizado para activar o desactivar una opción o funcionalidad.                                                      | [`01_Bitacora.md`](./01_Bitacora.md)         |
| **UI (Interfaz de Usuario)**           | Conjunto de elementos visuales mediante los cuales el usuario interactúa con la plataforma.                                                 | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **UX (Experiencia de Usuario)**        | Experiencia general que tiene una persona al utilizar la plataforma y sus funcionalidades.                                                  | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Wireframe / Estructura de Pantalla** | Representación estructural de una interfaz utilizada para definir la distribución de sus elementos antes de la implementación.              | [`01_Bitacora.md`](./01_Bitacora.md)         |
| **Desktop**                            | Versión de una interfaz diseñada para visualizarse principalmente en pantallas de computador de mayor tamaño.                               | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Mobile**                             | Versión de una interfaz adaptada para dispositivos móviles y pantallas de menor tamaño.                                                     | [`04_Arquitectura.md`](./04_Arquitectura.md) |

---

# Tecnologías

| Término        | Definición                                                                                                               | Documento relacionado                        |
| -------------- | ------------------------------------------------------------------------------------------------------------------------ | -------------------------------------------- |
| **CSS**        | Lenguaje utilizado para definir la apariencia visual, distribución y estilos de la aplicación web.                       | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **HTML**       | Lenguaje de marcado utilizado para estructurar las páginas y elementos de la aplicación web.                             | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **JavaScript** | Lenguaje utilizado para implementar interactividad y comportamientos dinámicos en la interfaz.                           | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **MySQL**      | Sistema gestor de bases de datos utilizado para almacenar y administrar la información de la plataforma.                 | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **PHP**        | Lenguaje de programación utilizado para desarrollar la lógica del backend de la plataforma.                              | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Inter**      | Familia tipográfica utilizada como tipografía principal en las interfaces de la plataforma.                              | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Poppins**    | Familia tipográfica utilizada inicialmente dentro de la definición del Design System durante la etapa inicial de diseño. | [`01_Bitacora.md`](./01_Bitacora.md)         |

---

# Control de Versiones

| Término                  | Definición                                                                                                 | Documento relacionado                            |
| ------------------------ | ---------------------------------------------------------------------------------------------------------- | ------------------------------------------------ |
| **Commit**               | Registro de un conjunto de cambios realizados en el repositorio mediante Git.                              | [`02_Planificacion.md`](./02_Planificacion.md)   |
| **Git**                  | Sistema de control de versiones distribuido utilizado durante el desarrollo del proyecto.                  | [`02_Planificacion.md`](./02_Planificacion.md)   |
| **GitHub**               | Plataforma utilizada para alojar y gestionar remotamente el repositorio del proyecto.                      | [`02_Planificacion.md`](./02_Planificacion.md)   |
| **Rama (Branch)**        | Línea de desarrollo independiente utilizada para implementar funcionalidades, cambios o correcciones.      | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| **Repositorio**          | Espacio donde se almacena el código fuente, documentación y demás archivos versionados del proyecto.       | [`README.md`](../README.md)                      |
| **Push**                 | Operación mediante la cual los cambios registrados localmente se envían al repositorio remoto.             | [`02_Planificacion.md`](./02_Planificacion.md)   |
| **Control de Versiones** | Sistema utilizado para registrar, organizar y recuperar diferentes versiones de los archivos del proyecto. | [`02_Planificacion.md`](./02_Planificacion.md)   |

---

# Usuarios y Roles

| Término           | Definición                                                                                                                                               | Documento relacionado                        |
| ----------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------- |
| **Administrador** | Usuario con permisos para administrar funcionalidades y contenidos de la plataforma según los permisos definidos.                                        | [`03_Requisitos.md`](./03_Requisitos.md)     |
| **Egresado**      | Persona que culminó sus estudios en la Institución Educativa Gilberto Alzate Avendaño y utiliza la plataforma como miembro de la comunidad de egresados. | [`03_Requisitos.md`](./03_Requisitos.md)     |
| **Rol**           | Conjunto de permisos y capacidades asignados a un usuario dentro de la plataforma.                                                                       | [`03_Requisitos.md`](./03_Requisitos.md)     |
| **Usuario**       | Persona que interactúa con la plataforma mediante una cuenta registrada.                                                                                 | [`03_Requisitos.md`](./03_Requisitos.md)     |
| **Autenticación** | Proceso mediante el cual el sistema verifica la identidad de un usuario.                                                                                 | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Permiso**       | Autorización que determina qué acciones o funcionalidades puede realizar un usuario dentro del sistema.                                                  | [`03_Requisitos.md`](./03_Requisitos.md)     |
| **Perfil**        | Espacio donde se almacena y presenta la información asociada a un usuario o egresado.                                                                    | [`03_Requisitos.md`](./03_Requisitos.md)     |

---

# Base de Datos

| Término                          | Definición                                                                                                | Documento relacionado                        |
| -------------------------------- | --------------------------------------------------------------------------------------------------------- | -------------------------------------------- |
| **Base de Datos**                | Conjunto organizado de información almacenada y administrada para ser utilizada por la plataforma.        | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Tabla**                        | Estructura de la base de datos utilizada para organizar información relacionada en filas y columnas.      | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Registro**                     | Conjunto de datos almacenados como una unidad dentro de una tabla.                                        | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Campo**                        | Elemento que representa un tipo específico de información dentro de una tabla.                            | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Clave Primaria (Primary Key)** | Campo o conjunto de campos utilizado para identificar de manera única un registro dentro de una tabla.    | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Clave Foránea (Foreign Key)**  | Campo utilizado para establecer una relación entre registros de diferentes tablas.                        | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Consulta (Query)**             | Instrucción utilizada para solicitar, modificar o administrar información almacenada en la base de datos. | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| **Relación**                     | Asociación existente entre datos de diferentes tablas dentro de una base de datos.                        | [`04_Arquitectura.md`](./04_Arquitectura.md) |

---

# Implementación

| Término            | Definición                                                                                                                          | Documento relacionado                          |
| ------------------ | ----------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------- |
| **Implementación** | Etapa del proyecto en la que los diseños, estructuras y especificaciones definidas previamente se convierten en código funcional.   | [`04_Arquitectura.md`](./04_Arquitectura.md)   |
| **Integración**    | Proceso de conectar y hacer funcionar conjuntamente los diferentes componentes del sistema, como frontend, backend y base de datos. | [`04_Arquitectura.md`](./04_Arquitectura.md)   |
| **Validación**     | Proceso mediante el cual se comprueba que los datos o acciones cumplen las condiciones establecidas por el sistema.                 | [`03_Requisitos.md`](./03_Requisitos.md)       |
| **Prueba**         | Actividad realizada para comprobar el funcionamiento correcto de una funcionalidad o componente.                                    | [`02_Planificacion.md`](./02_Planificacion.md) |
| **Frontend**       | Capa de presentación que implementa visualmente las interfaces definidas durante el diseño UI/UX.                                   | [`04_Arquitectura.md`](./04_Arquitectura.md)   |
| **Backend**        | Capa lógica que procesa solicitudes, aplica reglas de negocio y se comunica con la base de datos.                                   | [`04_Arquitectura.md`](./04_Arquitectura.md)   |

---

# Notas

* Los términos están organizados por categorías para facilitar su consulta.
* Los enlaces permiten acceder directamente al documento donde cada concepto se desarrolla con mayor profundidad.
* Algunos términos pueden pertenecer a más de una categoría debido a su relación con diferentes partes del proyecto.
* Los estados de los componentes pueden variar según las necesidades de interacción definidas durante el diseño.
* La tipografía Poppins se conserva en el glosario como referencia histórica de la etapa inicial del Design System, mientras que Inter corresponde a la tipografía utilizada posteriormente en las interfaces.
* El glosario deberá actualizarse durante todo el ciclo de vida del proyecto.
* Los nombres de los documentos relacionados deben mantenerse sincronizados con la estructura actual de la carpeta `docs/`.

---

# Control de Cambios

| Versión | Fecha      | Descripción                                                                                                                                                                                                                           | Autor            |
| ------- | ---------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------- |
| v0.1.0  | 09/07/2026 | Creación del glosario, organización por categorías e incorporación de referencias cruzadas entre documentos.                                                                                                                          | Santiago Emitola |
| v0.1.0  | 05/09/2026 | Actualización del glosario con conceptos relacionados con Figma, Design System, Components & Responsive, componentes, estados y flujo de información.                                                                                 | Santiago Emitola |
| v0.1.1  | 07/10/2026 | Actualización general del glosario para reflejar la finalización de la etapa de Diseño UI/UX, el avance hacia la implementación y la incorporación de nuevos conceptos técnicos, de diseño, usuarios, base de datos e implementación. | Santiago Emitola |
