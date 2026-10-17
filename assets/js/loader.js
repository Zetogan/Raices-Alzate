// assets/js/loader.js
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Módulo: Manejador del Page Loader Dinámico para Transiciones Rápidas y Suaves
 * Versión: 0.2.1
 */
(function () {
  'use strict';

  const getLoader = () => document.getElementById('page-loader') || document.getElementById('pageLoader');

  const showLoader = () => {
    if (typeof window.showPageLoader === 'function') {
      window.showPageLoader();
      return;
    }
    const loader = getLoader();
    if (!loader) return;
    loader.style.display = 'flex';
    void loader.offsetWidth;
    loader.classList.remove('loader-hidden', 'is-hidden');
  };

  const hideLoader = () => {
    if (typeof window.hidePageLoader === 'function') {
      window.hidePageLoader();
      return;
    }
    const loader = getLoader();
    if (!loader) return;
    loader.classList.add('loader-hidden', 'is-hidden');
    setTimeout(() => {
      if (loader && loader.classList.contains('loader-hidden')) {
        loader.style.display = 'none';
      }
    }, 360);
  };

  // 1. Gestión suave de entrada al cargar la página
  if (document.readyState === 'complete') {
    setTimeout(hideLoader, 200);
  } else {
    window.addEventListener('load', () => setTimeout(hideLoader, 200));
    document.addEventListener('DOMContentLoaded', () => setTimeout(hideLoader, 350));
  }

  // 2. Temporizador de rescate
  setTimeout(hideLoader, 1200);

  // 3. Interceptar navegación entre páginas para mostrar inmediatamente el loader
  document.addEventListener('click', (e) => {
    const link = e.target.closest('a');
    if (!link) return;

    const href = link.getAttribute('href');
    if (!href) return;

    if (
      href.startsWith('#') ||
      href.startsWith('javascript:') ||
      href.startsWith('mailto:') ||
      href.startsWith('tel:') ||
      link.getAttribute('target') === '_blank' ||
      link.hasAttribute('download') ||
      e.ctrlKey || e.metaKey || e.shiftKey || e.altKey
    ) {
      return;
    }

    if (
      href.includes('index.php') ||
      href.includes('page=') ||
      href.endsWith('.php') ||
      href.startsWith('/') ||
      !href.includes('://')
    ) {
      showLoader();
    }
  }, true);

  // 4. Interceptar envíos de formularios para mostrar el cargador
  document.addEventListener('submit', (e) => {
    const form = e.target;
    if (form && form.getAttribute('target') !== '_blank') {
      showLoader();
    }
  }, true);
})();
