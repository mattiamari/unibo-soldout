'use strict';

import htmlToElement from '../utils/htmlToElement.js';
import Language from '../model/Language.js'
import Account from '../model/Account.js';
import NavBar from '../components/NavBar.js';

const TicketCard = ticket => {
    return /*html*/`
        <li>
        <div class="ticket">
            <div class="infoContainer">
                <span class="showTitle">${ticket.title}</span>

                <span class="label">Data</span>
                <span>${Language.formatDateTime(ticket.date)}</span>

                <span class="label">Luogo</span>
                <span>${ticket.location}</span>

                <span class="label">Tipologia</span>
                <span>${ticket.type} - ${Language.formatCurrency(ticket.price)}</span>
            </div>

            <div class="qrContainer">
                <img src="/app/res/dummy-ticket-qr.png">
            </div>
        </div>
        </li>
    `;
};

class OrderDetailsPage {
    constructor(params) {
        this.orderId = params.id;
    }

    async render() {
        const order = await Account.getOrderDetails(this.orderId);
        const tickets = order.tickets.map(e => TicketCard(e)).join('\n');

        const template = /*html*/ `
            <div class="page page--orderdetails">
                <header>
                    <div class="header-content">
                        <h1>Ordine #${order.reference}</h1>
                    </div>
                </header>
                <main>
                    <table class="orderInfo">
                        <tr>
                            <th>Data</th>
                            <td>${Language.formatDateShort(order.date)}</td>
                        </tr>
                        <tr>
                            <th>Totale</th>
                            <td>${Language.formatCurrency(order.totalPrice)}</td>
                        </tr>
                    </table>

                    <ul class="ticketList">
                        ${tickets}
                    </ul>
                </main>
            </div>`;
        
        const page = htmlToElement(template);
        const header = page.querySelector('header');
        header.insertBefore((new NavBar()).render(), header.firstChild);

        return page;
    }
}

export default OrderDetailsPage;
