'use strict';

import Language from "./Language.js";

class PriceDetail {
    constructor(name, price) {
        this.name = name;
        this._price = price;
    }

    get price() {
        return Language.formatCurrency(this._price);
    }
}

class CartItem {
    constructor(event) {
        this.event = event;
        this.prices = [];
    }

    get totalPrice() {
        const total = this.prices.reduce((tot,c) => tot += c._price, 0.0);
        return Language.formatCurrency(total);
    }

    addPriceDetail(name, price) {
        this.prices.push(new PriceDetail(name, price));
        return this;
    }
}

export default CartItem;
