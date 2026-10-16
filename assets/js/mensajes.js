/**
 * Raíces Alzate - Lógica de Mensajes / Comunicaciones
 * Interacciones: Pestañas (Conectados vs Solicitudes), Chat en tiempo real, Modal de Perfil de Egresado, Búsqueda y Gestión de Conexiones
 * Arquitectura: Frontend Puro (CERO bases de datos, CERO endpoints AJAX)
 * Versión: 0.1.2
 */

// Catálogo de perfiles detallados para el Modal de Egresado
const alumniProfiles = {
    '101': {
        name: 'Valentina Ríos Gómez',
        emphasis: 'Técnico en Sistemas e Informática',
        promo: 'Promoción 2021',
        ciudad: 'Medellín, Colombia',
        cargo: 'Ingeniera de Software',
        empresa: 'Mercado Libre',
        avatar: 'V',
        color: 'avatar-blue',
        bio: 'Exalumna destacada por su compromiso académico y comunitario en la I.E. Gilberto Alzate Avendaño. Participa activamente en redes profesionales y programas de mentoría para bachilleres alzatistas en desarrollo web y programación.',
        skills: ['PHP', 'React', 'MySQL', 'Liderazgo', 'Arquitectura Cloud'],
        email: 'valentina.rios@ejemplo.com',
        linkedin: 'https://linkedin.com'
    },
    '102': {
        name: 'Mateo Henao Morales',
        emphasis: 'Bachillerato Académico',
        promo: 'Promoción 2021',
        ciudad: 'Medellín, Colombia',
        cargo: 'Psicólogo Organizacional',
        empresa: 'Grupo Nutresa',
        avatar: 'M',
        color: 'avatar-green',
        bio: 'Especialista en desarrollo del talento y clima organizacional. Lidera iniciativas de inserción laboral y orientación vocacional para egresados alzatistas.',
        skills: ['Gestión de Talento', 'Liderazgo', 'Selección de Personal', 'Comunicación'],
        email: 'mateo.henao@ejemplo.com',
        linkedin: 'https://linkedin.com'
    },
    '103': {
        name: 'Laura Camila Gómez Duque',
        emphasis: 'Técnico en Sistemas e Informática',
        promo: 'Promoción 2022',
        ciudad: 'Medellín, Colombia',
        cargo: 'Desarrolladora Frontend',
        empresa: 'Ruta N',
        avatar: 'L',
        color: 'avatar-teal',
        bio: 'Apasionada por la tecnología web y el software de código abierto. Promueve semilleros de innovación juvenil e inclusión digital en la comuna.',
        skills: ['JavaScript', 'Vue.js', 'CSS/Sass', 'UI/UX', 'Git'],
        email: 'laura.gomez@ejemplo.com',
        linkedin: 'https://linkedin.com'
    },
    '104': {
        name: 'Sara Muñoz Cardona',
        emphasis: 'Técnico en Gestión Comercial y Contable',
        promo: 'Promoción 2023',
        ciudad: 'Medellín, Colombia',
        cargo: 'Mercadóloga Digital',
        empresa: 'Pragma',
        avatar: 'S',
        color: 'avatar-red',
        bio: 'Enfocada en estrategias de crecimiento digital y comercio electrónico. Coordinadora del comité de egresados alzatistas promoción 2023.',
        skills: ['Marketing Digital', 'SEO', 'Estrategia Comercial', 'Analítica Web'],
        email: 'sara.munoz@ejemplo.com',
        linkedin: 'https://linkedin.com'
    }
};

// =========================================================================
// 1. FUNCIONES GLOBALES: NAVEGACIÓN Y GESTIÓN DE SOLICITUDES
// =========================================================================
function abrirPerfilModal() {
    const activeContact = document.querySelector('.contact-card-item.active');
    const contactId = activeContact ? activeContact.getAttribute('data-contact-id') : '1';
    window.location.href = `detalle-egresado.php?id=${encodeURIComponent(contactId)}`;
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

    const name = card.getAttribute('data-name') || 'Carlos Andrés Morales';
    const promo = card.getAttribute('data-promo') || '2020';
    const cargo = card.getAttribute('data-cargo') || 'Coordinador Financiero en Bancolombia';
    const initial = card.getAttribute('data-initial') || name.charAt(0);
    const color = card.getAttribute('data-color') || 'avatar-teal';
    const enfasis = card.getAttribute('data-enfasis') || 'Técnico en Gestión Comercial y Contable';
    const newId = egresadoId ? egresadoId : ('c_' + Date.now());

    // Guardar perfil para el catálogo dinámico
    if (typeof alumniProfiles !== 'undefined') {
        alumniProfiles[newId] = {
            name: name,
            emphasis: enfasis,
            promo: `Promoción ${promo}`,
            ciudad: 'Medellín, Colombia',
            cargo: cargo,
            empresa: 'Bancolombia',
            avatar: initial,
            color: color,
            bio: `Egresado alzatista de la promoción ${promo}. Conectado a través de la red Raíces Alzate.`,
            skills: ['Finanzas', 'Comercial', 'Liderazgo'],
            email: 'contacto.egresado@ejemplo.com',
            linkedin: 'https://linkedin.com'
        };
    }

    // Sincronizar y remover otras instancias con el mismo nombre si las hay
    const allCardsWithName = document.querySelectorAll(`.solicitud-card[data-name="${name}"]`);
    allCardsWithName.forEach(c => {
        if (c !== card) {
            c.classList.add('is-removing');
            setTimeout(() => c.remove(), 250);
        }
    });

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
            <p class="solicitud-success-desc">Ahora estás conectado con <strong>${escapeHtml(name)}</strong>.</p>
            <button type="button" class="btn-chat-direct btn-iniciar-chat-nuevo" data-new-id="${newId}" onclick="event.stopPropagation();">
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
    const threadMessagesBody = document.getElementById('threadMessagesBody');
    const typingIndicatorRow = document.getElementById('typingIndicatorRow');

    if (contactsList) {
        const newContactCard = document.createElement('div');
        newContactCard.className = 'contact-card-item';
        newContactCard.setAttribute('data-contact-id', newId);
        newContactCard.setAttribute('data-name', name);
        newContactCard.setAttribute('data-role', `${cargo} · Prom. ${promo}`);
        newContactCard.setAttribute('data-status', 'En línea');
        newContactCard.setAttribute('data-online', 'true');
        newContactCard.setAttribute('data-initial', initial);
        newContactCard.setAttribute('data-color', color);

        newContactCard.innerHTML = `
            <div class="contact-avatar-wrapper">
                <div class="contact-avatar ${color}">${initial}</div>
                <span class="contact-status-dot online"></span>
            </div>
            <div class="contact-meta-info">
                <div class="contact-row-top">
                    <span class="contact-name">${escapeHtml(name)}</span>
                    <span class="contact-time">Ahora</span>
                </div>
                <span class="contact-role-snippet">${escapeHtml(cargo)}</span>
                <div class="contact-row-bottom">
                    <span class="contact-last-msg">Inicia la conversación...</span>
                </div>
            </div>
        `;

        if (typeof window.bindContactItemClickGlobal === 'function') {
            window.bindContactItemClickGlobal(newContactCard);
        }
        contactsList.insertBefore(newContactCard, contactsList.firstChild);

        if (threadMessagesBody) {
            const newThread = document.createElement('div');
            newThread.className = 'chat-thread-container';
            newThread.id = `thread-messages-${newId}`;
            newThread.style.display = 'none';
            newThread.style.flexDirection = 'column';
            newThread.style.gap = '16px';
            newThread.innerHTML = `
                <div class="thread-date-separator">
                    <span class="thread-date-badge">Hoy</span>
                </div>
                <div class="chat-system-notice">Has conectado con ${escapeHtml(name)}. ¡Inicia la conversación!</div>
            `;
            if (typingIndicatorRow) {
                threadMessagesBody.insertBefore(newThread, typingIndicatorRow);
            } else {
                threadMessagesBody.appendChild(newThread);
            }
        }
    }

    if (typeof window.mostrarToastGlobal === 'function') {
        window.mostrarToastGlobal(`Has aceptado la conexión con ${name}.`);
    }

    const btnDirect = card.querySelector('.btn-iniciar-chat-nuevo');
    if (btnDirect) {
        btnDirect.addEventListener('click', (e) => {
            e.stopPropagation();
            const tabConectados = document.getElementById('tabConectados') || document.getElementById('tabBtnChats');
            if (tabConectados) tabConectados.click();
            const targetContact = contactsList?.querySelector(`[data-contact-id="${newId}"]`);
            if (targetContact) {
                targetContact.click();
            }
        });
    }
}

// Rechazar solicitud de conexión (invocable desde onclick o listener)
function rechazarSolicitud(btn) {
    if (!btn) return;
    const card = btn.closest('.solicitud-card, .request-card-large');
    if (!card) return;

    // Desactivar inmediatamente la redirección al perfil en la tarjeta
    card.removeAttribute('onclick');
    card.onclick = null;
    card.style.cursor = 'default';

    const name = card.getAttribute('data-name') || 'Egresado';
    
    // Sincronizar todas las tarjetas con ese nombre
    const allCardsWithName = document.querySelectorAll(`.solicitud-card[data-name="${name}"]`);
    allCardsWithName.forEach(c => c.classList.add('is-removing'));

    setTimeout(() => {
        allCardsWithName.forEach(c => c.remove());
        if (typeof window.updatePendingBadgeGlobal === 'function') {
            window.updatePendingBadgeGlobal(-1);
        }
        if (typeof window.mostrarToastGlobal === 'function') {
            window.mostrarToastGlobal(`Solicitud de ${name} rechazada.`);
        }
    }, 300);
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

    const simulatedReplies = [
        '¡Excelente Santiago! Cuenta conmigo, coordinamos por aquí cualquier detalle.',
        'Perfecto, quedo muy pendiente de tu mensaje. ¡Saludos!',
        '¡Qué buena iniciativa! Estaré revisando la información para colaborar.',
        'Totalmente de acuerdo. Sigamos en comunicación activa por este medio.'
    ];

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
            const contactId = item.getAttribute('data-contact-id') || '101';

            if (threadAvatar) {
                threadAvatar.textContent = initial;
                threadAvatar.className = `thread-avatar ${avatarColorClass}`;
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
                btnHeaderVerPerfil.setAttribute('href', `detalle-egresado.php?id=${encodeURIComponent(contactId)}`);
                btnHeaderVerPerfil.setAttribute('title', `Ver perfil de ${name}`);
            }
            const linkHeaderUser = document.getElementById('linkHeaderUser');
            if (linkHeaderUser) {
                linkHeaderUser.setAttribute('href', `detalle-egresado.php?id=${encodeURIComponent(contactId)}`);
                linkHeaderUser.setAttribute('title', `Ver perfil institucional de ${name}`);
            }

            // Mostrar thread de mensajes
            const allThreads = threadMessagesBody?.querySelectorAll('.chat-thread-container') || [];
            allThreads.forEach(t => t.style.display = 'none');

            const targetThread = document.getElementById(`thread-messages-${contactId}`);
            if (targetThread) {
                targetThread.style.display = 'flex';
            }

            if (typingIndicatorRow && threadMessagesBody) {
                threadMessagesBody.appendChild(typingIndicatorRow);
            }

            scrollToBottom();

            if (chatWrapper && window.innerWidth <= 768) {
                chatWrapper.classList.add('thread-open-mobile');
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
    // 8. ENVÍO DE MENSAJES Y RESPUESTA SIMULADA
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
        const contactId = activeContact ? activeContact.getAttribute('data-contact-id') : '101';
        const contactName = activeContact ? activeContact.getAttribute('data-name') : 'Egresado';
        let targetThread = document.getElementById(`thread-messages-${contactId}`);

        if (!targetThread && threadMessagesBody) {
            targetThread = document.createElement('div');
            targetThread.className = 'chat-thread-container';
            targetThread.id = `thread-messages-${contactId}`;
            targetThread.style.display = 'flex';
            targetThread.style.flexDirection = 'column';
            targetThread.style.gap = '16px';
            threadMessagesBody.insertBefore(targetThread, typingIndicatorRow);
        }

        const currentTime = getFormattedTime();

        const outgoingRow = document.createElement('div');
        outgoingRow.className = 'msg-row outgoing';
        outgoingRow.innerHTML = `
            <div class="msg-bubble">
                ${escapeHtml(texto)}
                <div class="msg-footer-meta">
                    <span>${currentTime}</span>
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
        inputChatMessage.focus();
        scrollToBottom();

        if (activeContact) {
            const lastMsgEl = activeContact.querySelector('.contact-last-msg');
            const timeEl = activeContact.querySelector('.contact-time');
            if (lastMsgEl) lastMsgEl.textContent = `Tú: ${texto}`;
            if (timeEl) timeEl.textContent = currentTime;

            if (contactsList && contactsList.firstChild !== activeContact) {
                contactsList.insertBefore(activeContact, contactsList.firstChild);
            }
        }

        // Respuesta simulada tras 1.2 segundos
        setTimeout(() => {
            if (typingIndicatorRow) {
                typingIndicatorRow.classList.add('active');
                scrollToBottom();
            }

            setTimeout(() => {
                if (typingIndicatorRow) {
                    typingIndicatorRow.classList.remove('active');
                }

                const randomReply = simulatedReplies[Math.floor(Math.random() * simulatedReplies.length)];
                const replyTime = getFormattedTime();

                const contactColor = activeContact ? activeContact.getAttribute('data-color') : 'avatar-blue';
                const contactInitial = activeContact ? activeContact.getAttribute('data-initial') : 'E';

                const incomingRow = document.createElement('div');
                incomingRow.className = 'msg-row incoming';
                incomingRow.innerHTML = `
                    <div class="msg-mini-avatar ${contactColor}">
                        ${contactInitial}
                    </div>
                    <div class="msg-bubble">
                        ${escapeHtml(randomReply)}
                        <div class="msg-footer-meta">
                            <span>${replyTime}</span>
                        </div>
                    </div>
                `;

                targetThread.appendChild(incomingRow);
                scrollToBottom();

                if (activeContact) {
                    const lastMsgEl = activeContact.querySelector('.contact-last-msg');
                    const timeEl = activeContact.querySelector('.contact-time');
                    if (lastMsgEl) lastMsgEl.textContent = randomReply;
                    if (timeEl) timeEl.textContent = replyTime;
                }

                mostrarToast(`Nuevo mensaje de ${contactName}`);
            }, 1400);
        }, 600);
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
    // 9. GESTIÓN DE SOLICITUDES DE CONEXIÓN (Aceptar / Rechazar)
    // =========================================================================
    const btnAcceptList = document.querySelectorAll('.btn-solicitud-accept, .btn-accept-request');
    const btnRejectList = document.querySelectorAll('.btn-solicitud-reject, .btn-reject-request');
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

    // ACEPTAR SOLICITUD
    btnAcceptList.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const card = btn.closest('.solicitud-card, .request-card-large');
            const egresadoId = card ? card.getAttribute('data-id') : null;
            aceptarSolicitud(btn, egresadoId);
        });
    });

    // RECHAZAR SOLICITUD
    btnRejectList.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            rechazarSolicitud(btn);
        });
    });
});
