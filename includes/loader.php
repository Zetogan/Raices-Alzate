<!-- includes/loader.php - Cargador Oficial Institucional Raíces Alzate -->
<style>
/* Estilos críticos en línea para renderizado inmediato sin bloqueos de red */
#page-loader {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
  z-index: 999999;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 1;
  visibility: visible;
  transition: opacity 0.35s ease, visibility 0.35s ease;
  pointer-events: auto;
}
#page-loader.loader-hidden,
#page-loader.is-hidden {
  opacity: 0 !important;
  visibility: hidden !important;
  pointer-events: none !important;
}
.loader-center-content {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 28px 36px;
  background: #ffffff;
  border-radius: 20px;
  box-shadow: 0 12px 36px rgba(0, 0, 0, 0.1), 0 2px 8px rgba(0, 0, 0, 0.04);
  border: 1px solid #E0E0E0;
}
.loader-spinner-wrapper {
  position: relative;
  width: 90px;
  height: 90px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.loader-spinner {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  box-sizing: border-box;
  border: 4px solid rgba(211, 47, 47, 0.15);
  border-top: 4px solid #D32F2F;
  border-right: 4px solid #1565C0;
  animation: alzateSpin 0.9s linear infinite;
}
.loader-logo {
  position: relative;
  z-index: 2;
  width: 46px;
  height: 46px;
  object-fit: contain;
  animation: alzatePulse 1.8s ease-in-out infinite;
  user-select: none;
}
.loader-text {
  margin-top: 14px;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 1rem;
  font-weight: 700;
  color: #212121;
  letter-spacing: -0.01em;
  display: inline-flex;
  align-items: baseline;
}
.loader-dots span {
  display: inline-block;
  animation: alzateDot 1.4s infinite;
  font-weight: 800;
  color: #D32F2F;
}
.loader-dots span:nth-child(1) { animation-delay: 0s; }
.loader-dots span:nth-child(2) { animation-delay: 0.2s; }
.loader-dots span:nth-child(3) { animation-delay: 0.4s; }

@keyframes alzateSpin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
@keyframes alzatePulse {
  0%, 100% { transform: scale(1); opacity: 0.95; }
  50% { transform: scale(1.08); opacity: 1; }
}
@keyframes alzateDot {
  0%, 20% { opacity: 0.2; transform: translateY(0); }
  50% { opacity: 1; transform: translateY(-2px); }
  100% { opacity: 0.2; transform: translateY(0); }
}
</style>

<div id="page-loader" class="page-loader" aria-live="polite" role="status">
  <div class="loader-center-content">
    <div class="loader-spinner-wrapper">
      <div class="loader-spinner"></div>
      <img 
        src="assets/icons/logo-alzate.ico" 
        alt="Logo Raíces Alzate" 
        class="loader-logo"
        width="46"
        height="46"
        onerror="this.onerror=null; this.src='assets/icons/logo%20sin%20fondo%20raices%20alzate.ico';"
      >
    </div>
    <span class="loader-text">
      Cargando<span class="loader-dots"><span>.</span><span>.</span><span>.</span></span>
    </span>
  </div>
</div>

<script>
(function() {
  var loader = document.getElementById('page-loader');
  if (!loader) return;

  function showLoader() {
    if (!loader) return;
    loader.style.display = 'flex';
    void loader.offsetWidth;
    loader.classList.remove('loader-hidden', 'is-hidden');
  }

  function hideLoader() {
    if (!loader) return;
    loader.classList.add('loader-hidden', 'is-hidden');
    setTimeout(function() {
      if (loader && loader.classList.contains('loader-hidden')) {
        loader.style.display = 'none';
      }
    }, 360);
  }

  // Transición inicial fluida al cargar el documento
  if (document.readyState === 'complete') {
    setTimeout(hideLoader, 250);
  } else {
    window.addEventListener('load', function() {
      setTimeout(hideLoader, 250);
    });
    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(hideLoader, 350);
    });
  }
  // Temporizador de rescate garantizado
  setTimeout(hideLoader, 1200);

  // Exponer API global
  window.showPageLoader = showLoader;
  window.hidePageLoader = hideLoader;

  // Interceptar navegación para mostrar el loader con el logo al pasar de una página a otra
  document.addEventListener('click', function(e) {
    var target = e.target.closest('a');
    if (!target) return;

    var href = target.getAttribute('href');
    if (!href) return;

    if (href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
    if (target.getAttribute('target') === '_blank') return;
    if (target.hasAttribute('download')) return;
    if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;

    if (href.indexOf('index.php') !== -1 || href.indexOf('page=') !== -1 || href.endsWith('.php') || href.startsWith('/') || !href.includes('://')) {
      showLoader();
    }
  }, true);

  // Interceptar envíos de formularios para mostrar el cargador
  document.addEventListener('submit', function(e) {
    var form = e.target;
    if (form && form.getAttribute('target') !== '_blank') {
      showLoader();
    }
  }, true);
})();
</script>
