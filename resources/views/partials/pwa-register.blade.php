<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/sw.js')
                .then(function (registration) {
                    console.log('Service Worker enregistré :', registration.scope);
                })
                .catch(function (error) {
                    console.log('Échec d\'enregistrement du Service Worker :', error);
                });
        });
    }
</script>
