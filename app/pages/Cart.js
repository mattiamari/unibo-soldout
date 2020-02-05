'use strict';

import Cart from '../model/Cart.js';
import Language from '../model/Language.js';
import NavBar from '../components/NavBar.js';
import htmlToElement from '../utils/htmlToElement.js';
import Statusbar from '../utils/statusbar.js';

const PriceRow = priceDetail => {
    return /*html*/`
        <li>
            <span class="ticket-type" aria-label="Ticket type">${priceDetail.name}</span>
            <span class="ticket-price" aria-label="Ticket price">${Language.formatCurrency(priceDetail.price)}</span>
        </li>
    `;
};

class Card {
    constructor(ticket) {
        this.ticket = ticket;
        this.card = null;
    }

    render() {
        const priceList = this.ticket.prices.map(e => PriceRow(e)).join('\n');
        
        const template = /*html*/`
            <li>
                <div>
                    <span class="event-name" aria-label="Event name">${this.ticket.event.title}</span>
                    <span class="event-location" aria-label="Event location">${this.ticket.event.location}</span>
                    <span class="event-date" aria-label="Event date">${this.ticket.event.date}</span>

                    <ul class="priceList">
                        ${priceList}

                        <li class="ticket-totalPrice">
                            <span>Totale</span>
                            <span class="ticket-price" aria-label="Ticket price">${Language.formatCurrency(this.ticket.totalPrice)}</span>
                        </li>
                    </ul>                
                </div>

                <button class="button button--flat btnRemove" aria-label="Remove from cart">Rimuovi</button>
            </li>
        `;

        this.card = htmlToElement(template);

        this.card.querySelector('button.btnRemove').onclick = () => {
            Cart.removeTicket(this.ticket);
            this.animateRemove();
        };

        return this.card;
    }

    animateRemove() {
        this.card.classList.add('animate--removeListItem');

        window.setTimeout(() => {
            this.card.remove();
        }, 2000);
    }
}

class CartPage {
    constructor(params) {
        this.page = null;
    }

    async render() {
        const navbar = await NavBar.render();
        await Cart.init();

        const template = /*html*/`
            <div class="page page--cart">
                ${navbar}

                <header class="header">
                    <div class="header-content">
                        <h1>Il tuo carrello</h1>
                    </div>
                </header>

                <main>
                    <ul class="cartList">  
                    </ul>

                    <a class="button button--outline btnNext" href="#/purchase-complete">Completa l'ordine</a>
                </main>
            </div>
        `;

        this.page = htmlToElement(template);
        this.updateList();

        return this.page;
    }

    async updateList() {
        const ticketCards = Cart.tickets.map(e => new Card(e).render());
        const cartList = this.page.querySelector('.cartList');
        cartList.innerHTML = '';
        ticketCards.forEach(e => cartList.append(e));
    }

    afterRender() {
        NavBar.afterRender(document.getElementById('navbar'));
        Statusbar.setColor('#323232');
    }
};

export default CartPage;
