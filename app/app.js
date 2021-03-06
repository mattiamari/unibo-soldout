'use strict';

import HomePage from './pages/Home.js';
import Error404 from './pages/Error404.js';
import ShowPage from './pages/Show.js';
import BuyPage from './pages/Buy.js';
import CartPage from './pages/Cart.js';
import BuyEndPage from './pages/BuyEnd.js';
import ProfilePage from './pages/Profile.js';
import LoginPage from './pages/Login.js';
import SignupPage from './pages/Signup.js';
import Account from './model/Account.js';
import NotificationsPage from './pages/Notifications.js';
import OrderDetailsPage from './pages/OrderDetails.js';
import VenuePage from './pages/Venue.js';
import ArtistPage from './pages/Artist.js';

import '@fortawesome/fontawesome-free/css/all.css';

const routes = {
    '/': HomePage,
    '/404': Error404,
    '/cart': CartPage,
    '/show/:id': ShowPage,
    '/show/:id/buy': BuyPage,
    '/purchase-complete': BuyEndPage,
    '/profile': ProfilePage,
    '/login': LoginPage,
    '/signup': SignupPage,
    '/notifications': NotificationsPage,
    '/order/:id': OrderDetailsPage,
    '/venue/:id': VenuePage,
    '/artist/:id': ArtistPage,
};

const mountpoint = 'app';

// Init some stuff
Account.init();

window.addEventListener('DOMContentLoaded', router);
window.addEventListener('hashchange', router);

let pageInstance = null;

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
        break;
    }

    // FIXME: causes elements to disappear when API endpoint has latency
    if (pageInstance && pageInstance.destroy) {
        pageInstance.destroy();
    }
    
    pageInstance = new page(params);
    const rendered = await pageInstance.render();
    
    const mountpointElement = document.getElementById(mountpoint);
    mountpointElement.innerHTML = '';

    // Scroll to top before changing page. This prevents issues when the page is
    // scrolled down and the next page has an entry animation
    document.documentElement.scroll(0, 0);

    mountpointElement.append(rendered);

    if (pageInstance.afterRender) {
        pageInstance.afterRender();
    }
}

function parseRoute(route) {
    return route.slice(1).split('/')
        .filter(e => e.length > 0);
}
