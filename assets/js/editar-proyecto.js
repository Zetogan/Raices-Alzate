/**
 * Raíces Alzate - Módulo de Editar Proyecto (editar-proyecto.js)
 * Especificación Figma: Editar proyecto - Desktop (y adaptación Mobile)
 * Funcionalidad:
 *   - Control de Sidebar y Drawer móvil compatible con el Dashboard
 *   - Gestión de estado persistente del Sidebar en Desktop (localStorage)
 *   - Drag & drop, selección y previsualización de imagen en Dropzone
 *   - Soporte para quitar imagen existente o reemplazarla
 *   - Validación reactiva accesible de campos obligatorios en el cliente
 *   - Estado de envío controlado y prevención de dobles envíos
 * Versión: 0.1.1
 */

document.addEventListener('DOMContentLoaded', () => {
    // =========================================================================
    // 1. CONTROL DE NAVEGACIÓN Y MENÚ RESPONSIVE (DRAWER MÓVIL Y SIDEBAR)
    // =========================================================================
    const sidebar = document.getElementById('dashboardSidebar');
    const menuToggle = document.getElementById('dashboardMenuToggle');
    const backdrop = document.getElementById('sidebarBackdrop');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');

    // 1.1 Colapso de barra lateral en Desktop
    if (sidebarToggleBtn && sidebar) {
        const updateToggleState = (isCollapsed) => {
            sidebarToggleBtn.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
            sidebarToggleBtn.setAttribute('title', isCollapsed ? 'Expandir menú' : 'Contraer menú');
        };

        try {
            const savedCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
            if (savedCollapsed && window.innerWidth > 768) {
                sidebar.classList.add('is-collapsed', 'collapsed');
                document.documentElement.classList.add('sidebar-is-collapsed');
                updateToggleState(true);
            }
        } catch (err) {}

        sidebarToggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const isCurrentlyCollapsed = sidebar.classList.contains('is-collapsed');
            const nextCollapsed = !isCurrentlyCollapsed;

            sidebar.classList.toggle('is-collapsed', nextCollapsed);
            sidebar.classList.toggle('collapsed', nextCollapsed);
            document.documentElement.classList.toggle('sidebar-is-collapsed', nextCollapsed);

            try {
                localStorage.setItem('sidebar_collapsed', nextCollapsed ? 'true' : 'false');
            } catch (err) {}

            updateToggleState(nextCollapsed);
        });
    }

    // 1.2 Menú Drawer en dispositivos móviles
    if (menuToggle && sidebar && backdrop) {
        const toggleMobileSidebar = (open) => {
            const isOpen = open !== undefined ? open : !sidebar.classList.contains('is-open');
            sidebar.classList.toggle('is-open', isOpen);
            backdrop.classList.toggle('is-visible', isOpen);
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            document.body.classList.toggle('sidebar-drawer-active', isOpen);
        };

        menuToggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleMobileSidebar();
        });

        backdrop.addEventListener('click', () => {
            toggleMobileSidebar(false);
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
                toggleMobileSidebar(false);
                menuToggle.focus();
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 768 && sidebar.classList.contains('is-open')) {
                toggleMobileSidebar(false);
            }
        });
    }

    // =========================================================================
    // 2. GESTIÓN DEL DROPZONE DE IMAGEN EN EDICIÓN
    // =========================================================================
    const dropzone = document.getElementById('dropzoneImagen');
    const fileInput = document.getElementById('imagen_proyecto');
    const placeholder = document.getElementById('dropzonePlaceholder');
    const previewWrap = document.getElementById('dropzonePreviewWrap');
    const previewImg = document.getElementById('imgPreview');
    const previewFilename = document.getElementById('previewFilename');
    const btnCambiarImagen = document.getElementById('btnCambiarImagen');
    const btnQuitarImagen = document.getElementById('btnQuitarImagen');
    const inputQuitarImagen = document.getElementById('inputQuitarImagen');
    const errorImagen = document.getElementById('errorImagenProyecto');

    const formatosPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    const tamanoMaximoBytes = 5 * 1024 * 1024; // 5 MB

    // Formatear tamaño de archivo legible
    const formatearTamano = (bytes) => {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    };

    // Procesar archivo seleccionado
    const procesarArchivo = (file) => {
        if (!file) return;

        // Limpiar error previo
        if (errorImagen) {
            errorImagen.textContent = '';
            errorImagen.classList.remove('is-visible');
        }

        // Validación de tipo MIME
        if (!formatosPermitidos.includes(file.type)) {
            if (errorImagen) {
                errorImagen.textContent = 'Solo se admiten imágenes en formato PNG, JPG o WEBP.';
                errorImagen.classList.add('is-visible');
            }
            return;
        }

        // Validación de tamaño
        if (file.size > tamanoMaximoBytes) {
            if (errorImagen) {
                errorImagen.textContent = 'La imagen seleccionada supera el límite máximo de 5 MB.';
                errorImagen.classList.add('is-visible');
            }
            return;
        }

        // Previsualizar con FileReader
        const reader = new FileReader();
        reader.onload = (e) => {
            if (previewImg) previewImg.src = e.target.result;
            if (previewFilename) {
                previewFilename.textContent = `${file.name} (${formatearTamano(file.size)})`;
                previewFilename.setAttribute('title', file.name);
            }
            if (placeholder) placeholder.style.display = 'none';
            if (previewWrap) previewWrap.style.display = 'flex';
            if (inputQuitarImagen) inputQuitarImagen.value = '0';
        };
        reader.readAsDataURL(file);
    };

    // Restaurar dropzone a estado inicial sin imagen
    const limpiarImagen = () => {
        if (fileInput) fileInput.value = '';
        if (previewImg) previewImg.src = '';
        if (previewFilename) previewFilename.textContent = '';
        if (previewWrap) previewWrap.style.display = 'none';
        if (placeholder) {
            placeholder.style.display = 'flex';
            const actionText = document.getElementById('dropzoneActionText');
            if (actionText) actionText.textContent = 'Haz clic para seleccionar una imagen';
        }
        if (inputQuitarImagen) inputQuitarImagen.value = '1';
    };

    if (dropzone && fileInput) {
        // Clic en el contenedor activa selector de archivo
        dropzone.addEventListener('click', (e) => {
            if (e.target.closest('.preview-actions')) return;
            fileInput.click();
        });

        // Soporte de accesibilidad por teclado (Enter o Espacio)
        dropzone.addEventListener('keydown', (e) => {
            if ((e.key === 'Enter' || e.key === ' ') && !e.target.closest('.preview-actions')) {
                e.preventDefault();
                fileInput.click();
            }
        });

        // Cambio en el input de archivo
        fileInput.addEventListener('change', (e) => {
            const files = e.target.files;
            if (files && files.length > 0) {
                procesarArchivo(files[0]);
            }
        });

        // Eventos Drag & Drop
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('is-dragover');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('is-dragover');
            });
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt ? dt.files : null;
            if (files && files.length > 0) {
                fileInput.files = files;
                procesarArchivo(files[0]);
            }
        });

        // Botón "Cambiar imagen"
        if (btnCambiarImagen) {
            btnCambiarImagen.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                fileInput.click();
            });
        }

        // Botón "Quitar imagen"
        if (btnQuitarImagen) {
            btnQuitarImagen.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                limpiarImagen();
            });
        }
    }

    // =========================================================================
    // 3. VALIDACIÓN DEL FORMULARIO EN TIEMPO REAL Y AL ENVIAR
    // =========================================================================
    const form = document.getElementById('formEditarProyecto');
    const inputNombre = document.getElementById('nombre_proyecto');
    const selectCategoria = document.getElementById('categoria');
    const textareaDescripcion = document.getElementById('descripcion_proyecto');
    const inputEnlace = document.getElementById('enlace_proyecto');
    const btnSubmit = document.getElementById('btnGuardarCambios');

    const errorNombre = document.getElementById('errorNombreProyecto');
    const errorCategoria = document.getElementById('errorCategoria');
    const errorDescripcion = document.getElementById('errorDescripcionProyecto');
    const errorEnlace = document.getElementById('errorEnlaceProyecto');

    const mostrarError = (campo, contenedorError, mensaje) => {
        if (campo) campo.classList.add('has-error');
        if (contenedorError) {
            contenedorError.textContent = mensaje;
            contenedorError.classList.add('is-visible');
        }
    };

    const limpiarError = (campo, contenedorError) => {
        if (campo) campo.classList.remove('has-error');
        if (contenedorError) {
            contenedorError.textContent = '';
            contenedorError.classList.remove('is-visible');
        }
    };

    // Limpieza reactiva en eventos input/change
    if (inputNombre) {
        inputNombre.addEventListener('input', () => {
            if (inputNombre.value.trim().length > 0) {
                limpiarError(inputNombre, errorNombre);
            }
        });
    }

    if (selectCategoria) {
        selectCategoria.addEventListener('change', () => {
            if (selectCategoria.value !== '') {
                limpiarError(selectCategoria, errorCategoria);
            }
        });
    }

    if (textareaDescripcion) {
        textareaDescripcion.addEventListener('input', () => {
            if (textareaDescripcion.value.trim().length > 0) {
                limpiarError(textareaDescripcion, errorDescripcion);
            }
        });
    }

    if (inputEnlace) {
        inputEnlace.addEventListener('input', () => {
            limpiarError(inputEnlace, errorEnlace);
        });
    }

    // Validación general al someter el formulario
    if (form) {
        form.addEventListener('submit', (e) => {
            let isValid = true;
            let primerElementoConError = null;

            // 1. Validar Nombre del proyecto
            const nombreVal = inputNombre ? inputNombre.value.trim() : '';
            if (!nombreVal) {
                mostrarError(inputNombre, errorNombre, 'El nombre del proyecto es obligatorio.');
                isValid = false;
                if (!primerElementoConError) primerElementoConError = inputNombre;
            } else if (nombreVal.length < 3) {
                mostrarError(inputNombre, errorNombre, 'El nombre debe contener al menos 3 caracteres.');
                isValid = false;
                if (!primerElementoConError) primerElementoConError = inputNombre;
            } else {
                limpiarError(inputNombre, errorNombre);
            }

            // 2. Validar Categoría
            const catVal = selectCategoria ? selectCategoria.value : '';
            if (!catVal) {
                mostrarError(selectCategoria, errorCategoria, 'Por favor selecciona una categoría para tu proyecto.');
                isValid = false;
                if (!primerElementoConError) primerElementoConError = selectCategoria;
            } else {
                limpiarError(selectCategoria, errorCategoria);
            }

            // 3. Validar Descripción
            const descVal = textareaDescripcion ? textareaDescripcion.value.trim() : '';
            if (!descVal) {
                mostrarError(textareaDescripcion, errorDescripcion, 'La descripción del proyecto es obligatoria.');
                isValid = false;
                if (!primerElementoConError) primerElementoConError = textareaDescripcion;
            } else if (descVal.length < 10) {
                mostrarError(textareaDescripcion, errorDescripcion, 'La descripción debe tener al menos 10 caracteres.');
                isValid = false;
                if (!primerElementoConError) primerElementoConError = textareaDescripcion;
            } else {
                limpiarError(textareaDescripcion, errorDescripcion);
            }

            // 4. Validar Enlace opcional (si se ingresa, verificar URL)
            if (inputEnlace && inputEnlace.value.trim().length > 0) {
                const urlVal = inputEnlace.value.trim();
                try {
                    const testUrl = new URL(urlVal.match(/^https?:\/\//i) ? urlVal : 'https://' + urlVal);
                    if (!testUrl.hostname.includes('.')) {
                        mostrarError(inputEnlace, errorEnlace, 'Por favor ingresa una URL válida (ej. https://ejemplo.com).');
                        isValid = false;
                        if (!primerElementoConError) primerElementoConError = inputEnlace;
                    } else {
                        limpiarError(inputEnlace, errorEnlace);
                    }
                } catch (err) {
                    mostrarError(inputEnlace, errorEnlace, 'Por favor ingresa una URL válida.');
                    isValid = false;
                    if (!primerElementoConError) primerElementoConError = inputEnlace;
                }
            }

            if (!isValid) {
                e.preventDefault();
                if (primerElementoConError) {
                    primerElementoConError.focus();
                }
                return;
            }

            // Si es válido, indicar estado de guardado y prevenir dobles clics
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.textContent = 'Guardando cambios...';
            }
        });
    }
});
