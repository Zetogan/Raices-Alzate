# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 📖 Glosario

## Información general

| Campo                        | Información                                    |
| ---------------------------- | ---------------------------------------------- |
| Proyecto                     | Raíces Alzate                                  |
| Institución                  | Institución Educativa Gilberto Alzate Avendaño |
| Fecha de inicio              | 2025                                           |
| Versión documental           | v0.1.1                                         |
| Estado                       | 🟡 En desarrollo                               |
| Fase actual                  | Fase 4 — Implementación                        |
| Responsable de documentación | Santiago Emitola                               |
| Última actualización         | 13/10/2026                                     |

---

## Introducción

El presente documento reúne los términos técnicos, académicos y funcionales utilizados durante el desarrollo del proyecto **Raíces Alzate**.

Su propósito es establecer un lenguaje común entre los integrantes del proyecto, docentes y futuros colaboradores, facilitando la comprensión de la documentación, el código fuente, el diseño y el funcionamiento general de la plataforma.

Los términos se organizan por categorías e incluyen referencias a los documentos en los que se desarrollan con mayor profundidad.

Este glosario se actualizará progresivamente conforme evolucionen las funcionalidades, tecnologías, procesos y decisiones del proyecto. Las definiciones relacionadas con funcionalidades en desarrollo deben interpretarse según el estado real de implementación y validación de cada componente.

---

## Índice

* [Conceptos del proyecto](#conceptos-del-proyecto)
* [Desarrollo web](#desarrollo-web)
* [Diseño UI/UX](#diseño-uiux)
* [Tecnologías](#tecnologías)
* [Arquitectura frontend](#arquitectura-frontend)
* [Autenticación y seguridad](#autenticación-y-seguridad)
* [Control de versiones](#control-de-versiones)
* [Usuarios y roles](#usuarios-y-roles)
* [Base de datos](#base-de-datos)
* [Implementación y pruebas](#implementación-y-pruebas)
* [Notas](#notas)
* [Control de cambios](#control-de-cambios)

---

## Conceptos del proyecto

| Término                | Definición                                                                                                        | Documento relacionado                          |
| ---------------------- | ----------------------------------------------------------------------------------------------------------------- | ---------------------------------------------- |
| Bitácora               | Documento que registra cronológicamente las actividades, avances, dificultades y novedades del proyecto.          | [`01_Bitacora.md`](./01_Bitacora.md)           |
| Changelog              | Documento que registra los cambios relevantes realizados entre las versiones del proyecto.                        | [`CHANGELOG.md`](../CHANGELOG.md)              |
| Documentación          | Conjunto de documentos técnicos, funcionales y organizativos que describen el proyecto.                           | [`README.md`](../README.md)                    |
| Flujo de información   | Representación de cómo circulan los datos entre los usuarios, los módulos y los componentes del sistema.          | [`04_Arquitectura.md`](./04_Arquitectura.md)   |
| Módulo                 | Conjunto de funcionalidades relacionadas que cumplen un objetivo específico dentro de la plataforma.              | [`04_Arquitectura.md`](./04_Arquitectura.md)   |
| Plataforma             | Sistema web desarrollado para fortalecer el vínculo entre la institución educativa y su comunidad de egresados.   | [`03_Requisitos.md`](./03_Requisitos.md)       |
| Sistema                | Conjunto de componentes que trabajan de manera integrada para ofrecer las funcionalidades de la plataforma.       | [`04_Arquitectura.md`](./04_Arquitectura.md)   |
| Versión                | Identificador que permite distinguir un estado o una actualización del proyecto.                                  | [`CHANGELOG.md`](../CHANGELOG.md)              |
| Fase de desarrollo     | Etapa del ciclo de vida del proyecto que agrupa actividades y objetivos relacionados.                             | [`02_Planificacion.md`](./02_Planificacion.md) |
| Criterio de aceptación | Condición verificable que debe cumplir una funcionalidad para considerarse aceptable dentro del alcance definido. | [`03_Requisitos.md`](./03_Requisitos.md)       |

---

## Desarrollo web

| Término        | Definición                                                                                                                                                                  | Documento relacionado                            |
| -------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------ |
| Backend        | Parte del sistema encargada de procesar solicitudes, aplicar reglas de negocio y comunicarse con la base de datos.                                                          | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Cliente        | Programa, normalmente un navegador web, desde el cual una persona accede e interactúa con la plataforma.                                                                    | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| CRUD           | Conjunto de operaciones básicas para crear, consultar, actualizar y eliminar información. Proviene de *Create, Read, Update, Delete*.                                       | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Dashboard      | Panel principal desde el cual un usuario puede consultar información y acceder a las funcionalidades disponibles según sus permisos.                                        | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Frontend       | Capa de presentación con la que interactúa directamente el usuario.                                                                                                         | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Interfaz       | Medio visual y funcional mediante el cual el usuario interactúa con el sistema.                                                                                             | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Responsive     | Capacidad de una interfaz para adaptarse a diferentes tamaños de pantalla y dispositivos.                                                                                   | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Servidor       | Equipo o servicio que procesa las solicitudes de los clientes y entrega las respuestas correspondientes.                                                                    | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Solicitud HTTP | Petición que un cliente envía a un servidor para solicitar un recurso o ejecutar una operación.                                                                             | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Respuesta HTTP | Mensaje que el servidor devuelve al cliente e incluye información sobre el resultado de una solicitud.                                                                      | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Subruta        | Ruta que identifica un recurso dentro de una ubicación base de una aplicación web. Es importante cuando el proyecto se ejecuta dentro de una subcarpeta del servidor local. | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |

---

## Diseño UI/UX

| Término                         | Definición                                                                                                                                         | Documento relacionado                        |
| ------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------- |
| Componentes                     | Elementos reutilizables de la interfaz que cumplen una función específica, como botones, campos de entrada, tarjetas y controles de selección.     | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| Components & Responsive         | Página del archivo de Figma destinada a organizar los componentes reutilizables, sus estados y sus criterios de adaptación a diferentes pantallas. | [`01_Bitacora.md`](./01_Bitacora.md)         |
| Design System                   | Conjunto organizado de criterios, estilos, reglas visuales y componentes que orientan la construcción coherente de las interfaces.                 | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| Desktop                         | Versión de una interfaz diseñada principalmente para pantallas de computador.                                                                      | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| Estado de componente            | Representación visual de una condición o situación de interacción de un elemento de la interfaz.                                                   | [`decisiones.md`](./decisiones.md)           |
| Figma                           | Herramienta utilizada para diseñar, organizar y documentar las interfaces y los componentes visuales del proyecto.                                 | [`01_Bitacora.md`](./01_Bitacora.md)         |
| Focus                           | Estado visual que indica que un elemento ha recibido el foco de interacción, por ejemplo, mediante el teclado.                                     | [`decisiones.md`](./decisiones.md)           |
| Hover                           | Estado visual que aparece cuando el cursor se sitúa sobre un elemento interactivo compatible.                                                      | [`decisiones.md`](./decisiones.md)           |
| Mobile                          | Versión de una interfaz adaptada a dispositivos móviles y pantallas de menor tamaño.                                                               | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| Normal                          | Estado visual predeterminado de un componente cuando no presenta una interacción o condición especial.                                             | [`decisiones.md`](./decisiones.md)           |
| Radio Button                    | Control de selección que permite elegir una única opción dentro de un grupo de alternativas.                                                       | [`01_Bitacora.md`](./01_Bitacora.md)         |
| Selected                        | Estado que indica que una opción o un componente se encuentra seleccionado.                                                                        | [`decisiones.md`](./decisiones.md)           |
| Switch                          | Control visual que permite activar o desactivar una opción o funcionalidad.                                                                        | [`01_Bitacora.md`](./01_Bitacora.md)         |
| UI (*User Interface*)           | Interfaz de usuario: conjunto de elementos visuales e interactivos mediante los cuales una persona utiliza la plataforma.                          | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| UX (*User Experience*)          | Experiencia de usuario: percepción y experiencia general de una persona al utilizar la plataforma.                                                 | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| Wireframe                       | Representación estructural de una interfaz que permite definir la distribución de sus elementos antes de desarrollar el diseño visual definitivo.  | [`01_Bitacora.md`](./01_Bitacora.md)         |
| Fidelidad visual                | Grado de correspondencia entre una interfaz implementada y su diseño de referencia en Figma.                                                       | [`04_Arquitectura.md`](./04_Arquitectura.md) |
| Punto de quiebre (*Breakpoint*) | Ancho de pantalla a partir del cual se aplican cambios en la distribución o presentación de una interfaz mediante reglas responsive.               | [`04_Arquitectura.md`](./04_Arquitectura.md) |

---

## Tecnologías

| Término                  | Definición                                                                                                                                                                                  | Documento relacionado                            |
| ------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------ |
| CSS                      | Lenguaje utilizado para definir la apariencia, distribución y presentación visual de una página web.                                                                                        | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| HTML                     | Lenguaje de marcado utilizado para estructurar el contenido de las páginas web.                                                                                                             | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| JavaScript               | Lenguaje de programación utilizado para implementar interactividad y comportamientos dinámicos en el navegador.                                                                             | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| MySQL                    | Sistema gestor de bases de datos relacionales utilizado para almacenar y administrar información estructurada.                                                                              | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| PHP                      | Lenguaje de programación utilizado en el servidor para procesar solicitudes y generar contenido web dinámico.                                                                               | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Inter                    | Familia tipográfica utilizada como tipografía principal en las interfaces de la plataforma.                                                                                                 | [`01_Bitacora.md`](./01_Bitacora.md)             |
| Poppins                  | Familia tipográfica utilizada inicialmente durante la definición del Design System. Se conserva como referencia histórica de la etapa de diseño.                                            | [`01_Bitacora.md`](./01_Bitacora.md)             |
| PDO (*PHP Data Objects*) | Extensión de PHP que proporciona una interfaz común para acceder a bases de datos mediante distintos controladores.                                                                         | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Vanilla JavaScript       | Uso de JavaScript sin depender de frameworks o bibliotecas adicionales para implementar la interactividad.                                                                                  | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| XAMPP                    | Paquete de herramientas que permite preparar un entorno de desarrollo local con servicios como Apache, PHP y MySQL o MariaDB.                                                               | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Apache                   | Servidor web que recibe solicitudes HTTP y entrega los recursos o ejecuta los procesos configurados para una aplicación web. En el entorno del proyecto puede utilizarse a través de XAMPP. | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| `localStorage`           | Mecanismo del navegador que permite guardar datos en el almacenamiento local para recuperarlos entre visitas. No debe utilizarse para guardar contraseñas ni secretos sensibles.            | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| `random_bytes()`         | Función de PHP que genera bytes aleatorios criptográficamente seguros. Puede utilizarse para crear valores destinados a tokens de recuperación.                                             | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |

---

## Arquitectura frontend

| Término                         | Definición                                                                                                                                                                               | Documento relacionado                            |
| ------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------ |
| Componente PHP reutilizable     | Archivo o fragmento PHP que contiene una parte de la interfaz que puede incluirse en diferentes páginas para evitar la duplicación de código.                                            | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Front Controller                | Patrón en el que un punto de entrada central recibe las solicitudes y coordina la carga de la sección correspondiente. En la arquitectura del proyecto, `index.php` cumple esta función. | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| CSS modular                     | Organización de los estilos en archivos separados según la responsabilidad de cada componente o sección de la interfaz.                                                                  | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| `main.css`                      | Hoja de estilos destinada a contener reglas generales y estilos compartidos por la aplicación.                                                                                           | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| `fonts.css`                     | Hoja de estilos destinada a centralizar la configuración tipográfica y, cuando corresponda, la carga de fuentes.                                                                         | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| `header.css`                    | Hoja de estilos destinada a definir la apariencia y el comportamiento responsive del encabezado y la navegación.                                                                         | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| `footer.css`                    | Hoja de estilos destinada a definir la apariencia y distribución del pie de página.                                                                                                      | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| `portal.css`                    | Hoja de estilos reportada para organizar reglas relacionadas con la interfaz del portal. Su alcance exacto debe corresponder a la estructura real del repositorio.                       | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| `dashboard.css`                 | Hoja de estilos destinada a los elementos visuales y comportamientos responsive del Dashboard.                                                                                           | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| `loader.css`                    | Hoja de estilos destinada a la presentación visual del indicador de carga.                                                                                                               | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Header                          | Encabezado principal que puede contener la identidad visual, la navegación y las acciones principales de la plataforma.                                                                  | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Footer                          | Pie de página que puede contener información institucional, enlaces secundarios y otros datos relevantes.                                                                                | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Favicon                         | Icono asociado al sitio web que puede aparecer en la pestaña del navegador y otros elementos de la interfaz del navegador.                                                               | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Navegación responsive           | Sistema de navegación que adapta la presentación del menú según el tamaño de pantalla, por ejemplo, mediante un menú desplegable en dispositivos móviles.                                | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Separación de responsabilidades | Principio de organización que distribuye el código según la función de cada archivo o componente, facilitando su mantenimiento.                                                          | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Sidebar                         | Barra lateral que agrupa enlaces de navegación y accesos a las secciones principales de un panel interno.                                                                                | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Topbar                          | Barra superior de una interfaz interna que puede contener información del usuario, controles de navegación y acciones rápidas.                                                           | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Mini-sidebar                    | Variante compacta de la barra lateral que reduce su ancho para dejar más espacio al contenido principal.                                                                                 | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Drawer                          | Panel de navegación que aparece desde un borde de la pantalla y puede abrirse o cerrarse, especialmente en dispositivos móviles.                                                         | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Backdrop                        | Capa visual que aparece detrás de un panel o menú abierto para separar el elemento activo del resto de la interfaz.                                                                      | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Loader                          | Indicador visual que informa al usuario de que una página o proceso está cargando. Debe desaparecer correctamente para no bloquear la interacción.                                       | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Overlay                         | Capa que se muestra sobre el contenido de la interfaz, por ejemplo, para indicar una carga o destacar un elemento.                                                                       | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Skeleton loader                 | Representación provisional de bloques de contenido que simulan la estructura de una página mientras se cargan los datos. Su uso debe evitar interferir con la interacción.               | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Persistencia de interfaz        | Conservación de una preferencia visual entre visitas o recargas, como el estado expandido o contraído de una barra lateral.                                                              | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Anti-FOUC                       | Técnica destinada a reducir la aparición momentánea de contenido sin estilos (*Flash of Unstyled Content*) durante la carga de una página.                                               | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Ámbito de un selector CSS       | Conjunto de elementos HTML a los que afecta una regla CSS. Restringir correctamente los selectores ayuda a evitar que un estilo altere componentes no relacionados.                      | [`04_Arquitectura.md`](./04_Arquitectura.md)     |

---

## Autenticación y seguridad

| Término                          | Definición                                                                                                                                                                                                                         | Documento relacionado                            |
| -------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------ |
| Autenticación                    | Proceso mediante el cual el sistema verifica la identidad de una persona que intenta acceder a una cuenta.                                                                                                                         | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Inicio de sesión (*Login*)       | Proceso mediante el cual un usuario proporciona sus credenciales para intentar acceder a su cuenta.                                                                                                                                | [`01_Bitacora.md`](./01_Bitacora.md)             |
| Registro                         | Proceso mediante el cual una persona proporciona los datos necesarios para crear una cuenta en la plataforma.                                                                                                                      | [`01_Bitacora.md`](./01_Bitacora.md)             |
| Recuperación de contraseña       | Proceso que permite solicitar instrucciones para recuperar el acceso a una cuenta cuando se ha olvidado la contraseña.                                                                                                             | [`01_Bitacora.md`](./01_Bitacora.md)             |
| Credenciales                     | Datos utilizados para verificar la identidad de un usuario, como el correo electrónico y la contraseña.                                                                                                                            | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Hash de contraseña               | Representación criptográfica de una contraseña que permite almacenarla sin guardar directamente el texto original. En PHP pueden utilizarse `password_hash()` y `password_verify()`.                                               | [`decisiones.md`](./decisiones.md)               |
| Token                            | Valor generado para identificar o autorizar una operación específica. En un flujo de recuperación de contraseña, debe gestionarse de forma segura.                                                                                 | [`decisiones.md`](./decisiones.md)               |
| Validación del lado del cliente  | Comprobación de datos mediante JavaScript o las capacidades del navegador antes de enviar un formulario al servidor.                                                                                                               | [`01_Bitacora.md`](./01_Bitacora.md)             |
| Validación del lado del servidor | Comprobación de datos en el backend antes de ejecutar una operación. Es necesaria aunque exista validación en el navegador.                                                                                                        | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Consulta preparada               | Consulta SQL en la que los valores se suministran mediante parámetros separados de la instrucción SQL, ayudando a prevenir la inyección SQL cuando se utiliza correctamente.                                                       | [`decisiones.md`](./decisiones.md)               |
| Inyección SQL                    | Vulnerabilidad que puede producirse cuando datos no confiables se incorporan incorrectamente a una consulta SQL, permitiendo alterar su comportamiento.                                                                            | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Enumeración de cuentas           | Situación en la que una respuesta del sistema permite descubrir si un correo electrónico u otro identificador corresponde a una cuenta registrada.                                                                                 | [`decisiones.md`](./decisiones.md)               |
| Respuesta genérica               | Mensaje que evita revelar información sensible sobre la existencia o el estado de una cuenta. En recuperación de contraseña, ayuda a reducir la enumeración de cuentas.                                                            | [`decisiones.md`](./decisiones.md)               |
| SMTP                             | Protocolo utilizado para enviar correos electrónicos. Su configuración permite integrar el envío de mensajes de recuperación u otras notificaciones.                                                                               | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Sesión                           | Mecanismo mediante el cual el servidor mantiene información temporal asociada a la interacción de un usuario con la aplicación.                                                                                                    | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| `session_start()`                | Función de PHP que inicia una sesión nueva o reanuda una existente. Debe ejecutarse antes de emitir contenido cuando la configuración requiera enviar encabezados HTTP.                                                            | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Control de acceso                | Comprobación de que una persona autenticada dispone de autorización para acceder a una página, recurso o acción.                                                                                                                   | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Autorización                     | Proceso mediante el cual el sistema determina qué acciones y recursos puede utilizar un usuario. Es diferente de comprobar su identidad.                                                                                           | [`03_Requisitos.md`](./03_Requisitos.md)         |
| `headers_sent()`                 | Función de PHP que permite comprobar si ya se han enviado encabezados o parte de la respuesta al cliente. Puede ayudar a diagnosticar problemas, pero no sustituye una correcta inicialización de la sesión antes de generar HTML. | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Acceso simulado                  | Mecanismo temporal que permite probar una interfaz utilizando una sesión de prueba sin completar el proceso de autenticación real. Debe limitarse al entorno de desarrollo.                                                        | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Cierre de sesión (*Logout*)      | Proceso mediante el cual se finaliza la sesión de un usuario y se eliminan o invalidan los datos de sesión correspondientes.                                                                                                       | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Cookie de sesión                 | Dato almacenado en el navegador que puede utilizarse para identificar la sesión mantenida por el servidor. Su configuración debe seguir prácticas de seguridad apropiadas.                                                         | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Token de recuperación            | Valor temporal que permite verificar una solicitud de restablecimiento de contraseña. Debe almacenarse y validarse de forma segura, contar con expiración y ser de un solo uso.                                                    | [`decisiones.md`](./decisiones.md)               |

---

## Control de versiones

| Término              | Definición                                                                                                                                                       | Documento relacionado                            |
| -------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------ |
| Commit               | Registro de un conjunto de cambios realizados en el repositorio mediante Git.                                                                                    | [`02_Planificacion.md`](./02_Planificacion.md)   |
| Git                  | Sistema de control de versiones distribuido que permite registrar y administrar cambios en los archivos del proyecto.                                            | [`02_Planificacion.md`](./02_Planificacion.md)   |
| GitHub               | Plataforma utilizada para alojar y gestionar repositorios Git de forma remota.                                                                                   | [`02_Planificacion.md`](./02_Planificacion.md)   |
| Rama (*Branch*)      | Línea independiente de desarrollo que permite trabajar en cambios sin modificar directamente otra rama.                                                          | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Repositorio          | Espacio que contiene los archivos versionados del proyecto y el historial de cambios registrado por Git.                                                         | [`README.md`](../README.md)                      |
| Push                 | Operación mediante la cual los commits locales se envían a un repositorio remoto.                                                                                | [`02_Planificacion.md`](./02_Planificacion.md)   |
| Pull                 | Operación que permite obtener e integrar cambios de un repositorio remoto en el entorno local, según la configuración y el estado de la rama.                    | [`02_Planificacion.md`](./02_Planificacion.md)   |
| Control de versiones | Práctica de registrar y organizar los cambios realizados en los archivos para consultar su historial y recuperar versiones anteriores.                           | [`02_Planificacion.md`](./02_Planificacion.md)   |
| Rama remota          | Referencia que representa el estado de una rama en un repositorio remoto.                                                                                        | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Working tree         | Estado de los archivos del repositorio local, que puede incluir cambios sin registrar mediante un commit.                                                        | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Pull Request         | Solicitud para revisar y proponer la integración de cambios de una rama en otra. Permite revisar las modificaciones antes de incorporarlas a la rama de destino. | [`02_Planificacion.md`](./02_Planificacion.md)   |
| Merge                | Operación que integra cambios procedentes de otra rama en la rama actual.                                                                                        | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Conflicto de merge   | Situación en la que Git no puede combinar automáticamente ciertos cambios y necesita que se resuelvan manualmente.                                               | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |

---

## Usuarios y roles

| Término       | Definición                                                                                                                         | Documento relacionado                    |
| ------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------- |
| Administrador | Usuario con permisos para gestionar funcionalidades y contenidos según las autorizaciones definidas por el sistema.                | [`03_Requisitos.md`](./03_Requisitos.md) |
| Egresado      | Persona que culminó sus estudios en la Institución Educativa Gilberto Alzate Avendaño y forma parte de su comunidad de egresados.  | [`03_Requisitos.md`](./03_Requisitos.md) |
| Estudiante    | Persona que cursa estudios en la institución y que puede acceder a las funcionalidades que se definan para este tipo de usuario.   | [`03_Requisitos.md`](./03_Requisitos.md) |
| Rol           | Categoría que agrupa los permisos y las capacidades asignadas a un usuario dentro de la plataforma.                                | [`03_Requisitos.md`](./03_Requisitos.md) |
| Usuario       | Persona que interactúa con la plataforma. Cuando dispone de una cuenta, puede acceder a las funcionalidades habilitadas para ella. | [`03_Requisitos.md`](./03_Requisitos.md) |
| Permiso       | Autorización que determina qué acciones puede realizar un usuario dentro del sistema.                                              | [`03_Requisitos.md`](./03_Requisitos.md) |
| Perfil        | Espacio en el que se presenta y administra la información asociada a un usuario o egresado, según las funcionalidades disponibles. | [`03_Requisitos.md`](./03_Requisitos.md) |

---

## Base de datos

| Término                        | Definición                                                                                                                                                    | Documento relacionado                            |
| ------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------ |
| Base de datos                  | Conjunto organizado de información almacenada y administrada para su consulta y utilización por parte de una aplicación.                                      | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Tabla                          | Estructura de una base de datos relacional que organiza información en filas y columnas.                                                                      | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Registro                       | Conjunto de valores que representa una fila dentro de una tabla.                                                                                              | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Campo                          | Elemento que representa un atributo o tipo de dato dentro de una tabla.                                                                                       | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Clave primaria (*Primary Key*) | Campo o conjunto de campos que identifica de forma única cada registro de una tabla.                                                                          | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Clave foránea (*Foreign Key*)  | Campo o conjunto de campos que establece una relación con una clave de otra tabla o de la misma tabla.                                                        | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Consulta (*Query*)             | Instrucción utilizada para consultar o manipular información de una base de datos.                                                                            | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Relación                       | Asociación entre tablas o registros que permite representar vínculos entre diferentes tipos de información.                                                   | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| SQL                            | Lenguaje utilizado para definir estructuras, consultar y manipular datos en bases de datos relacionales.                                                      | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| INNER JOIN                     | Operación SQL que combina registros de dos tablas cuando existe coincidencia entre las columnas relacionadas según la condición establecida.                  | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Esquema de base de datos       | Definición de la estructura de una base de datos, incluidas sus tablas, columnas, relaciones y restricciones.                                                 | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Integridad referencial         | Conjunto de reglas que mantiene la coherencia de las relaciones entre registros vinculados mediante claves.                                                   | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Conexión a la base de datos    | Comunicación establecida entre la aplicación y el sistema gestor de bases de datos para ejecutar operaciones autorizadas.                                     | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Transacción                    | Conjunto de operaciones que se ejecutan como una unidad lógica y que, según las propiedades de la base de datos, pueden confirmarse o revertirse en conjunto. | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |

> **Nota:** la estructura definitiva de las tablas, sus relaciones y restricciones debe documentarse de acuerdo con el esquema aprobado e implementado para las funcionalidades del proyecto. La conexión técnica con MySQL no implica que todo el modelo de datos esté terminado ni que todas las operaciones de la aplicación estén integradas.

---

## Implementación y pruebas

| Término                       | Definición                                                                                                                                                 | Documento relacionado                            |
| ----------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------ |
| Implementación                | Etapa en la que los diseños, estructuras y especificaciones se convierten en código y funcionalidades de la aplicación.                                    | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Integración                   | Proceso de conectar componentes para que trabajen conjuntamente, como las interfaces, los procesos PHP y la base de datos.                                 | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Validación                    | Comprobación de que los datos o las acciones cumplen las condiciones establecidas por el sistema.                                                          | [`03_Requisitos.md`](./03_Requisitos.md)         |
| Prueba                        | Actividad destinada a comprobar el comportamiento de una funcionalidad, componente o sistema bajo determinadas condiciones.                                | [`02_Planificacion.md`](./02_Planificacion.md)   |
| Implementación de interfaz    | Proceso de convertir una pantalla definida en Figma en una interfaz web mediante tecnologías como HTML, CSS, PHP y JavaScript.                             | [`01_Bitacora.md`](./01_Bitacora.md)             |
| Diseño responsive             | Enfoque de diseño y desarrollo que busca mantener la legibilidad, estructura y funcionalidad de una interfaz en diferentes tamaños de pantalla.            | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Regresión                     | Aparición de un error o deterioro en una funcionalidad que antes funcionaba correctamente después de introducir cambios.                                   | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Lint                          | Análisis automatizado del código para detectar ciertos errores, inconsistencias o problemas de estilo. No sustituye las pruebas funcionales.               | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Prueba de humo (*Smoke test*) | Comprobación inicial de que una aplicación o sus funciones esenciales arrancan y responden sin fallos evidentes.                                           | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Prueba funcional              | Evaluación de una funcionalidad para comprobar que produce los resultados esperados en determinadas condiciones.                                           | [`02_Planificacion.md`](./02_Planificacion.md)   |
| Prueba responsive             | Comprobación del comportamiento de una interfaz en diferentes tamaños de pantalla y dispositivos.                                                          | [`04_Arquitectura.md`](./04_Arquitectura.md)     |
| Prueba de integración         | Comprobación de que varios componentes conectados funcionan correctamente en conjunto.                                                                     | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Código HTTP 200               | Código de estado HTTP que indica que una solicitud se procesó correctamente. Por sí solo no demuestra que toda la funcionalidad de la página sea correcta. | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| `php -l`                      | Comando de PHP que verifica la sintaxis de un archivo sin ejecutar su lógica de aplicación. No sustituye las pruebas funcionales ni de seguridad.          | [`05_Manual_Tecnico.md`](./05_Manual_Tecnico.md) |
| Criterio de aceptación        | Condición verificable que debe cumplir una funcionalidad para considerarse aceptable dentro del alcance definido.                                          | [`03_Requisitos.md`](./03_Requisitos.md)         |

---

## Notas

* Los términos se organizan por categorías para facilitar su consulta.
* Las referencias permiten localizar documentos en los que se desarrollan con mayor profundidad los conceptos.
* Algunos términos pueden aparecer en más de una categoría debido a su relación con diferentes áreas del proyecto.
* Los estados de los componentes pueden variar según las necesidades de interacción definidas durante el diseño.
* Poppins se conserva como referencia histórica de la etapa inicial del Design System; Inter es la tipografía principal utilizada en las interfaces.
* La etapa de Diseño UI/UX se dio por finalizada a finales de septiembre de 2026. Se completaron 17 pantallas Desktop y 17 Mobile, para un total de 34 pantallas diseñadas.
* La finalización del diseño no significa que todas las pantallas estén implementadas. La correspondencia visual y la adaptación responsive deben comprobarse durante el desarrollo.
* La implementación frontend comenzó con la interfaz de Inicio y continuó con otras áreas, incluidas las interfaces de autenticación y el Dashboard. El alcance exacto debe mantenerse sincronizado con el código existente.
* El stack frontend utiliza PHP, HTML, CSS y JavaScript sin frameworks frontend adicionales.
* Los estilos se organizan de forma modular para facilitar el mantenimiento y la evolución de la aplicación. Los archivos y sus responsabilidades deben verificarse contra la estructura vigente del repositorio.
* Se reportaron ajustes al encabezado público compartido, la navegación lateral del Dashboard y su comportamiento responsive. Su validación integral en diferentes resoluciones continúa siendo necesaria.
* Se reportaron ajustes al loader global para mostrar un indicador de carga sin bloquear innecesariamente la interacción. Debe verificarse su comportamiento en las distintas páginas y evitar la duplicación de lógica entre scripts.
* Se reportaron pruebas sintácticas con `php -l` y respuestas HTTP satisfactorias en algunas rutas. Estos resultados no equivalen a una validación funcional, visual, de accesibilidad o seguridad completa.
* Se reportó la verificación de la conexión técnica entre PHP y MySQL. Esto no significa que el esquema definitivo de la base de datos o todas las operaciones funcionales estén terminados.
* Las pantallas de inicio de sesión, registro y recuperación de contraseña cuentan con trabajo de implementación documentado. Su funcionamiento integral debe confirmarse mediante pruebas.
* El envío real de correos para recuperar contraseñas depende de la configuración e integración de un servicio de correo, como SMTP.
* La generación de un token de recuperación no demuestra por sí sola que todo el proceso esté operativo. Deben verificarse su almacenamiento, expiración, validación, uso único y el cambio seguro de contraseña.
* La inicialización de sesiones debe realizarse antes de emitir contenido cuando sea necesario enviar encabezados HTTP. Las comprobaciones de `headers_sent()` pueden ayudar a diagnosticar problemas, pero no sustituyen una correcta organización del flujo de ejecución.
* Cualquier mecanismo de acceso simulado debe limitarse al entorno de desarrollo y no sustituye la autenticación real ni el control de acceso por roles.
* Las rutas y redirecciones deben probarse tanto en el entorno local de XAMPP como en la ubicación prevista para la aplicación.
* Los nombres y ubicaciones de los archivos mencionados deben mantenerse sincronizados con la estructura real del repositorio.
* El glosario deberá actualizarse durante el ciclo de vida del proyecto para reflejar cambios técnicos, funcionales y organizativos.

---

## Control de cambios

| Versión | Fecha      | Descripción                                                                                                                                                                                                                                                                                                                                         | Autor            |
| ------- | ---------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------- |
| v0.1.0  | 09/07/2026 | Creación del glosario, organización inicial por categorías e incorporación de referencias cruzadas entre documentos.                                                                                                                                                                                                                                | Santiago Emitola |
| v0.1.0  | 05/09/2026 | Actualización de conceptos relacionados con Figma, Design System, Components & Responsive, componentes, estados y flujo de información.                                                                                                                                                                                                             | Santiago Emitola |
| v0.1.1  | 07/10/2026 | Actualización general para reflejar la finalización de la etapa de Diseño UI/UX y el avance hacia la implementación.                                                                                                                                                                                                                                | Santiago Emitola |
| v0.1.1  | 08/10/2026 | Incorporación de conceptos relacionados con la implementación frontend, la arquitectura CSS modular, los componentes PHP reutilizables, el Front Controller y la navegación responsive.                                                                                                                                                             | Santiago Emitola |
| v0.1.1  | 09/10/2026 | Consolidación de definiciones y referencias; incorporación de términos relacionados con autenticación, seguridad, validación de formularios, bases de datos, control de versiones e implementación. Se precisan las funcionalidades que requieren verificación adicional.                                                                           | Santiago Emitola |
| v0.1.1  | 13/10/2026 | Actualización del glosario para incorporar conceptos de navegación del Dashboard, sidebar, topbar, loader, persistencia de interfaz, manejo de sesiones y pruebas. Se aclara que los avances reportados requieren validación funcional integral y que la base de datos y los flujos de autenticación no deben considerarse finalizados sin pruebas. | Santiago Emitola |
