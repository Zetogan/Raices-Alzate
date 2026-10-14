/**
 * Raíces Alzate - Lógica de Cliente para Edición de Perfil
 * Módulo: Pantalla de Edición de Perfil (Figma: 04 / 11 - Editar perfil)
 * Funcionalidades:
 *   1. Previsualización inmediata de foto de perfil con input oculto
 *   2. Generación dinámica de badges interactivos de habilidades y competencias
 *   3. Simulación de guardado con feedback visual antes de redirección
 *   4. Control de drawer móvil y colapso de barra lateral
 * Versión: 0.1.1
 */

document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    // =========================================================================
    // 1. PREVISUALIZACIÓN LOCAL DE FOTO DE PERFIL
    // =========================================================================
    const btnCambiarFoto = document.getElementById('btnCambiarFoto');
    const inputFotoOculta = document.getElementById('inputFotoOculta');
    const avatarPreviewCircle = document.getElementById('avatarPreviewCircle');

    if (btnCambiarFoto && inputFotoOculta) {
        btnCambiarFoto.addEventListener('click', (e) => {
            e.preventDefault();
            inputFotoOculta.click();
        });

        inputFotoOculta.addEventListener('change', (e) => {
            const file = e.target.files && e.target.files[0];
            if (!file) return;

            // Validación de tipo MIME
            if (!file.type.match(/^image\//i)) {
                alert('Por favor selecciona un archivo de imagen válido (JPG, PNG, WebP).');
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                if (avatarPreviewCircle) {
                    avatarPreviewCircle.innerHTML = `<img src="${event.target.result}" alt="Foto de perfil seleccionada">`;
                }
            };
            reader.readAsDataURL(file);
        });
    }

    // =========================================================================
    // 2. GESTIÓN DINÁMICA DE HABILIDADES Y COMPETENCIAS (TAGS / BADGES)
    // =========================================================================
    const inputSkills = document.getElementById('inputSkills');
    const skillsBadgesContainer = document.getElementById('skillsBadgesContainer');
    const inputHabilidadesHidden = document.getElementById('inputHabilidadesHidden');

    // Lista inicial de competencias predefinidas o cargadas
    let skillsList = [
        'HTML5',
        'CSS3',
        'JavaScript',
        'PHP',
        'Git',
        'Diseño UI/UX'
    ];

    /**
     * Sincroniza la lista de competencias con el campo oculto
     */
    const syncSkillsHiddenInput = () => {
        if (inputHabilidadesHidden) {
            inputHabilidadesHidden.value = skillsList.join(', ');
        }
    };

    /**
     * Renderiza la lista actual de badges en el contenedor
     */
    const renderSkillsBadges = () => {
        if (!skillsBadgesContainer) return;
        skillsBadgesContainer.innerHTML = '';

        skillsList.forEach((skill, index) => {
            const badge = document.createElement('span');
            badge.className = 'skill-badge';
            badge.setAttribute('data-index', index);

            const textSpan = document.createElement('span');
            textSpan.className = 'skill-badge-text';
            textSpan.textContent = skill;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'skill-badge-remove';
            removeBtn.setAttribute('aria-label', `Eliminar habilidad ${skill}`);
            removeBtn.innerHTML = '&times;';

            removeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                removeSkill(index);
            });

            badge.appendChild(textSpan);
            badge.appendChild(removeBtn);
            skillsBadgesContainer.appendChild(badge);
        });

        syncSkillsHiddenInput();
    };

    /**
     * Elimina una competencia por índice
     */
    const removeSkill = (index) => {
        if (index >= 0 && index < skillsList.length) {
            skillsList.splice(index, 1);
            renderSkillsBadges();
        }
    };

    /**
     * Añade una o varias competencias (separadas por coma)
     */
    const addSkillsFromInput = () => {
        if (!inputSkills) return;
        const rawValue = inputSkills.value.trim();
        if (!rawValue) return;

        const incomingSkills = rawValue.split(',');
        let addedCount = 0;

        incomingSkills.forEach((item) => {
            const cleaned = item.trim();
            if (cleaned.length > 0) {
                // Evitar duplicados no sensibles a mayúsculas
                const exists = skillsList.some(s => s.toLowerCase() === cleaned.toLowerCase());
                if (!exists) {
                    skillsList.push(cleaned);
                    addedCount++;
                }
            }
        });

        if (addedCount > 0) {
            renderSkillsBadges();
        }
        inputSkills.value = '';
    };

    if (inputSkills) {
        // Al presionar Enter o coma, agregar la habilidad
        inputSkills.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSkillsFromInput();
            } else if (e.key === ',') {
                e.preventDefault();
                addSkillsFromInput();
            }
        });

        // Al perder el foco con texto remanente, procesarlo
        inputSkills.addEventListener('blur', () => {
            if (inputSkills.value.trim().length > 0) {
                addSkillsFromInput();
            }
        });

        // Renderizado inicial
        renderSkillsBadges();
    }

    // =========================================================================
    // 3. SIMULACIÓN DE GUARDADO CON FEEDBACK VISUAL
    // =========================================================================
    const formEditarPerfil = document.getElementById('formEditarPerfil');

    if (formEditarPerfil) {
        formEditarPerfil.addEventListener('submit', (e) => {
            e.preventDefault();

            // Procesar cualquier texto pendiente en el input de habilidades
            if (inputSkills && inputSkills.value.trim().length > 0) {
                addSkillsFromInput();
            }

            const btnSubmit = formEditarPerfil.querySelector('.btn-perfil-save');
            if (btnSubmit) {
                const originalText = btnSubmit.innerHTML;
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation: spinPerfil 0.8s linear infinite;">
                        <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
                        <path d="M12 2a10 10 0 0 1 10 10"></path>
                    </svg>
                    Guardando cambios...
                `;

                // Inyectar animación de giro si no existe
                if (!document.getElementById('perfilSpinAnimation')) {
                    const style = document.createElement('style');
                    style.id = 'perfilSpinAnimation';
                    style.textContent = '@keyframes spinPerfil { 100% { transform: rotate(360deg); } }';
                    document.head.appendChild(style);
                }

                // Simulación de red y redirección fluida a perfil.php con feedback de éxito
                setTimeout(() => {
                    window.location.href = 'perfil.php?guardado=1';
                }, 450);
            } else {
                window.location.href = 'perfil.php?guardado=1';
            }
        });
    }

    // =========================================================================
    // 4. CONTROL DE SIDEBAR RESPONSIVE (DRAWER Y COLAPSO)
    // =========================================================================
    const sidebar = document.getElementById('dashboardSidebar');
    const menuToggle = document.getElementById('dashboardMenuToggle');
    const backdrop = document.getElementById('sidebarBackdrop');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');

    if (sidebarToggleBtn && sidebar) {
        sidebarToggleBtn.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.contains('is-collapsed');
            sidebar.classList.toggle('is-collapsed', !isCollapsed);
            sidebar.classList.toggle('collapsed', !isCollapsed);
            document.documentElement.classList.toggle('sidebar-is-collapsed', !isCollapsed);
            try {
                localStorage.setItem('sidebar_collapsed', (!isCollapsed) ? 'true' : 'false');
            } catch (err) {}
        });
    }

    if (menuToggle && sidebar && backdrop) {
        const toggleDrawer = (open) => {
            const isOpen = open !== undefined ? open : !sidebar.classList.contains('is-open');
            sidebar.classList.toggle('is-open', isOpen);
            backdrop.classList.toggle('is-visible', isOpen);
            document.body.classList.toggle('sidebar-drawer-active', isOpen);
        };

        menuToggle.addEventListener('click', () => toggleDrawer());
        backdrop.addEventListener('click', () => toggleDrawer(false));
    }
});
