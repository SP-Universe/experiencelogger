self.addEventListener("install" , evt => {
    console.log(evt);
    //evt.waitUntil(caches.open("main_cache").then(cache => cache.addAll(["/", "/something"])));

    self.skipWaiting();
});

self.addEventListener("activate" , evt => {
    clients.claim();
});

self.addEventListener("fetch" , event => {

    // Get the request
    let request = event.request;

    // Bug fix
    // https://stackoverflow.com/a/49719964
    if (event.request.cache === 'only-if-cached' && event.request.mode !== 'same-origin') return;

    // HTML files
    // Network-first
    if (request.headers.get('Accept').includes('text/html')) {
        event.respondWith(
            fetch(request).then(function (response) {
                return response;
            }).catch(function (error) {
                return caches.match(request).then(function (response) {
                    return response;
                });
            })
        );
        return;
    }

    // CSS & JavaScript
    // Offline-first
    if (request.headers.get('Accept').includes('text/css') || request.headers.get('Accept').includes('text/javascript')) {
        // Check the cache first
        // If it's not found, send the request to the network
        event.respondWith(
            caches.match(request).then(function (response) {
                return response || fetch(request).then(function (response) {
                    return response;
                });
            })
        );
        return;
    }

    // Images
    // Offline-first
    if (request.headers.get('Accept').includes('image')) {
        // Check the cache first
        // If it's not found, send the request to the network
        event.respondWith(
            caches.match(request).then(function (response) {
                return response || fetch(request).then(function (response) {
                    return response;
                });
            })
        );
    }
});
