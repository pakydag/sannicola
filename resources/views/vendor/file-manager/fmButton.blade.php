<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'File Manager') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12" id="fm-main-block">
            <div id="fm"></div>
        </div>
    </div>
</div>

<!-- Pulsante Infallibile Custom -->
<div id="custom-confirm-container" style="position: fixed; bottom: 0; left: 0; width: 100%; background: #ffffff; border-top: 2px solid #e2e8f0; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; z-index: 99999; box-shadow: 0 -4px 15px rgba(0,0,0,0.1);">
    <div>
        <span class="text-secondary fw-bold"><i class="bi bi-info-circle me-1"></i> Puoi selezionare più foto e cliccare qui a destra -></span>
    </div>
    <button id="custom-confirm-btn" class="btn btn-success btn-lg fw-bold px-5 py-2 shadow" style="background-color: #10b981; border-color: #059669;">
        <i class="bi bi-check2-all me-1"></i> CONFERMA FOTO SELEZIONATE
    </button>
</div>


<!-- File manager -->
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Override baseUrl to fix subdirectory paths (like /baseweb/public)
    fm.$store.commit('fm/settings/manualSettings', {
      baseUrl: '{{ url('file-manager') }}/'
    });
    fm.setAxiosConfig();
    fm.$store.dispatch('fm/initializeApp');

    // set fm height (sottraiamo l'altezza del footer custom)
    document.getElementById('fm-main-block').setAttribute('style', 'height:' + (window.innerHeight - 80) + 'px');

    // Add callback to file manager (per doppio click singolo file)
    fm.$store.commit('fm/setFileCallBack', function(fileUrl) {
      window.opener.fmSetLink(fileUrl);
      window.close();
    });

    // Custom Multi-Select Callback Infallibile
    document.getElementById('custom-confirm-btn').addEventListener('click', function() {
        let store = window.fm.$store;
        let paths = [];
        
        try {
            // Metodo 1: Accesso diretto allo state di Alexusmai v3
            let active = store.state.fm.activeManager || 'left';
            let selectedFiles = store.state.fm[active].selected.files;
            
            if (selectedFiles && selectedFiles.length > 0) {
                paths = selectedFiles;
            }
        } catch (e) {
            console.error(e);
        }

        // Metodo 2: Fallback Scrape dal DOM se lo state Vuex cambia in futuro
        if (paths.length === 0) {
            let active = store.state.fm.activeManager || 'left';
            let currentPath = store.state.fm[active].selectedDirectory || '';
            let selectedRows = document.querySelectorAll('tr.table-info, tr.-selected');
            
            selectedRows.forEach(row => {
                let nameStr = row.innerText.trim().split('\n')[0]; // Prende il nome del file dalla tabella
                if (nameStr) paths.push(currentPath + '/' + nameStr);
            });
        }

        if (paths.length === 0) {
            alert('Per favore, seleziona almeno un\'immagine prima di confermare. Se il problema persiste, la struttura interna è variata.');
            return;
        }

        window.opener.fmSetLink(paths);
        window.close();
    });
  });
</script>
<script>
    // Gestione altezza dinamica al resize per non coprire i file col footer
    window.addEventListener('resize', () => {
        document.getElementById('fm-main-block').setAttribute('style', 'height:' + (window.innerHeight - 80) + 'px');
    });
</script>
</body>
</html>

