'use strict';

import Events from '../model/Events.js';
import Language from '../model/Language.js';

import NavBar from '../components/NavBar.js';
import htmlToElement from '../utils/htmlToElement.js';
import Statusbar from '../utils/statusbar.js';

class EventPage {
    constructor(params) {
        this.eventId = params.id;
        this.page = null;
    }

    async render() {
        const event = await Events.getEventDetails(this.eventId);
        const navbar = await NavBar.render();

        const template = /*html*/`
            <div class="page page--event">
                ${navbar}

                <header class="header">
                    <div class="header-layer header-bg" style="background-image: url(${event.images.detailsBackground.url})"></div>
                    <div class="header-layer header-bg-blend"></div>

                    <div class="header-layer header-content">
                        <h1 class="header-title">${event.title}</h1>
                    </div>
                </header>

                <main>
                    <div class="buyButtonContainer">
                        <a class="button button--raised" href="#/event/${event.id}/buy">Acquista ora</a>
                    </div>

                    <article>
                        <ul class="eventInfoList">
                            <li>
                                <i class="fa fa-calendar-alt" aria-label="Event date"></i>
                                <span>${event.date}</span>
                            </li>
                            <li>
                                <i class="fa fa-map-marker-alt" aria-label="Event location"></i>
                                <span>${event.location}</span>
                            </li>
                            <li>
                                <i class="fa fa-ticket-alt" aria-label="Ticket price"></i>
                                <span>da ${Language.formatCurrency(event.basePrice)}</span>
                            </li>
                        </ul>

                        <h4>Dettagli</h4>
                        <p class="eventDescription">${event.description}</p>
                    </article>
                </main>
            </div>
        `;

        this.page = htmlToElement(template);
        return this.page;
    }

    afterRender() {
        NavBar.afterRender(document.getElementById('navbar'));
        Statusbar.setColor('#323232');
    }
};

export default EventPage;
