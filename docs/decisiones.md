# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 📘 Registro de Decisiones

## Información general

| Campo                | Información                                    |
| -------------------- | ---------------------------------------------- |
| Proyecto             | Raíces Alzate                                  |
| Institución          | Institución Educativa Gilberto Alzate Avendaño |
| Desarrollador        | Santiago Emitola                               |
| Fecha de inicio      | 2025                                           |
| Versión documental   | v0.1.1                                         |
| Estado               | 🟡 En desarrollo                               |
| Fase actual          | Fase 4 — Implementación                        |
| Última actualización | 13 de octubre de 2026                          |

---

## Introducción

Este documento registra las decisiones técnicas, funcionales, metodológicas y de diseño tomadas durante el desarrollo de Raíces Alzate.

Su propósito es documentar por qué se seleccionaron determinadas tecnologías, estructuras, metodologías y soluciones, facilitando el mantenimiento, la evolución y la implementación de la plataforma.

Cada decisión incluye su fecha de registro, descripción, justificación y estado. Cuando corresponde, también se documentan las condiciones bajo las cuales una decisión podría revisarse.

Las decisiones forman parte del historial del proyecto y podrán actualizarse o complementarse conforme evolucionen sus necesidades.

> **Nota:** Las fechas registradas corresponden a sesiones y actualizaciones documentadas durante el desarrollo. El proyecto se encuentra en desarrollo desde 2025; el 9 de julio de 2026 corresponde a una fecha de consolidación documental, no al inicio del proyecto.

---

# Decisiones registradas

## DEC-001 | Uso de PHP como lenguaje backend

**Fecha:** 9 de julio de 2026

**Estado:** Vigente

### Decisión

El backend de Raíces Alzate se desarrollará utilizando PHP.

### Justificación

* Forma parte de las tecnologías trabajadas durante la formación.
* Permite integrar la aplicación con MySQL.
* Facilita el procesamiento de formularios y las operaciones del servidor.
* Es compatible con diferentes entornos de alojamiento web.
* Permite desarrollar la lógica de negocio sin incorporar un framework backend obligatorio.

---

## DEC-002 | Control de versiones con Git y GitHub

**Fecha:** 9 de julio de 2026

**Estado:** Vigente

### Decisión

El desarrollo del proyecto se gestionará mediante Git y se alojará en GitHub.

### Justificación

* Mantener un historial de cambios.
* Facilitar la recuperación de versiones anteriores.
* Organizar el desarrollo mediante commits.
* Permitir la creación y administración de ramas.
* Facilitar la revisión de modificaciones.
* Mantener un respaldo remoto del repositorio.

### Criterio de trabajo

Los cambios importantes deberán agruparse en commits con mensajes descriptivos. Cuando corresponda, se utilizarán ramas de desarrollo y Pull Requests para revisar e integrar las modificaciones en la rama principal.

Los commits y las integraciones se considerarán confirmados únicamente después de comprobar su estado en Git y GitHub.

---

## DEC-003 | Documentación en Markdown

**Fecha:** 9 de julio de 2026

**Estado:** Vigente

### Decisión

La documentación técnica y de seguimiento del proyecto se escribirá principalmente en archivos Markdown (`.md`).

### Justificación

* Es compatible con GitHub.
* Permite leer y editar la documentación fácilmente.
* Facilita mantener los documentos junto al código.
* Permite controlar los cambios mediante Git.
* Admite títulos, tablas, listas, enlaces y bloques de código.
* Facilita la actualización de los documentos durante el desarrollo.

---

## DEC-004 | Diseño previo a la implementación

**Fecha:** 9 de julio de 2026

**Estado:** Vigente

### Decisión

Las interfaces y los módulos principales se planificarán y diseñarán antes de avanzar en su implementación correspondiente.

### Justificación

* Reducir retrabajos.
* Identificar necesidades antes de programar.
* Mantener una interfaz visual consistente.
* Facilitar la organización del trabajo.
* Utilizar los diseños como referencia durante la implementación.
* Identificar los componentes reutilizables antes de construir las interfaces.

Esta decisión establece una metodología de trabajo, sin impedir que los diseños se ajusten cuando las pruebas o las necesidades técnicas lo justifiquen.

---

## DEC-005 | Metodología de trabajo por etapas

**Fecha:** 9 de julio de 2026

**Estado:** Vigente

### Decisión

El proyecto seguirá un proceso organizado por etapas:

1. Planificación.
2. Documentación.
3. Diseño UI/UX.
4. Implementación.
5. Pruebas y validación.
6. Actualización de la documentación.
7. Consolidación de cambios mediante Git.

### Justificación

Esta metodología permite mantener un desarrollo organizado, documentado y verificable, además de facilitar el seguimiento de los avances y la identificación de tareas pendientes.

Las etapas pueden desarrollarse de forma iterativa cuando la implementación revele necesidades de ajuste en el diseño o la arquitectura.

---

## DEC-006 | Uso de Figma para el diseño UI/UX

**Fecha:** 10 de julio de 2026

**Estado:** Vigente

### Decisión

Figma será la herramienta de referencia para el diseño de las interfaces y la planificación de la experiencia de usuario de Raíces Alzate.

### Justificación

* Permite diseñar las interfaces antes de implementarlas.
* Facilita organizar los componentes reutilizables.
* Permite definir dimensiones, distribución y jerarquía visual.
* Facilita documentar los estados de los componentes.
* Sirve como referencia para la implementación frontend.
* Permite planificar las versiones Desktop y Mobile.

---

## DEC-007 | Implementación de un Design System

**Fecha:** 10 de julio de 2026

**Estado:** Vigente

### Decisión

El proyecto utilizará un Design System como base para definir y mantener la identidad visual de la plataforma.

### Justificación

* Mantener la consistencia visual.
* Centralizar los criterios de diseño.
* Facilitar la reutilización de elementos.
* Reducir inconsistencias entre las pantallas.
* Establecer una referencia común para diseño e implementación.

### Elementos definidos

* Identidad visual.
* Paleta de colores.
* Tipografía.
* Jerarquía tipográfica.
* Sistema de espaciado.
* Botones.
* Componentes reutilizables.
* Estados de interacción.
* Criterios responsive.

### Referencias visuales principales

| Elemento                               | Valor     |
| -------------------------------------- | --------- |
| Color primario                         | `#D32F2F` |
| Hover del color primario               | `#B71C1C` |
| Active del color primario              | `#8E0000` |
| Color secundario                       | `#1565C0` |
| Texto principal                        | `#212121` |
| Texto secundario                       | `#757575` |
| Fondo claro                            | `#F5F5F5` |
| Blanco                                 | `#FFFFFF` |
| Borde                                  | `#E0E0E0` |
| Tipografía principal de las interfaces | Inter     |

---

## DEC-008 | Separación de fundamentos y componentes en Figma

**Fecha:** 20 de julio de 2026

**Estado:** Vigente

### Decisión

El archivo de Figma se organizará separando los fundamentos visuales de los componentes reutilizables.

La estructura contempla las páginas:

1. `Design System`.
2. `Components & Responsive`.

### Justificación

* Evitar sobrecargar una sola página.
* Mantener una organización clara.
* Facilitar la consulta de los componentes.
* Separar los fundamentos visuales de los elementos de interfaz.
* Aprovechar de manera organizada las limitaciones del plan de Figma utilizado.

---

## DEC-009 | Diseño responsive

**Fecha:** 5 de septiembre de 2026

**Estado:** Vigente

### Decisión

Las interfaces de Raíces Alzate se diseñarán considerando diferentes tamaños de pantalla.

### Justificación

* Proporcionar una experiencia adecuada en diferentes dispositivos.
* Facilitar la adaptación durante la implementación frontend.
* Mantener la consistencia visual en distintas resoluciones.
* Identificar anticipadamente los cambios necesarios entre Desktop y Mobile.
* Establecer criterios comunes para la construcción de interfaces adaptables.

Durante el diseño se utilizó un ancho de referencia de 390 px para las interfaces móviles.

La implementación deberá comprobarse posteriormente en diferentes anchos de pantalla para verificar su comportamiento responsive.

---

## DEC-010 | Componentes reutilizables y estados de interacción

**Fecha:** 5 de septiembre de 2026

**Estado:** Vigente

### Decisión

Los componentes de interfaz se diseñarán de forma reutilizable y contemplarán los estados de interacción que correspondan a su comportamiento.

### Estados considerados

* Normal.
* Selected.
* Hover.
* Focus.
* Disabled.
* Error.

No todos los componentes necesitan todos los estados. Su definición dependerá del tipo de elemento y de las interacciones previstas.

### Justificación

* Mantener la consistencia entre las interfaces.
* Evitar diseñar repetidamente los mismos elementos.
* Representar correctamente las situaciones de interacción.
* Facilitar la posterior implementación en HTML, CSS y JavaScript.
* Mejorar la claridad de los estados de los formularios.

---

## DEC-011 | No utilizar Auto Layout durante la etapa inicial de Figma

**Fecha:** 20 de julio de 2026

**Estado:** Vigente para el diseño realizado

### Decisión

Durante la construcción inicial del Design System y sus componentes en Figma no se utilizará Auto Layout.

### Justificación

* Mantener un proceso de construcción manual y controlado.
* Comprender las proporciones y la distribución de los componentes.
* Conservar el control directo sobre las posiciones de los elementos.
* Adaptar el procedimiento a las condiciones del archivo de diseño.

Esta decisión corresponde a la metodología utilizada durante la etapa de diseño. Podrá revisarse si las necesidades futuras de mantenimiento o evolución del archivo lo justifican.

---

## DEC-012 | Definición del flujo de información antes del diseño de pantallas

**Fecha:** septiembre de 2026

**Estado:** Vigente

### Decisión

Antes de consolidar las pantallas finales se definieron los recorridos principales y las relaciones entre los módulos de la plataforma.

### Justificación

* Comprender cómo se relacionan las diferentes secciones.
* Identificar los recorridos principales de los usuarios.
* Determinar las pantallas necesarias.
* Evitar diseñar interfaces sin conocer su función dentro del sistema.
* Utilizar la navegación como referencia para el diseño y la implementación.

### Flujo general definido

* Inicio → Autenticación → Dashboard.
* Dashboard → Perfil.
* Dashboard → Proyectos.
* Dashboard → Administración.
* Perfil → Egresados.
* Proyectos → Noticias.

Este esquema representa la estructura general documentada. Las rutas concretas y los permisos deberán definirse y verificarse durante la implementación.

---

## DEC-013 | Diseño de interfaces Desktop y Mobile

**Fecha:** septiembre de 2026

**Estado:** Vigente

### Decisión

Las interfaces principales se diseñarán considerando versiones Desktop y adaptaciones Mobile según las necesidades de cada pantalla.

### Justificación

* Proporcionar una experiencia coherente en diferentes dispositivos.
* Facilitar la implementación responsive.
* Identificar desde el diseño las diferencias de distribución entre resoluciones.
* Mantener una identidad visual consistente.
* Preparar referencias visuales para el desarrollo frontend.

Se completaron 17 pantallas Desktop y sus 17 adaptaciones Mobile, para un total de 34 pantallas diseñadas en Figma.

La finalización de la fase de diseño no significa que todas las interfaces hayan sido implementadas o que todas las resoluciones hayan sido validadas en código.

---

## DEC-014 | Uso de Inter como tipografía de las interfaces

**Fecha:** septiembre de 2026

**Estado:** Vigente

### Decisión

La tipografía principal de las interfaces implementadas será Inter, manteniendo una jerarquía visual coherente para títulos, textos y elementos de interacción.

### Justificación

* Favorecer la legibilidad en interfaces digitales.
* Mantener una apariencia limpia.
* Facilitar la lectura en diferentes tamaños de pantalla.
* Conservar la consistencia tipográfica entre las interfaces.

### Evolución de la decisión

Poppins se utilizó inicialmente para definir la jerarquía tipográfica del Design System. Posteriormente, Inter se adoptó como tipografía principal de las interfaces.

Esta evolución permite distinguir la referencia tipográfica inicial del sistema de diseño de la tipografía utilizada en la implementación.

---

## DEC-015 | Estructura visual simple y consistente

**Fecha:** septiembre de 2026

**Estado:** Vigente

### Decisión

Las interfaces de Raíces Alzate seguirán una estructura visual limpia, organizada y con suficiente espacio entre los elementos, evitando la sobrecarga de información.

### Justificación

* Facilitar la comprensión de las interfaces.
* Mejorar la lectura y la navegación.
* Evitar interfaces visualmente saturadas.
* Mantener una jerarquía clara de la información.
* Facilitar la adaptación del diseño a diferentes dispositivos.

---

## DEC-016 | Figma como referencia visual para la implementación

**Fecha:** septiembre de 2026

**Estado:** Vigente

### Decisión

Las interfaces consolidadas en Figma se utilizarán como referencia visual durante la implementación frontend.

### Justificación

* Mantener la correspondencia entre diseño y desarrollo.
* Reducir diferencias visuales durante la programación.
* Facilitar la construcción de estructuras HTML y estilos CSS.
* Utilizar las dimensiones, los colores, los estados y las estructuras definidas.
* Servir como referencia para la adaptación responsive.

Cuando las limitaciones técnicas requieran modificar una decisión de diseño, el cambio deberá evaluarse y documentarse para mantener la coherencia del proyecto.

---

## DEC-017 | Modularización de los estilos frontend

**Fecha:** 8 de octubre de 2026

**Estado:** Vigente

### Decisión

Los estilos CSS se organizarán de forma modular, separando los estilos generales de los estilos específicos de los componentes compartidos y de las pantallas que lo requieran.

### Estructura de estilos registrada inicialmente

```text
assets/
└── css/
    ├── main.css
    ├── fonts.css
    ├── header.css
    ├── footer.css
    ├── auth.css
    ├── portal.css
    ├── dashboard.css
    └── loader.css
```

### Responsabilidades previstas

* `main.css`: estilos generales, reglas compartidas y tokens visuales.
* `fonts.css`: centralización de la tipografía.
* `header.css`: estilos específicos del encabezado.
* `footer.css`: estilos específicos del pie de página.
* `auth.css`: estilos de las interfaces y componentes de autenticación.
* `portal.css`: estilos compartidos de las páginas públicas.
* `dashboard.css`: estilos del área privada.
* `loader.css`: estilos del cargador global.

### Justificación

* Evitar concentrar todos los estilos en un solo archivo.
* Facilitar el mantenimiento del código.
* Separar responsabilidades visuales.
* Reducir el riesgo de afectar componentes no relacionados.
* Facilitar el crecimiento progresivo del frontend.

La lista representa la organización registrada durante la implementación y deberá contrastarse con el repositorio antes de considerarla un inventario definitivo.

---

## DEC-018 | Componentes PHP reutilizables para elementos compartidos

**Fecha:** 8 de octubre de 2026

**Estado:** Vigente

### Decisión

Los elementos compartidos entre las páginas se implementarán mediante componentes PHP reutilizables, comenzando por el encabezado, el pie de página y los elementos de navegación del área privada.

### Justificación

* Evitar duplicar estructuras HTML.
* Mantener una navegación consistente.
* Facilitar futuras modificaciones de los elementos compartidos.
* Reutilizar la misma estructura entre distintas pantallas.
* Reducir errores derivados de mantener versiones duplicadas.
* Facilitar el mantenimiento del frontend.

### Consideración de arquitectura

El proyecto utiliza archivos dentro de `includes/` y `components/` para organizar los elementos compartidos.

La responsabilidad de cada archivo deberá mantenerse clara y sin duplicar innecesariamente la misma estructura o lógica. Antes de reorganizar estos directorios, se verificará su contenido y su uso real en el repositorio.

---

## DEC-019 | Uso de PHP, HTML, CSS y JavaScript vanilla

**Fecha:** 8 de octubre de 2026

**Estado:** Vigente

### Decisión

La implementación utilizará PHP, HTML, CSS y JavaScript vanilla, sin incorporar actualmente frameworks frontend como React o Vue.

### Justificación

* Mantener coherencia con las tecnologías del proyecto.
* Reducir la complejidad de la implementación.
* Facilitar la comprensión y el mantenimiento del código.
* Trasladar las estructuras diseñadas en Figma a HTML y CSS.
* Utilizar JavaScript para los comportamientos interactivos necesarios.
* Mantener una arquitectura adecuada al alcance actual.

La decisión podrá revisarse si las necesidades funcionales o técnicas justifican incorporar nuevas tecnologías.

---

## DEC-020 | Centralización de los estados y validaciones de formularios

**Fecha:** 8 de octubre de 2026

**Estado:** Vigente

### Decisión

Los formularios de autenticación utilizarán criterios visuales y funciones de validación compartidos para mantener un comportamiento consistente entre las diferentes pantallas.

### Aplicación

La implementación registrada contempla:

* Estados visuales para campos de formulario.
* Estados de botones de opción.
* Mensajes de error asociados a los campos.
* Limpieza reactiva de los errores.
* Validaciones en JavaScript.
* Procesamiento y validaciones en PHP.
* Prevención de envíos duplicados en los formularios correspondientes.

Entre las funciones auxiliares registradas se encuentran:

* `setInputError()`.
* `clearInputError()`.
* `setRadioGroupError()`.
* `clearRadioGroupError()`.

### Justificación

* Evitar comportamientos inconsistentes entre formularios.
* Reducir la duplicación de código.
* Facilitar la corrección de errores.
* Mantener coherencia visual con Figma.
* Mejorar la retroalimentación durante la interacción.

Las validaciones del frontend complementan las del backend, pero no las sustituyen.

---

## DEC-021 | Uso de PDO y consultas preparadas para el acceso a datos

**Fecha:** octubre de 2026

**Estado:** Aplicación registrada; integración general pendiente de validación

### Decisión

El acceso a la base de datos mediante PHP utilizará PDO y consultas preparadas en las operaciones correspondientes.

### Justificación

* Separar los datos recibidos de las instrucciones SQL.
* Reducir el riesgo de inyección SQL.
* Facilitar el manejo de errores de base de datos.
* Mantener una estructura consistente para las operaciones de persistencia.
* Facilitar la integración con MySQL.

### Alcance

El uso de PDO y consultas preparadas se encuentra registrado en los procesos de autenticación desarrollados.

La definición del esquema definitivo de la base de datos y la validación integral de las operaciones de la plataforma permanecen pendientes.

La presencia de PDO en el código no demuestra por sí sola que todas las operaciones sean seguras ni que la integración con la base de datos esté completa.

---

## DEC-022 | Almacenamiento seguro de contraseñas

**Fecha:** octubre de 2026

**Estado:** Implementación registrada; verificación integral pendiente

### Decisión

Las contraseñas se procesarán mediante las funciones de hashing seguras disponibles en PHP, utilizando `password_hash()` con `PASSWORD_DEFAULT`.

### Justificación

* Evitar almacenar contraseñas en texto plano.
* Utilizar un mecanismo de hashing diseñado para contraseñas.
* Aprovechar los mecanismos de seguridad proporcionados por PHP.
* Facilitar la verificación posterior mediante las funciones correspondientes.

### Consideraciones

* El sistema no debe almacenar contraseñas en texto plano.
* La verificación del inicio de sesión debe utilizar el mecanismo apropiado para comprobar las contraseñas.
* Las credenciales y otros datos sensibles no deben exponerse en mensajes de error ni en registros innecesarios.
* La implementación debe probarse de forma integral antes de considerar finalizado el módulo de autenticación.

---

## DEC-023 | Uso de respuestas genéricas en la recuperación de contraseña

**Fecha:** 9 de octubre de 2026

**Estado:** Implementación parcial

### Decisión

La pantalla de recuperación de contraseña utilizará una respuesta genérica para evitar revelar si una dirección de correo electrónico corresponde a una cuenta registrada.

### Justificación

* Reducir el riesgo de enumeración de cuentas.
* Evitar que una respuesta revele información sobre los usuarios registrados.
* Mantener un comportamiento uniforme en las solicitudes de recuperación.

### Respuesta prevista

> Si el correo electrónico ingresado coincide con una cuenta activa en la comunidad, recibirás las instrucciones correspondientes.

### Consideraciones

La implementación registrada contempla la generación de tokens de recuperación mediante `random_bytes(32)`.

Para completar el flujo se requiere verificar:

* El almacenamiento seguro de los tokens.
* Su expiración.
* Su validación durante el restablecimiento.
* Su uso único cuando corresponda.
* La integración con un servicio de correo electrónico.
* La respuesta del sistema ante tokens inválidos o vencidos.

La configuración SMTP permanece pendiente, por lo que no debe considerarse finalizado el envío efectivo de las instrucciones de recuperación.

---

## DEC-024 | Encabezado público compartido y persistente durante el desplazamiento

**Fecha:** octubre de 2026

**Estado:** Implementación reportada; validación visual pendiente

### Decisión

El portal público utilizará un encabezado compartido entre las páginas, con un comportamiento *sticky* para mantenerlo visible durante el desplazamiento.

### Criterios definidos durante la implementación

* Altura de referencia de 72 px.
* Fondo blanco.
* Borde inferior sutil.
* Logotipo y marca institucional.
* Navegación consistente entre las páginas públicas.
* Adaptación a diferentes tamaños de pantalla.
* Capa de visualización superior mediante `z-index`.
* Sombra suave cuando corresponda al diseño implementado.

### Justificación

* Mantener la identidad visual entre las páginas.
* Facilitar el acceso a la navegación principal.
* Reducir la duplicación del encabezado.
* Mejorar la continuidad de la experiencia de usuario.
* Facilitar el mantenimiento de los elementos compartidos.

### Consideraciones

Los informes de implementación registran diferentes valores para el ancho máximo del contenedor. Por tanto, ese valor deberá confirmarse en el código antes de establecerlo como una especificación definitiva.

El comportamiento *sticky* y las dimensiones deberán compararse con Figma y comprobarse en escritorio y móvil.

---

## DEC-025 | Barra lateral adaptable y colapsable para el Dashboard

**Fecha:** octubre de 2026

**Estado:** Implementación reportada; pruebas funcionales pendientes

### Decisión

La navegación del Dashboard utilizará una barra lateral que permita alternar entre un modo expandido y uno contraído en escritorio, y un panel desplegable en dispositivos móviles.

### Criterios de implementación registrados

* Ancho expandido de referencia: 240 px.
* Ancho contraído de referencia: 76 px.
* Botón de alternancia identificado como `#sidebarToggleBtn`.
* Persistencia de la preferencia mediante `localStorage`.
* Clave de almacenamiento: `sidebar_collapsed`.
* Ocultamiento de las etiquetas cuando la barra lateral está contraída.
* Conservación de los iconos de navegación.
* Panel desplegable y fondo de superposición en móvil.
* Adaptación según el tamaño de pantalla.

### Justificación

* Aprovechar mejor el espacio disponible en el Dashboard.
* Permitir que el usuario elija cuánto espacio ocupa la navegación.
* Mantener acceso a las secciones principales.
* Adaptar la navegación a las necesidades de escritorio y móvil.

### Consideraciones

La persistencia de preferencias visuales mediante `localStorage` no sustituye los controles de autorización del servidor.

La visibilidad de los enlaces deberá corresponder con los permisos reales del usuario, y el acceso directo a cada recurso privado deberá verificarse independientemente de la interfaz.

---

## DEC-026 | Barra superior móvil independiente para el Dashboard

**Fecha:** octubre de 2026

**Estado:** Implementación reportada

### Decisión

En pantallas móviles, el Dashboard utilizará una barra superior que muestre la información del usuario y un control para abrir la navegación.

### Criterios registrados

* Información del usuario y avatar a la izquierda.
* Nombre y rol cuando estén disponibles.
* Botón de menú hamburguesa a la derecha.
* Integración con el panel lateral desplegable.
* Adaptación a pantallas de hasta 768 px según los estilos registrados.

### Justificación

* Facilitar el acceso a la navegación en pantallas pequeñas.
* Mantener visibles los datos básicos de la sesión.
* Evitar que la barra lateral permanente ocupe demasiado espacio.
* Conservar una estructura coherente con el Dashboard de escritorio.

La distribución y el comportamiento definitivo deberán comprobarse durante las pruebas responsive.

---

## DEC-027 | Uso de un cargador global minimalista

**Fecha:** octubre de 2026

**Estado:** Implementación reportada; revisión técnica pendiente

### Decisión

La plataforma utilizará un cargador visual minimalista para indicar que una página está cargando, evitando una estructura de *Skeleton Screen* que pueda bloquear innecesariamente la interacción.

### Criterios registrados

* Fondo blanco semitransparente.
* Indicador circular de carga.
* Logotipo de Raíces Alzate.
* Mensaje «Cargando…».
* Uso de los colores institucionales.
* Desaparición automática cuando corresponda.
* Temporizador de seguridad para evitar bloqueos prolongados.

### Justificación

* Comunicar visualmente que existe una operación de carga.
* Mantener la coherencia con la identidad del proyecto.
* Evitar una interfaz de carga visualmente recargada.
* Reducir el riesgo de dejar controles inaccesibles después de la carga.

### Consideraciones

Durante la implementación se retiró una propuesta inicial basada en bloques grises y siluetas de contenido.

Se reportó la incorporación de lógica de desmontaje tanto en un script en línea como en `assets/js/loader.js`. Antes de considerar consolidada esta solución, deberá comprobarse que no exista lógica duplicada que produzca comportamientos inconsistentes.

También deberá verificarse que el cargador no impida utilizar los formularios ni acceder a los controles cuando la página esté lista.

---

## DEC-028 | Inicialización de sesiones antes de emitir contenido HTML

**Fecha:** octubre de 2026

**Estado:** Criterio vigente; aplicación integral pendiente de revisión

### Decisión

La inicialización de sesiones y las operaciones que puedan generar redirecciones o modificar cabeceras deberán realizarse antes de emitir contenido HTML.

### Justificación

* Evitar advertencias de PHP relacionadas con `Headers already sent`.
* Mantener el control del flujo de autenticación.
* Permitir que las redirecciones HTTP se procesen correctamente.
* Separar las responsabilidades de los controladores y las vistas.

### Criterios de implementación

* Inicializar la sesión en el punto de entrada o controlador correspondiente.
* Evitar llamar innecesariamente a `session_start()` desde vistas que ya se incluyen después de comenzar la respuesta.
* Comprobar el estado de la sesión antes de inicializarla nuevamente.
* Realizar las redirecciones antes de enviar contenido.
* Evitar que espacios, texto o marcas HTML involuntarias precedan a operaciones que necesitan modificar cabeceras.

### Consideraciones

Se reportaron ajustes en las páginas de inicio de sesión, registro y recuperación de contraseña.

La revisión deberá abarcar los controladores, el Front Controller y las vistas para comprobar que el flujo de sesiones sea consistente. Las comprobaciones de `session_status()` y `headers_sent()` no sustituyen una organización correcta del flujo de ejecución.

---

## DEC-029 | Entorno de desarrollo con acceso simulado

**Fecha:** octubre de 2026

**Estado:** Retirado definitivamente por seguridad; autenticación 100% mediante base de datos MySQL

### Decisión

Se elimina de forma permanente el archivo `dev-login.php` y todo acceso simulado. Toda autenticación en la plataforma debe validarse directamente contra la base de datos MySQL mediante consultas preparadas PDO, tokens CSRF y funciones de hash seguras (`password_hash` y `password_verify`).

### Implementación registrada

* Eliminación completa de `dev-login.php` y de su ruta en el Front Controller.
* Integración definitiva de `auth/login-process.php` con verificación de contraseñas hasheadas (`password_verify`).
* Regeneración de ID de sesión (`session_regenerate_id(true)`) contra Session Fixation.
* Inclusión de semilla administrativa segura en `database/setup.php` y `database/schema.sql`.
* Control estricto de roles (`hasRole('administrador')`) en áreas protegidas.

### Justificación

* Facilitar las pruebas visuales del área privada.
* Permitir revisar la navegación y los componentes del Dashboard.
* Reducir la dependencia de la base de datos durante determinadas tareas de maquetación.
* Separar las pruebas de interfaz de la validación de la autenticación real.

### Restricciones

* El acceso simulado no demuestra que el inicio de sesión real funcione.
* No debe utilizarse como mecanismo de autenticación en producción.
* Debe impedirse su uso en un entorno público.
* La sesión de prueba no debe otorgar privilegios reales por el simple hecho de existir.
* Antes de publicar la plataforma, debe comprobarse que los archivos y las rutas auxiliares estén eliminados, deshabilitados o protegidos adecuadamente.

---

## DEC-030 | Separación entre maquetación y autenticación real

**Fecha:** octubre de 2026

**Estado:** Vigente durante el desarrollo

### Decisión

La implementación visual de las pantallas y la validación de los procesos reales de autenticación se tratarán como actividades relacionadas, pero diferenciadas.

### Justificación

* Facilitar la construcción de interfaces antes de terminar la integración de datos.
* Permitir revisar los componentes visuales sin depender continuamente de servicios externos.
* Evitar confundir una vista maquetada con un flujo funcional completo.
* Mantener claridad sobre las tareas pendientes.
* Facilitar la identificación de errores de presentación, navegación, lógica de negocio y persistencia.

### Aplicación

Durante el desarrollo se reportaron redirecciones temporales desactivadas desde las pantallas de autenticación para facilitar las pruebas de maquetación.

Estas modificaciones son temporales y no deben considerarse el comportamiento definitivo del sistema.

### Condiciones para cerrar esta etapa

* Verificar el inicio de sesión contra la base de datos.
* Confirmar el tratamiento seguro de contraseñas.
* Verificar la creación y destrucción de sesiones.
* Comprobar las redirecciones según el estado de autenticación.
* Proteger los recursos privados.
* Validar el registro y la recuperación de contraseña.
* Retirar los accesos simulados antes de la publicación.

---

## DEC-031 | Validación progresiva de las interfaces implementadas

**Fecha:** octubre de 2026

**Estado:** Vigente

### Decisión

Las interfaces se validarán progresivamente frente a sus referencias de Figma durante la implementación.

### Justificación

* Detectar diferencias entre el diseño y el código.
* Corregir problemas de dimensiones, espaciado y alineación.
* Verificar la adaptación a distintos tamaños de pantalla.
* Evitar acumular diferencias visuales al final del desarrollo.
* Facilitar la identificación de problemas de CSS que afecten a varios componentes.

### Criterios de validación

* Comparar la estructura visual con Figma.
* Revisar la tipografía, los colores y el espaciado.
* Comprobar las dimensiones de los componentes.
* Verificar el comportamiento de botones, formularios y navegación.
* Revisar las interfaces en escritorio y móvil.
* Registrar las diferencias y los ajustes realizados.

La existencia de una pantalla implementada no implica que haya superado todas estas verificaciones.

---

## DEC-032 | Verificación antes de registrar un avance como completado

**Fecha:** octubre de 2026

**Estado:** Vigente

### Decisión

Los avances se documentarán distinguiendo entre cambios implementados, pruebas reportadas y resultados verificados directamente.

### Justificación

* Mantener una documentación fiable.
* Evitar presentar como terminado un flujo que todavía requiere pruebas.
* Diferenciar la implementación visual de la integración funcional.
* Conservar un historial claro de los cambios.
* Facilitar la preparación de commits y revisiones de código.

### Criterios

* Las modificaciones de código se registran como implementadas cuando existe evidencia del cambio.
* Las pruebas reportadas por herramientas se identifican como tales hasta que se confirmen cuando sea necesario.
* Los resultados funcionales se consideran validados después de ejecutar las pruebas correspondientes.
* Los commits y *pushes* se confirman mediante el estado del repositorio.
* Las funciones incompletas deben conservar sus pendientes documentados.

---

# Estado general de las decisiones

Las decisiones registradas establecen las principales bases metodológicas, visuales y técnicas de Raíces Alzate.

Durante las etapas anteriores se consolidaron la documentación, el Design System, los componentes reutilizables, la estructura de navegación y las referencias visuales en Figma.

La **Fase 3 — Diseño UI/UX** finalizó a finales de septiembre de 2026. Se completaron 17 pantallas Desktop y 17 adaptaciones Mobile. Estos diseños sirven como referencia para la implementación.

Actualmente, el proyecto se encuentra en la **Fase 4 — Implementación**, iniciada formalmente en octubre de 2026.

Durante esta etapa se han reportado avances en las páginas públicas, el Dashboard, los componentes compartidos, los estilos modulares, la navegación responsive, los formularios de autenticación y los mecanismos auxiliares de prueba.

Sin embargo, la implementación continúa en desarrollo. La autenticación integral, la definición definitiva de la base de datos, la recuperación completa de contraseñas y la validación de seguridad todavía requieren trabajo y comprobaciones.

## Próximas áreas de trabajo

* Validación visual de las interfaces frente a Figma.
* Implementación de las pantallas restantes.
* Consolidación de los componentes reutilizables.
* Definición y validación del modelo entidad-relación (MER) y del esquema definitivo de la base de datos.
* Integración del frontend con el backend y MySQL.
* Verificación de autenticación, sesiones y roles.
* Finalización del flujo de recuperación de contraseña.
* Configuración y comprobación del servicio de correo mediante SMTP.
* Revisión del cargador global y su comportamiento.
* Retiro de los mecanismos exclusivos de desarrollo antes de publicar.
* Pruebas funcionales, de seguridad, accesibilidad y diseño responsive.
* Actualización continua de la documentación.
* Confirmación de commits, ramas e integraciones en Git y GitHub.

Las decisiones podrán modificarse o ampliarse conforme avance el proyecto. Los cambios relevantes deberán quedar registrados para conservar el historial y la justificación de las decisiones técnicas.
