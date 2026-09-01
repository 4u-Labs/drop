/* =======================================================
   4U DROP — Service Worker (Dynamic Anti-Cache Engine)
   ======================================================= */

const CACHE_NAME = '4udrop-cache-v14.0';

// Install Event — Skip waiting to apply immediately
self.addEventListener('install', (e) => {
  console.log('[Service Worker] Installed');
  self.skipWaiting();
});

// Activate Event — Clean Old Caches Immediately
self.addEventListener('activate', (e) => {
  e.waitUntil(
    caches.keys().then((keyList) => {
      return Promise.all(
        keyList.map((key) => {
          console.log('[Service Worker] Removing cache:', key);
          return caches.delete(key);
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event — Network First Always for freshness
self.addEventListener('fetch', (e) => {
  e.respondWith(
    fetch(e.request).catch(() => caches.match(e.request))
  );
});
