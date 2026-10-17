/**
 * Raíces Alzate - Lógica Frontend para PQRS y Reporte de Errores
 * Arquitectura: Cero bases de datos, cero APIs externas, manipulación pura del DOM.
 * Versión: 0.1.1
 */

document.addEventListener('DOMContentLoaded', () => {
    // =========================================================================
    // 1. SELECTOR RÁPIDO DE TIPO DE SOLICITUD
    // =========================================================================
    const typeButtons = document.querySelectorAll('.pqr-type-btn');
    const selectTipo = document.getElementById('pqrTipoSolicitud');
    const errorFieldsWrap = document.getElementById('pqrErrorExtraFields');

    const updateTypeVisuals = (selectedType) => {
        typeButtons.forEach(btn => {
            const btnType = btn.getAttribute('data-type');
            btn.classList.toggle('active', btnType === selectedType);
        });

        // Mostrar campo específico si es reporte de error / bug
        if (errorFieldsWrap) {
            errorFieldsWrap.style.display = (selectedType === 'error') ? 'block' : 'none';
        }
    };

    typeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const type = btn.getAttribute('data-type');
            if (selectTipo) {
                selectTipo.value = type;
            }
            updateTypeVisuals(type);
        });
    });

    if (selectTipo) {
        selectTipo.addEventListener('change', (e) => {
            updateTypeVisuals(e.target.value);
        });
    }

    // =========================================================================
    // 2. CONTADOR DE CARACTERES EN TEXTAREA
    // =========================================================================
    const descTextarea = document.getElementById('pqrDescripcion');
    const charCountEl = document.getElementById('pqrCharCount');

    if (descTextarea && charCountEl) {
        descTextarea.addEventListener('input', () => {
            const len = descTextarea.value.length;
            charCountEl.textContent = `${len}/1000`;
            if (len >= 950) {
                charCountEl.style.color = '#D32F2F';
            } else {
                charCountEl.style.color = '#757575';
            }
        });
    }

    // =========================================================================
    // 3. ADJUNTO DE EVIDENCIA (FEEDBACK VISUAL DE ARCHIVO SELECCIONADO)
    // =========================================================================
    const fileInput = document.getElementById('pqrArchivo');
    const fileBox = document.getElementById('pqrFileUploadBox');
    const fileNameEl = document.getElementById('pqrSelectedFileName');

    if (fileInput && fileBox && fileNameEl) {
        fileBox.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                fileNameEl.textContent = `✓ Archivo cargado: ${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
                fileNameEl.style.display = 'block';
            } else {
                fileNameEl.textContent = '';
                fileNameEl.style.display = 'none';
            }
        });
    }

    // =========================================================================
    // 4. TOAST NOTIFICATION HELPER
    // =========================================================================
    const toast = document.getElementById('toastNotificacionPQRS') || document.getElementById('toastNotificacionEgresados');
    const toastMsg = document.getElementById('toastMensajePQRS') || document.getElementById('toastMensajeEgresados');
    let toastTimer = null;

    const mostrarToast = (mensaje) => {
        if (!toast) return;
        if (toastMsg) {
            toastMsg.textContent = mensaje;
        }
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 4000);
    };

    // =========================================================================
    // 5. RADICACIÓN Y VALIDACIÓN DEL FORMULARIO (DOM PURA)
    // =========================================================================
    const formPqr = document.getElementById('formRadicacionPQR');
    const ticketsList = document.getElementById('pqrTicketsList');
    const statTotalEl = document.getElementById('statTotalRadicados');
    const statTramiteEl = document.getElementById('statEnTramite');

    if (formPqr) {
        formPqr.addEventListener('submit', (e) => {
            e.preventDefault();

            const tipo = (selectTipo ? selectTipo.value : 'peticion') || 'peticion';
            const asunto = document.getElementById('pqrAsunto')?.value.trim();
            const modulo = document.getElementById('pqrModulo')?.value;
            const prioridad = document.getElementById('pqrPrioridad')?.value || 'Media';
            const descripcion = descTextarea?.value.trim();
            const nombre = document.getElementById('pqrNombre')?.value.trim() || 'Usuario Alzatista';

            if (!asunto) {
                alert('Por favor indica el asunto de tu solicitud.');
                document.getElementById('pqrAsunto')?.focus();
                return;
            }

            if (!descripcion || descripcion.length < 15) {
                alert('Por favor redacta una descripción detallada de al menos 15 caracteres.');
                descTextarea?.focus();
                return;
            }

            // Generar número de radicado institucional dinámico
            const randomCode = Math.floor(1000 + Math.random() * 9000);
            const radicadoNumber = `RAD-2026-${randomCode}`;

            // Etiqueta y clases según tipo
            const tipoLabels = {
                peticion: { label: 'Petición', class: 'badge-peticion' },
                queja: { label: 'Queja', class: 'badge-queja' },
                reclamo: { label: 'Reclamo', class: 'badge-reclamo' },
                sugerencia: { label: 'Sugerencia', class: 'badge-sugerencia' },
                felicitacion: { label: 'Felicitación', class: 'badge-felicitacion' },
                error: { label: 'Reporte de Error', class: 'badge-error' }
            };

            const tipoInfo = tipoLabels[tipo] || { label: 'Petición', class: 'badge-peticion' };

            // Crear elemento de tarjeta de radicado para insertar en el DOM
            const newTicket = document.createElement('article');
            newTicket.className = 'pqr-ticket-item';
            newTicket.setAttribute('data-status', 'tramite');
            newTicket.innerHTML = `
                <div class="pqr-ticket-top">
                    <span class="pqr-ticket-radicado">${radicadoNumber}</span>
                    <span class="pqr-badge-type ${tipoInfo.class}">${tipoInfo.label}</span>
                    <span class="pqr-badge-status status-tramite">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        En trámite
                    </span>
                </div>
                <h3 class="pqr-ticket-subject">${asunto}</h3>
                <div class="pqr-ticket-meta">
                    <span><strong>Módulo:</strong> ${modulo || 'General'}</span>
                    <span><strong>Prioridad:</strong> ${prioridad}</span>
                    <span><strong>Fecha:</strong> Hoy</span>
                </div>
                <div class="pqr-ticket-detail-accordion">
                    <button type="button" class="pqr-toggle-details-btn" onclick="togglePqrRespuesta(this)">
                        Ver seguimiento institucional
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="pqr-ticket-response-box">
                        <div class="pqr-response-header">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                            Seguimiento oficial — I.E. Gilberto Alzate Avendaño
                        </div>
                        <p class="pqr-response-text">
                            Tu solicitud ha sido radicada correctamente con el código <strong>${radicadoNumber}</strong>. El equipo encargado se encuentra revisando la información reportada y emitirá respuesta formal dentro de los términos institucionales.
                        </p>
                    </div>
                </div>
            `;

            // Insertar arriba en la lista
            if (ticketsList) {
                const emptyMsg = ticketsList.querySelector('.pqr-empty-tickets');
                if (emptyMsg) emptyMsg.remove();
                ticketsList.insertBefore(newTicket, ticketsList.firstChild);
            }

            // Actualizar contadores
            if (statTotalEl) {
                const cur = parseInt(statTotalEl.textContent) || 0;
                statTotalEl.textContent = cur + 1;
            }
            if (statTramiteEl) {
                const cur = parseInt(statTramiteEl.textContent) || 0;
                statTramiteEl.textContent = cur + 1;
            }

            // Notificación Toast
            mostrarToast(`¡Radicado exitoso! Código: ${radicadoNumber}. Recibirás respuesta formal.`);

            // Resetear formulario
            formPqr.reset();
            updateTypeVisuals('peticion');
            if (charCountEl) charCountEl.textContent = '0/1000';
            if (fileNameEl) {
                fileNameEl.textContent = '';
                fileNameEl.style.display = 'none';
            }
        });
    }

    // =========================================================================
    // 6. FILTRO DE TICKETS POR ESTADO (TODOS, EN TRÁMITE, RESUELTOS)
    // =========================================================================
    const filterTabs = document.querySelectorAll('.pqr-tab-filter-btn');
    filterTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            filterTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const filter = tab.getAttribute('data-filter');
            const items = document.querySelectorAll('.pqr-ticket-item');

            items.forEach(item => {
                const status = item.getAttribute('data-status');
                if (filter === 'todos') {
                    item.style.display = 'block';
                } else if (filter === 'tramite') {
                    item.style.display = (status === 'tramite' || status === 'revision') ? 'block' : 'none';
                } else if (filter === 'resueltos') {
                    item.style.display = (status === 'resuelto' || status === 'cerrado') ? 'block' : 'none';
                }
            });
        });
    });

    // =========================================================================
    // 7. ACORDEÓN DE RESPUESTA OFICIAL
    // =========================================================================
    window.togglePqrRespuesta = function (btn) {
        if (!btn) return;
        const parent = btn.closest('.pqr-ticket-detail-accordion');
        if (!parent) return;
        const box = parent.querySelector('.pqr-ticket-response-box');
        if (box) {
            box.classList.toggle('is-open');
            const svg = btn.querySelector('svg');
            if (svg) {
                svg.style.transform = box.classList.contains('is-open') ? 'rotate(180deg)' : 'rotate(0deg)';
                svg.style.transition = 'transform 0.2s ease';
            }
        }
    };
});
