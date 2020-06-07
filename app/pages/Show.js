'use strict';

import { Shows } from '../model/Shows.js';
import Language from '../model/Language.js';

import NavBar from '../components/NavBar.js';
import htmlToElement from '../utils/htmlToElement.js';
import Statusbar from '../utils/statusbar.js';

class ShowPage {
    constructor(params) {
        this.showId = params.id;
    }

    async render() {
        const show = await Shows.getShowDetails(this.showId);

        let buyButton = /*html*/`<a class="button button--raised" href="#/show/${show.id}/buy">Acquista ora</a>`;
        if (show.isSoldout) {
            buyButton = /*html*/`<a class="button button--raised button--disabled" href="">Biglietti esauriti</a>`;
        }

        const template = /*html*/`
            <div class="page page--show">
                <header class="header">
                    <div class="header-layer header-bg" style="background-image: url(${show.getImage('horizontal').url})"></div>
                    <div class="header-layer header-bg-blend"></div>

                    <div class="header-layer header-content">
                        <h1 class="header-title">${show.title}</h1>
                    </div>
                </header>

                <main>
                    <div class="buyButtonContainer">
                        ${buyButton}
                    </div>

                    <article>
                        <ul class="showInfoList">
                            <li>
                                <i class="fa fa-calendar-alt" aria-label="Show date"></i>
                                <span>${Language.formatDateLong(show.date)}</span>
                            </li>
                            <li>
                                <i class="fa fa-map-marker-alt" aria-label="Show location"></i>
                                <a href="#/venue/${show.venueId}">${show.location}</a>
                            </li>
                            <li>
                                <i class="fa fa-user" aria-label="Show artist"></i>
                                <a href="#/artist/${show.artistId}">${show.artist}</a>
                            </li>
                            <li>
                                <i class="fa fa-ticket-alt" aria-label="Ticket price"></i>
                                <span>da ${Language.formatCurrency(show.lowestPrice)}</span>
                            </li>
                        </ul>

                        <h4>Dettagli</h4>
                        <p class="showDescription">${show.description}</p>
                    </article>
                </main>
            </div>
        `;

        const page = htmlToElement(template);
        const header = page.querySelector('header');

        this.navbar = new NavBar();
        header.insertBefore(this.navbar.render(), header.firstChild);

        return page;
    }

    afterRender() {
        Statusbar.setColor('#323232');
    }

    destroy() {
        this.navbar.destroy();
    }
};

export default ShowPage;
