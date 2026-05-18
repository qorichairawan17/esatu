{{-- PWA Service Worker Registration --}}
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            var hasController = Boolean(navigator.serviceWorker.controller);

            navigator.serviceWorker.register('/sw.js', {
                    scope: '/'
                })
                .then(function(registration) {
                    registration.addEventListener('updatefound', function() {
                        var newWorker = registration.installing;

                        if (!newWorker) {
                            return;
                        }

                        newWorker.addEventListener('statechange', function() {
                            if (newWorker.state !== 'installed' || !hasController) {
                                return;
                            }

                            if (typeof Swal === 'undefined') {
                                return;
                            }

                            Swal.fire({
                                icon: 'info',
                                title: 'Update E-SATU Tersedia',
                                text: 'Versi terbaru aplikasi sudah siap. Muat ulang halaman untuk memperbarui.',
                                confirmButtonText: 'Muat Ulang',
                                showCancelButton: true,
                                cancelButtonText: 'Nanti',
                            }).then(function(result) {
                                if (result.isConfirmed) {
                                    newWorker.postMessage({
                                        type: 'SKIP_WAITING'
                                    });
                                    window.location.reload();
                                }
                            });
                        });
                    });
                })
                .catch(function(error) {
                    console.log('[PWA] Service Worker registration failed:', error);
                });

            navigator.serviceWorker.addEventListener('controllerchange', function() {
                hasController = true;
            });
        });
    }
</script>
