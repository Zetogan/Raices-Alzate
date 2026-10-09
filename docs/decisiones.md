# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 📘 Registro de Decisiones

## Información general

| Campo                | Información                                    |
| -------------------- | ---------------------------------------------- |
| Proyecto             | Raíces Alzate                                  |
| Institución          | Institución Educativa Gilberto Alzate Avendaño |
| Fecha de inicio      | 2025                                           |
| Versión documental   | v0.1.1                                         |
| Estado               | 🟡 En desarrollo                               |
| Fase actual          | Fase 4 — Implementación                        |
| Última actualización | 9 de octubre de 2026                           |

---

## Introducción

Este documento registra las decisiones técnicas, funcionales, metodológicas y de diseño tomadas durante el desarrollo de Raíces Alzate.

Su propósito es documentar por qué se seleccionaron determinadas tecnologías, estructuras, metodologías y soluciones, facilitando el mantenimiento, la evolución y la implementación de la plataforma.

Cada decisión incluye su fecha de registro, descripción y justificación. Cuando corresponde, también se documentan las estructuras establecidas y las condiciones bajo las cuales una decisión podría revisarse.

Las decisiones forman parte del historial del proyecto y podrán actualizarse o complementarse conforme evolucionen sus necesidades.

> **Nota:** Las fechas registradas corresponden a sesiones y actualizaciones documentadas durante el desarrollo. El proyecto se encuentra en desarrollo desde 2025.

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

El sistema de diseño contempla:

* Identidad visual.
* Paleta de colores.
* Tipografía.
* Jerarquía tipográfica.
* Sistema de espaciado.
* Botones.
* Componentes reutilizables.
* Estados de interacción.
* Criterios responsive.

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

Durante el diseño se utilizó un ancho de referencia de 390 px para las interfaces móviles trabajadas.

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

Se desarrollaron las principales interfaces Desktop y se trabajaron adaptaciones Mobile para las pantallas priorizadas.

La finalización de la fase de diseño no significa que todas las interfaces móviles estén terminadas o que todas las resoluciones hayan sido validadas en código.

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

Los estilos CSS se organizarán de forma modular, separando los estilos generales de los estilos específicos de los componentes compartidos.

### Estructura de estilos registrada

```text
assets/
└── css/
    ├── main.css
    ├── fonts.css
    ├── header.css
    ├── footer.css
    └── auth.css
```

### Responsabilidades

* `main.css`: estilos generales, reglas compartidas y tokens visuales.
* `fonts.css`: centralización de la tipografía.
* `header.css`: estilos específicos del encabezado.
* `footer.css`: estilos específicos del pie de página.
* `auth.css`: estilos de las interfaces y componentes de autenticación.

### Justificación

* Evitar concentrar todos los estilos en un solo archivo.
* Facilitar el mantenimiento del código.
* Separar responsabilidades visuales.
* Reducir el riesgo de afectar componentes no relacionados.
* Facilitar el crecimiento progresivo del frontend.

La estructura podrá ampliarse cuando el número de pantallas y componentes justifique nuevos archivos.

---

## DEC-018 | Componentes PHP reutilizables para elementos compartidos

**Fecha:** 8 de octubre de 2026
**Estado:** Vigente

### Decisión

Los elementos compartidos entre las páginas se implementarán mediante componentes PHP reutilizables, comenzando por el Header y el Footer.

### Justificación

* Evitar duplicar estructuras HTML.
* Mantener una navegación consistente.
* Facilitar futuras modificaciones de los elementos compartidos.
* Reutilizar la misma estructura entre distintas pantallas.
* Reducir errores derivados de mantener versiones duplicadas.
* Facilitar el mantenimiento del frontend.

### Consideración de arquitectura

Los componentes compartidos se integran mediante la estructura modular existente del proyecto.

La ubicación definitiva de cada archivo debe corresponder con la estructura real del repositorio. Antes de ampliar o reorganizar los componentes, se verificará si su ubicación es `includes/`, `components/` o una combinación de ambas según sus responsabilidades.

---

## DEC-019 | Uso de PHP, HTML, CSS y JavaScript vanilla

**Fecha:** 8 de octubre de 2026
**Estado:** Vigente

### Decisión

La implementación frontend utilizará PHP, HTML, CSS y JavaScript vanilla, sin incorporar actualmente frameworks frontend como React o Vue.

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
**Estado:** Aplicado en los procesos registrados; sujeto a revisión integral

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

La definición del esquema definitivo de la base de datos y la validación integral de las operaciones de la plataforma permanecen sujetas al avance de la implementación.

---

## DEC-022 | Almacenamiento seguro de contraseñas

**Fecha:** octubre de 2026
**Estado:** Aplicado en el registro; pendiente de verificación integral

### Decisión

Las contraseñas se procesarán mediante las funciones de hashing seguras disponibles en PHP, utilizando `password_hash()` con `PASSWORD_DEFAULT`.

### Justificación

* Evitar almacenar contraseñas en texto plano.
* Utilizar un mecanismo de hashing diseñado para contraseñas.
* Aprovechar los parámetros de seguridad proporcionados por PHP.
* Facilitar la verificación posterior de las contraseñas mediante las funciones correspondientes.

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

### Respuesta registrada

> Si el correo electrónico ingresado coincide con una cuenta activa en la comunidad, recibirás las instrucciones correspondientes.

### Consideraciones

La implementación registrada contempla la generación de tokens de recuperación mediante `random_bytes(32)`.

Para completar el flujo se requiere verificar:

* El almacenamiento seguro de los tokens.
* Su expiración.
* Su validación durante el restablecimiento.
* El uso único de los tokens cuando corresponda.
* La integración con un servicio de correo electrónico.
* La respuesta del sistema ante tokens inválidos o vencidos.

La configuración SMTP permanece pendiente, por lo que no debe considerarse finalizado el envío efectivo de las instrucciones de recuperación.

---

# Estado general de las decisiones

Las decisiones registradas establecen las principales bases metodológicas, visuales y técnicas de Raíces Alzate.

Durante las etapas anteriores se consolidaron la documentación, el Design System, los componentes reutilizables, la estructura de navegación y las principales referencias de diseño en Figma.

La **Fase 3 — Diseño UI/UX** finalizó a finales de septiembre de 2026. Sus resultados se utilizan como referencia para la implementación.

Actualmente, el proyecto se encuentra en la **Fase 4 — Implementación**, iniciada formalmente en octubre de 2026.

Durante esta etapa se han desarrollado la estructura inicial de Inicio, los componentes compartidos, los estilos modulares y las primeras interfaces de autenticación.

## Próximas áreas de trabajo

* Validación visual de las interfaces frente a Figma.
* Implementación progresiva de las pantallas restantes.
* Consolidación de los componentes reutilizables.
* Definición y validación del esquema definitivo de la base de datos.
* Integración del frontend con el backend y MySQL.
* Verificación de autenticación, sesiones y roles.
* Finalización del flujo de recuperación de contraseña.
* Pruebas funcionales, de seguridad y de accesibilidad.
* Actualización continua de la documentación.

Las decisiones podrán modificarse o ampliarse conforme avance el proyecto. Los cambios relevantes deberán quedar registrados para conservar el historial y la justificación de las decisiones técnicas.
