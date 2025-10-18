importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.5.4/workbox-sw.js');

if (workbox) {
    console.log('Workbox loaded');

    // Precache your critical assets
    workbox.precaching.precacheAndRoute([
        { url: '/',           revision: '1' },
        { url: '/css/app.css', revision: '1' },
        { url: '/js/app.js',   revision: '1' },
        { url: '/img/logo.svg',revision: '1' },
        { url: '/manifest.json', revision: '1' },
    ]);

    // Cache-first for CSS / JS / Images
    workbox.routing.registerRoute(
        ({ request }) => ['style','script','image'].includes(request.destination),
        new workbox.strategies.CacheFirst({
            cacheName: 'static-resources-v1',
            plugins: [
                new workbox.expiration.ExpirationPlugin({
                    maxEntries: 60,
                    maxAgeSeconds: 30 * 24 * 60 * 60, // 30 Days
                })
            ]
        })
    );

    // Stale-while-revalidate for GET /api/…
    workbox.routing.registerRoute(
        ({ url, request }) => url.pathname.startsWith('/api/') && request.method === 'GET',
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'api-cache-v1',
            plugins: [
                new workbox.expiration.ExpirationPlugin({
                    maxEntries: 50,
                    maxAgeSeconds: 5 * 60, // 5 minutes
                })
            ]
        })
    );

} else {
    console.error('Workbox failed to load');
}
