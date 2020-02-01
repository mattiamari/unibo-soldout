'use strict';

class CartItem {
    constructor(event) {
        this.event = event;
        this.ticketTypeId = null;
        this._prices = [];
    }

    get prices() {
        let p = [];
        
        if (this.ticketType) {
            p = p.concat(this.ticketType);
        }

        p = p.concat(this._prices);
        return p;
    }

    get totalPrice() {
        const total = this.prices.reduce((tot,c) => tot += c.price, 0.0);
        return total;
    }

    get ticketType() {
        return this.event.ticketTypes.find(e => e.id === this.ticketTypeId);
    }

    addAdditionalPrice(name, price) {
        this._prices.push({name: name, price: price});
        return this;
    }
}

export default CartItem;
