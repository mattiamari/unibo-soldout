'use strict';

import throttle from '../../node_modules/lodash-es/throttle.js';

const NavBar = {
    render: async () => {
        return /*html*/`
            <nav class="navbar" id="navbar">
                <a class="button button--icononly" href="#" aria-label="App menu"><i class="button-icon fa fa-bars"></i></a>

                <div class="navbar-buttons-right">
                    <a class="button button--icononly" href="#/profile" aria-label="User profile"><i class="button-icon fa fa-user"></i></a>
                    <a class="button button--icononly" href="#/cart" aria-label="Shopping cart"><i class="button-icon fa fa-shopping-cart"></i></a>
                </div>
            </nav>
        `;
    },

    afterRender: node => {
        window.addEventListener('scroll', throttle(() => {
            if (document.documentElement.scrollTop < 56) {
                NavBar.onScrollTop(node);
                return;
            }

            NavBar.onScrollMiddle(node);
        }, 100));
    },

    onScrollTop: node => {
        node.classList.remove('navbar--raised');
    },

    onScrollMiddle: node => {
        node.classList.add('navbar--raised');
    }
};

export default NavBar;
