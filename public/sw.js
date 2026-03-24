const CACHE_NAME = 'gs-pama-v2';
const assetsToCache = [
    '/',
    '/manifest.json',
    '/icons/icon-192x192.jpg',
    '/icons/icon-512x512.jpg',
    '/icons/screenshot-1.jpg'
];

// Install Event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[SW] Pre-caching core assets');
            return cache.addAll(assetsToCache);
        })
    );
});

// Activate Event (Cleanup old caches)
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        console.log('[SW] Removing old cache:', cache);
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
});

// Fetch Event (Network falling back to cache)
self.addEventListener('fetch', (event) => {
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request);
        })
    );
});
