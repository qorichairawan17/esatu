/**
 * Service Worker for e-SuKa PWA
 * Elektronik Surat Kuasa - Pengadilan Negeri Lubuk Pakam
 * Version: 2.0.0
 */

const CACHE_NAME = "esuka-v2.0.0";
const OFFLINE_URL = "/offline.html";

// Assets to pre-cache during installation
const PRECACHE_ASSETS = [
    OFFLINE_URL,
    "/icons/android-icon-192x192.png",
    "/icons/android-icon-144x144.png",
    "/icons/favicon-96x96.png",
    "/icons/favicon-32x32.png",
    "/icons/favicon-16x16.png",
    "/icons/favicon.ico",
];

// Install event - cache essential assets
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches
            .open(CACHE_NAME)
            .then((cache) => {
                console.log("[SW] Pre-caching offline assets");
                return cache.addAll(PRECACHE_ASSETS);
            })
            .then(() => self.skipWaiting()),
    );
});

// Activate event - clean up old caches
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames
                        .filter((cacheName) => cacheName !== CACHE_NAME)
                        .map((cacheName) => {
                            console.log("[SW] Deleting old cache:", cacheName);
                            return caches.delete(cacheName);
                        }),
                );
            })
            .then(() => self.clients.claim()),
    );
});

// Helper: check if response is valid for caching
function isValidResponse(response) {
    // Don't cache error responses
    if (!response) return false;
    // Cache successful same-origin responses
    if (response.status === 200 && response.type === "basic") return true;
    // Cache opaque responses (cross-origin without CORS) cautiously
    if (response.type === "opaque") return true;
    return false;
}

// Fetch event - Network First strategy for navigations, Cache First for static assets
self.addEventListener("fetch", (event) => {
    const { request } = event;

    // Skip non-GET requests
    if (request.method !== "GET") return;

    // Skip Chrome extension and non-http(s) requests
    if (!request.url.startsWith("http")) return;

    // Skip API calls and form submissions
    if (
        request.url.includes("/api/") ||
        request.url.includes("/login") ||
        request.url.includes("/logout")
    )
        return;

    // Navigation requests (HTML pages) - Network First
    if (request.mode === "navigate") {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    if (isValidResponse(response)) {
                        var responseToCache = response.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseToCache);
                        });
                    }
                    return response;
                })
                .catch(() => {
                    // Try to serve from cache, fallback to offline page
                    return caches.match(request).then((cachedResponse) => {
                        return cachedResponse || caches.match(OFFLINE_URL);
                    });
                }),
        );
        return;
    }

    // Static assets (CSS, JS, images, fonts) - Stale While Revalidate
    if (
        request.destination === "style" ||
        request.destination === "script" ||
        request.destination === "image" ||
        request.destination === "font" ||
        request.url.match(
            /\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot|ico)$/,
        )
    ) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                var fetchPromise = fetch(request)
                    .then((networkResponse) => {
                        // Clone FIRST before doing anything else
                        var responseToCache = networkResponse.clone();
                        // Only cache valid responses
                        if (isValidResponse(networkResponse)) {
                            caches.open(CACHE_NAME).then((cache) => {
                                try {
                                    cache.put(request, responseToCache);
                                } catch (e) {
                                    console.log("[SW] Cache put failed:", e);
                                }
                            });
                        }
                        return networkResponse;
                    })
                    .catch(() => cachedResponse);

                return cachedResponse || fetchPromise;
            }),
        );
        return;
    }
});

// Listen for messages from the app
self.addEventListener("message", (event) => {
    if (event.data && event.data.type === "SKIP_WAITING") {
        self.skipWaiting();
    }
});
