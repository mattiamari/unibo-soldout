'use strict';

class CartItem {
    constructor(show) {
        this.show = show;
        this._ticketTypeId = null;
        this._quantity = 1;
        this._prices = [];
        this.changeHandlers = [];
    }

    get prices() {
        let p = [];
        
        if (this._ticketTypeId) {
            p.push({
                name: `${this.quantity}x ${this.ticketType.name}`,
                price: this.quantity * this.ticketType.price
            });
        }

        p = p.concat(this._prices);
        return p;
    }

    get totalPrice() {
        const total = this.prices.reduce((tot,c) => tot += c.price, 0.0);
        return total;
    }

    get ticketType() {
        return this.show.ticketTypes.find(e => e.id === this._ticketTypeId);
    }

    get quantity() {
        return this._quantity;
    }

    set quantity(quantity) {
        this._quantity = quantity;
        this.notifyChange();
    }

    get ticketTypeId() {
        return this._ticketTypeId;
    }

    set ticketTypeId(id) {
        this._ticketTypeId = id;
        this.notifyChange();
    }

    addAdditionalPrice(name, price) {
        this._prices.push({name: name, price: price});
        return this;
    }

    quantityPlus() {
        if (this.quantity < this.show.maxTicketsPerPurchase) {
            this.quantity += 1;
            this.notifyChange();
        }
    }

    quantityMinus() {
        if (this.quantity > 1) {
            this.quantity -= 1;
            this.notifyChange();
        }
    }

    addOnChangeHandler(handler) {
        this.changeHandlers.push(handler);
    }

    notifyChange() {
        this.changeHandlers.forEach(h => h());
    }
}

export default CartItem;
