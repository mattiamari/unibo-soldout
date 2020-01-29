'use strict';

import HomePage from './pages/Home.js';
import Error404 from './pages/Error404.js';
import EventPage from './pages/Event.js';
import BuyPage from './pages/Buy.js';

const routes = {
    '/': HomePage,
    '/404': Error404,
    '/event/:id': EventPage,
    '/event/:id/buy': BuyPage,
};

const mountpoint = 'app';

window.addEventListener('DOMContentLoaded', router);
window.addEventListener('hashchange', router);

async function router() {
    const request = parseRoute(location.hash);
    let params = {};
    let page = Error404;

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

    const rendered = await page.render(params);

    // Scroll to top before changing page. This prevents issues when the page is
    // scrolled down and the next page has an entry animation
    document.documentElement.scroll(0, 0);
    
    document.getElementById(mountpoint).innerHTML = rendered;
    page.afterRender();
}

function parseRoute(route) {
    return route.slice(1).split('/')
        .filter(e => e.length > 0);
}
