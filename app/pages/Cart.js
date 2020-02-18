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
        this.element = null;
    }

    render() {
        const priceList = this.ticket.prices.map(e => PriceRow(e)).join('\n');
        
        const template = /*html*/`
            <li>
                <div>
                    <span class="show-name" aria-label="Show name">${this.ticket.show.title}</span>
                    <span class="show-location" aria-label="Show location">${this.ticket.show.location}</span>
                    <span class="show-date" aria-label="Show date">${Language.formatDateMedium(this.ticket.show.date)}</span>

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

        this.element = htmlToElement(template);

        this.element.querySelector('button.btnRemove').onclick = () => {
            Cart.removeTicket(this.ticket);
            this.animateRemove();
        };

        return this.element;
    }

    animateRemove() {
        this.element.classList.add('animate--removeListItem');

        window.setTimeout(() => {
            this.element.remove();
        }, 2000);
    }
}

class CartPage {
    constructor(params) {
        this.page = null;
    }

    async render() {
        const template = /*html*/`
            <div class="page page--cart">
                <header class="header">
                    <div class="header-content">
                        <h1>Il tuo carrello</h1>
                    </div>
                </header>

                <main>
                    <ul class="cartList"></ul>

                    <div class="cartEmpty hidden">
                        <h3>Il tuo carrello è vuoto</h3>
                        <a class="button button--flat" href="#/">Torna alla home</a>
                    </div>

                    <button class="button button--outline btnNext">Completa l'ordine</button>
                </main>
            </div>
        `;

        await Cart.init();
        this.page = htmlToElement(template);
        const header = this.page.querySelector('header');
        header.insertBefore((new NavBar()).render(), header.firstChild);

        this.page.querySelector('button.btnNext').addEventListener('click', () => {
            Cart.placeOrder().then(() => window.location.hash = '#/purchase-complete');
        });

        this.refreshDisplay();

        Cart.addOnChangeHandler(() => {
            this.toggleCartEmpty();
        });
        
        return this.page;
    }

    refreshDisplay() {
        const cartList = this.page.querySelector('.cartList');

        const ticketCards = Cart.tickets.map(e => new Card(e).render());
        cartList.innerHTML = '';
        ticketCards.forEach(e => cartList.append(e));

        this.toggleCartEmpty();
    }

    toggleCartEmpty() {
        const cartEmpty = this.page.querySelector('.cartEmpty');
        const btnNext = this.page.querySelector('.btnNext');

        if (Cart.isEmpty) {
            cartEmpty.classList.remove('hidden');
            btnNext.classList.add('hidden');
        } else {
            cartEmpty.classList.add('hidden');
            btnNext.classList.remove('hidden');
        }
    }

    afterRender() {
        Statusbar.setColor('#d7487d');
    }
};

export default CartPage;
