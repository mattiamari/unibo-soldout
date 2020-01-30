'use strict';

import CartItem from './CartItem.js';
import Events from './Events.js';

const tickets = [];

const Cart = {
    async init() {
        const dummyTicket = new CartItem(await Events.getEventDetails('abc'));
        dummyTicket.addPriceDetail('Poltrona', 100.00)
                   .addPriceDetail('Prevendita', 9.90);
        this.addTicket(dummyTicket);
    },

    get tickets() {
        return tickets;
    },

    addTicket(ticket) {
        tickets.push(ticket);
    },

    removeTicket(ticket) {
        const idx = tickets.findIndex((a,b) => a === ticket);
        
        if (idx == -1) {
            return;
        }

        tickets.splice(idx, 1);
    },

    clear() {
        tickets.splice(0, tickets.length);
    },
}

export default Cart;
