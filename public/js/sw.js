/**
 * COMPASS Sovereign Service Worker & Web Push Handler
 * Handles offline caching and native W3C Push notifications.
 */

self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

// Native Web Push Notification Event Listener
self.addEventListener('push', (event) => {
  let data = {
    title: 'COMPASS System Update',
    body: 'New notification from your COMPASS cockpit.',
    icon: '',
    badge: '',
    url: '/wp-admin/admin.php?page=xophz-compass#/',
    tag: 'compass-notification',
  };

  if (event.data) {
    try {
      const parsed = event.data.json();
      data = { ...data, ...parsed };
    } catch (e) {
      data.body = event.data.text() || data.body;
    }
  }

  const notificationOptions = {
    body: data.body,
    icon: data.icon || undefined,
    badge: data.badge || undefined,
    tag: data.tag || 'compass-alert',
    vibrate: [200, 100, 200],
    data: {
      url: data.url || '/wp-admin/admin.php?page=xophz-compass#/',
      timestamp: Date.now(),
    },
    actions: [
      { action: 'open', title: 'Open COMPASS' }
    ]
  };

  event.waitUntil(
    self.registration.showNotification(data.title, notificationOptions)
  );
});

// Notification Click Interaction
self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  const targetUrl = (event.notification.data && event.notification.data.url) ? event.notification.data.url : '/wp-admin/admin.php?page=xophz-compass#/';

  event.waitUntil(
    self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
      for (const client of clientList) {
        if (client.url && 'focus' in client) {
          if (client.url.includes('page=xophz-compass') || client.url.includes(targetUrl)) {
            return client.focus();
          }
        }
      }
      if (self.clients.openWindow) {
        return self.clients.openWindow(targetUrl);
      }
    })
  );
});
