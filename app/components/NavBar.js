'use strict';

import throttle from '../utils/throttle.js';
import htmlToElement from '../utils/htmlToElement.js';
import Account from '../model/Account.js';
import AppLogo from './AppLogo.js'

class NavBar {
    constructor() {
    }

    render() {
        let backButtonTemplate = /*html*/`<button class="button button--icononly" id="btnBack" aria-label="Go back">
            <i class="button-icon fas fa-arrow-left"></i></button>`;
        
        if (location.hash == '' || location.hash == '#/') {
            backButtonTemplate = '';
        }

        const logo = AppLogo();

        const template = /*html*/`
            <nav class="navbar">
                ${backButtonTemplate}
                <a class="homeButton" href="#/">${logo}</a>
                <div class="navbar-buttons-right">
                    <a class="button button--icononly" href="#/notifications" aria-label="Notifications">
                        <i class="button-icon fa fa-bell"></i>
                    </a>
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
        const btnBack = this.element.querySelector('#btnBack');

        if (btnBack) {
            btnBack.onclick = () => history.back();
        }

        this.onScroll = throttle(200, () => {
            if (document.documentElement.scrollTop < 56) {
                this.element.classList.remove('navbar--raised');
            } else {
                this.element.classList.add('navbar--raised');
            }
        });

        window.addEventListener('scroll', this.onScroll);

        return this.element;
    }

    destroy() {
        window.removeEventListener('scroll', this.onScroll);
        this.onScroll = null;
        this.element = null;
    }
}

export default NavBar;
