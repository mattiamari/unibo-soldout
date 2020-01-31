'use strict';

import Cart from '../model/Cart.js';
import NavBar from '../components/NavBar.js';

const PriceRow = priceDetail => {
    return /*html*/`
        <li>
            <span class="ticket-type" aria-label="Ticket type">${priceDetail.name}</span>
            <span class="ticket-price" aria-label="Ticket price">${priceDetail.price}</span>
        </li>
    `;
};

const Card = ticket => {
    const priceList = ticket.prices.map(e => PriceRow(e)).join('\n');

    return /*html*/`
        <li>
            <div>
                <span class="event-name" aria-label="Event name">${ticket.event.title}</span>
                <span class="event-location" aria-label="Event location">${ticket.event.location}</span>
                <span class="event-date" aria-label="Event date">${ticket.event.date}</span>

                <ul class="priceList">
                    ${priceList}

                    <li class="ticket-totalPrice">
                        <span>Totale</span>
                        <span class="ticket-price" aria-label="Ticket price">${ticket.totalPrice}</span>
                    </li>
                </ul>                
            </div>

            <button class="button button--flat btnRemove" aria-label="Remove from cart">Rimuovi</button>
        </li>
    `;
};

class CartPage {
    constructor(params) {
    }

    async render() {
        const navbar = await NavBar.render();
        await Cart.init();
        const tickets = Cart.tickets;
        const ticketCards = tickets.map(e => Card(e)).join('\n');

        return /*html*/`
            <div class="page page--cart">
                ${navbar}

                <header class="header">
                    <div class="header-content">
                        <h1>Il tuo carrello</h1>
                    </div>
                </header>

                <main>
                    <ul class="cartList">
                        ${ticketCards}
                    </ul>

                    <a class="button button--outline btnNext" href="#/purchase-complete">Completa l'ordine</a>
                </main>
            </div>
        `;
    }

    afterRender() {
        NavBar.afterRender(document.getElementById('navbar'));
    }
};

export default CartPage;
