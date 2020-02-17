'use strict';

import Account from "./Account.js";
import CartItem from "./CartItem.js";

const tickets = [];
const onChangeHandlers = [];

const Cart = {
    async init() {
        if (!Account.isLoggedIn) {
            return;
        }

        const res = await fetch('/api/cart', {headers: Account.authHeaders});
        const items = (await res.json()).cartItems;

        tickets.splice(0, tickets.length);

        for (let e of items) {
            const showRes = await fetch('/api/show/' + e.show_id);
            const show = (await showRes.json()).show;
            const cartItem = new CartItem(show);
            cartItem._quantity = e.quantity;
            cartItem._ticketTypeId = e.ticketTypeId;
            tickets.push(cartItem);
        }

        notifyChange();
    },

    get tickets() {
        return tickets;
    },

    async addTicket(ticket) {
        const req = new Request('/api/cart', {
            method: 'PUT',
            headers: Account.authHeaders,
            body: ticket.toJson()
        });
        const res = await fetch(req);

        if (!res.ok) {
            return new Error();
        }

        tickets.push(ticket);
        notifyChange();
    },

    async removeTicket(ticket) {
        const idx = tickets.findIndex(e => e === ticket);
        
        if (idx == -1) {
            return;
        }

        const req = new Request('/api/cart/' + ticket._ticketTypeId, {
            method: 'DELETE',
            headers: Account.authHeaders
        });
        const res = await fetch(req);

        if (!res.ok) {
            return new Error();
        }

        tickets.splice(idx, 1);
        notifyChange();
    },

    async clear() {
        const req = new Request('/api/cart', {
            method: 'DELETE',
            headers: Account.authHeaders
        });
        const res = await fetch(req);

        if (!res.ok) {
            return new Error();
        }

        tickets.splice(0, tickets.length);
        notifyChange();
    },

    async placeOrder() {
        const req = new Request('/api/cart/place-order', {
            method: 'PUT',
            headers: Account.authHeaders
        });
        const res = await fetch(req);

        if (!res.ok) {
            return new Error();
        }
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
