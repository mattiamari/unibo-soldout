'use strict';

const tickets = [];
const onChangeHandlers = [];

const Cart = {
    async init() {
        // TODO fetch cart from API
        notifyChange();
    },

    get tickets() {
        return tickets;
    },

    async addTicket(ticket) {
        tickets.push(ticket);
        notifyChange();

        // TODO sync cart with API
    },

    removeTicket(ticket) {
        const idx = tickets.findIndex(e => e === ticket);
        
        if (idx == -1) {
            return;
        }

        tickets.splice(idx, 1);
        notifyChange();

        // TODO sync cart with API
    },

    clear() {
        tickets.splice(0, tickets.length);
        notifyChange();
        // TODO sync cart with API
    },

    get isEmpty() {
        return tickets.length == 0;
    },

    addOnChangeHandler(handler) {
        onChangeHandlers.push(handler);
    },
}

function notifyChange() {
    onChangeHandlers.forEach(h => h());
}

export default Cart;
