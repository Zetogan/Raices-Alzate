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
                    actualizarCompletitudLive();
                }
            };
            reader.readAsDataURL(file);
        });
    }

    // =========================================================================
    // 1.1 CÁLCULO DINÁMICO EN TIEMPO REAL DE COMPLETITUD (%)
    // =========================================================================
    const actualizarCompletitudLive = () => {
        const labelPct = document.getElementById('labelPorcentajeCompletitud');
        const barPct = document.getElementById('barPorcentajeCompletitud');
        if (!labelPct || !barPct) return;

        const val = (id) => {
            const el = document.getElementById(id);
            return el ? el.value.trim() : '';
        };

        const campos = [
            val('inputNombre').length > 0,
            val('inputEmailPrincipal').length > 0,
            val('textareaBio').length > 0,
            val('selectGenero').length > 0,
            val('inputNacimiento').length > 0,
            val('inputCiudad').length > 0,
            val('inputDireccion').length > 0,
            val('inputTelefono').length > 0,
            val('inputEmailSecundario').length > 0,
            val('inputTituloInstitucional').length > 0,
            val('inputAnioGraduacion').length > 0,
            val('inputProgramaSuperior').length > 0,
            val('inputInstitucionSuperior').length > 0,
            val('inputOcupacionRol').length > 0,
            val('inputEmpresa').length > 0,
            val('inputAreaEspecialidad').length > 0,
            val('inputAniosExperiencia').length > 0,
            skillsList.length > 0,
            val('inputLinkedin').length > 0,
            val('inputGithub').length > 0,
            val('inputWeb').length > 0,
            (document.querySelector('#avatarPreviewCircle img') !== null)
        ];

        const total = campos.length;
        const llenos = campos.filter(Boolean).length;
        const porcentaje = Math.round((llenos / total) * 100);

        labelPct.textContent = porcentaje + '%';
        barPct.style.width = porcentaje + '%';
    };

    // =========================================================================
    // 2. GESTIÓN DINÁMICA DE HABILIDADES Y COMPETENCIAS (TAGS / BADGES)
    // =========================================================================
    const inputSkills = document.getElementById('inputSkills');
    const skillsBadgesContainer = document.getElementById('skillsBadgesContainer');
    const inputHabilidadesHidden = document.getElementById('inputHabilidadesHidden');

    // Lista inicial de competencias leídas desde el valor real del input oculto
    let skillsList = [];
    if (inputHabilidadesHidden && inputHabilidadesHidden.value.trim().length > 0) {
        skillsList = inputHabilidadesHidden.value
            .split(',')
            .map(s => s.trim())
            .filter(s => s.length > 0);
    }

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
        actualizarCompletitudLive();
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
    // 3. ENVÍO REAL DEL FORMULARIO CON FEEDBACK VISUAL
    // =========================================================================
    const formEditarPerfil = document.getElementById('formEditarPerfil');

    if (formEditarPerfil) {
        // Escucha en tiempo real para recalcular el porcentaje dinámicamente si el usuario borra o agrega datos
        formEditarPerfil.addEventListener('input', actualizarCompletitudLive);
        formEditarPerfil.addEventListener('change', actualizarCompletitudLive);

        formEditarPerfil.addEventListener('submit', () => {
            // Procesar cualquier texto pendiente en el input de habilidades antes de enviar
            if (inputSkills && inputSkills.value.trim().length > 0) {
                addSkillsFromInput();
            }

            const btnSubmit = formEditarPerfil.querySelector('.btn-perfil-save');
            if (btnSubmit) {
                btnSubmit.style.pointerEvents = 'none';
                btnSubmit.style.opacity = '0.85';
                btnSubmit.textContent = 'Guardando cambios...';
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

    // =========================================================================
    // 5. NAVEGACIÓN Y ACCESIBILIDAD EN TARJETAS DE PROYECTOS ("Mis proyectos")
    // =========================================================================
    document.addEventListener('click', (e) => {
        const card = e.target.closest('.profile-project-card');
        if (!card) return;

        // Si el clic fue en el botón de edición o dentro de él, detener
        if (e.target.closest('.btn-editar-proyecto') || e.target.closest('.btn-editar-card')) {
            return;
        }

        // Si el clic fue en el enlace explícito de consultar proyecto, permitir acción nativa
        if (e.target.closest('.project-card-link')) {
            return;
        }

        // Navegación nativa ante cualquier clic sobre la tarjeta hacia detalle de proyecto
        const href = card.getAttribute('data-href') || 'index.php?page=detalle-proyecto';
        if (href) {
            window.location.href = href;
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            const activeCard = document.activeElement;
            if (activeCard && activeCard.classList.contains('profile-project-card')) {
                const href = activeCard.getAttribute('data-href') || 'index.php?page=detalle-proyecto';
                window.location.href = href;
            }
        }
    });
});
