<!-- includes/loader.php - Cargador Minimalista Oficial Raíces Alzate -->
<div id="page-loader" class="page-loader" aria-hidden="true" role="status">
  <div class="loader-center-content">
    <div class="loader-spinner-wrapper">
      <div class="loader-spinner"></div>
      <img 
        src="assets/icons/logo sin fondo raices alzate.ico" 
        alt="Raíces Alzate" 
        class="loader-logo"
      >
    </div>
    <span class="loader-text">Cargando...</span>
  </div>
</div>

<script>
  // Script en línea de ejecución inmediata para evitar dependencias de archivos externos
  (function() {
    function hideLoader() {
      var loader = document.getElementById('page-loader');
      if (!loader) return;
      loader.classList.add('loader-hidden');
      setTimeout(function() {
        loader.style.display = 'none';
        if (loader.parentNode) {
          loader.parentNode.removeChild(loader);
        }
      }, 300);
    }

    if (document.readyState === 'complete') {
      hideLoader();
    } else {
      window.addEventListener('load', hideLoader);
      document.addEventListener('DOMContentLoaded', function() {
        setTimeout(hideLoader, 300);
      });
    }

    // Temporizador de seguridad garantizado (máximo 700ms)
    setTimeout(hideLoader, 700);
  })();
</script>
