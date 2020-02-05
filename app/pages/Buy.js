'use strict';

import Events from '../model/Events.js'
import Language from '../model/Language.js';
import CartItem from '../model/CartItem.js';
import Cart from '../model/Cart.js';
import NavBar from '../components/NavBar.js';
import htmlToElement from '../utils/htmlToElement.js';
import QuantitySelector from '../components/QuantitySelector.js';
import Statusbar from '../utils/statusbar.js';

const bindings = new WeakMap();

class TicketTypeRow {
    constructor(ticketType, cartItem) {
        this.ticketType = ticketType;
        this.cartItem = cartItem;
        this.element = null;
    }

    render() {
        const template = /*html*/`
            <li>
                <span class="ticketType-name" aria-label="Ticket type">${this.ticketType.name}</span>
                <span class="ticketType-price" aria-label="Ticket price">${Language.formatCurrency(this.ticketType.price)}</span>
            </li>
        `;

        this.element = htmlToElement(template);
        bindings.set(this.element, this);

        this.element.onclick = e => {
            e.target.parentElement.querySelectorAll('li').forEach(r => bindings.get(r).deselect());
            this.select();
        };

        return this.element;
    }

    deselect() {
        this.element.classList.remove('ticketType--selected');
    }

    select() {
        this.element.classList.add('ticketType--selected');
        this.cartItem.ticketTypeId = this.ticketType.id;
    }
}

class BuyPage {
    constructor(params) {
        this.eventId = params.id;
        this.page = null;
        this.cartItem = null;
    }

    async render() {
        const navbar = await NavBar.render();
        const event = await Events.getEventDetails(this.eventId);
        this.cartItem = new CartItem(event);
        const ticketTypes = event.ticketTypes.map(e => new TicketTypeRow(e, this.cartItem));
        const quantitySelector = new QuantitySelector(this.cartItem);

        const template = /*html*/`
            <div class="page page--buy">
                ${navbar}

                <main>
                    <section class="buyStep buyStep--ticketType">
                        <h2>Che tipo di biglietto vuoi acquistare?</h2>

                        <ul class="ticketTypeList"></ul>

                        <button class="button button--outline button--disabled btnNext" id="btnShowStepQuantity">Continua</button>
                    </section>

                    <section class="buyStep buyStep--quantity buyStep--hidden">
                        <h2>Di quanti biglietti <span class="ticketType"></span> hai bisogno?</h2>

                        <div class="placeholder--quantitySelector"></div>

                        <button class="button button--outline btnNext" id="btnAddToCart">Aggiungi al carrello</button>
                    </section>

                    <section class="buyStep buyStep--success buyStep--hidden">
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
        const ticketTypeList = this.page.querySelector('.ticketTypeList');
        const ticketTypeName = this.page.querySelector('.buyStep--quantity .ticketType');
        const btnGoQuantityStep = this.page.querySelector('.buyStep--ticketType .btnNext');

        for (let ticketRow of ticketTypes) {
            ticketTypeList.append(ticketRow.render());
        }

        btnGoQuantityStep.onclick = () => this.showStep('quantity');
        this.page.querySelector('.placeholder--quantitySelector').replaceWith(quantitySelector.render());

        this.cartItem.addOnChangeHandler(() => {
            if (this.cartItem.ticketTypeId) {
                btnGoQuantityStep.classList.remove('button--disabled');
            } else {
                btnGoQuantityStep.classList.add('button--disabled');
            }

            ticketTypeName.innerHTML = this.cartItem.ticketType.name;
        });

        this.page.querySelector('.buyStep--quantity .btnNext').onclick = async () => {
            await Cart.addTicket(this.cartItem);
            this.showStep('success');
        };

        return this.page;
    }

    showStep(step) {
        this.page.querySelectorAll('.buyStep').forEach(e => e.classList.add('buyStep--hidden'));
        this.page.querySelector('.buyStep--' + step).classList.remove('buyStep--hidden');
    }

    afterRender() {
        NavBar.afterRender(document.getElementById('navbar'));
        Statusbar.setColor('#323232');
    }
};

export default BuyPage;