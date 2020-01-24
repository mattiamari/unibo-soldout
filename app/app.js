'use strict';

import HomePage from './pages/Home.js';
import Error404 from './pages/Error404.js';

const routes = {
    '/': HomePage,
    '/404': Error404,
};

window.addEventListener('DOMContentLoaded', router);
window.addEventListener('hashchange', router);

let firstLoad = true;

async function router() {
    const hashIsPage = location.hash.startsWith('#/');

    if (!firstLoad && !hashIsPage) {
        return;
    }

    const request = parseRoute(location.hash);
    let params = {};

    let page = Error404;

    if (firstLoad && !hashIsPage) {
        page = HomePage;
        firstLoad = false;
    }

    routesLoop:
    for (const route in routes) {
        const parsed = parseRoute(route);

        if (parsed.length != request.length) {
            continue;
        }
    
        for (const [i, frag] of parsed.entries()) {
            // If the current route template fragment is a variable (starts with ':')
            // add that to params
            if (frag.startsWith(':')) {
                params[frag.slice(1)] = request[i];
                continue;
            }
            
            // Route didn't match. Go on with the next
            if (frag !== request[i]) {
                params = {};
                continue routesLoop;
            }
        }

        // Route matched
        page = routes[route];
    }

    document.body.innerHTML = await page.render(params);
    page.afterRender();
}

function parseRoute(route) {
    return route.slice(1).split('/')
        .filter(e => e.length > 0);
}
