'use strict';

import Events from '../model/Events.js';
import Language from '../model/Language.js';

import NavBar from '../components/NavBar.js';

const EventPage = {
    render: async params => {
        const event = await Events.getEventDetails(params.id);
        const navbar = await NavBar.render();

        return /*html*/`
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
    },

    afterRender: () => {
        NavBar.afterRender(document.querySelector('.page.page--event .navbar'));
    },
};

export default EventPage;
