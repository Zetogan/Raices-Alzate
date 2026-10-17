/**
 * Raíces Alzate - Módulo de Autenticación (JavaScript Client-side)
 * Manejo interactivo de estados de Figma para Inputs y Radio Buttons
 * Validación accesible, feedback visual y prevención de doble submit
 * Versión: 0.1.6
 */

document.addEventListener('DOMContentLoaded', () => {
    initLoginForm();
    initRegistroForm();
    initRecuperarForm();
    initResetPasswordForm();
});

// =============================================================================
// FUNCIONES COMPARTIDAS DE MANEJO DE ESTADOS VISUALES (FIGMA DESIGN SYSTEM)
// =============================================================================

/**
 * Aplica el estado de Error definido en Figma a un input:
 * - Asigna la clase de estado .input-error y .is-invalid (borde exterior en #D32F2F)
 * - Muestra el texto o placeholder en color rojo (#D32F2F)
 * - Coloca el mensaje descriptivo en el placeholder si el campo está vacío
 * - Activa el contenedor accesible de error si existe
 *
 * @param {HTMLInputElement} input - Elemento input objetivo
 * @param {string} errorMessage - Mensaje de validación
 * @param {HTMLElement|null} errorElement - Contenedor del mensaje de error
 */
function setInputError(input, errorMessage, errorElement) {
    if (!input) return;

    // Respaldar placeholder original si no se ha guardado previamente
    if (input.dataset.originalPlaceholder === undefined) {
        input.dataset.originalPlaceholder = input.placeholder || '';
    }

    input.classList.add('input-error', 'is-invalid');
    input.setAttribute('aria-invalid', 'true');

    // Si el campo quedó vacío, mostrar el mensaje directamente en el placeholder (se tiñe de rojo en CSS)
    if (input.value.trim() === '') {
        input.placeholder = errorMessage;
    }

    if (errorElement) {
        errorElement.textContent = errorMessage;
        errorElement.classList.add('is-visible');
        errorElement.style.display = 'block';
    }
}

/**
 * Limpia el estado de Error de un input inmediatamente:
 * - Restaura el placeholder original
 * - Remueve las clases .input-error e .is-invalid
 * - Restaura el color de texto a normal (#212121)
 * - Oculta el mensaje de error complementario
 *
 * @param {HTMLInputElement} input - Elemento input objetivo
 * @param {HTMLElement|null} errorElement - Contenedor del mensaje de error
 */
function clearInputError(input, errorElement) {
    if (!input) return;

    if (input.dataset.hasErrorState === 'true' || 
        input.value === 'Correo electrónico inválido.' || 
        input.value === 'Ingresa tu correo electrónico') {
        input.value = '';
        delete input.dataset.hasErrorState;
    }

    // Restaurar placeholder original si fue sustituido por el mensaje de error
    if (input.dataset.originalPlaceholder !== undefined) {
        input.placeholder = input.dataset.originalPlaceholder;
    }

    input.classList.remove('input-error', 'is-invalid');
    input.removeAttribute('aria-invalid');
    input.style.color = '#212121';

    if (errorElement) {
        errorElement.textContent = '';
        errorElement.classList.remove('is-visible');
        errorElement.style.display = 'none';
    }
}

/**
 * Aplica el estado de Error a un grupo de radio buttons:
 * - Contorno rojo (#D32F2F) en los radios
 * - Texto de las opciones teñido de rojo (#D32F2F)
 *
 * @param {HTMLElement|null} groupElement - Contenedor .radio-group
 * @param {Array<HTMLInputElement>} radioInputs - Lista de radio buttons
 * @param {string} errorMessage - Mensaje de error
 * @param {HTMLElement|null} errorElement - Contenedor del mensaje
 */
function setRadioGroupError(groupElement, radioInputs, errorMessage, errorElement) {
    if (groupElement) {
        groupElement.classList.add('input-error', 'is-invalid');
    }
    if (radioInputs) {
        radioInputs.forEach(radio => radio.classList.add('input-error', 'is-invalid'));
    }
    if (errorElement) {
        errorElement.textContent = errorMessage;
        errorElement.classList.add('is-visible');
        errorElement.style.display = 'block';
    }
}

/**
 * Limpia el estado de Error de un grupo de radio buttons
 *
 * @param {HTMLElement|null} groupElement - Contenedor .radio-group
 * @param {Array<HTMLInputElement>} radioInputs - Lista de radio buttons
 * @param {HTMLElement|null} errorElement - Contenedor del mensaje
 */
function clearRadioGroupError(groupElement, radioInputs, errorElement) {
    if (groupElement) {
        groupElement.classList.remove('input-error', 'is-invalid');
    }
    if (radioInputs) {
        radioInputs.forEach(radio => radio.classList.remove('input-error', 'is-invalid'));
    }
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.classList.remove('is-visible');
        errorElement.style.display = 'none';
    }
}

// Iconografía vectorial SVG para alertas corporativas
const alertIcons = {
    success: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>',
    warning: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>',
    error: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>',
    info: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>'
};

// =============================================================================
// INICIALIZACIÓN: FORMULARIO DE LOGIN (INICIO DE SESIÓN)
// =============================================================================
function initLoginForm() {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) {
        return;
    }

    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const submitBtn = document.getElementById('submitBtn');
    const feedbackBox = document.getElementById('formFeedback');

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let isSubmitting = false;

    // Respaldo preventivo de placeholders originales
    if (emailInput && emailInput.placeholder) {
        emailInput.dataset.originalPlaceholder = emailInput.placeholder;
    }
    if (passwordInput && passwordInput.placeholder) {
        passwordInput.dataset.originalPlaceholder = passwordInput.placeholder;
    }

    // Limpieza reactiva en eventos input y focus (Figma: limpia el estado en cuanto el usuario empiece a escribir/enfocar)
    if (emailInput) {
        emailInput.addEventListener('input', () => {
            clearInputError(emailInput, null);
            hideFeedback();
        });
        emailInput.addEventListener('focus', () => {
            if (emailInput.classList.contains('input-error')) {
                clearInputError(emailInput, null);
            }
        });
        emailInput.addEventListener('blur', () => validateLoginEmail(false));
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', () => {
            clearInputError(passwordInput, null);
            hideFeedback();
        });
        passwordInput.addEventListener('focus', () => {
            if (passwordInput.classList.contains('input-error')) {
                clearInputError(passwordInput, null);
            }
        });
        passwordInput.addEventListener('blur', () => validateLoginPassword(false));
    }

    function validateLoginEmail(isSubmitAttempt) {
        if (!emailInput) return true;
        const val = emailInput.value.trim();

        if (val === '') {
            if (isSubmitAttempt) {
                setInputError(emailInput, 'Ingresa tu correo electrónico', null);
            }
            return false;
        }

        if (!emailRegex.test(val)) {
            if (isSubmitAttempt) {
                setInputError(emailInput, 'Correo electrónico inválido', null);
            }
            return false;
        }

        clearInputError(emailInput, null);
        return true;
    }

    function validateLoginPassword(isSubmitAttempt) {
        if (!passwordInput) return true;
        const val = passwordInput.value;

        if (val === '') {
            if (isSubmitAttempt) {
                setInputError(passwordInput, 'Ingresa tu contraseña', null);
            }
            return false;
        }

        if (val.length < 6) {
            if (isSubmitAttempt) {
                setInputError(passwordInput, 'Mínimo 6 caracteres', null);
            }
            return false;
        }

        clearInputError(passwordInput, null);
        return true;
    }

    loginForm.addEventListener('submit', (e) => {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        hideFeedback();

        const isEmailValid = validateLoginEmail(true);
        const isPasswordValid = validateLoginPassword(true);

        if (!isEmailValid || !isPasswordValid) {
            e.preventDefault();
            showFeedback('Por favor, completa correctamente todos los campos obligatorios.', 'error');

            if (!isEmailValid && emailInput) {
                emailInput.focus();
            } else if (!isPasswordValid && passwordInput) {
                passwordInput.focus();
            }
            return;
        }

        isSubmitting = true;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.dataset.originalText = submitBtn.textContent;
            submitBtn.textContent = 'Iniciando sesión...';
        }
    });

    function showFeedback(message, type = 'error') {
        if (!feedbackBox) return;

        const iconContainer = feedbackBox.querySelector('.alert-icon') || document.getElementById('formFeedbackIcon');
        const textContainer = feedbackBox.querySelector('.alert-text') || document.getElementById('formFeedbackText');
        const selectedSvg = alertIcons[type] || alertIcons.error;

        if (iconContainer) {
            iconContainer.innerHTML = selectedSvg;
        }
        if (textContainer) {
            textContainer.textContent = message;
        } else {
            feedbackBox.innerHTML = `
                <span class="alert-icon" aria-hidden="true">${selectedSvg}</span>
                <p class="alert-text">${escapeHtml(message)}</p>
            `;
        }

        feedbackBox.className = 'alert alert-' + type;
        feedbackBox.style.display = 'flex';
    }

    function hideFeedback() {
        if (!feedbackBox) return;
        feedbackBox.style.display = 'none';
        const textContainer = feedbackBox.querySelector('.alert-text') || document.getElementById('formFeedbackText');
        if (textContainer) {
            textContainer.textContent = '';
        }
    }
}

// =============================================================================
// INICIALIZACIÓN: FORMULARIO DE REGISTRO (CREAR CUENTA)
// =============================================================================
function initRegistroForm() {
    const registroForm = document.getElementById('registroForm');
    if (!registroForm) {
        return;
    }

    const nombreInput = document.getElementById('nombre_completo');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirm');
    const submitBtn = document.getElementById('submitBtn');

    const radioGroup = registroForm.querySelector('.radio-group');
    const radioInputs = Array.from(registroForm.querySelectorAll('input[name="tipo_usuario"]'));

    const errorNombre = document.getElementById('error_nombre_completo');
    const errorEmail = document.getElementById('error_email');
    const errorRadio = document.getElementById('error_tipo_usuario');
    const errorPassword = document.getElementById('error_password');
    const errorConfirm = document.getElementById('error_password_confirm');

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let isSubmitting = false;

    // Respaldo de placeholders originales
    [nombreInput, emailInput, passwordInput, confirmInput].forEach(inp => {
        if (inp && inp.placeholder) {
            inp.dataset.originalPlaceholder = inp.placeholder;
        }
    });

    // Limpieza reactiva en eventos input y focus (Figma)
    if (nombreInput) {
        nombreInput.addEventListener('input', () => clearInputError(nombreInput, errorNombre));
        nombreInput.addEventListener('focus', () => {
            if (nombreInput.classList.contains('input-error')) clearInputError(nombreInput, errorNombre);
        });
        nombreInput.addEventListener('blur', () => validateNombre(false));
    }

    if (emailInput) {
        emailInput.addEventListener('input', () => clearInputError(emailInput, errorEmail));
        emailInput.addEventListener('focus', () => {
            if (emailInput.classList.contains('input-error')) clearInputError(emailInput, errorEmail);
        });
        emailInput.addEventListener('blur', () => validateEmail(false));
    }

    if (radioInputs.length > 0) {
        radioInputs.forEach(radio => {
            radio.addEventListener('change', () => clearRadioGroupError(radioGroup, radioInputs, errorRadio));
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', () => {
            clearInputError(passwordInput, errorPassword);
            if (confirmInput && confirmInput.value.trim() !== '') {
                validateConfirmPassword(false);
            }
        });
        passwordInput.addEventListener('focus', () => {
            if (passwordInput.classList.contains('input-error')) clearInputError(passwordInput, errorPassword);
        });
        passwordInput.addEventListener('blur', () => validatePassword(false));
    }

    if (confirmInput) {
        confirmInput.addEventListener('input', () => clearInputError(confirmInput, errorConfirm));
        confirmInput.addEventListener('focus', () => {
            if (confirmInput.classList.contains('input-error')) clearInputError(confirmInput, errorConfirm);
        });
        confirmInput.addEventListener('blur', () => validateConfirmPassword(false));
    }

    function validateNombre(isSubmitAttempt) {
        if (!nombreInput) return true;
        const value = nombreInput.value.trim();

        if (value === '') {
            if (isSubmitAttempt) {
                setInputError(nombreInput, 'Ingresa tu nombre completo', errorNombre);
            }
            return false;
        }

        if (value.length < 3) {
            if (isSubmitAttempt) {
                setInputError(nombreInput, 'Mínimo 3 caracteres', errorNombre);
            }
            return false;
        }

        clearInputError(nombreInput, errorNombre);
        return true;
    }

    function validateEmail(isSubmitAttempt) {
        if (!emailInput) return true;
        const value = emailInput.value.trim();

        if (value === '') {
            if (isSubmitAttempt) {
                setInputError(emailInput, 'Ingresa tu correo electrónico', errorEmail);
            }
            return false;
        }

        if (!emailRegex.test(value)) {
            if (isSubmitAttempt) {
                setInputError(emailInput, 'Ingresa un correo electrónico válido', errorEmail);
            }
            return false;
        }

        clearInputError(emailInput, errorEmail);
        return true;
    }

    function validateRadio(isSubmitAttempt) {
        if (!radioInputs || radioInputs.length === 0) return true;
        const isAnyChecked = radioInputs.some(r => r.checked);

        if (!isAnyChecked) {
            if (isSubmitAttempt) {
                setRadioGroupError(radioGroup, radioInputs, 'Selecciona una opción', errorRadio);
            }
            return false;
        }

        clearRadioGroupError(radioGroup, radioInputs, errorRadio);
        return true;
    }

    function validatePassword(isSubmitAttempt) {
        if (!passwordInput) return true;
        const value = passwordInput.value;

        if (value === '') {
            if (isSubmitAttempt) {
                setInputError(passwordInput, 'Ingresa una contraseña', errorPassword);
            }
            return false;
        }

        if (value.length < 6) {
            if (isSubmitAttempt) {
                setInputError(passwordInput, 'Mínimo 6 caracteres', errorPassword);
            }
            return false;
        }

        clearInputError(passwordInput, errorPassword);
        return true;
    }

    function validateConfirmPassword(isSubmitAttempt) {
        if (!confirmInput || !passwordInput) return true;
        const confirmVal = confirmInput.value;
        const passwordVal = passwordInput.value;

        if (confirmVal === '') {
            if (isSubmitAttempt) {
                setInputError(confirmInput, 'Confirma tu contraseña', errorConfirm);
            }
            return false;
        }

        if (confirmVal !== passwordVal) {
            if (isSubmitAttempt) {
                setInputError(confirmInput, 'Las contraseñas no coinciden', errorConfirm);
            }
            return false;
        }

        clearInputError(confirmInput, errorConfirm);
        return true;
    }

    registroForm.addEventListener('submit', (e) => {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        const isNombreValid = validateNombre(true);
        const isEmailValid = validateEmail(true);
        const isRadioValid = validateRadio(true);
        const isPasswordValid = validatePassword(true);
        const isConfirmValid = validateConfirmPassword(true);

        const isFormValid = isNombreValid && isEmailValid && isRadioValid && isPasswordValid && isConfirmValid;

        if (!isFormValid) {
            e.preventDefault();

            if (!isNombreValid && nombreInput) {
                nombreInput.focus();
            } else if (!isEmailValid && emailInput) {
                emailInput.focus();
            } else if (!isRadioValid && radioInputs[0]) {
                radioInputs[0].focus();
            } else if (!isPasswordValid && passwordInput) {
                passwordInput.focus();
            } else if (!isConfirmValid && confirmInput) {
                confirmInput.focus();
            }
            return;
        }

        const selectedRolInput = document.querySelector('input[name="tipo_usuario"]:checked');
        const tipoUsuario = selectedRolInput ? selectedRolInput.value : 'estudiante';

        const payload = {
            nombre_completo: nombreInput.value.trim(),
            email: emailInput.value.trim(),
            tipo_usuario: tipoUsuario,
            password: passwordInput.value,
            password_confirm: confirmInput.value
        };

        console.info('[Raíces Alzate] Payload de registro preparado (JSON):', JSON.stringify(payload, null, 2));

        isSubmitting = true;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.dataset.originalText = submitBtn.textContent;
            submitBtn.textContent = 'Registrando...';
        }
    });
}

// =============================================================================
// INICIALIZACIÓN: FORMULARIO DE RECUPERACIÓN DE CONTRASEÑA
// =============================================================================
function initRecuperarForm() {
    const recuperarForm = document.getElementById('recuperarForm');
    if (!recuperarForm) {
        return;
    }

    const inputCorreo = document.getElementById('correo') || document.getElementById('email');
    const submitBtn = document.getElementById('submitBtn');
    const errorCorreo = document.getElementById('error_correo');
    const feedbackBox = document.getElementById('formFeedback');
    const feedbackText = document.getElementById('formFeedbackText');
    const feedbackIcon = document.getElementById('formFeedbackIcon');

    // Expresión regular recomendada para validación de formato de correo legítimo
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let isSubmitting = false;

    // Respaldo preventivo de placeholder original
    if (inputCorreo && inputCorreo.placeholder) {
        inputCorreo.dataset.originalPlaceholder = inputCorreo.placeholder;
    }

    function showFeedback(message, type = 'error') {
        if (!feedbackBox) return;
        if (feedbackText) feedbackText.textContent = message;
        feedbackBox.className = `alert alert-${type}`;
        if (feedbackIcon) {
            feedbackIcon.innerHTML = type === 'success'
                ? '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>'
                : '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>';
        }
        feedbackBox.style.display = 'flex';
    }

    function hideFeedback() {
        if (!feedbackBox) return;
        feedbackBox.style.display = 'none';
        if (feedbackText) feedbackText.textContent = '';
    }

    // Limpieza de estado de error (Figma): restaura clase, placeholder y color a #212121
    function clearErrorState() {
        if (!inputCorreo) return;

        if (inputCorreo.dataset.hasErrorState === 'true' || 
            inputCorreo.value === 'Correo electrónico inválido.' || 
            inputCorreo.value === 'Ingresa tu correo electrónico') {
            inputCorreo.value = '';
            delete inputCorreo.dataset.hasErrorState;
        }

        inputCorreo.classList.remove('input-error', 'is-invalid');
        inputCorreo.removeAttribute('aria-invalid');
        inputCorreo.style.color = '#212121';

        if (inputCorreo.dataset.originalPlaceholder !== undefined) {
            inputCorreo.placeholder = inputCorreo.dataset.originalPlaceholder;
        }

        if (errorCorreo) {
            errorCorreo.textContent = '';
            errorCorreo.classList.remove('is-visible');
            errorCorreo.style.display = 'none';
        }

        hideFeedback();
    }

    // Escuchadores de eventos input, focus y keydown: al enfocar o escribir, retira inmediatamente el error
    if (inputCorreo) {
        inputCorreo.addEventListener('input', () => {
            clearErrorState();
        });

        inputCorreo.addEventListener('focus', () => {
            if (inputCorreo.classList.contains('input-error') || 
                inputCorreo.classList.contains('is-invalid') || 
                inputCorreo.value === 'Correo electrónico inválido.' ||
                inputCorreo.dataset.hasErrorState === 'true') {
                clearErrorState();
            }
        });

        inputCorreo.addEventListener('keydown', () => {
            if (inputCorreo.dataset.hasErrorState === 'true' || inputCorreo.value === 'Correo electrónico inválido.') {
                clearErrorState();
            }
        });
    }

    // Manejador del evento submit
    recuperarForm.addEventListener('submit', (e) => {
        e.preventDefault();

        if (isSubmitting) {
            return;
        }

        if (!inputCorreo) {
            return;
        }

        const correoVal = inputCorreo.value.trim();

        // 1. Si está vacío:
        if (correoVal === '' || correoVal === 'Ingresa tu correo electrónico' || correoVal === 'Correo electrónico inválido.') {
            inputCorreo.classList.add('input-error', 'is-invalid');
            inputCorreo.setAttribute('aria-invalid', 'true');
            inputCorreo.value = '';
            inputCorreo.placeholder = 'Ingresa tu correo electrónico';
            inputCorreo.dataset.hasErrorState = 'true';

            if (errorCorreo) {
                errorCorreo.textContent = 'Ingresa tu correo electrónico';
                errorCorreo.classList.add('is-visible');
                errorCorreo.style.display = 'block';
            }

            showFeedback('Ingresa tu correo electrónico.', 'error');
            return;
        }

        // 2. Si el formato es inválido o incompleto (ej. "alejo", "alejo@", sin dominio válido):
        if (!emailRegex.test(correoVal)) {
            inputCorreo.classList.add('input-error', 'is-invalid');
            inputCorreo.setAttribute('aria-invalid', 'true');
            inputCorreo.value = 'Correo electrónico inválido.';
            inputCorreo.dataset.hasErrorState = 'true';

            if (errorCorreo) {
                errorCorreo.textContent = 'Correo electrónico inválido.';
                errorCorreo.classList.add('is-visible');
                errorCorreo.style.display = 'block';
            }

            showFeedback('Correo electrónico inválido.', 'error');
            return;
        }

        // 3. Formato válido: enviar formulario al backend
        hideFeedback();
        if (errorCorreo) {
            errorCorreo.textContent = '';
            errorCorreo.classList.remove('is-visible');
            errorCorreo.style.display = 'none';
        }

        isSubmitting = true;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.dataset.originalText = submitBtn.textContent;
            submitBtn.textContent = 'Enviando instrucciones...';
        }

        HTMLFormElement.prototype.submit.call(recuperarForm);
    });
}

function escapeHtml(string) {
    const div = document.createElement('div');
    div.textContent = string;
    return div.innerHTML;
}

// =============================================================================
// INICIALIZACIÓN: FORMULARIO DE RESTABLECIMIENTO (NUEVA CONTRASEÑA)
// =============================================================================
function initResetPasswordForm() {
    const resetForm = document.getElementById('resetPasswordForm');
    if (!resetForm) {
        return;
    }

    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirm');
    const submitBtn = document.getElementById('submitBtn');
    const errorPassword = document.getElementById('error_password');
    const errorConfirm = document.getElementById('error_password_confirm');
    const feedbackBox = document.getElementById('formFeedback');
    const feedbackText = document.getElementById('formFeedbackText');
    const feedbackIcon = document.getElementById('formFeedbackIcon');

    let isSubmitting = false;

    if (passwordInput) {
        passwordInput.addEventListener('input', () => {
            clearInputError(passwordInput, errorPassword);
            if (feedbackBox) feedbackBox.style.display = 'none';
        });
    }

    if (confirmInput) {
        confirmInput.addEventListener('input', () => {
            clearInputError(confirmInput, errorConfirm);
            if (feedbackBox) feedbackBox.style.display = 'none';
        });
    }

    // Toggle interactivo de visibilidad de contraseñas (mostrar/ocultar)
    const toggleBtns = resetForm.querySelectorAll('.btn-toggle-password');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = btn.dataset.target;
            const targetInput = document.getElementById(targetId);
            if (!targetInput) return;

            const isPassword = targetInput.type === 'password';
            targetInput.type = isPassword ? 'text' : 'password';

            if (isPassword) {
                btn.innerHTML = `<svg class="eye-icon" viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.44-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg>`;
                btn.setAttribute('aria-label', 'Ocultar contraseña');
            } else {
                btn.innerHTML = `<svg class="eye-icon" viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>`;
                btn.setAttribute('aria-label', 'Mostrar contraseña');
            }
        });
    });

    resetForm.addEventListener('submit', (e) => {
        e.preventDefault();

        if (isSubmitting) return;

        let isValid = true;
        const passVal = passwordInput ? passwordInput.value : '';
        const confirmVal = confirmInput ? confirmInput.value : '';

        if (passVal.length < 6) {
            setInputError(passwordInput, 'La contraseña debe contener al menos 6 caracteres.', errorPassword);
            isValid = false;
        } else {
            clearInputError(passwordInput, errorPassword);
        }

        if (confirmVal === '') {
            setInputError(confirmInput, 'Por favor confirma tu contraseña.', errorConfirm);
            isValid = false;
        } else if (confirmVal !== passVal) {
            setInputError(confirmInput, 'Las contraseñas no coinciden.', errorConfirm);
            isValid = false;
        } else {
            clearInputError(confirmInput, errorConfirm);
        }

        if (!isValid) {
            if (feedbackBox && feedbackText) {
                feedbackText.textContent = 'Por favor corrige los errores señalados en el formulario.';
                feedbackBox.className = 'alert alert-error';
                feedbackBox.style.display = 'flex';
            }
            return;
        }

        isSubmitting = true;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.dataset.originalText = submitBtn.textContent;
            submitBtn.textContent = 'Guardando contraseña...';
        }

        HTMLFormElement.prototype.submit.call(resetForm);
    });
}

