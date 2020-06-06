'use strict';

import { CardBig } from '../components/ShowCard.js';
import htmlToElement from '../utils/htmlToElement.js';

class ShowSlider {
    constructor(content) {
        this.content = content;
    }

    render() {
        const template = /*html*/`
            <div class="slider">
                <div class="slider-header">
                    <span class="slider-title">${this.content.title}</span>
                </div>
                <div class="slider-content">
                </div>
            </div>
        `;

        const element = htmlToElement(template);
        const container = element.querySelector('.slider-content');
        const shows = this.content.shows.map(e => CardBig(e));

        for (let show of shows) {
            container.appendChild(show);
        }

        return element;
    }

};

export default ShowSlider;
