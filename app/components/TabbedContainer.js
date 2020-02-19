'use strict';

import htmlToElement from '../utils/htmlToElement.js';

class Slide {
    constructor(name, content, container) {
        this.name = name;
        this.content = content;
        this.container = container;
    }

    render() {
        this.element = document.createElement('section');
        this.element.classList.add('tabbedContainer-slide');

        if (this.content instanceof Array) {
            this.element.append(...this.content);
        } else {
            this.element.append(this.content);
        }

        this.container.element.addEventListener('tabChanged', () => {
            if (this.container.activeTab == this.name) {
                this.element.parentElement.scrollTo(this.element.offsetLeft - this.element.parentElement.offsetLeft, 0);
            }
        });
    
        return this.element;
    }
}

class TabButton {
    constructor(name, container) {
        this.name = name;
        this.container = container;
    }

    render() {
        this.element = document.createElement('button');
        this.element.classList.add('button', 'button--tab');
        this.element.innerHTML = this.name;
        this.element.onclick = () => {
            this.container.selectTab(this.name);
        };

        this.container.element.addEventListener('tabScrolled', () => this.onTabChanged());

        return this.element;
    }

    onTabChanged() {
        if (this.container.activeTab != this.name) {
            this.element.classList.remove('button--active');
            return;
        }

        this.element.classList.add('button--active');

        // Scroll to the selected button if it's not visible
        if (this.element.offsetLeft + this.element.clientWidth > this.element.parentElement.scrollLeft + this.element.parentElement.clientWidth
            || this.element.offsetLeft < this.element.parentElement.scrollLeft
        ) {
            this.element.parentElement.scrollTo(this.element.offsetLeft, 0);
        }
    }
}

class TabbedContainer {
    constructor(content) {
        this.content = content;
        this.activeTab = null;
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
        const slideObjs = new WeakMap();

        const options = {
            root: slides,
            rootMargin: '0px',
            threshold: 0.6
        };
        const slideObserver = new IntersectionObserver(e => {
            const currentSlide = slideObjs.get(e[0].target);

            if (currentSlide.name == this.activeTab) {
                return;
            }

            this.activeTab = currentSlide.name;
            this.element.dispatchEvent(new Event('tabScrolled'));
        }, options);

        for (let tab of this.content) {
            const button = new TabButton(tab.name, this);
            const slide = new Slide(tab.name, tab.content, this);
            const slideElement = slide.render();
            slideObjs.set(slideElement, slide);

            nav.append(button.render());

            slideObserver.observe(slideElement);
            slides.append(slideElement);
        }

        // Select first tab on load
        if (this.content.length) {
            this.selectTab(this.content[0].name);
            this.element.dispatchEvent(new Event('tabScrolled'));
        }
        
        return this.element;
    }

    selectTab(name) {
        this.activeTab = name;
        this.element.dispatchEvent(new Event('tabChanged'));
    }

    destroy() {
        this.element.remove()
        this.element = null;
    }
}

export default TabbedContainer;
