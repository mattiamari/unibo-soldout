'use strict';

import NavBar from '../components/NavBar.js';
import htmlToElement from '../utils/htmlToElement.js';

class BuyEndPage {
    constructor(params) {
    }

    async render() {
        const navbar = await NavBar.render();

        const template = /*html*/`
            <div class="page page--buy">
                ${navbar}

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

        return htmlToElement(template);
    }

    afterRender() {
        NavBar.afterRender(document.getElementById('navbar'));
    }
}

export default BuyEndPage;
