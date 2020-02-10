'use strict';

import htmlToElement from "../utils/htmlToElement.js";

const bindings = new WeakMap();

const Slide = content => {
    const element = document.createElement('section');
    element.classList.add('tabbedContainer-slide');

    if (content instanceof Array) {
        element.append(...content);
    } else {
        element.append(content);
    }

    return element;
};

class TabButton {
    constructor(name, slide, container) {
        this.element = null;
        this.name = name;
        this.slide = slide;
        this.container = container;
    }

    render() {
        this.element = document.createElement('button');
        this.element.classList.add('button', 'button--tab');
        this.element.innerHTML = this.name;
        this.element.onclick = () => {
            this.select();
        };

        bindings.set(this.element, this);
        return this.element;
    }

    select() {
        this.element.parentElement.querySelectorAll('.button--tab').forEach(e => {
            bindings.get(e).deselect();
        });

        this.element.classList.add('button--active');
        this.container.scrollTo(this.slide.offsetLeft, 0);
    }

    deselect() {
        this.element.classList.remove('button--active');
    }
}

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
            const slide = Slide(tab.content);
            nav.append((new TabButton(tab.name, slide, slides)).render());
            slides.append(slide);
        }

        return this.element;
    }
}

export default TabbedContainer;
