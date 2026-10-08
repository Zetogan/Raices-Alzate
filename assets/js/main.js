/**
 * Raíces Alzate - JavaScript Principal
 * Interacciones de interfaz base y menú responsive
 * Versión: 0.1.2
 */

document.addEventListener('DOMContentLoaded', () => {
    initMobileNav();
});

/**
 * Control del menú de navegación en dispositivos móviles (Drawer lateral)
 */
function initMobileNav() {
    const menuToggle = document.getElementById('menuToggle');
    const mainNav = document.getElementById('mainNav');

    if (!menuToggle || !mainNav) {
        return;
    }

    menuToggle.addEventListener('click', (event) => {
        event.stopPropagation();
        const isOpen = mainNav.classList.toggle('is-open');
        menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        document.body.classList.toggle('nav-drawer-open', isOpen);
    });

    // Cerrar el menú al hacer clic en cualquier enlace del drawer móvil
    const navLinks = mainNav.querySelectorAll('.nav-link, .mobile-drawer-actions a, .mobile-actions a');
    navLinks.forEach((link) => {
        link.addEventListener('click', () => {
            if (mainNav.classList.contains('is-open')) {
                mainNav.classList.remove('is-open');
                menuToggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('nav-drawer-open');
            }
        });
    });

    // Cerrar el menú al hacer clic fuera del mismo
    document.addEventListener('click', (event) => {
        if (mainNav.classList.contains('is-open') && !mainNav.contains(event.target) && !menuToggle.contains(event.target)) {
            mainNav.classList.remove('is-open');
            menuToggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('nav-drawer-open');
        }
    });

    // Cerrar menú con la tecla Escape
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && mainNav.classList.contains('is-open')) {
            mainNav.classList.remove('is-open');
            menuToggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('nav-drawer-open');
            menuToggle.focus();
        }
    });

    // Restablecer estado al redimensionar a pantalla de escritorio
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768 && mainNav.classList.contains('is-open')) {
            mainNav.classList.remove('is-open');
            menuToggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('nav-drawer-open');
        }
    });
}
