'use strict';

import { Shows } from '../model/Shows.js';

import NavBar from '../components/NavBar.js';
import htmlToElement from '../utils/htmlToElement.js';
import Statusbar from '../utils/statusbar.js';

class VenuePage {
    constructor(params) {
        this.venueId = params.id;
    }

    async render() {
        const venue = await Shows.getVenueDetails(this.venueId);

        const template = /*html*/`
            <div class="page page--venue">
                <header class="header">
                    <div class="header-layer header-bg" style="background-image: url(${venue.image.url})"></div>
                    <div class="header-layer header-bg-blend"></div>

                    <div class="header-layer header-content">
                        <h1 class="header-title">${venue.name}</h1>
                    </div>
                </header>

                <main>
                    <article>
                        <ul class="showInfoList">
                            <li>
                                <i class="fa fa-map-marker-alt" aria-label="Venue location"></i>
                                <span>${venue.location}</span>
                            </li>
                        </ul>

                        <h4>Dettagli</h4>
                        <p class="showDescription">${venue.description}</p>
                    </article>
                </main>
            </div>`;

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

export default VenuePage;
