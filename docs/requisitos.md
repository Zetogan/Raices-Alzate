# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 📑 Requisitos del Proyecto

## 1. Información general

| Campo                           | Información                                    |
| ------------------------------- | ---------------------------------------------- |
| Proyecto                        | Raíces Alzate                                  |
| Institución                     | Institución Educativa Gilberto Alzate Avendaño |
| Versión documental              | `v0.2.1`                                       |
| Estado                          | 🟡 En desarrollo                               |
| Fase actual                     | Fase 4 — Implementación e integración funcional|
| Inicio del proyecto             | 2025                                           |
| Última actualización            | 17 de octubre de 2026                          |
| Responsable de la documentación | Santiago Emitola                               |

---

## 2. Introducción

Este documento reúne los requisitos funcionales y no funcionales que definen el comportamiento, las características y las condiciones de funcionamiento de la plataforma Raíces Alzate.

Los requisitos sirven como referencia durante las etapas de implementación, integración y pruebas. Su propósito es mantener una relación clara entre las necesidades del proyecto, las funcionalidades previstas y los criterios que permitirán comprobar su correcto funcionamiento.

El proyecto se encuentra en desarrollo desde 2025. Las fases de preparación, planificación y documentación base se encuentran completadas, mientras que la fase de diseño UI/UX finalizó a finales de septiembre de 2026.

Durante el diseño se definieron la identidad visual, el Design System, los componentes reutilizables, los criterios responsive, la estructura de navegación y las interfaces de referencia en Figma.

Actualmente, el proyecto se encuentra en la **Fase 4 — Implementación**, iniciada en octubre de 2026. Los trabajos de desarrollo han comenzado con la página de Inicio, la estructura compartida de la aplicación y las interfaces iniciales de autenticación, registro y recuperación de contraseña. También se han reportado avances en la estructura del dashboard y en otros elementos compartidos de la interfaz.

Este documento distingue entre los requisitos definidos, los avances de implementación reportados y las funcionalidades que todavía requieren integración, pruebas o validación. Las funcionalidades no deberán considerarse finalizadas únicamente por disponer de una interfaz visual o de una implementación inicial.

---

# 3. Requisitos funcionales

Los requisitos funcionales describen las acciones y los servicios que deberá ofrecer la plataforma.

## 3.1. RF-01 — Módulo de Inicio

La plataforma deberá:

* RF-01.01: Mostrar la página principal del proyecto.
* RF-01.02: Presentar información sobre el propósito de la plataforma.
* RF-01.03: Mostrar el acceso al inicio de sesión.
* RF-01.04: Mostrar el acceso al registro de usuarios.
* RF-01.05: Presentar los principales beneficios y oportunidades de la comunidad.
* RF-01.06: Facilitar la navegación hacia las secciones públicas disponibles.
* RF-01.07: Mantener una estructura visual coherente con el diseño aprobado en Figma.
* RF-01.08: Adaptar la navegación y los contenidos a diferentes tamaños de pantalla.
* RF-01.09: Incorporar los elementos de identidad visual establecidos para el proyecto.
* RF-01.10: Mantener una estructura de encabezado y pie de página coherente con las demás páginas públicas.

### Estado de implementación

* 🟢 Estructura inicial del frontend implementada.
* 🟢 Encabezado y pie de página compartidos incorporados.
* 🟢 Navegación responsive y menú móvil implementados.
* 🟢 Isotipo y favicon incorporados.
* 🟢 Interfaz Desktop desarrollada tomando como referencia el diseño de 1440 px.
* 🟢 Adaptación inicial Mobile desarrollada tomando como referencia el diseño de 390 px.
* 🟡 Revisión visual completa frente a Figma pendiente.
* 🟡 Validación completa en diferentes tamaños de pantalla pendiente.
* 🟡 Revisión final de accesibilidad y comportamiento de los elementos interactivos pendiente.

> Los estados reflejan los avances de implementación reportados. La aceptación definitiva requiere comprobar la interfaz en ejecución y contrastarla con los criterios de diseño aprobados.

---

## 3.2. RF-02 — Registro de usuarios

La plataforma deberá:

* RF-02.01: Permitir el registro de nuevos usuarios.
* RF-02.02: Solicitar los datos personales requeridos para crear una cuenta.
* RF-02.03: Solicitar un correo electrónico válido.
* RF-02.04: Permitir establecer una contraseña.
* RF-02.05: Solicitar la confirmación de la contraseña cuando corresponda al formulario definido.
* RF-02.06: Validar los campos obligatorios.
* RF-02.07: Verificar que el correo electrónico no esté registrado previamente.
* RF-02.08: Validar la información en el cliente y en el servidor, según corresponda.
* RF-02.09: Almacenar las contraseñas mediante mecanismos de hash seguros.
* RF-02.10: Mostrar mensajes de retroalimentación cuando el registro sea aceptado o se produzca un error.
* RF-02.11: Asociar el tipo de usuario correspondiente cuando el sistema lo requiera.
* RF-02.12: Evitar la creación de cuentas con información inválida o duplicada.
* RF-02.13: Impedir que usuarios no autorizados asignen privilegios elevados durante el registro.

### Estado de implementación

* 🟢 Interfaz inicial de registro implementada.
* 🟢 Procesamiento inicial del formulario reportado.
* 🟢 Validaciones y comprobación de correo duplicado reportadas.
* 🟢 Uso de `password_hash()` reportado para el tratamiento de contraseñas.
* 🟡 Integración completa con la base de datos por verificar.
* 🟡 Comprobación de las reglas de registro y los tipos de usuario pendiente.
* 🟡 Pruebas funcionales y de seguridad pendientes.

> Los campos definitivos del registro deberán coincidir con el formulario implementado y con las decisiones funcionales del proyecto. No se debe asumir que todas las validaciones, restricciones y reglas de asignación de roles están completamente integradas sin verificar el código.

---

## 3.3. RF-03 — Inicio y cierre de sesión

La plataforma deberá:

* RF-03.01: Permitir el inicio de sesión mediante las credenciales registradas.
* RF-03.02: Validar las credenciales en el servidor.
* RF-03.03: Rechazar las credenciales incorrectas.
* RF-03.04: Permitir el cierre de sesión.
* RF-03.05: Mantener la sesión activa mientras se cumplan las condiciones de seguridad definidas.
* RF-03.06: Restringir las funcionalidades privadas a los usuarios autenticados.
* RF-03.07: Proporcionar mensajes de retroalimentación adecuados.
* RF-03.08: Evitar que los mensajes de error revelen información sensible.
* RF-03.09: Controlar el acceso a las páginas protegidas.
* RF-03.10: Verificar los permisos del usuario antes de ejecutar operaciones restringidas.
* RF-03.11: Impedir que una sesión finalizada permita continuar accediendo a funcionalidades privadas.

### Estado de implementación

* 🟢 Interfaz inicial de inicio de sesión implementada.
* 🟢 Procesamiento inicial de credenciales reportado.
* 🟢 Validaciones y mensajes de retroalimentación incorporados según los avances reportados.
* 🟢 Funcionalidad de cierre de sesión reportada.
* 🟡 Verificación integral de autenticación pendiente.
* 🟡 Integración definitiva con las credenciales almacenadas en MySQL por verificar.
* 🟡 Gestión completa y segura de sesiones pendiente de validación.
* 🟡 Protección de todas las rutas privadas por verificar.
* 🟡 Pruebas de seguridad pendientes.

> El cierre de sesión y las simulaciones de acceso utilizadas durante el desarrollo no demuestran por sí solos que la autenticación definitiva esté terminada. Las rutas privadas deberán comprobar los permisos en el servidor y no depender únicamente de la navegación o de la visibilidad de los elementos de interfaz.

---

## 3.4. RF-04 — Recuperación de contraseña

La plataforma deberá:

* RF-04.01: Permitir solicitar la recuperación de una contraseña.
* RF-04.02: Solicitar el correo electrónico asociado a la cuenta.
* RF-04.03: Validar el formato del correo electrónico.
* RF-04.04: Generar un mecanismo seguro de recuperación cuando corresponda.
* RF-04.05: Establecer una vigencia limitada para los mecanismos de recuperación.
* RF-04.06: Verificar la validez del mecanismo antes de permitir el cambio de contraseña.
* RF-04.07: Permitir establecer una nueva contraseña mediante un flujo seguro.
* RF-04.08: Evitar que un mecanismo de recuperación pueda reutilizarse cuando deba ser de un solo uso.
* RF-04.09: Mostrar una respuesta genérica que no permita determinar si una cuenta existe.
* RF-04.10: Informar al usuario sobre los pasos disponibles para completar la recuperación.
* RF-04.11: Invalidar los mecanismos de recuperación cuando corresponda después de completar el proceso.
* RF-04.12: Evitar que los tokens de recuperación queden expuestos en registros, mensajes de error o ubicaciones no protegidas.

### Estado de implementación

* 🟢 Interfaz de solicitud y restablecimiento (`pages/reset-password.php`) alineadas con Figma.
* 🟢 Validación de formato de correo y longitud de contraseña (mínimo 8 caracteres) en frontend.
* 🟢 Generación de tokens criptográficos pseudoaleatorios con `random_bytes(32)`.
* 🟢 Almacenamiento seguro del hash SHA-256 en las columnas `reset_token` y `reset_expira` con caducidad estricta a 60 minutos.
* 🟢 Integración de PHPMailer y SMTP autenticado (`config/mail.php`, `includes/mailer.php`, `includes/PHPMailer/`).
* 🟢 Plantilla HTML responsiva con logotipo institucional embebido mediante CID Inline (`logo-raices-alzate.png`).
* 🟢 Alternador interactivo para visualizar u ocultar la clave (ícono de ojo).
* 🟢 Hashing seguro de la nueva contraseña con `password_hash(PASSWORD_DEFAULT)`.
* 🟢 Invalidación inmediata del token tras su uso para evitar ataques de repetición.
* 🟡 Pruebas con servidor SMTP en entorno de producción por verificar.

---

## 3.5. RF-05 — Usuarios y perfiles

La plataforma deberá:

* RF-05.01: Permitir consultar la información asociada al perfil del usuario.
* RF-05.02: Permitir editar los datos personales autorizados.
* RF-05.03: Permitir actualizar la información profesional y académica cuando corresponda.
* RF-05.04: Permitir cambiar la fotografía de perfil, si esta funcionalidad forma parte del alcance definitivo.
* RF-05.05: Mantener actualizada la información de contacto.
* RF-05.06: Mostrar la información del usuario en las secciones correspondientes.
* RF-05.07: Restringir la modificación del perfil a su propietario o a los usuarios autorizados.
* RF-05.08: Validar los datos antes de almacenarlos.
* RF-05.09: Controlar los archivos cargados como fotografías de perfil, si se habilita esta función.
* RF-05.10: Evitar que un usuario modifique información privada de otros usuarios sin autorización.

### Estado de implementación

* 🟢 Interfaz de perfil (`pages/perfil.php`) con métricas, banner institucional y estética Figma.
* 🟢 Formulario modular de edición (`pages/editar-perfil.php` / `assets/css/perfil-form.css`) con 4 bloques temáticos.
* 🟢 Tablas relacionales correspondientes formalizadas en `database/schema.sql` (`usuarios`, `perfiles`, `educacion`, `experiencia`, `habilidades`, `perfil_habilidades`).
* 🟡 Persistencia dinámica completa mediante consultas PDO en fase de pruebas integrales.

---

## 3.6. RF-06 — Gestión de egresados

La plataforma deberá:

* RF-06.01: Registrar información de egresados.
* RF-06.02: Consultar los perfiles disponibles según los permisos del usuario.
* RF-06.03: Buscar egresados mediante criterios definidos.
* RF-06.04: Filtrar los resultados mediante los campos disponibles.
* RF-06.05: Visualizar información relevante de los perfiles.
* RF-06.06: Mantener actualizada la información académica y profesional.
* RF-06.07: Restringir la consulta de información según los permisos y las reglas de privacidad.
* RF-06.08: Facilitar el seguimiento institucional de la comunidad de egresados.
* RF-06.09: Evitar la exposición de datos personales que no deban ser públicos.

### Estado de implementación

* 🟢 Directorio interactivo de egresados implementado (`pages/egresados.php`, `includes/egresados-privado.php`).
* 🟢 Filtros en tiempo real por promoción, área y estado de conexión en JavaScript (`assets/js/egresados.js`).
* 🟢 Vista de detalle de egresado (`pages/detalle-egresado.php`) simétrica a perfil.
* 🟢 Botones condicionales («Conectar» interactivo vs. «Enviar mensaje» hacia chat privado).
* 🟢 Notificación toast flotante `#egresadoToast` para confirmación de acciones en vivo.
* 🟢 Endpoints API JSON para solicitudes (`api/conectar-egresado.php`, `api/responder-solicitud.php`).
* 🟡 Pruebas integrales de persistencia en tabla `conexiones` en curso.

---

## 3.7. RF-07 — Gestión de proyectos

La plataforma deberá:

* RF-07.01: Permitir crear proyectos.
* RF-07.02: Permitir publicar proyectos.
* RF-07.03: Consultar proyectos publicados.
* RF-07.04: Visualizar el detalle de cada proyecto.
* RF-07.05: Permitir editar proyectos a los usuarios autorizados.
* RF-07.06: Permitir eliminar proyectos conforme a las reglas de permisos.
* RF-07.07: Asociar cada proyecto con su autor o autores correspondientes.
* RF-07.08: Validar los datos del proyecto antes de almacenarlos.
* RF-07.09: Controlar la publicación y modificación de proyectos.
* RF-07.10: Gestionar las imágenes y otros archivos asociados, cuando corresponda.
* RF-07.11: Evitar que usuarios sin autorización modifiquen o eliminen proyectos mediante solicitudes directas.

### Estado de implementación

* 🟢 Catálogo de proyectos implementado (`pages/proyectos.php`) con cuadrícula simétrica de 3 columnas en escritorio.
* 🟢 Portadas en relación 16:9 y autoría institucional normalizada.
* 🟢 Vista de detalle de proyecto (`pages/detalle-proyecto.php`).
* 🟢 Interfaces de creación y edición (`pages/crear-proyecto.php`, `pages/editar-proyecto.php`).
* 🟢 Paginación incremental asíncrona mediante AJAX / Fetch (`assets/js/proyectos.js`).
* 🟢 Estructura DDL en `database/schema.sql` (tabla `proyectos`).
* 🟡 Pruebas integrales de persistencia con PDO en curso.

---

## 3.8. RF-08 — Gestión de noticias

La plataforma deberá:

* RF-08.01: Permitir crear noticias.
* RF-08.02: Publicar noticias para su consulta.
* RF-08.03: Mostrar el listado de noticias disponibles.
* RF-08.04: Visualizar el detalle de cada noticia.
* RF-08.05: Permitir editar noticias a los usuarios autorizados.
* RF-08.06: Permitir eliminar noticias conforme a las reglas de permisos.
* RF-08.07: Validar el contenido antes de publicarlo.
* RF-08.08: Gestionar los datos y recursos asociados a las publicaciones.
* RF-08.09: Restringir la publicación y modificación a los usuarios autorizados.
* RF-08.10: Evitar la publicación de contenido mediante solicitudes no autorizadas.

### Estado de implementación

* 🟢 Vista de noticias institucionales implementada (`pages/noticias.php`).
* 🟢 Selector interactivo de categorías temáticas y filtrado en cliente (`assets/js/noticias.js`).
* 🟢 Vista de detalle de noticia (`pages/detalle-noticia.php`) y creación (`pages/crear-noticia.php`).
* 🟢 Tabla `noticias` formalizada en `database/schema.sql`.
* 🟡 Pruebas de persistencia con base de datos en curso.

---

## 3.9. RF-09 — Panel administrativo y moderación

La plataforma deberá:

* RF-09.01: Permitir el acceso al panel administrativo únicamente a usuarios autorizados.
* RF-09.02: Gestionar usuarios.
* RF-09.03: Gestionar información de egresados.
* RF-09.04: Gestionar proyectos.
* RF-09.05: Gestionar noticias.
* RF-09.06: Administrar los permisos que correspondan al rol autorizado.
* RF-09.07: Controlar las operaciones administrativas.
* RF-09.08: Mostrar la información administrativa disponible.
* RF-09.09: Validar las operaciones antes de ejecutarlas.
* RF-09.10: Impedir que usuarios sin permisos ejecuten operaciones administrativas mediante solicitudes directas.
* RF-09.11: Registrar las operaciones administrativas relevantes cuando las necesidades del sistema lo requieran.

### Estado de implementación

* 🟢 Pantalla oficial de administración implementada (`pages/administracion.php` / `admin.php`).
* 🟢 Tarjetas de métricas KPI interactivas (Registros pendientes, contenidos en revisión, PQRS por resolver, egresados verificados).
* 🟢 Pestañas de moderación: Aprobación de Registros, Moderación de Contenidos y Resolución de PQRS.
* 🟢 Modales interactivos (`modalRegistro`, `modalContenido`, `modalPQRS`) y toast `#adminToast`.
* 🟢 Estilos completos conforme al diseño de Figma (`assets/css/administracion.css`).
* 🟡 Verificación integral de permisos y persistencia administrativa en base de datos.

---

## 3.10. RF-10 — Red de contactos y mensajería privada

La plataforma deberá:

* RF-10.01: Permitir el envío de solicitudes de conexión institucional entre egresados.
* RF-10.02: Gestionar estados de vinculación (pendiente, aceptada, rechazada).
* RF-10.03: Habilitar un canal de chat privado entre usuarios conectados.
* RF-10.04: Almacenar el historial de conversaciones y mensajes con marca temporal.
* RF-10.05: Ofrecer endpoints API para sincronización asíncrona de mensajes.

### Estado de implementación

* 🟢 Interfaz de mensajería implementada (`pages/mensajes.php` / `assets/css/mensajes.css` / `assets/js/mensajes.js`).
* 🟢 Endpoints API JSON implementados (`api/conectar-egresado.php`, `api/responder-solicitud.php`, `api/enviar-mensaje.php`, `api/obtener-mensajes.php`).
* 🟢 Tablas relacionales `conexiones` y `mensajes` formalizadas en `database/schema.sql`.
* 🟡 Pruebas integrales de flujo en tiempo real y sanitización XSS en curso.

---

## 3.11. RF-11 — Módulo de PQRS y soporte ciudadano

La plataforma deberá:

* RF-11.01: Disponer de un formulario para registrar Peticiones, Quejas, Reclamos, Sugerencias y reportes de errores.
* RF-11.02: Permitir la adjunción opcional de capturas de pantalla o evidencias.
* RF-11.03: Generar número de radicado para seguimiento de la solicitud.
* RF-11.04: Permitir al administrador gestionar y responder solicitudes institucionales.

### Estado de implementación

* 🟢 Formulario de PQRS implementado (`pages/pqr.php` / `assets/css/pqr.css` / `assets/js/pqr.js`).
* 🟢 Dropzone interactivo para arrastrar y soltar capturas de pantalla.
* 🟢 Corrección de visibilidad del ícono SVG en el sidebar en blanco puro (`#FFFFFF`).
* 🟢 Pestaña de resolución de PQRS integrada en el panel administrativo (`pages/administracion.php`).
* 🟡 Integración con almacenamiento persistente de radicados en fase de pruebas.

---

# 4. Requisitos no funcionales

Los requisitos no funcionales establecen las condiciones de calidad, seguridad, rendimiento, compatibilidad y mantenimiento que deberá cumplir la plataforma.

## 4.1. RNF-01 — Rendimiento

* RNF-01.01: La plataforma deberá cargar de manera eficiente.
* RNF-01.02: Las imágenes deberán optimizarse para reducir los tiempos de carga.
* RNF-01.03: Se deberán evitar recursos innecesarios.
* RNF-01.04: Las consultas a la base de datos deberán diseñarse de manera eficiente.
* RNF-01.05: El código deberá evitar operaciones innecesarias que afecten el rendimiento.
* RNF-01.06: Los recursos frontend deberán mantenerse organizados para facilitar su mantenimiento y optimización.
* RNF-01.07: Las operaciones del servidor deberán manejar los errores sin provocar interrupciones evitables.
* RNF-01.08: Los recursos de terceros, cuando existan, deberán incorporarse únicamente si aportan valor al funcionamiento de la plataforma.

Los objetivos cuantitativos de rendimiento deberán definirse y comprobarse durante las pruebas, una vez que existan módulos funcionales.

---

## 4.2. RNF-02 — Seguridad

* RNF-02.01: Las contraseñas deberán almacenarse mediante mecanismos de hash seguros.
* RNF-02.02: Los datos recibidos deberán validarse antes de procesarse o almacenarse.
* RNF-02.03: Se deberán proteger las sesiones de los usuarios.
* RNF-02.04: Se deberá implementar control de acceso basado en los permisos definidos.
* RNF-02.05: Se deberán prevenir accesos no autorizados a información y funcionalidades.
* RNF-02.06: Las funcionalidades administrativas deberán estar restringidas a usuarios autorizados.
* RNF-02.07: Se deberán aplicar validaciones en el cliente y en el servidor cuando corresponda.
* RNF-02.08: Las operaciones de base de datos deberán utilizar consultas preparadas cuando corresponda.
* RNF-02.09: Los mensajes de error no deberán exponer contraseñas, tokens, credenciales ni detalles internos sensibles.
* RNF-02.10: Los mecanismos de recuperación de contraseña deberán utilizar tokens seguros, con vigencia limitada y validación en el servidor.
* RNF-02.11: Los secretos y las credenciales de conexión no deberán publicarse en el repositorio.
* RNF-02.12: Se deberán validar los permisos en el servidor, sin depender únicamente de la visibilidad de botones o elementos de interfaz.
* RNF-02.13: Los archivos cargados deberán validarse de acuerdo con su tipo, tamaño y uso permitido.
* RNF-02.14: La configuración de producción deberá evitar la exposición de errores internos.
* RNF-02.15: Se deberán revisar las solicitudes que modifican datos para aplicar las protecciones pertinentes, incluido el control de solicitudes entre sitios cuando corresponda.
* RNF-02.16: Las herramientas de acceso simulado o de desarrollo deberán quedar deshabilitadas o excluidas del entorno de producción.
* RNF-02.17: La aplicación deberá regenerar el identificador de sesión después de una autenticación satisfactoria cuando corresponda.
* RNF-02.18: El cierre de sesión deberá invalidar la sesión del lado del servidor y eliminar las cookies de sesión aplicables.

---

## 4.3. RNF-03 — Compatibilidad

La plataforma deberá funcionar correctamente en versiones compatibles de los principales navegadores web, incluyendo:

* Google Chrome.
* Microsoft Edge.
* Mozilla Firefox.

También deberá ofrecer una experiencia adecuada en dispositivos móviles, tablets y computadores de escritorio.

Las versiones concretas de los navegadores y los dispositivos de prueba se definirán durante la validación.

---

## 4.4. RNF-04 — Diseño y experiencia de usuario

* RNF-04.01: La interfaz deberá ser intuitiva y fácil de utilizar.
* RNF-04.02: El diseño deberá ser responsive.
* RNF-04.03: La interfaz deberá mantener una identidad visual coherente.
* RNF-04.04: Los componentes deberán conservar consistencia visual.
* RNF-04.05: Los elementos interactivos deberán contemplar los estados necesarios.
* RNF-04.06: La interfaz deberá considerar criterios básicos de accesibilidad.
* RNF-04.07: El diseño deberá mantener una jerarquía visual clara.
* RNF-04.08: Los elementos de navegación deberán ser fáciles de identificar.
* RNF-04.09: Las diferentes pantallas deberán mantener una estructura visual consistente.
* RNF-04.10: La experiencia de navegación deberá ser coherente entre los módulos.
* RNF-04.11: Las interfaces implementadas deberán corresponder a los diseños aprobados en Figma.
* RNF-04.12: Los formularios deberán ofrecer instrucciones y mensajes de validación comprensibles.
* RNF-04.13: Los controles deberán mantener una presentación legible en diferentes tamaños de pantalla.
* RNF-04.14: Los elementos compartidos deberán conservar un comportamiento coherente entre las páginas que los utilicen.

---

## 4.5. RNF-05 — Accesibilidad

* RNF-05.01: Se deberá utilizar HTML semántico cuando corresponda.
* RNF-05.02: Los campos de formulario deberán disponer de etiquetas comprensibles.
* RNF-05.03: Los controles interactivos deberán poder utilizarse mediante teclado cuando corresponda.
* RNF-05.04: Los estados de error no deberán comunicarse únicamente mediante el color.
* RNF-05.05: Se deberán mantener contrastes adecuados entre texto y fondo.
* RNF-05.06: Las imágenes informativas deberán contar con alternativas textuales apropiadas.
* RNF-05.07: Los mensajes de validación deberán poder ser identificados por tecnologías de asistencia cuando corresponda.
* RNF-05.08: Los elementos interactivos deberán mostrar estados de foco identificables.
* RNF-05.09: Los controles móviles deberán tener un tamaño y una separación que faciliten su utilización.

---

## 4.6. RNF-06 — Mantenibilidad

* RNF-06.01: El código deberá mantenerse organizado.
* RNF-06.02: La arquitectura deberá facilitar futuras modificaciones.
* RNF-06.03: Se utilizarán componentes reutilizables cuando corresponda.
* RNF-06.04: Git se utilizará para el control de versiones.
* RNF-06.05: GitHub se utilizará para gestionar el repositorio.
* RNF-06.06: La documentación deberá actualizarse conforme avance el proyecto.
* RNF-06.07: Los cambios relevantes deberán registrarse en la documentación y en el historial de versiones.
* RNF-06.08: Los estilos deberán organizarse por responsabilidades.
* RNF-06.09: Los elementos compartidos de la interfaz deberán implementarse mediante componentes PHP reutilizables cuando corresponda.
* RNF-06.10: Se deberá mantener una separación razonable entre presentación, lógica del servidor y acceso a datos.
* RNF-06.11: Los nombres de archivos, funciones y componentes deberán seguir convenciones consistentes.
* RNF-06.12: Los cambios deberán probarse antes de integrarse a la rama principal del repositorio.

### Organización de los estilos CSS

La estructura de referencia para los estilos compartidos del frontend contempla:

```text
assets/css/
├── main.css
├── fonts.css
├── header.css
└── footer.css
```

Responsabilidades previstas:

* `main.css`: estilos generales, estructura base, elementos compartidos y reglas globales.
* `fonts.css`: configuración tipográfica.
* `header.css`: estilos del encabezado y la navegación.
* `footer.css`: estilos del pie de página.

Durante la implementación se han reportado otros archivos de estilos para páginas y componentes específicos, como los relacionados con el dashboard y el loader.

La estructura definitiva deberá mantenerse sincronizada con el repositorio real. Este esquema no implica que todos los estilos de la aplicación estén limitados a esos cuatro archivos.

### Componentes PHP compartidos

La implementación reportada contempla componentes reutilizables para el encabezado, el pie de página y otras partes de la interfaz.

La ubicación y los mecanismos de inclusión deberán mantenerse coherentes con la arquitectura real del repositorio, evitando duplicar estructuras compartidas entre páginas.

---

## 4.7. RNF-07 — Integridad de la información

* RNF-07.01: Los datos deberán validarse antes de almacenarse.
* RNF-07.02: Las relaciones entre entidades deberán respetar las reglas de integridad definidas.
* RNF-07.03: Se deberán evitar registros duplicados cuando las reglas del sistema no los permitan.
* RNF-07.04: Las operaciones relacionadas deberán mantener la consistencia de la información.
* RNF-07.05: Los errores durante las operaciones de base de datos deberán manejarse de manera controlada.
* RNF-07.06: Los datos personales deberán tratarse de acuerdo con los permisos y las reglas de privacidad del proyecto.
* RNF-07.07: Las operaciones que requieran atomicidad deberán utilizar transacciones cuando corresponda.
* RNF-07.08: Se deberá comprobar la persistencia de los datos mediante pruebas de lectura y escritura.

---

# 5. Sistema de diseño

La interfaz deberá basarse en el sistema de diseño establecido durante la fase UI/UX para mantener la consistencia visual durante la implementación.

El sistema contempla:

* Identidad visual.
* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Jerarquía tipográfica.
* Componentes reutilizables.
* Estados de interacción.
* Criterios responsive.
* Estructura visual consistente.

## 5.1. Componentes definidos

* Botones.
* Inputs.
* Checkbox.
* Radio Button.
* Switch.
* Tablas.

La incorporación de nuevos componentes deberá mantener la coherencia con el Design System y responder a las necesidades de las interfaces.

## 5.2. Estados de los componentes

Según la función de cada componente, se deberán contemplar los siguientes estados:

* Normal.
* Hover.
* Focus.
* Selected.
* Disabled.
* Error.
* On.
* Off.

No todos los estados se aplican a todos los componentes. Su implementación deberá respetar el comportamiento definido en el Design System.

## 5.3. Paleta de colores

| Token o función              | Valor     |
| ---------------------------- | --------- |
| Color primario               | `#D32F2F` |
| Color secundario             | `#1565C0` |
| Texto principal              | `#212121` |
| Texto secundario             | `#757575` |
| Fondo claro                  | `#F5F5F5` |
| Blanco                       | `#FFFFFF` |
| Borde                        | `#E0E0E0` |
| Texto auxiliar y placeholder | `#9E9E9E` |

Los colores deberán utilizarse de manera coherente y revisarse en cuanto a contraste y legibilidad.

## 5.4. Sistema de espaciado

Los valores definidos para el sistema de espaciado son:

`4px`, `8px`, `12px`, `16px`, `24px`, `32px`, `48px` y `64px`.

Estos valores sirven como referencia para mantener la consistencia entre márgenes, rellenos, separaciones y distribución de los elementos.

## 5.5. Tipografía

La tipografía de interfaz establecida es **Inter**.

Poppins se utilizó en etapas iniciales del Design System y se conserva como referencia histórica de ese proceso. La implementación actual deberá mantener la tipografía definida para cada componente y pantalla.

El sistema de diseño se encuentra documentado en Figma y constituye la referencia visual para el desarrollo frontend.

---

# 6. Requisitos responsive

La plataforma deberá adaptarse a diferentes tamaños de pantalla sin perder legibilidad, funcionalidad ni coherencia visual.

Se deberá considerar:

* Dispositivos móviles.
* Tablets.
* Computadores de escritorio.
* Reorganización de los elementos según el espacio disponible.
* Legibilidad de los textos.
* Tamaño y accesibilidad de botones y controles.
* Visualización correcta de los componentes.
* Navegación funcional en diferentes resoluciones.
* Adaptación de las interfaces según el dispositivo.
* Prevención de desbordamientos horizontales.
* Conservación de la jerarquía visual.
* Adaptación de los elementos compartidos y de las páginas internas.

## Referencias de diseño

Durante la implementación se utilizan como referencias iniciales:

| Tipo de interfaz | Ancho de referencia |
| ---------------- | ------------------: |
| Desktop          |            `1440px` |
| Mobile           |             `390px` |

Estos valores son referencias de diseño y no límites exclusivos de funcionamiento.

Durante la fase UI/UX se definieron interfaces Desktop y se trabajaron adaptaciones Mobile para las pantallas de la plataforma. La implementación responsive comenzó con Inicio y se han reportado avances en la navegación y en componentes internos del dashboard.

Las demás interfaces deberán implementarse y verificarse progresivamente en diferentes resoluciones.

---

# 7. Requisitos de diseño UI/UX

Antes de la implementación se estableció la estructura visual y funcional de la plataforma mediante Figma.

El proceso contempló:

1. Definición de la identidad visual.
2. Creación del Design System.
3. Definición de componentes reutilizables.
4. Definición de estados de interacción.
5. Establecimiento de criterios responsive.
6. Definición del flujo de información.
7. Definición de los recorridos principales de los usuarios.
8. Diseño de la estructura de navegación.
9. Diseño de las interfaces Desktop.
10. Desarrollo de las adaptaciones Mobile.
11. Revisión y consolidación visual de las interfaces.
12. Preparación de las pantallas como referencia para la implementación.

La fase de diseño UI/UX se considera finalizada desde finales de septiembre de 2026. Sus resultados sirven como referencia para la implementación y la validación visual.

El cierre de esta fase no significa que las interfaces estén implementadas ni que su correspondencia visual con el código ya haya sido verificada.

---

# 8. Interfaces diseñadas

Durante la fase UI/UX se consolidaron 17 interfaces Desktop en Figma:

1. Inicio.
2. Autenticación.
3. Dashboard.
4. Perfil.
5. Egresados.
6. Proyectos.
7. Noticias.
8. Administración.
9. Crear proyecto.
10. Detalle de proyecto.
11. Editar perfil.
12. Recuperar contraseña.
13. Registro.
14. Editar proyecto.
15. Crear noticia.
16. Detalle de noticia.
17. Editar noticia.

También se diseñaron sus correspondientes adaptaciones Mobile, para un total de **34 pantallas de referencia: 17 Desktop y 17 Mobile**.

Las adaptaciones Mobile contemplan la reorganización de los elementos para pantallas pequeñas. Entre los apartados trabajados se encuentran Inicio, Autenticación y las vistas internas relacionadas con Dashboard, Mi perfil, Mis proyectos y Oportunidades.

La implementación y validación de las 34 pantallas se realizará progresivamente durante la Fase 4.

Las interfaces de Figma constituyen la referencia visual para la implementación frontend.

---

# 9. Estado de los requisitos

## 9.1. Requisitos definidos

* [x] Requisitos funcionales principales.
* [x] Requisitos no funcionales.
* [x] Requisitos de seguridad.
* [x] Requisitos de compatibilidad.
* [x] Requisitos de rendimiento.
* [x] Requisitos de diseño y experiencia de usuario.
* [x] Criterios responsive.
* [x] Componentes reutilizables.
* [x] Estados de interacción.
* [x] Sistema de diseño.
* [x] Estructura de navegación.
* [x] Flujo general de información.
* [x] Interfaces principales Desktop.
* [x] Adaptaciones Mobile diseñadas.

## 9.2. Diseño UI/UX

* [x] Design System.
* [x] Identidad visual.
* [x] Paleta de colores.
* [x] Tipografía.
* [x] Sistema de espaciado.
* [x] Componentes reutilizables.
* [x] Estados de los componentes.
* [x] Página Components & Responsive.
* [x] Criterios responsive.
* [x] Estructura de navegación.
* [x] Definición de las interfaces principales.
* [x] Consolidación visual del diseño.
* [x] Preparación de las pantallas como referencia para la implementación.

**Estado:** fase finalizada a finales de septiembre de 2026.

## 9.3. Implementación reportada

* [x] Estructura inicial del frontend.
* [x] Interfaz Inicio.
* [x] Encabezado compartido.
* [x] Pie de página compartido.
* [x] Navegación responsive.
* [x] Menú móvil.
* [x] Integración del isotipo.
* [x] Integración del favicon.
* [x] Organización modular inicial de los estilos CSS.
* [x] Configuración tipográfica mediante `fonts.css`, según los avances reportados.
* [x] Interfaz inicial de inicio de sesión.
* [x] Interfaz inicial de registro.
* [x] Interfaz inicial de recuperación de contraseña.
* [x] Avances de estructura visual del dashboard reportados.
* [x] Conectividad entre PHP y MySQL verificada previamente.

> Las casillas indican avances reportados. No significan que cada módulo esté completamente terminado, integrado, probado o listo para producción.

## 9.4. En implementación o validación

* [ ] Revisión visual definitiva de Inicio frente a Figma.
* [ ] Validación integral de autenticación.
* [ ] Validación integral del registro.
* [ ] Validación integral de recuperación de contraseña.
* [ ] Verificación de la gestión de sesiones.
* [ ] Verificación de las rutas protegidas y los permisos.
* [ ] Completar la implementación de las interfaces restantes.
* [ ] Completar las funcionalidades del dashboard.
* [ ] Completar el backend.
* [ ] Definir e implementar el esquema definitivo de la base de datos.
* [ ] Integrar los módulos con la base de datos.
* [ ] Completar las adaptaciones responsive.
* [ ] Completar la integración entre módulos.
* [ ] Verificar la seguridad de las operaciones del servidor.

## 9.5. Pendiente

* [ ] Pruebas funcionales integrales.
* [ ] Pruebas de integración.
* [ ] Pruebas de seguridad.
* [ ] Pruebas de compatibilidad.
* [ ] Validación integral de requisitos.
* [ ] Corrección de errores detectados.
* [ ] Pruebas de regresión.
* [ ] Validación final de la experiencia de usuario.
* [ ] Validación completa de persistencia de datos.
* [ ] Despliegue de la plataforma.

---

# 10. Criterios generales de aceptación

La plataforma deberá cumplir los siguientes criterios antes de considerarse funcionalmente validada:

* Las funcionalidades implementadas deberán responder correctamente a las acciones del usuario.
* Los usuarios deberán poder autenticarse mediante un proceso seguro y verificable.
* La información deberá validarse antes de almacenarse.
* Los permisos deberán controlar el acceso a las funcionalidades correspondientes.
* Las interfaces deberán adaptarse a los tamaños de pantalla contemplados.
* Los componentes deberán mantener consistencia visual.
* La aplicación deberá funcionar en los navegadores definidos para las pruebas.
* La estructura del código deberá mantenerse organizada y ser fácil de mantener.
* Las interfaces implementadas deberán corresponder a los diseños aprobados en Figma.
* La información deberá presentarse de forma clara y organizada.
* Las funcionalidades administrativas deberán estar protegidas mediante controles de autorización en el servidor.
* La documentación deberá actualizarse conforme avance el desarrollo.
* Las pruebas deberán comprobar las funcionalidades implementadas antes del despliegue.
* Los componentes compartidos deberán mantener un comportamiento coherente entre las diferentes interfaces.
* La implementación deberá respetar el sistema de diseño establecido.
* Las adaptaciones responsive deberán conservar la funcionalidad y la jerarquía visual.
* Los errores deberán manejarse sin exponer información sensible.
* Los resultados de las pruebas deberán registrarse para facilitar la corrección de problemas.
* Las operaciones que modifican información deberán verificar la autorización del usuario en el servidor.
* Los datos que deban persistir deberán conservarse correctamente después de completar las operaciones correspondientes.
* Las funcionalidades de autenticación y recuperación deberán comprobarse mediante pruebas de extremo a extremo.
* Los mecanismos auxiliares de desarrollo no deberán permitir eludir la autenticación en producción.

El cumplimiento de estos criterios deberá comprobarse mediante pruebas y revisión del código. La existencia de una interfaz visual no constituye evidencia suficiente de que la funcionalidad asociada esté terminada.

---

# 11. Tecnologías relacionadas

## Frontend

* HTML5.
* CSS3.
* JavaScript vanilla.

## Backend

* PHP nativo con organización modular.

## Base de datos

* MySQL.
* PDO para el acceso a los datos.

## Diseño UI/UX

* Figma.

## Control de versiones

* Git.
* GitHub.

## Entorno de desarrollo

* XAMPP.
* Apache.
* Visual Studio Code.

Las tecnologías anteriores corresponden al alcance tecnológico definido para el proyecto. Las versiones concretas y la configuración final deberán verificarse frente al entorno real de desarrollo y despliegue.

---

# 12. Estado actual del proyecto

Raíces Alzate se encuentra en la **Fase 4 — Implementación e integración funcional**, alcanzando la versión **v0.2.1** al 17 de octubre de 2026.

El proyecto comenzó en 2025. La fase de diseño UI/UX finalizó a finales de septiembre de 2026 (con 34 pantallas en Figma), y la implementación técnica ha alcanzado los siguientes hitos consolidados:

* Estructura completa de frontend para Inicio, Dashboard, Egresados, Perfil, Proyectos, Noticias, PQRS, Administración y Mensajería.
* Navegación compartida estandarizada: cabecera pública, sidebar colapsable en escritorio (240px/76px), drawer en móviles y topbar autenticado.
* Sistema de autenticación con hashing seguro de contraseñas mediante `password_hash(PASSWORD_DEFAULT)`.
* Flujo integral de recuperación y restablecimiento de contraseña (`pages/reset-password.php`) con generación de tokens criptográficos SHA-256 temporales (60 min) y revocación inmediata de un solo uso.
* Integración de PHPMailer transaccional con configuración SMTP institucional (`config/mail.php`, `includes/mailer.php`) y plantilla HTML responsiva con logotipo embebido CID Inline.
* Arquitectura de base de datos relacional formalizada en `database/schema.sql` (11 tablas con claves foráneas, restricciones e índices).
* Instalador y aprovisionamiento automatizado `database/setup.php` compatible con ejecución CLI y web.
* Endpoints API JSON en `api/` para interacciones asíncronas de red y chat privado.
* Centralización modular de todas las vistas bajo `pages/`.

Las siguientes actividades se concentran en la validación funcional integral de la persistencia de datos en cada módulo, pruebas con servidores SMTP en entornos reales y auditoría de accesibilidad y diseño responsive de cara a la Fase 5 (Pruebas).

---

# 13. Historial de cambios

| Versión | Fecha      | Descripción                                                                                                                                                                                                                            |
| ------- | ---------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| v0.1.0  | 09/07/2026 | Creación inicial de los requisitos del proyecto.                                                                                                                                                                                       |
| v0.1.0  | 05/09/2026 | Actualización de requisitos funcionales y no funcionales; incorporación de criterios UI/UX, componentes, estados y diseño responsive.                                                                                                  |
| v0.1.1  | 07/10/2026 | Consolidación de la fase UI/UX, actualización del estado del proyecto y preparación de los requisitos para la implementación.                                                                                                          |
| v0.1.1  | 08/10/2026 | Actualización de los avances iniciales de Inicio, estructura CSS, componentes compartidos, navegación responsive e integración con MySQL reportada.                                                                                    |
| v0.1.1  | 09/10/2026 | Actualización de los requisitos y del estado de los módulos de inicio de sesión, registro y recuperación de contraseña.                                                                                                                |
| v0.1.1  | 13/10/2026 | Actualización del estado de implementación, avances reportados en componentes compartidos y dashboard, criterios de validación, requisitos de seguridad y distinción entre funcionalidades implementadas y pendientes de verificación. |
| v0.2.1  | 17/10/2026 | Actualización a versión v0.2.1; consolidación de requisitos de recuperación segura de contraseña con PHPMailer y tokens SHA-256, módulos de proyectos, noticias, egresados, perfil, PQRS, administración, mensajería y persistencia relacional en 11 tablas con setup.php. |

---

# 14. Control del documento

| Campo                           | Información             |
| ------------------------------- | ----------------------- |
| Documento                       | `docs/03_Requisitos.md` |
| Versión documental              | `v0.2.1`                |
| Estado                          | 🟡 En desarrollo        |
| Fase actual                     | Fase 4 — Implementación e integración funcional |
| Inicio del proyecto             | 2025                    |
| Última actualización            | 17 de octubre de 2026   |
| Responsable de la documentación | Santiago Emitola        |

---

<div align="center">

# 🌱 Raíces Alzate

### Conectando generaciones, fortaleciendo el futuro.

Desarrollado por el equipo de Raíces Alzate.

</div>
