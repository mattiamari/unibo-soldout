'use strict';

import htmlToElement from '../utils/htmlToElement.js';
import NavBar from '../components/NavBar.js';
import Account from '../model/Account.js';

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

        header.insertBefore((new NavBar).render(), header.firstChild);

        form.addEventListener('submit', e => {
            e.preventDefault();
            const formdata = new FormData(form);
            Account.signup(formdata).then(() => window.location.hash = '#/profile');
        });

        return this.page;
    }
}

export default SignupPage;
