'use strict';

import htmlToElement from "../utils/htmlToElement.js";

const Slide = content => {
    const element = document.createElement('section');
    element.classList.add('tabbedContainer-slide');
    element.append(content);
    return element;
};

const TabButton = name => {
    const element = document.createElement('button');
    element.classList.add('button', 'button--tab');
    element.innerHTML = name;
    return element;
};

class TabbedContainer {
    constructor(content) {
        this.content = content;
        this.element = null;
    }

    render() {
        const template = /*html*/`
            <div class="tabbedContainer">
                <nav></nav>
                <div class="tabbedContainer-slides"></div>
            </div>
        `;

        this.element = htmlToElement(template);

        const nav = this.element.querySelector('nav');
        const slides = this.element.querySelector('.tabbedContainer-slides');

        for (let tab of this.content) {
            nav.append(TabButton(tab.name));
        }

        nav.append(...this.content.map(e => TabButton(e.name)));
        slids.append(...this.content.map(e => Slide(e.content)));
        
        return this.element;
    }
}

export default TabbedContainer;
