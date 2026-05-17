{{-- PWA Service Worker Registration --}}
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js', {
                    scope: '/'
                })
                .then(function(registration) {
                    console.log('[PWA] Service Worker registered with scope:', registration.scope);

                    // Check for updates
                    registration.addEventListener('updatefound', function() {
                        var newWorker = registration.installing;
                        newWorker.addEventListener('statechange', function() {
                            if (newWorker.state === 'activated') {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Update Tersedia',
                                        text: 'Versi terbaru e-SuKa tersedia. Muat ulang halaman untuk memperbarui.',
                                        confirmButtonText: 'Muat Ulang',
                                        showCancelButton: true,
                                        cancelButtonText: 'Nanti',
                                    }).then(function(result) {
                                        if (result.isConfirmed) {
                                            window.location.reload();
                                        }
                                    });
                                }
                            }
                        });
                    });
                })
                .catch(function(error) {
                    console.log('[PWA] Service Worker registration failed:', error);
                });
        });
    }
</script>
