<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>File Manager Selection</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css">
    <link href="{{ asset('vendor/file-manager/css/file-manager.css') }}" rel="stylesheet">
</head>
<body>
    <div style="height: 100vh;">
        <div id="fm"></div>
    </div>

    <!-- JS -->
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Document listener to catch the selection from the file manager
            document.getElementById('fm').addEventListener('click', (event) => {
                // This is a bit tricky with this specific package as it uses Vue.
                // But generally, we can listen for the 'select-file' event if the package supports it,
                // or we use the 'fmSetLink' callback which we define below.
            });

            // Set explicitly the base URL
            fm.$store.commit('fm/settings/manualSettings', {
                baseUrl: '{{ url('file-manager') }}/'
            });
            
            fm.setAxiosConfig();
            fm.$store.dispatch('fm/initializeApp');
        });

        // The file manager package will look for this global function if used in a button mode
        // Or we can use the 'fmSetLink' that is already called by the file manager JS
        function fmSetLink($url) {
            if (window.opener && typeof window.opener.fmSetLink === 'function') {
                window.opener.fmSetLink($url);
                window.close();
            }
        }
    </script>
</body>
</html>
