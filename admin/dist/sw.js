/**
 * My Compass Admin Service Worker
 *
 * Lightweight caching for app shell and static assets with strict security exclusions
 * for WordPress REST APIs, authentication endpoints, and admin mutations.
 *
 * Strict Chemical X Directives:
 * - 2-Stage Atomic Booleans
 * - Zero em dashes (hyphens or colons only)
 */

const CACHE_NAME = 'compass-admin-v2';

const PRECACHE_ASSETS = [
  './manifest.webmanifest',
  './compass-icon.svg',
  './compass-icon-192.png',
  './compass-icon-512.png',
  './favicon.svg',
  './favicon.png'
];

self.oninstall = (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(PRECACHE_ASSETS).catch(() => {});
    })
  );
  self.skipWaiting();
};

self.onactivate = (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      const deletionPromises = cacheNames
        .filter((name) => name.startsWith('compass-admin-') && name !== CACHE_NAME)
        .map((name) => caches.delete(name));
      return Promise.all(deletionPromises);
    }).then(() => self.clients.claim())
  );
};

const shouldBypassRequest = (request, url) => {
  const isNotGet = request.method !== 'GET';
  const isRestApi = url.pathname.includes('/wp-json/');
  const isAdminAjax = url.pathname.includes('admin-ajax.php');
  const isWpLogin = url.pathname.includes('wp-login.php');
  const hasWpNonce = url.searchParams.has('_wpnonce') || url.searchParams.has('nonce');

  return isNotGet || isRestApi || isAdminAjax || isWpLogin || hasWpNonce;
};

self.onfetch = (event) => {
  const { request } = event;
  const url = new URL(request.url);
  const shouldBypass = shouldBypassRequest(request, url);
  if (shouldBypass) {
    return;
  }

  const isNavigation = request.mode === 'navigate';
  if (isNavigation) {
    event.respondWith(
      fetch(request).catch(() => caches.match(request))
    );
    return;
  }

  const hasStaticExtension = Boolean(url.pathname.match(/\.(?:js|css|woff2?|ttf|png|jpe?g|svg|webp|ico)$/i));
  const isInAssetsDir = url.pathname.includes('/assets/');
  const isStaticAsset = hasStaticExtension || isInAssetsDir;

  if (isStaticAsset) {
    event.respondWith(
      caches.match(request).then((cachedResponse) => {
        if (cachedResponse) {
          return cachedResponse;
        }

        return fetch(request).then((networkResponse) => {
          const isSuccessful = Boolean(networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic');
          if (!isSuccessful) {
            return networkResponse;
          }

          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(request, responseToCache);
          });

          return networkResponse;
        });
      })
    );
  }
};
