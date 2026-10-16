<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Editar Perfil (Figma: 04 / 11 - Editar perfil - Desktop y Mobile)
 * Versión: 0.1.1
 */

ob_start();

if (file_exists(__DIR__ . '/config/config.php')) {
    require_once __DIR__ . '/config/config.php';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si no hay sesión activa (tanto por $_SESSION['user'] como $_SESSION['usuario'])
if (empty($_SESSION['user']) && empty($_SESSION['usuario']) && empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// 1. Variables obligatorias para includes globales según directiva
$seccion_activa = 'perfil';
$titulo_seccion = 'Editar perfil';
$titulo_pagina = 'Editar perfil — Raíces Alzate';
$currentPage = 'perfil';
$pageTitle = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// 2. Extracción de identidad de usuario (con soporte para sesión activa o datos por defecto)
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Santiago Emitola');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado Alzatista');
$userEmail = trim($user['email'] ?? 'santiago@ejemplo.com');

$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'S';

// 3. Hojas de estilo y scripts adicionales gestionados por header.php y footer.php
$extraCss = ['dashboard.css', 'perfil-form.css'];
$extraJs = ['assets/js/perfil.js'];

// 4. Inclusión del encabezado global compartido
require_once __DIR__ . '/includes/header.php';
?>
<script>
    // Asignación de clases en body para el entorno privado del Dashboard
    document.body.classList.add('dashboard-body-page', 'editar-perfil-page');
</script>

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="dashboard-layout" id="dashboardLayout">
    <!-- Menú lateral de navegación privada (Sidebar con ítem "Perfil" activo en rojo institucional) -->
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

    <div class="dashboard-main-area">
        <!-- Barra superior (Topbar con título dinámico "Editar perfil" y perfil de usuario) -->
        <?php require_once __DIR__ . '/includes/topbar.php'; ?>

        <!-- Área Principal: Fluye naturalmente debajo de la Topbar con padding: 32px -->
        <main class="dashboard-content content-area editar-perfil-content-wrapper" id="editarPerfilMainContent" tabindex="-1">
            
            <div class="perfil-form-container">
                <!-- A. Miga de pan / Breadcrumb -->
                <nav class="perfil-form-breadcrumb" aria-label="Ruta de navegación">
                    <ol class="perfil-breadcrumb-list">
                        <li class="perfil-breadcrumb-item">
                            <a href="dashboard.php" class="perfil-breadcrumb-link">Dashboard</a>
                        </li>
                        <li class="perfil-breadcrumb-item">
                            <span class="perfil-breadcrumb-sep" aria-hidden="true">/</span>
                            <a href="perfil.php" class="perfil-breadcrumb-link">Mi perfil</a>
                        </li>
                        <li class="perfil-breadcrumb-item">
                            <span class="perfil-breadcrumb-sep" aria-hidden="true">/</span>
                            <span class="perfil-breadcrumb-current" aria-current="page">Editar perfil</span>
                        </li>
                    </ol>
                </nav>

                <!-- B. Cabecera: Avatar circular con inicial "S", nombre, rol y botón Cambiar foto -->
                <section class="profile-section-card perfil-header-card" aria-label="Identidad del egresado">
                    <div class="perfil-header-left">
                        <div class="perfil-avatar-circle" id="avatarPreviewCircle" aria-hidden="true">
                            <?php echo htmlspecialchars($userInitial); ?>
                        </div>
                        <div class="perfil-header-user-info">
                            <h1 class="perfil-header-name"><?php echo htmlspecialchars($userName); ?></h1>
                            <p class="perfil-header-role"><?php echo htmlspecialchars($userRole); ?></p>
                        </div>
                    </div>
                    <div class="perfil-header-right">
                        <button type="button" class="btn-cambiar-foto" id="btnCambiarFoto" aria-label="Cambiar fotografía de perfil">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                            Cambiar foto
                        </button>
                        <input type="file" id="inputFotoOculta" accept="image/*" style="display: none;" aria-hidden="true">
                    </div>
                </section>

                <!-- C. Formulario Estático de Edición Completo -->
                <form action="perfil.php" method="POST" class="perfil-edit-form" id="formEditarPerfil">
                    
                    <!-- 1. Sobre mí (Bloque independiente con encabezado estructurado y espaciado) -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloSobreMi">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloSobreMi">Sobre mí</h2>
                            <p class="card-subtitle perfil-card-subtitle">Escribe una breve presentación sobre tu trayectoria, intereses y metas profesionales.</p>
                        </div>
                        <div class="form-field-group">
                            <label for="textareaBio" class="form-field-label">Descripción y presentación personal</label>
                            <textarea id="textareaBio" name="bio" class="form-field-textarea" rows="4" placeholder="Cuéntale a la comunidad alzatista sobre ti...">Egresado apasionado por el desarrollo de software y la tecnología. Con experiencia en construcción de interfaces modernas y soluciones web dinámicas, buscando aportar a proyectos innovadores de impacto comunitario e institucional.</textarea>
                            <span class="form-field-helper">Máximo 500 caracteres recomendados.</span>
                        </div>
                    </section>

                    <!-- 2. Información personal -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloInfoPersonal">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloInfoPersonal">Información personal</h2>
                            <p class="card-subtitle perfil-card-subtitle">Consulta y actualiza tus datos básicos de identificación.</p>
                        </div>
                        <div class="perfil-grid-2">
                            <div class="form-field-group">
                                <label for="inputNombre" class="form-field-label">Nombre completo</label>
                                <input type="text" id="inputNombre" name="nombre" class="form-field-input" value="Santiago Emitola" required>
                            </div>
                            <div class="form-field-group">
                                <label for="selectGenero" class="form-field-label">Género</label>
                                <select id="selectGenero" name="genero" class="form-field-select">
                                    <option value="Masculino" selected>Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                    <option value="Prefiero no decir">Prefiero no decir</option>
                                </select>
                            </div>
                            <div class="form-field-group">
                                <label for="inputNacimiento" class="form-field-label">Fecha de nacimiento</label>
                                <input type="date" id="inputNacimiento" name="fecha_nacimiento" class="form-field-input" value="2003-05-14">
                            </div>
                            <div class="form-field-group">
                                <label for="inputCiudad" class="form-field-label">Ciudad</label>
                                <input type="text" id="inputCiudad" name="ciudad" class="form-field-input" value="Medellín, Colombia">
                            </div>
                        </div>
                    </section>

                    <!-- 3. Información de contacto -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloInfoContacto">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloInfoContacto">Información de contacto</h2>
                            <p class="card-subtitle perfil-card-subtitle">Mantén tus canales de comunicación actualizados para oportunidades y convocatorias.</p>
                        </div>
                        <div class="perfil-grid-2">
                            <div class="form-field-group">
                                <label for="inputEmailPrincipal" class="form-field-label">Correo electrónico principal</label>
                                <input type="email" id="inputEmailPrincipal" name="email" class="form-field-input" value="santiago@ejemplo.com" required>
                            </div>
                            <div class="form-field-group">
                                <label for="inputEmailSecundario" class="form-field-label">Correo secundario</label>
                                <input type="email" id="inputEmailSecundario" name="email_secundario" class="form-field-input" value="santiago.alzate@institucional.edu.co">
                            </div>
                            <div class="form-field-group">
                                <label for="inputTelefono" class="form-field-label">Teléfono celular</label>
                                <input type="tel" id="inputTelefono" name="telefono" class="form-field-input" value="+57 300 123 4567">
                            </div>
                            <div class="form-field-group">
                                <label for="inputDireccion" class="form-field-label">Dirección / Barrio</label>
                                <input type="text" id="inputDireccion" name="direccion" class="form-field-input" value="Robledo, Medellín">
                            </div>
                        </div>
                    </section>

                    <!-- 4. Formación académica -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloFormacion">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloFormacion">Formación académica</h2>
                            <p class="card-subtitle perfil-card-subtitle">Registra tus títulos institucionales y estudios superiores complementarios.</p>
                        </div>
                        <div class="perfil-grid-2">
                            <div class="form-field-group">
                                <label for="inputTituloInstitucional" class="form-field-label">Título obtenido en la institución</label>
                                <input type="text" id="inputTituloInstitucional" name="titulo_institucional" class="form-field-input" value="Bachiller Técnico en Sistemas y Gestión Comercial">
                            </div>
                            <div class="form-field-group">
                                <label for="inputAnioGraduacion" class="form-field-label">Año de graduación</label>
                                <input type="text" id="inputAnioGraduacion" name="anio_graduacion" class="form-field-input" value="2025">
                            </div>
                            <div class="form-field-group">
                                <label for="inputProgramaSuperior" class="form-field-label">Programa académico superior</label>
                                <input type="text" id="inputProgramaSuperior" name="programa_superior" class="form-field-input" value="Ingeniería de Sistemas">
                            </div>
                            <div class="form-field-group">
                                <label for="inputInstitucionSuperior" class="form-field-label">Institución de estudios superiores</label>
                                <input type="text" id="inputInstitucionSuperior" name="institucion_superior" class="form-field-input" value="Instituto Tecnológico Metropolitano (ITM)">
                            </div>
                        </div>
                    </section>

                    <!-- 5. Experiencia laboral y profesional -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloExperiencia">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloExperiencia">Experiencia laboral y profesional</h2>
                            <p class="card-subtitle perfil-card-subtitle">Comparte tu trayectoria y posición laboral actual con la red de egresados.</p>
                        </div>
                        <div class="perfil-grid-2">
                            <div class="form-field-group">
                                <label for="inputOcupacionRol" class="form-field-label">Ocupación / Rol profesional</label>
                                <input type="text" id="inputOcupacionRol" name="ocupacion" class="form-field-input" value="Desarrollador Frontend Junior">
                            </div>
                            <div class="form-field-group">
                                <label for="inputEmpresa" class="form-field-label">Empresa</label>
                                <input type="text" id="inputEmpresa" name="empresa" class="form-field-input" value="InnovaTech Medellín">
                            </div>
                            <div class="form-field-group">
                                <label for="inputAreaEspecialidad" class="form-field-label">Área de especialidad</label>
                                <input type="text" id="inputAreaEspecialidad" name="area_especialidad" class="form-field-input" value="Desarrollo Web y Aplicaciones">
                            </div>
                            <div class="form-field-group">
                                <label for="inputAniosExperiencia" class="form-field-label">Años de experiencia</label>
                                <input type="text" id="inputAniosExperiencia" name="anios_experiencia" class="form-field-input" value="2 años">
                            </div>
                        </div>
                    </section>

                    <!-- 6. Habilidades y competencias -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloHabilidades">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloHabilidades">Habilidades y competencias</h2>
                            <p class="card-subtitle perfil-card-subtitle">Escribe tus competencias técnicas o blandas y pulsa coma o Enter para agregarlas.</p>
                        </div>
                        <div class="form-field-group">
                            <label for="inputSkills" class="form-field-label">Competencias y habilidades</label>
                            <!-- Contenedor dinámico de pills gestionado por perfil.js -->
                            <div class="skills-badges-container" id="skillsBadgesContainer" aria-live="polite"></div>
                            <input type="text" id="inputSkills" class="form-field-input" placeholder="Ej: React, Python, Liderazgo (escribe y pulsa Enter o coma)">
                            <input type="hidden" name="habilidades" id="inputHabilidadesHidden" value="HTML5, CSS3, JavaScript, PHP, Git, Diseño UI/UX">
                            <span class="form-field-helper">Haz clic en la "×" de cualquier etiqueta para removerla.</span>
                        </div>
                    </section>

                    <!-- 7. Redes y enlaces -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloRedes">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloRedes">Redes y enlaces</h2>
                            <p class="card-subtitle perfil-card-subtitle">Enlaza tus perfiles profesionales y portafolios digitales.</p>
                        </div>
                        <div class="perfil-grid-2">
                            <div class="form-field-group">
                                <label for="inputLinkedin" class="form-field-label">LinkedIn</label>
                                <input type="url" id="inputLinkedin" name="linkedin" class="form-field-input" value="https://linkedin.com/in/santiago-emitola" placeholder="https://linkedin.com/in/tu-perfil">
                            </div>
                            <div class="form-field-group">
                                <label for="inputGithub" class="form-field-label">GitHub</label>
                                <input type="url" id="inputGithub" name="github" class="form-field-input" value="https://github.com/santiago-emitola" placeholder="https://github.com/tu-usuario">
                            </div>
                            <div class="form-field-group">
                                <label for="inputWeb" class="form-field-label">Portafolio web</label>
                                <input type="url" id="inputWeb" name="web" class="form-field-input" value="https://santiago-emitola.dev" placeholder="https://tu-sitio-web.com">
                            </div>
                            <div class="form-field-group">
                                <label for="inputRedAdicional" class="form-field-label">Red social adicional</label>
                                <input type="text" id="inputRedAdicional" name="red_adicional" class="form-field-input" value="Twitter / X: @santiago_dev" placeholder="Twitter, Behance o Instagram">
                            </div>
                        </div>
                    </section>

                    <!-- 8. Barra Inferior de Acciones -->
                    <div class="perfil-actions-bottom">
                        <a href="perfil.php" class="btn-perfil-cancel">Cancelar</a>
                        <button type="submit" class="btn-perfil-save">Guardar cambios</button>
                    </div>

                </form>
            </div>

        </main>
    </div>
</div>

<?php
// 5. Inclusión del pie de página global compartido
require_once __DIR__ . '/includes/footer.php';
?>
