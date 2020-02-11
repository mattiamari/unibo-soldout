'use strict';

import htmlToElement from '../utils/htmlToElement.js';
import NavBar from '../components/NavBar.js';
import Account from '../model/Account.js';

class LoginPage {
    constructor() {
        this.page = null;
    }

    render() {
        const template = /*html*/ `
            <div class="page page--login">
                <header class="header">
                    <div class="header-content">
                        <h1>Accedi</h1>
                    </div>
                </header>
                <main>
                    <form class="form" action="#">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email">

                        <label for="password">Password</label>
                        <input type="password" id="password" name="password">

                        <button type="submit" class="button button--raised">Accedi</button>

                        <a class="button button--flat btnSignup" href="#/signup">Non sei registrato?</a>
                    </form>
                </main>
            </div>
        `;

        this.page = htmlToElement(template);
        const header = this.page.querySelector('header');
        const form = this.page.querySelector('form');

        header.insertBefore((new NavBar).render(), header.firstChild);

        form.addEventListener('submit', e => {
            e.preventDefault();
            const formdata = new FormData(form);
            Account.login(formdata).then(() => window.location.hash = '#/profile');
        });
        
        return this.page;
    }
}

export default LoginPage;
