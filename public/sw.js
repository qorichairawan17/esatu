/**
 * Service Worker for E-SATU PWA
 * Digital Surat Kuasa - Pengadilan Negeri Mandailing Natal
 * Version: 3.0.1
 */

const CACHE_NAME = "esatu-pwa-v3.0.3";
const OFFLINE_URL = "/offline.html";
const STATIC_ASSET_PATTERN =
    /\.(css|js|png|jpg|jpeg|gif|svg|webp|woff|woff2|ttf|eot|ico)$/i;
const SENSITIVE_PATH_PREFIXES = [
    "/admin",
    "/api",
    "/auth",
    "/login",
    "/logout",
    "/signin",
    "/signup",
    "/forgot-password",
    "/reset-password",
    "/profile",
    "/surat-kuasa",
    "/sync",
];

const PRECACHE_ASSETS = [
    OFFLINE_URL,
    "/manifest.json",
    "/icons/android-icon-192x192.png",
    "/icons/android-icon-512x512.png",
    "/icons/maskable-icon-512x512.png",
    "/icons/apple-icon-180x180.png",
    "/icons/favicon-96x96.png",
    "/icons/favicon-32x32.png",
    "/icons/favicon-16x16.png",
    "/icons/favicon.ico",
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches
            .open(CACHE_NAME)
            .then((cache) => cache.addAll(PRECACHE_ASSETS))
            .then(() => self.skipWaiting()),
    );
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((cacheNames) =>
                Promise.all(
                    cacheNames
                        .filter((cacheName) => cacheName !== CACHE_NAME)
                        .map((cacheName) => caches.delete(cacheName)),
                ),
            )
            .then(() => self.clients.claim()),
    );
});

function isHttpRequest(request) {
    return (
        request.url.startsWith("http://") || request.url.startsWith("https://")
    );
}

function isSameOrigin(url) {
    return url.origin === self.location.origin;
}

function isValidCacheResponse(response) {
    return Boolean(
        response && response.status === 200 && response.type === "basic",
    );
}

function isStaticAsset(request, url) {
    return (
        request.destination === "style" ||
        request.destination === "script" ||
        request.destination === "image" ||
        request.destination === "font" ||
        STATIC_ASSET_PATTERN.test(url.pathname)
    );
}

function isSensitivePath(url) {
    return SENSITIVE_PATH_PREFIXES.some(
        (pathPrefix) =>
            url.pathname === pathPrefix ||
            url.pathname.startsWith(`${pathPrefix}/`),
    );
}

async function networkFirst(request) {
    try {
        const response = await fetch(request);

        if (isValidCacheResponse(response)) {
            const cache = await caches.open(CACHE_NAME);
            await cache.put(request, response.clone());
        }

        return response;
    } catch (error) {
        const cachedResponse = await caches.match(request);

        return cachedResponse || caches.match(OFFLINE_URL);
    }
}

async function cacheFirstWithRefresh(request) {
    const cachedResponse = await caches.match(request);
    const networkResponsePromise = fetch(request)
        .then(async (response) => {
            if (isValidCacheResponse(response)) {
                const cache = await caches.open(CACHE_NAME);
                await cache.put(request, response.clone());
            }

            return response;
        })
        .catch(() => cachedResponse);

    return cachedResponse || networkResponsePromise;
}

self.addEventListener("fetch", (event) => {
    const { request } = event;

    if (request.method !== "GET" || !isHttpRequest(request)) {
        return;
    }

    const url = new URL(request.url);

    if (!isSameOrigin(url)) {
        return;
    }

    if (request.mode === "navigate") {
        if (isSensitivePath(url)) {
            event.respondWith(
                fetch(request).catch(() => caches.match(OFFLINE_URL)),
            );

            return;
        }

        event.respondWith(networkFirst(request));

        return;
    }

    if (isStaticAsset(request, url)) {
        event.respondWith(cacheFirstWithRefresh(request));
    }
});

self.addEventListener("message", (event) => {
    if (event.data?.type === "SKIP_WAITING") {
        self.skipWaiting();
    }
});
