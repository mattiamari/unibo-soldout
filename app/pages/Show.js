'use strict';

import Shows from '../model/Shows.js';
import Language from '../model/Language.js';

import NavBar from '../components/NavBar.js';
import htmlToElement from '../utils/htmlToElement.js';
import Statusbar from '../utils/statusbar.js';

class ShowPage {
    constructor(params) {
        this.showId = params.id;
        this.page = null;
    }

    async render() {
        const show = await Shows.getShowDetails(this.showId);

        const template = /*html*/`
            <div class="page page--show">
                <header class="header">
                    <div class="header-layer header-bg" style="background-image: url(${show.getImage('vertical').url})"></div>
                    <div class="header-layer header-bg-blend"></div>

                    <div class="header-layer header-content">
                        <h1 class="header-title">${show.title}</h1>
                    </div>
                </header>

                <main>
                    <div class="buyButtonContainer">
                        <a class="button button--raised" href="#/show/${show.id}/buy">Acquista ora</a>
                    </div>

                    <article>
                        <ul class="showInfoList">
                            <li>
                                <i class="fa fa-calendar-alt" aria-label="Show date"></i>
                                <span>${Language.formatDateLong(show.date)}</span>
                            </li>
                            <li>
                                <i class="fa fa-map-marker-alt" aria-label="Show location"></i>
                                <span>${show.location}</span>
                            </li>
                            <li>
                                <i class="fa fa-ticket-alt" aria-label="Ticket price"></i>
                                <span>da ${Language.formatCurrency(show.basePrice)}</span>
                            </li>
                        </ul>

                        <h4>Dettagli</h4>
                        <p class="showDescription">${show.description}</p>
                    </article>
                </main>
            </div>
        `;

        this.page = htmlToElement(template);
        const header = this.page.querySelector('header');
        header.insertBefore((new NavBar()).render(), header.firstChild);

        return this.page;
    }

    afterRender() {
        Statusbar.setColor('#323232');
    }
};

export default ShowPage;
