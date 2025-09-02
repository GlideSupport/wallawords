/**
Import the Workbox library from the Google CDN.
It is possible to serve this locally should we want to remove the dependency on Google
See here for more info: https://developers.google.com/web/tools/workbox/modules/workbox-sw
**/
importScripts('https://storage.googleapis.com/workbox-cdn/releases/3.3.1/workbox-sw.js');
const offlinePage = '/offline/';
// SETTINGS
workbox.setConfig({
  // set the local path is not using Google CDN
  //modulePathPrefix: '/directory/to/workbox/'

  // By default, workbox-sw will use the debug build for sites on localhost,
  // but for any other origin it’ll use the production build. Force by setting to true.
  // debug: true
});

/**
 * Pages to cache (networkFirst)
 */

/**
 * Add on install
 */
self.addEventListener('install', (event) => {
  const urls = ['/offline/'];
  const cacheName = workbox.core.cacheNames.runtime;
  event.waitUntil(caches.open(cacheName).then((cache) => cache.addAll(urls)))
});

var networkFirst = workbox.strategies.networkFirst({
  cacheName: 'cache-pages' 
});

const customHandler = async (args) => {
  try {
    const response = await networkFirst.handle(args);
    return response || await caches.match(offlinePage);
  } catch (error) {
    return await caches.match(offlinePage);
  }
};

workbox.routing.registerRoute(
    new RegExp('/'), 
    customHandler
);

// If any precache rules are defined in the Workbox setup this is where they will be injected
workbox.precaching.precacheAndRoute([]);

// skip the 'worker in waiting' phase and activate immediately
workbox.skipWaiting();
// claim any clients that match the worker scope immediately. requests on these pages will
// now go via the service worker. 
workbox.clientsClaim();
