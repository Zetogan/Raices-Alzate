# 🌱 Raíces Alzate

> Plataforma para la gestión, seguimiento y fortalecimiento de la comunidad de egresados de la Institución Educativa Gilberto Alzate Avendaño.

---

# 📑 Requisitos del Proyecto

## 1. Información general

| Campo                | Información                                    |
| -------------------- | ---------------------------------------------- |
| Proyecto             | Raíces Alzate                                  |
| Institución          | Institución Educativa Gilberto Alzate Avendaño |
| Versión documental   | v0.1.1                                         |
| Estado               | 🟡 En desarrollo                               |
| Fase actual          | Fase 4 — Implementación                        |
| Inicio del proyecto  | 2025                                           |
| Última actualización | 9 de octubre de 2026                           |

---

## 2. Introducción

Este documento reúne los requisitos funcionales y no funcionales que definen el comportamiento, las características y las condiciones de funcionamiento de la plataforma Raíces Alzate.

Los requisitos establecidos sirven como referencia durante las etapas de diseño, desarrollo, integración y pruebas, permitiendo mantener una relación clara entre las necesidades del proyecto y las funcionalidades que se deben implementar.

Las fases de planificación y documentación base se encuentran consolidadas. La fase de diseño UI/UX finalizó a finales de septiembre de 2026, con la definición del sistema de diseño, los componentes reutilizables, los criterios responsive, la estructura de navegación y las principales interfaces Desktop.

También se trabajaron adaptaciones Mobile para las pantallas principales.

Actualmente, el proyecto se encuentra en la **Fase 4 — Implementación**. El desarrollo frontend comenzó con la interfaz Inicio y posteriormente avanzó hacia los módulos de autenticación, registro y recuperación de contraseña.

Este documento distingue entre los requisitos definidos, las funcionalidades con avances de implementación reportados y aquellas que todavía requieren desarrollo, integración o validación.

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

### Estado de implementación

* 🟢 Estructura inicial del frontend implementada.
* 🟢 Encabezado y pie de página compartidos implementados.
* 🟢 Navegación responsive y menú móvil implementados.
* 🟢 Isotipo y favicon incorporados.
* 🟢 Versión Desktop desarrollada tomando como referencia el diseño de 1440 px.
* 🟢 Adaptación inicial Mobile desarrollada tomando como referencia el diseño de 390 px.
* 🟡 Revisión visual final frente a Figma pendiente.
* 🟡 Validación completa en diferentes dispositivos pendiente.

> El estado indicado refleja los avances de implementación reportados. La aceptación definitiva requiere verificar la interfaz en ejecución.

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
* RF-02.08: Validar la información tanto en el cliente como en el servidor, según corresponda.
* RF-02.09: Almacenar las contraseñas mediante mecanismos de hash seguros.
* RF-02.10: Mostrar mensajes de retroalimentación cuando el registro sea aceptado o se produzca un error.
* RF-02.11: Asociar el tipo de usuario correspondiente cuando el sistema lo requiera.
* RF-02.12: Evitar la creación de cuentas con información inválida o duplicada.

### Estado de implementación

* 🟢 Interfaz inicial de registro implementada.
* 🟢 Procesamiento inicial del formulario reportado.
* 🟢 Validaciones y comprobación de correo duplicado reportadas.
* 🟢 Uso de `password_hash()` reportado para el tratamiento de contraseñas.
* 🟡 Integración y comportamiento completo por verificar.
* 🟡 Pruebas funcionales y de seguridad pendientes.

> Los campos definitivos del registro deberán coincidir con el formulario implementado y con las decisiones funcionales del proyecto. No se debe asumir que los campos condicionales o los tipos de usuario están completamente integrados sin verificar el código.

---

## 3.3. RF-03 — Inicio y cierre de sesión

La plataforma deberá:

* RF-03.01: Permitir el inicio de sesión mediante las credenciales registradas.
* RF-03.02: Validar las credenciales en el servidor.
* RF-03.03: Rechazar credenciales incorrectas.
* RF-03.04: Permitir el cierre de sesión.
* RF-03.05: Mantener la sesión activa mientras se cumplan las condiciones de seguridad definidas.
* RF-03.06: Restringir las funcionalidades privadas a los usuarios autenticados.
* RF-03.07: Proporcionar mensajes de retroalimentación adecuados.
* RF-03.08: Evitar que los mensajes de error revelen información sensible.
* RF-03.09: Controlar el acceso a las páginas protegidas.

### Estado de implementación

* 🟢 Interfaz inicial de inicio de sesión implementada.
* 🟢 Procesamiento inicial de credenciales reportado.
* 🟢 Validaciones y mensajes de retroalimentación incorporados según los avances reportados.
* 🟡 Verificación integral de autenticación pendiente.
* 🟡 Gestión completa de sesiones y acceso a páginas protegidas por validar.
* 🟡 Pruebas de seguridad pendientes.

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

### Estado de implementación

* 🟢 Interfaz inicial de recuperación implementada.
* 🟢 Validación del correo en el cliente reportada.
* 🟢 Procesamiento inicial de recuperación reportado.
* 🟢 Generación de un token aleatorio mediante `random_bytes(32)` reportada.
* 🟢 Respuesta genérica para reducir la enumeración de cuentas incorporada según los informes.
* 🟡 Almacenamiento, expiración y validación integral de los tokens por verificar.
* 🟡 Flujo completo para establecer una nueva contraseña por verificar.
* 🟡 Envío de correos mediante SMTP pendiente de configuración.
* 🟡 Pruebas integrales de recuperación pendientes.

> La generación de un token no demuestra por sí sola que el flujo de recuperación esté completo. Se deberá comprobar su almacenamiento, vigencia, validación, uso único y relación con el cambio de contraseña. Mientras no exista un servicio de correo configurado y probado, no se deberá afirmar que las instrucciones se envían efectivamente al usuario.

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

### Estado de implementación

⏳ Pendiente de implementación funcional integral.

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

### Estado de implementación

⏳ Pendiente de implementación.

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

### Estado de implementación

⏳ Pendiente de implementación funcional.

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

### Estado de implementación

⏳ Pendiente de implementación funcional.

---

## 3.9. RF-09 — Panel administrativo

La plataforma deberá:

* RF-09.01: Permitir el acceso al panel administrativo únicamente a usuarios autorizados.
* RF-09.02: Gestionar usuarios.
* RF-09.03: Gestionar información de egresados.
* RF-09.04: Gestionar proyectos.
* RF-09.05: Gestionar noticias.
* RF-09.06: Administrar los permisos que correspondan al rol autorizado.
* RF-09.07: Controlar las operaciones administrativas.
* RF-09.08: Mostrar información administrativa disponible.
* RF-09.09: Validar las operaciones antes de ejecutarlas.
* RF-09.10: Impedir que usuarios sin permisos ejecuten operaciones administrativas mediante solicitudes directas.

### Estado de implementación

⏳ Pendiente de implementación funcional.

---

# 4. Requisitos no funcionales

Los requisitos no funcionales establecen las condiciones de calidad, seguridad, rendimiento, compatibilidad y mantenimiento que deberá cumplir la plataforma.

## 4.1. RNF-01 — Rendimiento

* RNF-01.01: La plataforma deberá cargar de manera eficiente.
* RNF-01.02: Las imágenes deberán optimizarse para reducir tiempos de carga.
* RNF-01.03: Se deberán evitar recursos innecesarios.
* RNF-01.04: Las consultas a la base de datos deberán diseñarse de manera eficiente.
* RNF-01.05: El código deberá evitar operaciones innecesarias que afecten el rendimiento.
* RNF-01.06: Los recursos frontend deberán mantenerse organizados para facilitar su mantenimiento y optimización.
* RNF-01.07: Las operaciones del servidor deberán manejar los errores sin provocar interrupciones evitables.

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

---

## 4.3. RNF-03 — Compatibilidad

La plataforma deberá funcionar correctamente en las versiones compatibles de los principales navegadores web, incluyendo:

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

---

## 4.5. RNF-05 — Accesibilidad

* RNF-05.01: Se deberá utilizar HTML semántico cuando corresponda.
* RNF-05.02: Los campos de formulario deberán disponer de etiquetas comprensibles.
* RNF-05.03: Los controles interactivos deberán poder utilizarse mediante teclado cuando corresponda.
* RNF-05.04: Los estados de error no deberán comunicarse únicamente mediante el color.
* RNF-05.05: Se deberán mantener contrastes adecuados entre texto y fondo.
* RNF-05.06: Las imágenes informativas deberán contar con alternativas textuales apropiadas.
* RNF-05.07: Los mensajes de validación deberán poder ser identificados por tecnologías de asistencia cuando corresponda.

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

### Organización de los estilos CSS

La estructura modular reportada para el frontend contempla:

```text
assets/css/
├── main.css
├── fonts.css
├── header.css
└── footer.css
```

Responsabilidades:

* `main.css`: estilos generales, estructura base, elementos compartidos y reglas globales.
* `fonts.css`: configuración tipográfica.
* `header.css`: estilos del encabezado y la navegación.
* `footer.css`: estilos del pie de página.

La estructura deberá actualizarse si se incorporan nuevos archivos o se modifica la organización real del proyecto.

### Componentes PHP compartidos

La implementación reportada contempla componentes reutilizables para el encabezado y el pie de página.

La ubicación y los mecanismos de inclusión deberán mantenerse coherentes con la arquitectura real del repositorio, evitando duplicar estructuras compartidas entre las páginas.

---

## 4.7. RNF-07 — Integridad de la información

* RNF-07.01: Los datos deberán validarse antes de almacenarse.
* RNF-07.02: Las relaciones entre entidades deberán respetar las reglas de integridad definidas.
* RNF-07.03: Se deberán evitar registros duplicados cuando las reglas del sistema no los permitan.
* RNF-07.04: Las operaciones relacionadas deberán mantener la consistencia de la información.
* RNF-07.05: Los errores durante las operaciones de base de datos deberán manejarse de manera controlada.
* RNF-07.06: Los datos personales deberán tratarse de acuerdo con los permisos y las reglas de privacidad del proyecto.

---

# 5. Sistema de diseño

La interfaz deberá basarse en el sistema de diseño establecido durante la fase UI/UX para mantener la consistencia visual durante la implementación.

El sistema contempla:

* Paleta de colores.
* Tipografía.
* Sistema de espaciado.
* Componentes reutilizables.
* Estados de interacción.
* Criterios responsive.
* Jerarquía visual.
* Estructura visual consistente.

### 5.1. Componentes definidos

* Botones.
* Inputs.
* Checkbox.
* Radio Button.
* Switch.
* Tablas.

### 5.2. Estados de los componentes

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

### 5.3. Paleta de colores

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

### 5.4. Sistema de espaciado

Los valores definidos para el sistema de espaciado son:

`4px`, `8px`, `12px`, `16px`, `24px`, `32px`, `48px` y `64px`.

### 5.5. Tipografía

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
* Legibilidad de textos.
* Tamaño y accesibilidad de botones y controles.
* Visualización correcta de componentes.
* Navegación funcional en diferentes resoluciones.
* Adaptación de las interfaces según el dispositivo.
* Prevención de desbordamientos horizontales.
* Conservación de la jerarquía visual.

### Referencias de diseño

Durante la implementación se utilizan como referencias iniciales:

| Tipo de interfaz | Ancho de referencia |
| ---------------- | ------------------: |
| Desktop          |            `1440px` |
| Mobile           |             `390px` |

Estos valores son referencias de diseño y no límites exclusivos de funcionamiento.

Durante la fase UI/UX se definieron interfaces Desktop y se trabajaron adaptaciones Mobile para las pantallas principales.

La implementación responsive comenzó con Inicio, incluyendo la adaptación de la navegación mediante el menú móvil. Las demás interfaces deberán implementarse y verificarse progresivamente.

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
7. Definición de recorridos principales de los usuarios.
8. Diseño de la estructura de navegación.
9. Diseño de interfaces Desktop.
10. Trabajo de adaptaciones Mobile.
11. Revisión y consolidación visual de las interfaces.
12. Preparación de las pantallas como referencia para la implementación.

La fase de diseño UI/UX se considera finalizada. Sus resultados sirven como referencia para la implementación y validación visual.

---

# 8. Interfaces diseñadas

Durante la fase UI/UX se consolidaron las siguientes interfaces Desktop:

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

También se trabajaron las siguientes interfaces y adaptaciones Mobile:

* Inicio Mobile.
* Autenticación Mobile.
* Dashboard Mobile.
* Mi perfil.
* Mis proyectos.
* Oportunidades.

El trabajo responsive Mobile no debe considerarse completamente finalizado hasta que se implementen y validen las adaptaciones correspondientes.

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
* [x] Adaptaciones Mobile trabajadas.

## 9.2. Diseño UI/UX

* [x] Design System.
* [x] Identidad visual.
* [x] Paleta de colores.
* [x] Tipografía.
* [x] Sistema de espaciado.
* [x] Componentes reutilizables.
* [x] Estados de componentes.
* [x] Página Components & Responsive.
* [x] Criterios responsive.
* [x] Estructura de navegación.
* [x] Definición de las interfaces principales.
* [x] Consolidación visual del diseño.

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
* [x] Organización modular de estilos.
* [x] Configuración tipográfica mediante `fonts.css`.
* [x] Interfaz inicial de inicio de sesión.
* [x] Interfaz inicial de registro.
* [x] Interfaz inicial de recuperación de contraseña.
* [x] Validaciones y estados de formulario reportados.
* [x] Conectividad entre PHP y MySQL verificada previamente.

> Las casillas indican avances reportados. La validación integral de los módulos y su correspondencia con los criterios de aceptación continúan pendientes.

## 9.4. En implementación

* [ ] Revisión visual definitiva de Inicio.
* [ ] Validación integral de autenticación.
* [ ] Validación integral de registro.
* [ ] Validación integral de recuperación de contraseña.
* [ ] Completar la implementación de las interfaces restantes.
* [ ] Completar el backend.
* [ ] Implementar la gestión de sesiones.
* [ ] Implementar roles y permisos.
* [ ] Definir e implementar el esquema definitivo de la base de datos.
* [ ] Integrar los módulos con la base de datos.
* [ ] Completar las adaptaciones responsive.
* [ ] Completar la integración de los módulos.

## 9.5. Pendiente

* [ ] Pruebas funcionales.
* [ ] Pruebas de integración.
* [ ] Pruebas de seguridad.
* [ ] Pruebas de compatibilidad.
* [ ] Validación integral de requisitos.
* [ ] Corrección de errores.
* [ ] Pruebas de regresión.
* [ ] Validación final de la experiencia de usuario.
* [ ] Despliegue de la plataforma.

---

# 10. Criterios generales de aceptación

La plataforma deberá cumplir los siguientes criterios antes de considerarse funcionalmente validada:

* Las funcionalidades implementadas deberán responder correctamente a las acciones del usuario.
* Los usuarios deberán poder autenticarse de forma segura.
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

---

# 12. Estado actual del proyecto

Raíces Alzate se encuentra en la **Fase 4 — Implementación**.

El proyecto comenzó en 2025. La fase de diseño UI/UX finalizó a finales de septiembre de 2026, y la implementación frontend comenzó en octubre del mismo año.

Los avances reportados incluyen:

* Estructura inicial del frontend.
* Interfaz Inicio.
* Encabezado y pie de página compartidos.
* Navegación responsive y menú móvil.
* Incorporación del isotipo y favicon.
* Organización modular de los estilos CSS.
* Interfaz inicial de inicio de sesión.
* Interfaz inicial de registro.
* Interfaz inicial de recuperación de contraseña.
* Validaciones y estados visuales de formularios.
* Conectividad entre PHP y MySQL verificada previamente.

Los módulos de autenticación, registro y recuperación requieren validación integral. En particular, debe verificarse el flujo completo de recuperación de contraseña, incluido el tratamiento de tokens y el envío de correos cuando se configure SMTP.

La estructura definitiva de tablas y relaciones de la base de datos todavía debe definirse e implementarse.

El desarrollo continuará con la validación de los módulos iniciales, la implementación progresiva de las interfaces restantes y la integración del frontend, el backend y la base de datos.

---

# 13. Historial de cambios

| Versión | Fecha      | Descripción                                                                                                                                                                                              |
| ------- | ---------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| v0.1.0  | 09/07/2026 | Creación inicial de los requisitos del proyecto.                                                                                                                                                         |
| v0.1.0  | 05/09/2026 | Actualización de requisitos funcionales y no funcionales; incorporación de criterios UI/UX, componentes, estados y diseño responsive.                                                                    |
| v0.1.1  | 07/10/2026 | Consolidación de la fase UI/UX, actualización del estado del proyecto y preparación de los requisitos para la implementación.                                                                            |
| v0.1.1  | 08/10/2026 | Actualización de los avances iniciales de Inicio, estructura CSS, componentes compartidos, navegación responsive e integración con MySQL reportada.                                                      |
| v0.1.1  | 09/10/2026 | Actualización de los requisitos y del estado de los módulos de inicio de sesión, registro y recuperación de contraseña; incorporación de criterios para validar los flujos de autenticación y seguridad. |

---

# 14. Control del documento

| Campo                | Información             |
| -------------------- | ----------------------- |
| Documento            | `docs/03_Requisitos.md` |
| Versión documental   | v0.1.1                  |
| Estado               | 🟡 En desarrollo        |
| Fase actual          | Fase 4 — Implementación |
| Última actualización | 9 de octubre de 2026    |

---

<div align="center">

# 🌱 Raíces Alzate

### Conectando generaciones, fortaleciendo el futuro.

Desarrollado por el equipo de Raíces Alzate.

</div>
