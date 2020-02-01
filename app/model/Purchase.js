'use strict';

class Purchase {
    constructor(event) {
        this.event = event;
        this.ticketType = null;
        this._quantity = null;
    }

    get quantity() {
        return this._quantity;
    }

    quantityPlus() {
        if (this._quantity < this.event.maxTicketsPerPurchase) {
            this._quantity += 1;
        }
    }

    quantityMinus() {
        if (this._quantity > 1) {
            this._quantity -= 1;
        }
    }
}

export default Purchase;