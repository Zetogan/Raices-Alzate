// assets/js/loader.js
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Módulo: Manejador del Page Loader Dinámico y Prevención de Bloqueo Infinito
 * Versión: 0.1.7
 */
(function () {
  'use strict';

  const hideLoader = () => {
    const loader = document.getElementById('page-loader') || document.getElementById('pageLoader');
    if (!loader || loader.classList.contains('loader-hidden') || loader.classList.contains('is-hidden')) return;

    loader.classList.add('loader-hidden');
    loader.classList.add('is-hidden');
    setTimeout(() => {
      loader.style.display = 'none';
      if (loader.parentNode) {
        loader.remove(); // Remueve el elemento del DOM para liberar memoria y clics
      }
    }, 400);
  };

  // 1. Si la página ya terminó de cargar antes de ejecutar el script
  if (document.readyState === 'complete') {
    hideLoader();
  } else {
    // 2. Escuchar evento de carga habitual
    window.addEventListener('load', hideLoader);
    document.addEventListener('DOMContentLoaded', () => {
      // Retiro rápido una vez el DOM estructural esté listo
      setTimeout(hideLoader, 800);
    });
  }

  // 3. Temporizador de rescate/seguridad garantizado (máximo 700ms)
  setTimeout(hideLoader, 700);
})();
