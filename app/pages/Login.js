'use strict';

import htmlToElement from '../utils/htmlToElement.js';
import NavBar from '../components/NavBar.js';
import Account from '../model/Account.js';
import formToObject from '../utils/formToObject.js';

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
                        <input type="email" id="email" name="email" required>

                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>

                        <button type="submit" class="button button--raised">Accedi</button>

                        <a class="button button--flat btnSignup" href="#/signup">Non sei registrato?</a>
                    </form>
                </main>
            </div>
        `;

        this.page = htmlToElement(template);
        const header = this.page.querySelector('header');
        const form = this.page.querySelector('form');

        this.navbar = new NavBar();
        header.insertBefore(this.navbar.render(), header.firstChild);

        form.addEventListener('submit', async e => {
            e.preventDefault();
            try {
                await Account.login(formToObject(form));
                window.location.hash = '#/profile';
            } catch (err) {
                // TODO Inform the user that the login was unsuccessful
            }
        });
        
        return this.page;
    }

    destroy() {
        this.navbar.destroy();
    }
}

export default LoginPage;
