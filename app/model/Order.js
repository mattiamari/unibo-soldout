'use strict';

class Order {
    constructor() {}

    get totalPrice() {
        return this.tickets.reduce((acc, e) => acc += e.price, 0.0);
    }
}

export default Order;
