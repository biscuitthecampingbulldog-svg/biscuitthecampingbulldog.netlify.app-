// Biscuit the Camping Bulldog -- offline support.
// Bump CACHE_VERSION any time the site's core files change materially,
// so visitors' phones pick up the new version instead of a stale cache.
const CACHE_VERSION = 'biscuit-v2';
const CORE_ASSETS = [
  '/',
  '/index.html',
  '/vet-data.js',
  '/manifest.json'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_VERSION)
      .then((cache) => cache.addAll(CORE_ASSETS))
      .catch(() => {})
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => Promise.all(
      keys.filter((k) => k !== CACHE_VERSION).map((k) => caches.delete(k))
    ))
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;
  const url = new URL(event.request.url);
  // Only manage our own files -- let cross-origin requests (fonts, analytics,
  // the React library) pass straight through to the network/browser cache.
  if (url.origin !== self.location.origin) return;

  // Pages, scripts and data: always try the network first so visitors see
  // the newest version; fall back to the saved copy only when offline.
  const isImage = event.request.destination === 'image' || /\.(jpe?g|png|webp|svg|gif)$/i.test(url.pathname);
  if (!isImage) {
    event.respondWith(
      fetch(event.request).then((response) => {
        if (response && response.status === 200) {
          const copy = response.clone();
          caches.open(CACHE_VERSION).then((cache) => cache.put(event.request, copy));
        }
        return response;
      }).catch(() => caches.match(event.request))
    );
    return;
  }

  event.respondWith(
    caches.match(event.request).then((cached) => {
      const network = fetch(event.request).then((response) => {
        if (response && response.status === 200) {
          const copy = response.clone();
          caches.open(CACHE_VERSION).then((cache) => cache.put(event.request, copy));
        }
        return response;
      }).catch(() => cached);
      return cached || network;
    })
  );
});
