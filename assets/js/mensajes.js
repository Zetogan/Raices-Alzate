/**
 * Raíces Alzate - Lógica de Mensajes / Comunicaciones
 * Interacciones: Pestañas (Conectados vs Solicitudes), Chat en tiempo real, Búsqueda y Gestión de Conexiones
 * Arquitectura: 100% Mensajes Reales Usuario a Usuario respaldados en MySQL con Polling Asíncrono
 * CERO MENSAJES AUTOMÁTICOS NI RESPUESTAS SIMULADAS
 * Versión: 0.2.0
 */

// =========================================================================
// 1. FUNCIONES GLOBALES: NAVEGACIÓN Y GESTIÓN DE SOLICITUDES
// =========================================================================
function abrirPerfilModal() {
    const activeContact = document.querySelector('.contact-card-item.active');
    const contactId = activeContact ? activeContact.getAttribute('data-contact-id') : '1';
    window.location.href = `index.php?page=detalle-egresado&id=${encodeURIComponent(contactId)}`;
}

function cerrarPerfilModal() {
    // Función de compatibilidad
}

// Aceptar solicitud de conexión (invocable desde onclick o listener)
function aceptarSolicitud(btn, egresadoId) {
    if (!btn) return;
    const card = btn.closest('.solicitud-card, .request-card-large');
    if (!card) return;

    // Desactivar inmediatamente la redirección al perfil en la tarjeta
    card.removeAttribute('onclick');
    card.onclick = null;
    card.style.cursor = 'default';

    const targetId = egresadoId || card.getAttribute('data-id');
    const name = card.getAttribute('data-name') || 'Egresado Alzatista';
    const csrfToken = document.getElementById('csrfTokenMensajes')?.value || '';

    // Deshabilitar botones mientras se procesa
    card.querySelectorAll('button').forEach(b => b.disabled = true);

    const formData = new FormData();
    formData.append('solicitante_id', targetId);
    formData.append('accion', 'aceptar');
    formData.append('csrf_token', csrfToken);

    fetch('api/responder-solicitud.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            if (typeof window.mostrarToastGlobal === 'function') {
                window.mostrarToastGlobal(data.error || 'Error al aceptar solicitud.');
            }
            card.querySelectorAll('button').forEach(b => b.disabled = false);
            return;
        }

        const contacto = data.contacto || {
            id: targetId,
            nombre: name,
            cargo: card.getAttribute('data-cargo') || 'Egresado Alzatista',
            promocion: card.getAttribute('data-promo') || 'Alzatista',
            inicial: card.getAttribute('data-initial') || name.charAt(0),
            color: card.getAttribute('data-color') || 'avatar-teal',
            foto_url: card.getAttribute('data-avatar-img') || card.querySelector('.solicitud-avatar img')?.getAttribute('src') || ''
        };

        const escapeHtml = (str) => {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };

        // Transformar la tarjeta en estado de éxito sin redirección
        card.innerHTML = `
            <div class="solicitud-card-success" onclick="event.stopPropagation();">
                <div class="solicitud-success-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <h3 class="solicitud-success-title">¡Conexión establecida con éxito!</h3>
                <p class="solicitud-success-desc">Ahora estás conectado con <strong>${escapeHtml(contacto.nombre)}</strong>.</p>
                <button type="button" class="btn-chat-direct btn-iniciar-chat-nuevo" data-new-id="${contacto.id}" onclick="event.stopPropagation();">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Abrir conversación
                </button>
            </div>
        `;

        if (typeof window.updatePendingBadgeGlobal === 'function') {
            window.updatePendingBadgeGlobal(-1);
        }

        const contactsList = document.getElementById('contactsScrollArea');
        const emptyListNotice = contactsList ? contactsList.querySelector('.contacts-empty-list') : null;
        if (emptyListNotice) emptyListNotice.remove();

        const threadMessagesBody = document.getElementById('threadMessagesBody');
        const typingIndicatorRow = document.getElementById('typingIndicatorRow');

        if (contactsList) {
            let existingContact = contactsList.querySelector(`[data-contact-id="${contacto.id}"]`);
            if (!existingContact) {
                const newContactCard = document.createElement('div');
                newContactCard.className = 'contact-card-item';
                newContactCard.setAttribute('data-contact-id', contacto.id);
                newContactCard.setAttribute('data-name', contacto.nombre);
                newContactCard.setAttribute('data-role', `${contacto.cargo} · Prom. ${contacto.promocion}`);
                newContactCard.setAttribute('data-status', 'En línea');
                newContactCard.setAttribute('data-online', 'true');
                newContactCard.setAttribute('data-initial', contacto.inicial);
                newContactCard.setAttribute('data-color', contacto.color);
                newContactCard.setAttribute('data-avatar-img', contacto.foto_url || '');

                const avatarHtml = contacto.foto_url 
                    ? `<img src="${escapeHtml(contacto.foto_url)}" alt="${escapeHtml(contacto.nombre)}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`
                    : escapeHtml(contacto.inicial);

                newContactCard.innerHTML = `
                    <div class="contact-avatar-wrapper">
                        <div class="contact-avatar ${contacto.foto_url ? '' : contacto.color}">${avatarHtml}</div>
                        <span class="contact-status-dot online"></span>
                    </div>
                    <div class="contact-meta-info">
                        <div class="contact-row-top">
                            <span class="contact-name">${escapeHtml(contacto.nombre)}</span>
                            <span class="contact-time">Ahora</span>
                        </div>
                        <span class="contact-role-snippet">${escapeHtml(contacto.cargo)}</span>
                        <div class="contact-row-bottom">
                            <span class="contact-last-msg">Inicia la conversación...</span>
                        </div>
                    </div>
                `;

                if (typeof window.bindContactItemClickGlobal === 'function') {
                    window.bindContactItemClickGlobal(newContactCard);
                }
                contactsList.insertBefore(newContactCard, contactsList.firstChild);
            }

            const countTag = document.getElementById('contactsCountTag');
            if (countTag) {
                const totalCards = contactsList.querySelectorAll('.contact-card-item').length;
                countTag.textContent = `${totalCards} activos`;
            }

            if (threadMessagesBody) {
                let targetThread = document.getElementById(`thread-messages-${contacto.id}`);
                if (!targetThread) {
                    targetThread = document.createElement('div');
                    targetThread.className = 'chat-thread-container';
                    targetThread.id = `thread-messages-${contacto.id}`;
                    targetThread.style.display = 'none';
                    targetThread.style.flexDirection = 'column';
                    targetThread.style.gap = '16px';
                    targetThread.innerHTML = `
                        <div class="thread-date-separator">
                            <span class="thread-date-badge">Hoy</span>
                        </div>
                        <div class="chat-system-notice" style="text-align: center; color: #757575; font-size: 0.88rem; padding: 24px 0;">Has conectado con ${escapeHtml(contacto.nombre)}. ¡Inicia la conversación!</div>
                    `;
                    if (typingIndicatorRow) {
                        threadMessagesBody.insertBefore(targetThread, typingIndicatorRow);
                    } else {
                        threadMessagesBody.appendChild(targetThread);
                    }
                }
            }
        }

        if (typeof window.mostrarToastGlobal === 'function') {
            window.mostrarToastGlobal(data.mensaje || `Has aceptado la conexión con ${contacto.nombre}.`);
        }

        const btnDirect = card.querySelector('.btn-iniciar-chat-nuevo');
        if (btnDirect) {
            btnDirect.addEventListener('click', (e) => {
                e.stopPropagation();
                const tabConectados = document.getElementById('tabConectados') || document.getElementById('tabBtnChats');
                if (tabConectados) tabConectados.click();
                const targetContact = contactsList?.querySelector(`[data-contact-id="${contacto.id}"]`);
                if (targetContact) {
                    targetContact.click();
                }
            });
        }
    })
    .catch(err => {
        if (typeof window.mostrarToastGlobal === 'function') {
            window.mostrarToastGlobal('Error de conexión al procesar la solicitud.');
        }
        card.querySelectorAll('button').forEach(b => b.disabled = false);
    });
}

// Rechazar solicitud de conexión (invocable desde onclick o listener)
function rechazarSolicitud(btn, egresadoId) {
    if (!btn) return;
    const card = btn.closest('.solicitud-card, .request-card-large');
    if (!card) return;

    card.removeAttribute('onclick');
    card.onclick = null;
    card.style.cursor = 'default';

    const targetId = egresadoId || card.getAttribute('data-id');
    const name = card.getAttribute('data-name') || 'Egresado';
    const csrfToken = document.getElementById('csrfTokenMensajes')?.value || '';

    card.querySelectorAll('button').forEach(b => b.disabled = true);

    const formData = new FormData();
    formData.append('solicitante_id', targetId);
    formData.append('accion', 'rechazar');
    formData.append('csrf_token', csrfToken);

    fetch('api/responder-solicitud.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            if (typeof window.mostrarToastGlobal === 'function') {
                window.mostrarToastGlobal(data.error || 'Error al rechazar solicitud.');
            }
            card.querySelectorAll('button').forEach(b => b.disabled = false);
            return;
        }

        card.classList.add('is-removing');
        setTimeout(() => {
            card.remove();
            if (typeof window.updatePendingBadgeGlobal === 'function') {
                window.updatePendingBadgeGlobal(-1);
            }
            if (typeof window.mostrarToastGlobal === 'function') {
                window.mostrarToastGlobal(data.mensaje || `Solicitud de ${name} rechazada.`);
            }
        }, 300);
    })
    .catch(err => {
        if (typeof window.mostrarToastGlobal === 'function') {
            window.mostrarToastGlobal('Error de red al rechazar la solicitud.');
        }
        card.querySelectorAll('button').forEach(b => b.disabled = false);
    });
}

// Hacer accesibles globalmente en window
window.abrirPerfilModal = abrirPerfilModal;
window.cerrarPerfilModal = cerrarPerfilModal;
window.aceptarSolicitud = aceptarSolicitud;
window.rechazarSolicitud = rechazarSolicitud;

document.addEventListener('DOMContentLoaded', () => {
    // =========================================================================
    // 2. CONTROL DE SIDEBAR Y MENÚ RESPONSIVE
    // =========================================================================
    const sidebar = document.getElementById('dashboardSidebar');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const menuToggle = document.getElementById('dashboardMenuToggle');
    const backdrop = document.getElementById('sidebarBackdrop');

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

    if (menuToggle && sidebar && backdrop) {
        const toggleMobileSidebar = (open) => {
            const isOpen = open !== undefined ? open : !sidebar.classList.contains('is-open');
            sidebar.classList.toggle('is-open', isOpen);
            backdrop.classList.toggle('is-visible', isOpen);
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            document.body.classList.toggle('sidebar-drawer-active', isOpen);
        };

        menuToggle.addEventListener('click', (e) => {
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
    // 3. EVENTOS DE CIERRE DEL MODAL DE PERFIL (Esc y Click Exterior)
    // =========================================================================
    const modalEgresado = document.getElementById('modalEgresado');
    if (modalEgresado) {
        modalEgresado.addEventListener('click', (e) => {
            if (e.target === modalEgresado) {
                cerrarPerfilModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modalEgresado.classList.contains('modal-visible')) {
                cerrarPerfilModal();
            }
        });
    }

    // =========================================================================
    // 4. MANEJO DE ALTERNANCIA DE PESTAÑAS (REGLA SOLICITADA EXACTA)
    // =========================================================================
    const tabConectados = document.getElementById('tabConectados') || document.getElementById('tabBtnChats');
    const tabSolicitudes = document.getElementById('tabSolicitudes') || document.getElementById('tabBtnSolicitudes');
    const chatMainContainer = document.getElementById('mensajesChatWrapper') || document.querySelector('.chat-main-container');

    if (tabConectados) {
        tabConectados.addEventListener('click', function() {
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            if (tabSolicitudes) {
                tabSolicitudes.classList.remove('active');
                tabSolicitudes.setAttribute('aria-selected', 'false');
            }
            
            if (chatMainContainer) {
                chatMainContainer.classList.remove('view-requests');
            }

            const panelConectados = document.getElementById('panelConectados');
            const panelSolicitudes = document.getElementById('panelSolicitudes');
            const chatConversationArea = document.querySelector('.chat-conversation-area');
            const requestsMainView = document.getElementById('requestsMainView');

            // Restaurar vista estándar: 340px lista lateral y área de chat
            if (panelConectados) panelConectados.classList.remove('d-none');
            if (panelSolicitudes) panelSolicitudes.classList.add('d-none');
            
            // Mostrar chat y ocultar panel central de solicitudes
            if (chatConversationArea) chatConversationArea.classList.remove('d-none');
            if (requestsMainView) requestsMainView.classList.add('d-none');
        });
    }

    if (tabSolicitudes) {
        tabSolicitudes.addEventListener('click', function() {
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            if (tabConectados) {
                tabConectados.classList.remove('active');
                tabConectados.setAttribute('aria-selected', 'false');
            }
            
            // Activar clase view-requests para expandir a ancho completo
            if (chatMainContainer) {
                chatMainContainer.classList.add('view-requests');
            }

            const panelSolicitudes = document.getElementById('panelSolicitudes');
            const panelConectados = document.getElementById('panelConectados');
            const chatConversationArea = document.querySelector('.chat-conversation-area');
            const requestsMainView = document.getElementById('requestsMainView');

            // OCULTAR COLUMNA LATERAL POR COMPLETO (evitar duplicidad)
            if (panelSolicitudes) panelSolicitudes.classList.add('d-none');
            if (panelConectados) panelConectados.classList.add('d-none');
            
            // Ocultar área de chat y mostrar solicitudes a ancho 100%
            if (chatConversationArea) chatConversationArea.classList.add('d-none');
            if (requestsMainView) requestsMainView.classList.remove('d-none');
        });
    }

    // Auto-activar pestaña si viene con parámetro URL ?tab=solicitudes
    try {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'solicitudes' && tabSolicitudes) {
            tabSolicitudes.click();
        }
    } catch (e) {}

    // =========================================================================
    // 5. HELPER DE NOTIFICACIÓN TOAST
    // =========================================================================
    const toast = document.getElementById('toastMensajes');
    const toastMsg = document.getElementById('toastMensajeTexto');
    let toastTimer = null;

    const mostrarToast = (mensaje) => {
        if (!toast || !toastMsg) return;
        toastMsg.textContent = mensaje;
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 3500);
    };
    window.mostrarToastGlobal = mostrarToast;

    // =========================================================================
    // 6. BÚSQUEDA EN TIEMPO REAL DE CONTACTOS
    // =========================================================================
    const inputSearchContacts = document.getElementById('inputSearchContacts');
    const btnClearSearch = document.getElementById('btnClearContactsSearch');
    const contactsList = document.getElementById('contactsScrollArea');
    const emptySearchMsg = document.getElementById('contactsEmptySearch');

    const filterContacts = (query) => {
        const term = query.trim().toLowerCase();
        const contactItems = contactsList?.querySelectorAll('.contact-card-item') || [];
        let visibleCount = 0;

        contactItems.forEach((item) => {
            const name = item.getAttribute('data-name')?.toLowerCase() || '';
            const role = item.getAttribute('data-role')?.toLowerCase() || '';

            if (name.includes(term) || role.includes(term)) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (btnClearSearch) {
            btnClearSearch.classList.toggle('visible', term.length > 0);
        }

        if (emptySearchMsg) {
            emptySearchMsg.classList.toggle('visible', visibleCount === 0);
        }
    };

    if (inputSearchContacts) {
        inputSearchContacts.addEventListener('input', (e) => {
            filterContacts(e.target.value);
        });
    }

    if (btnClearSearch) {
        btnClearSearch.addEventListener('click', () => {
            if (inputSearchContacts) {
                inputSearchContacts.value = '';
                filterContacts('');
                inputSearchContacts.focus();
            }
        });
    }

    // =========================================================================
    // 7. SELECCIÓN DE CONVERSACIÓN ACTIVA Y CAMBIO DE HILO
    // =========================================================================
    const chatWrapper = document.getElementById('mensajesChatWrapper');
    const btnBackToContacts = document.getElementById('btnBackToContacts');
    const threadAvatar = document.getElementById('threadAvatar');
    const threadUserName = document.getElementById('threadUserName');
    const threadUserStatusText = document.getElementById('threadUserStatusText');
    const threadMessagesBody = document.getElementById('threadMessagesBody');
    const typingIndicatorRow = document.getElementById('typingIndicatorRow');
    const typingIndicatorName = document.getElementById('typingIndicatorName');
    const badgeTotalUnread = document.getElementById('badgeTotalUnread');

    const getFormattedTime = () => {
        const now = new Date();
        let hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        return `${hours}:${minutes} ${ampm}`;
    };

    const scrollToBottom = () => {
        if (threadMessagesBody) {
            threadMessagesBody.scrollTop = threadMessagesBody.scrollHeight;
        }
    };

    scrollToBottom();

    const bindContactItemClick = (item) => {
        item.addEventListener('click', () => {
            document.querySelectorAll('#panelConectados .contact-card-item').forEach(c => c.classList.remove('active'));
            item.classList.add('active');

            // Limpiar badge no leído
            const unreadBadge = item.querySelector('.contact-unread-badge');
            if (unreadBadge) {
                const count = parseInt(unreadBadge.textContent.trim(), 10) || 0;
                unreadBadge.remove();
                if (badgeTotalUnread) {
                    const currentTotal = parseInt(badgeTotalUnread.textContent.trim(), 10) || 0;
                    const newTotal = Math.max(0, currentTotal - count);
                    badgeTotalUnread.textContent = newTotal;
                    if (newTotal === 0) {
                        badgeTotalUnread.style.display = 'none';
                    }
                }
            }

            const name = item.getAttribute('data-name') || 'Egresado';
            const status = item.getAttribute('data-status') || 'En línea';
            const isOnline = item.getAttribute('data-online') === 'true';
            const initial = item.getAttribute('data-initial') || name.charAt(0);
            const avatarColorClass = item.getAttribute('data-color') || 'avatar-blue';
            const avatarImg = item.getAttribute('data-avatar-img');
            const contactId = item.getAttribute('data-contact-id') || '101';

            if (threadAvatar) {
                if (avatarImg) {
                    threadAvatar.innerHTML = `<img src="${escapeHtml(avatarImg)}" alt="${escapeHtml(name)}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
                    threadAvatar.className = 'thread-avatar';
                } else {
                    threadAvatar.textContent = initial;
                    threadAvatar.className = `thread-avatar ${avatarColorClass}`;
                }
            }
            if (threadUserName) threadUserName.textContent = name;
            if (threadUserStatusText) {
                threadUserStatusText.innerHTML = isOnline 
                    ? `<span class="thread-online-indicator"></span> ${status}`
                    : status;
            }
            if (typingIndicatorName) typingIndicatorName.textContent = name;

            // Actualizar enlace a detalle de egresado en la cabecera
            const btnHeaderVerPerfil = document.getElementById('btnHeaderVerPerfil');
            if (btnHeaderVerPerfil) {
                btnHeaderVerPerfil.setAttribute('href', `index.php?page=detalle-egresado&id=${encodeURIComponent(contactId)}`);
                btnHeaderVerPerfil.setAttribute('title', `Ver perfil de ${name}`);
            }
            const linkHeaderUser = document.getElementById('linkHeaderUser');
            if (linkHeaderUser) {
                linkHeaderUser.setAttribute('href', `index.php?page=detalle-egresado&id=${encodeURIComponent(contactId)}`);
                linkHeaderUser.setAttribute('title', `Ver perfil institucional de ${name}`);
            }

            // Ocultar estado vacío si estaba visible
            const emptySelection = document.getElementById('chatEmptySelection');
            if (emptySelection) {
                emptySelection.style.setProperty('display', 'none', 'important');
            }

            // Mostrar cabecera, cuerpo y caja de redacción
            const threadHeader = document.getElementById('threadHeader');
            if (threadHeader) {
                threadHeader.style.setProperty('display', 'flex', 'important');
            }
            if (threadMessagesBody) {
                threadMessagesBody.style.setProperty('display', 'flex', 'important');
            }
            const threadComposerBox = document.getElementById('threadComposerBox');
            if (threadComposerBox) {
                threadComposerBox.style.setProperty('display', 'flex', 'important');
            }

            // Mostrar thread de mensajes
            const allThreads = threadMessagesBody?.querySelectorAll('.chat-thread-container') || [];
            allThreads.forEach(t => t.style.display = 'none');

            let targetThread = document.getElementById(`thread-messages-${contactId}`);
            if (!targetThread && threadMessagesBody) {
                targetThread = document.createElement('div');
                targetThread.className = 'chat-thread-container';
                targetThread.id = `thread-messages-${contactId}`;
                targetThread.style.display = 'flex';
                targetThread.style.flexDirection = 'column';
                targetThread.style.gap = '16px';
                targetThread.innerHTML = `
                    <div class="thread-date-separator">
                        <span class="thread-date-badge">Hoy</span>
                    </div>
                    <div class="chat-system-notice" style="text-align: center; color: #757575; font-size: 0.88rem; padding: 24px 0;">Has conectado con ${escapeHtml(name)}. ¡Inicia la conversación!</div>
                `;
                if (typingIndicatorRow) {
                    threadMessagesBody.insertBefore(targetThread, typingIndicatorRow);
                } else {
                    threadMessagesBody.appendChild(targetThread);
                }
            } else if (targetThread) {
                targetThread.style.display = 'flex';
            }

            if (typingIndicatorRow && threadMessagesBody) {
                threadMessagesBody.appendChild(typingIndicatorRow);
            }

            scrollToBottom();

            if (inputChatMessage) {
                inputChatMessage.focus();
            }

            if (chatWrapper && window.innerWidth <= 768) {
                chatWrapper.classList.add('thread-open-mobile');
            }

            // Consultar inmediatamente mensajes pendientes del nuevo contacto seleccionado
            if (typeof pollMensajes === 'function') {
                pollMensajes();
            }
        });
    };
    window.bindContactItemClickGlobal = bindContactItemClick;

    document.querySelectorAll('#panelConectados .contact-card-item').forEach(bindContactItemClick);

    if (btnBackToContacts && chatWrapper) {
        btnBackToContacts.addEventListener('click', () => {
            chatWrapper.classList.remove('thread-open-mobile');
        });
    }

    // =========================================================================
    // 8. ENVÍO DE MENSAJES REALES DE USUARIO A USUARIO (CERO MENSAJES AUTOMÁTICOS)
    // =========================================================================
    const inputChatMessage = document.getElementById('inputChatMessage');
    const btnSendMessage = document.getElementById('btnSendMessage');

    const escapeHtml = (text) => {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    };

    const enviarMensaje = () => {
        if (!inputChatMessage) return;
        const texto = inputChatMessage.value.trim();
        if (!texto) return;

        const activeContact = document.querySelector('#panelConectados .contact-card-item.active');
        const contactId = activeContact ? activeContact.getAttribute('data-contact-id') : null;
        const contactName = activeContact ? activeContact.getAttribute('data-name') : 'Egresado';
        
        if (!contactId) {
            if (typeof window.mostrarToastGlobal === 'function') {
                window.mostrarToastGlobal('Por favor selecciona un contacto con quien conversar.');
            }
            return;
        }

        const csrfToken = document.getElementById('csrfTokenMensajes')?.value || '';
        const formData = new FormData();
        formData.append('destinatario_id', contactId);
        formData.append('mensaje', texto);
        formData.append('csrf_token', csrfToken);

        if (btnSendMessage) btnSendMessage.disabled = true;

        fetch('api/enviar-mensaje.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (btnSendMessage) btnSendMessage.disabled = false;
            if (!data.success) {
                if (typeof window.mostrarToastGlobal === 'function') {
                    window.mostrarToastGlobal(data.error || 'No se pudo enviar el mensaje.');
                }
                return;
            }

            let targetThread = document.getElementById(`thread-messages-${contactId}`);
            if (!targetThread && threadMessagesBody) {
                targetThread = document.createElement('div');
                targetThread.className = 'chat-thread-container';
                targetThread.id = `thread-messages-${contactId}`;
                targetThread.style.display = 'flex';
                targetThread.style.flexDirection = 'column';
                targetThread.style.gap = '16px';
                if (typingIndicatorRow) {
                    threadMessagesBody.insertBefore(targetThread, typingIndicatorRow);
                } else {
                    threadMessagesBody.appendChild(targetThread);
                }
            }

            // Quitar aviso de sistema si existía
            const notice = targetThread?.querySelector('.chat-system-notice');
            if (notice) notice.remove();

            const timeStr = data.mensaje?.hora || getFormattedTime();

            const outgoingRow = document.createElement('div');
            outgoingRow.className = 'msg-row outgoing';
            if (data.mensaje?.id) {
                outgoingRow.setAttribute('data-msg-id', data.mensaje.id);
            }
            outgoingRow.innerHTML = `
                <div class="msg-bubble">
                    ${escapeHtml(texto)}
                    <div class="msg-footer-meta">
                        <span>${escapeHtml(timeStr)}</span>
                        <span class="msg-status-check" title="Entregado">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6L7 17l-5-5"></path>
                                <path d="M22 10l-7.5 7.5L13 16"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            `;

            targetThread.appendChild(outgoingRow);
            inputChatMessage.value = '';
            inputChatMessage.style.height = 'auto';
            inputChatMessage.focus();
            scrollToBottom();

            if (activeContact) {
                const lastMsgEl = activeContact.querySelector('.contact-last-msg');
                const timeEl = activeContact.querySelector('.contact-time');
                if (lastMsgEl) lastMsgEl.textContent = `Tú: ${texto}`;
                if (timeEl) timeEl.textContent = timeStr;

                if (contactsList && contactsList.firstChild !== activeContact) {
                    contactsList.insertBefore(activeContact, contactsList.firstChild);
                }
            }
        })
        .catch(err => {
            if (btnSendMessage) btnSendMessage.disabled = false;
            if (typeof window.mostrarToastGlobal === 'function') {
                window.mostrarToastGlobal('Error de conexión al enviar el mensaje.');
            }
        });
    };

    if (btnSendMessage) {
        btnSendMessage.addEventListener('click', (e) => {
            e.preventDefault();
            enviarMensaje();
        });
    }

    if (inputChatMessage) {
        inputChatMessage.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                enviarMensaje();
            }
        });
    }

    // =========================================================================
    // 9. POLLING DE MENSAJES REALES EN TIEMPO REAL
    // =========================================================================
    const actualizarResumenNoLeidos = (resumenList) => {
        if (!contactsList || !Array.isArray(resumenList)) return;
        let totalGeneral = 0;
        const activeContact = document.querySelector('#panelConectados .contact-card-item.active');
        const activeId = activeContact ? activeContact.getAttribute('data-contact-id') : null;

        resumenList.forEach(item => {
            const senderId = String(item.remitente_id);
            const count = parseInt(item.count, 10) || 0;
            if (senderId !== activeId) {
                totalGeneral += count;
            }

            const card = contactsList.querySelector(`[data-contact-id="${senderId}"]`);
            if (card) {
                if (senderId !== activeId) {
                    let badge = card.querySelector('.contact-unread-badge');
                    if (count > 0) {
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'contact-unread-badge';
                            const rowBottom = card.querySelector('.contact-row-bottom');
                            if (rowBottom) rowBottom.appendChild(badge);
                        }
                        badge.textContent = count;
                    } else if (badge) {
                        badge.remove();
                    }
                }

                if (item.ultimo_mensaje) {
                    const lastMsgEl = card.querySelector('.contact-last-msg');
                    if (lastMsgEl) lastMsgEl.textContent = item.ultimo_mensaje;
                }
            }
        });

        if (badgeTotalUnread) {
            badgeTotalUnread.textContent = totalGeneral;
            badgeTotalUnread.style.display = totalGeneral > 0 ? 'inline-flex' : 'none';
        }
    };

    const pollMensajes = () => {
        const activeContact = document.querySelector('#panelConectados .contact-card-item.active');
        if (!activeContact) {
            fetch('api/obtener-mensajes.php?resumen=1')
                .then(res => res.json())
                .then(data => {
                    if (data.success && Array.isArray(data.resumen_no_leidos)) {
                        actualizarResumenNoLeidos(data.resumen_no_leidos);
                    }
                })
                .catch(() => {});
            return;
        }

        const contactId = activeContact.getAttribute('data-contact-id');
        if (!contactId) return;

        const targetThread = document.getElementById(`thread-messages-${contactId}`);
        const msgRows = targetThread ? targetThread.querySelectorAll('.msg-row[data-msg-id]') : [];
        let maxId = 0;
        msgRows.forEach(row => {
            const mid = parseInt(row.getAttribute('data-msg-id'), 10) || 0;
            if (mid > maxId) maxId = mid;
        });

        fetch(`api/obtener-mensajes.php?contacto_id=${encodeURIComponent(contactId)}&desde_id=${maxId}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) return;

                if (Array.isArray(data.resumen_no_leidos)) {
                    actualizarResumenNoLeidos(data.resumen_no_leidos);
                }

                // Actualizar estado de conexión en vivo
                if (typeof data.contacto_online === 'boolean') {
                    activeContact.setAttribute('data-online', data.contacto_online ? 'true' : 'false');
                    activeContact.setAttribute('data-status', data.contacto_estado || (data.contacto_online ? 'En línea' : 'Desconectado'));
                    const statusDot = activeContact.querySelector('.contact-status-dot');
                    if (statusDot) {
                        statusDot.classList.toggle('online', data.contacto_online);
                    }
                    if (threadUserStatusText) {
                        threadUserStatusText.innerHTML = data.contacto_online 
                            ? `<span class="thread-online-indicator"></span> ${escapeHtml(data.contacto_estado || 'En línea')}`
                            : escapeHtml(data.contacto_estado || 'Desconectado');
                    }
                }

                if (Array.isArray(data.mensajes) && data.mensajes.length > 0) {
                    const contactColor = activeContact.getAttribute('data-color') || 'avatar-blue';
                    const contactInitial = activeContact.getAttribute('data-initial') || 'E';
                    const avatarImg = activeContact.getAttribute('data-avatar-img') || data.contacto_foto || '';
                    let hasNew = false;

                    let threadBox = targetThread;
                    if (!threadBox && threadMessagesBody) {
                        threadBox = document.createElement('div');
                        threadBox.className = 'chat-thread-container';
                        threadBox.id = `thread-messages-${contactId}`;
                        threadBox.style.display = 'flex';
                        threadBox.style.flexDirection = 'column';
                        threadBox.style.gap = '16px';
                        if (typingIndicatorRow) {
                            threadMessagesBody.insertBefore(threadBox, typingIndicatorRow);
                        } else {
                            threadMessagesBody.appendChild(threadBox);
                        }
                    }

                    data.mensajes.forEach(m => {
                        if (threadBox && !threadBox.querySelector(`[data-msg-id="${m.id}"]`)) {
                            hasNew = true;
                            const isIncoming = (m.tipo === 'incoming');
                            const row = document.createElement('div');
                            row.className = `msg-row ${m.tipo}`;
                            row.setAttribute('data-msg-id', m.id);

                            const miniAvatarHtml = isIncoming 
                                ? (avatarImg 
                                    ? `<div class="msg-mini-avatar"><img src="${escapeHtml(avatarImg)}" alt="${escapeHtml(activeContact.getAttribute('data-name') || '')}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;"></div>`
                                    : `<div class="msg-mini-avatar ${contactColor}">${contactInitial}</div>`)
                                : '';

                            row.innerHTML = `
                                ${miniAvatarHtml}
                                <div class="msg-bubble">
                                    ${escapeHtml(m.texto)}
                                    <div class="msg-footer-meta">
                                        <span>${escapeHtml(m.hora)}</span>
                                        ${!isIncoming ? `
                                        <span class="msg-status-check" title="Entregado">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 6L7 17l-5-5"></path>
                                                <path d="M22 10l-7.5 7.5L13 16"></path>
                                            </svg>
                                        </span>` : ''}
                                    </div>
                                </div>
                            `;
                            threadBox.appendChild(row);

                            const lastMsgEl = activeContact.querySelector('.contact-last-msg');
                            const timeEl = activeContact.querySelector('.contact-time');
                            if (lastMsgEl) lastMsgEl.textContent = m.texto;
                            if (timeEl) timeEl.textContent = m.hora;
                        }
                    });

                    if (hasNew && threadBox) {
                        const notice = threadBox.querySelector('.chat-system-notice');
                        if (notice) notice.remove();
                        scrollToBottom();
                    }
                }
            })
            .catch(() => {});
    };

    setInterval(pollMensajes, 3500);

    // =========================================================================
    // 10. GESTIÓN DE CONTADORES DE SOLICITUDES
    // =========================================================================
    const badgePendingRequests = document.getElementById('badgePendingRequests');
    const requestsMainCountTag = document.getElementById('requestsMainCountTag');
    const solicitudesCounterTag = document.getElementById('solicitudesCounterTag');
    const solicitudesEmptyState = document.getElementById('solicitudesEmptyState');

    const updatePendingBadge = (delta) => {
        const updateElementCount = (el, d) => {
            if (!el) return 0;
            const current = parseInt(el.textContent.trim(), 10) || 0;
            const updated = Math.max(0, current + d);
            el.textContent = updated;
            return updated;
        };

        const count = updateElementCount(badgePendingRequests, delta);
        if (requestsMainCountTag) requestsMainCountTag.textContent = `${count} solicitudes`;
        if (solicitudesCounterTag) solicitudesCounterTag.textContent = `${count} pendientes`;

        if (count === 0) {
            if (badgePendingRequests) badgePendingRequests.style.display = 'none';
            if (solicitudesEmptyState) solicitudesEmptyState.style.display = 'flex';
        }
    };

    window.updatePendingBadgeGlobal = updatePendingBadge;
});
