# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 📖 Glosario

## Información General

| Campo             | Información                                    |
| ----------------- | ---------------------------------------------- |
| Proyecto          | Raíces Alzate                                  |
| Institución       | Institución Educativa Gilberto Alzate Avendaño |
| Fecha de creación | 09 de julio de 2026                            |
| Versión           | v0.1.0                                         |
| Estado            | 🟡 En desarrollo                               |

---

# Introducción

El presente documento reúne los términos técnicos, académicos y funcionales utilizados durante el desarrollo del proyecto **Raíces Alzate**.

Su propósito es establecer un lenguaje común entre los integrantes del proyecto, docentes y futuros colaboradores, facilitando la comprensión de la documentación, el código fuente, el diseño y el funcionamiento general de la plataforma.

Este glosario será actualizado de forma continua conforme evolucionen las funcionalidades, tecnologías y procesos del proyecto.

---

# Índice

* [Conceptos del Proyecto](#conceptos-del-proyecto)
* [Desarrollo Web](#desarrollo-web)
* [Diseño UI/UX](#diseño-uiux)
* [Tecnologías](#tecnologías)
* [Control de Versiones](#control-de-versiones)
* [Usuarios y Roles](#usuarios-y-roles)
* [Notas](#notas)
* [Control de Cambios](#control-de-cambios)

---

# Conceptos del Proyecto

| Término                  | Definición                                                                                            | Documento relacionado                  |
| ------------------------ | ----------------------------------------------------------------------------------------------------- | -------------------------------------- |
| **Bitácora**             | Documento donde se registran cronológicamente las actividades, avances y novedades del proyecto.      | [`bitacora.md`](./bitacora.md)         |
| **Changelog**            | Documento que registra los cambios importantes realizados durante el desarrollo del proyecto.         | [`changelog.md`](./changelog.md)       |
| **Documentación**        | Conjunto de documentos técnicos, funcionales y organizativos del proyecto.                            | [`README.md`](../README.md)            |
| **Flujo de Información** | Representación de cómo circula la información entre usuarios, módulos y componentes del sistema.      | [`arquitectura.md`](./arquitectura.md) |
| **Módulo**               | Conjunto de funcionalidades relacionadas que cumplen un objetivo específico dentro de la plataforma.  | [`arquitectura.md`](./arquitectura.md) |
| **Plataforma**           | Sistema web desarrollado para fortalecer el vínculo entre la institución y sus egresados.             | [`requisitos.md`](./requisitos.md)     |
| **Sistema**              | Conjunto de componentes que trabajan de manera integrada para ofrecer los servicios de la plataforma. | [`arquitectura.md`](./arquitectura.md) |

---

# Desarrollo Web

| Término       | Definición                                                                                                            | Documento relacionado                  |
| ------------- | --------------------------------------------------------------------------------------------------------------------- | -------------------------------------- |
| **Backend**   | Lógica del servidor encargada del procesamiento de datos y reglas de negocio.                                         | [`arquitectura.md`](./arquitectura.md) |
| **Cliente**   | Navegador o aplicación desde donde un usuario accede a la plataforma.                                                 | [`arquitectura.md`](./arquitectura.md) |
| **CRUD**      | Operaciones de Crear, Leer, Actualizar y Eliminar información.                                                        | [`arquitectura.md`](./arquitectura.md) |
| **Dashboard** | Panel principal disponible después de iniciar sesión y desde el cual se pueden consultar o gestionar funcionalidades. | [`roadmap.md`](./roadmap.md)           |
| **Frontend**  | Parte visual del sistema con la que interactúa directamente el usuario.                                               | [`arquitectura.md`](./arquitectura.md) |
| **Servidor**  | Equipo o servicio que ejecuta la aplicación y responde a las solicitudes de los usuarios.                             | [`arquitectura.md`](./arquitectura.md) |

---

# Diseño UI/UX

| Término                         | Definición                                                                                                                          | Documento relacionado                  |
| ------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------- |
| **Componente**                  | Elemento reutilizable de la interfaz que cumple una función específica dentro del sistema.                                          | [`arquitectura.md`](./arquitectura.md) |
| **Components & Responsive**     | Sección del archivo de Figma destinada a organizar los componentes reutilizables, sus estados y criterios de adaptación responsive. | [`arquitectura.md`](./arquitectura.md) |
| **Design System**               | Conjunto organizado de reglas, estilos y componentes que establecen la identidad visual y los criterios de diseño del proyecto.     | [`arquitectura.md`](./arquitectura.md) |
| **Estado**                      | Representación visual de una condición o situación de interacción de un componente.                                                 | [`decisiones.md`](./decisiones.md)     |
| **Figma**                       | Herramienta utilizada para diseñar, organizar y documentar la interfaz de usuario y el Design System del proyecto.                  | [`bitacora.md`](./bitacora.md)         |
| **Focus**                       | Estado visual que indica que un elemento de la interfaz se encuentra enfocado o seleccionado para interacción.                      | [`decisiones.md`](./decisiones.md)     |
| **Hover**                       | Estado visual que aparece cuando el cursor se encuentra sobre un elemento interactivo.                                              | [`decisiones.md`](./decisiones.md)     |
| **Normal**                      | Estado visual predeterminado de un componente cuando no presenta una interacción o condición especial.                              | [`decisiones.md`](./decisiones.md)     |
| **Radio Button**                | Componente de selección que permite elegir una única opción dentro de un conjunto de alternativas.                                  | [`bitacora.md`](./bitacora.md)         |
| **Responsive**                  | Capacidad de una interfaz para adaptarse a diferentes tamaños de pantalla y dispositivos.                                           | [`arquitectura.md`](./arquitectura.md) |
| **Selected**                    | Estado visual que indica que una opción o componente ha sido seleccionado.                                                          | [`decisiones.md`](./decisiones.md)     |
| **Switch**                      | Componente de interfaz utilizado para activar o desactivar una opción o funcionalidad.                                              | [`bitacora.md`](./bitacora.md)         |
| **UI (Interfaz de Usuario)**    | Conjunto de elementos gráficos mediante los cuales el usuario interactúa con la plataforma.                                         | [`roadmap.md`](./roadmap.md)           |
| **UX (Experiencia de Usuario)** | Experiencia general que tiene una persona al utilizar la plataforma y sus funcionalidades.                                          | [`roadmap.md`](./roadmap.md)           |

---

# Tecnologías

| Término        | Definición                                                             | Documento relacionado                  |
| -------------- | ---------------------------------------------------------------------- | -------------------------------------- |
| **CSS**        | Lenguaje utilizado para definir la apariencia visual de la aplicación. | [`arquitectura.md`](./arquitectura.md) |
| **HTML**       | Lenguaje de marcado utilizado para estructurar las páginas web.        | [`arquitectura.md`](./arquitectura.md) |
| **JavaScript** | Lenguaje utilizado para implementar la interactividad de la interfaz.  | [`arquitectura.md`](./arquitectura.md) |
| **MySQL**      | Sistema gestor de bases de datos utilizado por la plataforma.          | [`arquitectura.md`](./arquitectura.md) |
| **PHP**        | Lenguaje de programación utilizado para desarrollar el backend.        | [`arquitectura.md`](./arquitectura.md) |

---

# Control de Versiones

| Término           | Definición                                                                                          | Documento relacionado                      |
| ----------------- | --------------------------------------------------------------------------------------------------- | ------------------------------------------ |
| **Commit**        | Registro permanente de un conjunto de cambios realizados mediante Git.                              | [`planificacion.md`](./planificacion.md)   |
| **Git**           | Sistema de control de versiones distribuido utilizado durante el desarrollo.                        | [`planificacion.md`](./planificacion.md)   |
| **GitHub**        | Plataforma donde se aloja el repositorio remoto del proyecto.                                       | [`planificacion.md`](./planificacion.md)   |
| **Rama (Branch)** | Línea de desarrollo independiente utilizada para implementar nuevas funcionalidades o correcciones. | [`manual-tecnico.md`](./manual-tecnico.md) |
| **Repositorio**   | Espacio donde se almacena el código fuente y la documentación del proyecto.                         | [`README.md`](../README.md)                |

---

# Usuarios y Roles

| Término           | Definición                                                                             | Documento relacionado              |
| ----------------- | -------------------------------------------------------------------------------------- | ---------------------------------- |
| **Administrador** | Usuario con permisos para administrar el funcionamiento de la plataforma.              | [`requisitos.md`](./requisitos.md) |
| **Egresado**      | Persona que culminó sus estudios en la Institución Educativa Gilberto Alzate Avendaño. | [`requisitos.md`](./requisitos.md) |
| **Rol**           | Conjunto de permisos asignados a un usuario.                                           | [`requisitos.md`](./requisitos.md) |
| **Usuario**       | Persona que interactúa con la plataforma mediante una cuenta registrada.               | [`requisitos.md`](./requisitos.md) |

---

# Notas

* Los términos están organizados por categorías para facilitar su consulta.

* Los enlaces permiten acceder directamente al documento donde cada concepto se desarrolla con mayor profundidad.

* Los estados de los componentes pueden variar según las necesidades de interacción definidas durante el diseño.

* El glosario deberá actualizarse durante todo el ciclo de vida del proyecto.

---

# Control de Cambios

| Versión | Fecha      | Descripción                                                                                                                                           | Autor            |
| ------- | ---------- | ----------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------- |
| v0.1.0  | 09/07/2026 | Creación del glosario, organización por categorías e incorporación de referencias cruzadas entre documentos.                                          | Santiago Emitola |
| v0.1.0  | 05/09/2026 | Actualización del glosario con conceptos relacionados con Figma, Design System, Components & Responsive, componentes, estados y flujo de información. | Santiago Emitola |
