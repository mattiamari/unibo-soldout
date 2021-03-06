'use strict';

import htmlToElement from '../utils/htmlToElement.js';
import NavBar from '../components/NavBar.js';
import Account from '../model/Account.js';
import formToObject from '../utils/formToObject.js';

class SignupPage {
    constructor() {
        this.page = null;
    }

    render() {
        const template = /*html*/ `
            <div class="page page--signup">
                <header class="header">
                    <div class="header-content">
                        <h1>Registrati</h1>
                    </div>
                </header>
                <main>
                    <form class="form" action="#">
                        <label for="firstname">Nome</label>
                        <input type="text" id="firstname" name="firstname">

                        <label for="lastname">Cognome</label>
                        <input type="text" id="lastname" name="lastname">

                        <label for="birthdate">Data di nascita</label>
                        <input type="date" id="birthdate" name="birthdate">
                        
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email">

                        <label for="password">Password</label>
                        <input type="password" id="password" name="password">

                        <label for="passwordRepeat">Ripeti password</label>
                        <input type="password" id="passwordRepeat" name="passwordRepeat">

                        <button type="submit" class="button button--raised">Registrati</button>
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
                await Account.signup(formToObject(form));
                window.location.hash = '#/profile';
            } catch (err) {
                // TODO Inform the user about the error
            }
        });

        return this.page;
    }

    destroy() {
        this.navbar.destroy();
    }
}

export default SignupPage;
