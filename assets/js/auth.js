/**
 * Raíces Alzate - Módulo de Autenticación (JavaScript Client-side)
 * Validación accesible de campos y prevención de doble submit
 * Versión: 0.1.5
 */

document.addEventListener('DOMContentLoaded', () => {
    initLoginForm();
});

function initLoginForm() {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) {
        return;
    }

    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const submitBtn = document.getElementById('submitBtn');
    const feedbackBox = document.getElementById('formFeedback');

    let isSubmitting = false;

    // Validación interactiva al escribir y al perder foco
    if (emailInput) {
        emailInput.addEventListener('input', () => clearFieldValidation(emailInput));
        emailInput.addEventListener('blur', () => validateEmail(emailInput, false));
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', () => clearFieldValidation(passwordInput));
        passwordInput.addEventListener('blur', () => validatePassword(passwordInput, false));
    }

    loginForm.addEventListener('submit', (e) => {
        // Prevención de envíos múltiples simultáneos
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        hideFeedback();

        const isEmailValid = validateEmail(emailInput, true);
        const isPasswordValid = validatePassword(passwordInput, true);

        // Si algún campo no cumple con los requisitos, se cancela el envío
        if (!isEmailValid || !isPasswordValid) {
            e.preventDefault();
            showFeedback('Por favor, completa correctamente todos los campos obligatorios.', 'error');

            // Foco accesible al primer campo con error
            if (!isEmailValid && emailInput) {
                emailInput.focus();
            } else if (!isPasswordValid && passwordInput) {
                passwordInput.focus();
            }
            return;
        }

        // Bloqueo de doble submit y feedback visual de progreso
        isSubmitting = true;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.dataset.originalText = submitBtn.textContent;
            submitBtn.textContent = 'Iniciando sesión...';
        }
    });

    /**
     * Valida formato y presencia de correo electrónico
     */
    function validateEmail(input, isSubmitAttempt) {
        if (!input) return false;
        const val = input.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!val) {
            if (isSubmitAttempt) {
                markFieldInvalid(input);
            }
            return false;
        }

        if (!emailRegex.test(val)) {
            markFieldInvalid(input);
            return false;
        }

        markFieldValid(input);
        return true;
    }

    /**
     * Valida longitud mínima y presencia de contraseña
     */
    function validatePassword(input, isSubmitAttempt) {
        if (!input) return false;
        const val = input.value;

        if (!val) {
            if (isSubmitAttempt) {
                markFieldInvalid(input);
            }
            return false;
        }

        if (val.length < 6) {
            markFieldInvalid(input);
            return false;
        }

        markFieldValid(input);
        return true;
    }

    function markFieldInvalid(input) {
        input.classList.add('is-invalid');
        input.setAttribute('aria-invalid', 'true');
    }

    function markFieldValid(input) {
        input.classList.remove('is-invalid');
        input.removeAttribute('aria-invalid');
    }

    function clearFieldValidation(input) {
        input.classList.remove('is-invalid');
        input.removeAttribute('aria-invalid');
    }

    const alertIcons = {
        success: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>',
        warning: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>',
        error: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>',
        info: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>'
    };

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

    function escapeHtml(string) {
        const div = document.createElement('div');
        div.textContent = string;
        return div.innerHTML;
    }
}
