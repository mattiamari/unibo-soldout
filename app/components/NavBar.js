'use strict';

import throttle from '../../node_modules/lodash-es/throttle.js';

const NavBar = {
    render: async () => {
        return /*html*/`
            <nav class="navbar" id="navbar">
                <a class="navbar-button" href="#"><i class="fa fa-bars"></i></a>

                <div class="navbar-buttons-right">
                    <a class="navbar-button" href="#/profile"><i class="fa fa-user"></i></a>
                    <a class="navbar-button" href="#/cart"><i class="fa fa-shopping-cart"></i></a>
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
