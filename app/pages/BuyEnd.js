'use strict';

import NavBar from '../components/NavBar.js';
import htmlToElement from '../utils/htmlToElement.js';
import Statusbar from '../utils/statusbar.js';

class BuyEndPage {
    constructor(params) {
    }

    async render() {
        const template = /*html*/`
            <div class="page page--buy">
                <header class="header"></header>

                <main>
                    <section class="buyStep">
                        <h2>Acquisto completato! 🎉</h2>
                        <p>Puoi trovare i tuoi biglietti nel tuo profilo utente.</p>

                        <nav class="buttons buttons--vertical">
                            <a class="button button--outline" href="#/profile">Vai al profilo</a>
                            <a class="button button--flat" href="#/">Torna alla home</a>
                        </nav>
                    </section>
                </main>
            </div>
        `;

        const page = htmlToElement(template);
        const header = page.querySelector('header');
        header.insertBefore((new NavBar()).render(), header.firstChild);

        return page;
    }

    afterRender() {
        Statusbar.setColor('#d7487d');
    }
}

export default BuyEndPage;
