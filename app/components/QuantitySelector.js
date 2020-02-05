'use strict';

import htmlToElement from "../utils/htmlToElement.js";

class QuantitySelector {
    constructor(model) {
        this.model = model;
    }

    render() {
        const template = /*html*/`
            <div class="quantitySelector">
                <button class="button button--outline button--round quantityMinus" aria-label="Decrease quantity">-</button>
                <span aria-label="Current quantity" class="quantity">1</span>
                <button class="button button--outline button--round quantityPlus" aria-label="Increase quantity">+</button>
            </div>
        `;

        this.element = htmlToElement(template);
        const quantityDisplay = this.element.querySelector('.quantity');

        this.element.querySelector('.quantityMinus').onclick = () => this.model.quantityMinus();
        this.element.querySelector('.quantityPlus').onclick = () => this.model.quantityPlus();

        this.model.addOnChangeHandler(() => {
            quantityDisplay.innerHTML = this.model.quantity;
        });

        quantityDisplay.innerHTML = this.model.quantity;

        return this.element;
    }
}

export default QuantitySelector;
