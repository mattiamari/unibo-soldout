'use strict';

import CartItem from './CartItem.js';
import Events from './Events.js';

const tickets = [];

const Cart = {
    async init() {
        const dummyTicket = new CartItem(await Events.getEventDetails('abcd1'));
        dummyTicket.ticketTypeId = 'abc1';
        dummyTicket.addAdditionalPrice('Prevendita', 9.90);
        this.addTicket(dummyTicket);
    },

    get tickets() {
        return tickets;
    },

    addTicket(ticket) {
        tickets.push(ticket);
    },

    removeTicket(ticket) {
        const idx = tickets.findIndex(e => e === ticket);
        
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
