'use strict';

import throttle from '../utils/throttle.js';
import htmlToElement from '../utils/htmlToElement.js';
import Account from '../model/Account.js';

class NavBar {
    constructor() {
        this.element = null;
    }

    render() {
        const template = /*html*/`
            <nav class="navbar">
                <a class="button button--icononly" href="#" aria-label="App menu"><i class="button-icon fa fa-bars"></i></a>

                <div class="navbar-buttons-right">
                    <a class="button button--icononly" href="#/${Account.isLoggedIn ? 'profile' : 'login'}" aria-label="User profile">
                        <i class="button-icon fa fa-user"></i>
                    </a>
                    <a class="button button--icononly" href="#/cart" aria-label="Shopping cart">
                        <i class="button-icon fa fa-shopping-cart"></i>
                    </a>
                </div>
            </nav>
        `;

        this.element = htmlToElement(template);

        window.addEventListener('scroll', throttle(200, () => {
            if (document.documentElement.scrollTop < 56) {
                this.onScrollTop();
            } else {
                this.onScrollMiddle();
            }
        }));

        return this.element;
    }

    onScrollTop() {
        this.element.classList.remove('navbar--raised');
    }

    onScrollMiddle() {
        this.element.classList.add('navbar--raised');
    }
}

export default NavBar;
