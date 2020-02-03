'use strict';

import Events from '../model/Events.js'
import Purchase from '../model/Purchase.js';
import Language from '../model/Language.js';
import NavBar from '../components/NavBar.js';
import htmlToElement from '../utils/htmlToElement.js';


const ticketTypeRow = ticketType => {
    return /*html*/`
        <li data-ticket-type-id="${ticketType.id}">
            <span class="ticketType-name" aria-label="Ticket type">${ticketType.name}</span>
            <span class="ticketType-price" aria-label="Ticket price">${Language.formatCurrency(ticketType.price)}</span>
        </li>
    `;
};


class BuyPage {
    constructor(params) {
        this.eventId = params.id;
        this.page = null;
        this.purchase = null;
    }

    async render() {
        const navbar = await NavBar.render();
        const event = await Events.getEventDetails(this.eventId);
        this.purchase = new Purchase(event);

        const ticketTypes = event.ticketTypes.map(e => ticketTypeRow(e)).join('\n');

        const template = /*html*/`
            <div class="page page--buy">
                ${navbar}

                <main>
                    <section class="buyStep">
                        <h2>Che tipo di biglietto vuoi acquistare?</h2>

                        <ul class="ticketTypeList">
                            ${ticketTypes}
                        </ul>

                        <button class="button button--outline btnNext" id="btnShowStepQuantity">Continua</button>
                    </section>

                    <section class="buyStep buyStep--hidden">
                        <h2>Di quanti biglietti hai bisogno?</h2>

                        <div class="quantitySelector" id="quantitySelector">
                            <button class="button button--outline button--round" id="quantityMinus" aria-label="Decrease quantity">-</button>
                            <span aria-label="Current quantity">1</span>
                            <button class="button button--outline button--round" id="quantityPlus" aria-label="Increase quantity">+</button>
                        </div>

                        <button class="button button--outline btnNext" id="btnAddToCart">Aggiungi al carrello</button>
                    </section>

                    <section class="buyStep buyStep--hidden">
                        <h2>Abbiamo aggiunto il biglietto al tuo carrello!</h2>

                        <nav class="buttons buttons--vertical">
                            <a class="button button--outline" href="#/cart">Vai al carrello</a>
                            <a class="button button--flat" href="#/event/${event.id}">Torna all'evento</a>
                        </nav>
                    </section>
                </main>
            </div>
        `;

        this.page = htmlToElement(template);
        return this.page;
    }

    onTicketTypeClick(node) {
        node.parent.querySelectorAll('li').forEach(e => e.classList.remove('ticketType--selected'));
    }

    afterRender() {
        NavBar.afterRender(document.getElementById('navbar'));

        /*document.querySelector('.ticketTypeList > li').forEach(e => {
            e.onclick = clickEvent => onTicketTypeClick(clickEvent.targetElement);
        }); */
    }
};

export default BuyPage;
