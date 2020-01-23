'use strict';

import HomePage from './pages/Home.js';
import Error404 from './pages/Error404.js';

const routes = {
    '/': HomePage
};

window.addEventListener('DOMContentLoaded', router);
window.addEventListener('hashchange', router);

async function router() {
    const pageContainer = document.body;
    const request = parseRoute(location.hash);
    let page = Error404;
    let params = {};

    for (const route in routes) {
        const parsed = parseRoute(route);

        if (parsed.length != request.length) {
            break;
        }
    
        for (const [i, frag] of parsed.entries()) {
            // If the current route template fragment is a variable (starts with ':')
            // add that to params
            if (frag.startsWith(':')) {
                params[frag.slice(1)] = request[i];
                continue;
            }
    
            if (frag !== request[i]) {
                break;
            }
        }

        // Route has been matched
        page = routes[route];
    }

    pageContainer.innerHTML = await page.render(params);
    page.afterRender();
}

function parseRoute(route) {
    return route.slice(1).split('/')
        .filter(e => e.length > 0);
}
