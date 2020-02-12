'use strict';

import Shows from '../model/Shows.js';
import NavBar from '../components/NavBar.js';
import ShowSlider from '../components/ShowSlider.js';
import htmlToElement from '../utils/htmlToElement.js';
import Statusbar from '../utils/statusbar.js';
import TabbedContainer from '../components/TabbedContainer.js';
import { CardSmall } from '../components/ShowCard.js';

class HomePage {
    constructor() {
    }

    async render() {
        const template = /*html*/`
            <div class="page page--home">
            <header class="header">
                <div class="header-layer header-bg"></div>
                <div class="header-layer header-bg-filter"></div>
                <div class="header-layer header-bg-blend"></div>

                <div class="header-layer header-content">
                    <div class="app-logo">
                        <span class="logo-sold">sold</span><span class="logo-out">OUT</span>
                    </div>

                    <div class="searchbar">
                        <input type="search" placeholder="Trova un evento...">
                    </div>
                </div>
            </header>

            <main class="pageContent"></main>
            </div>
        `;

        this.element = htmlToElement(template);
        const header = this.element.querySelector('header');
        const content = this.element.querySelector('main');

        this.navbar = new NavBar();
        header.insertBefore(this.navbar.render(), header.firstChild);

        const sliderNewShows = new ShowSlider({
            title: 'Nuovi',
            shows: await Shows.getNewShows()
        });

        const sliderNearYouShows = new ShowSlider({
            title: 'Vicino a te',
            shows: await Shows.getNewShows()
        });

        const showsCategorized = (await Shows.getShowsCategorized()).map(category => {
            return {
                name: category.name,
                content: category.shows.map(e => CardSmall(e))
            };
        });

        this.showTabs = new TabbedContainer(showsCategorized);

        content.appendChild(sliderNewShows.render());
        content.appendChild(sliderNearYouShows.render());
        content.appendChild(this.showTabs.render());

        return this.element;
    }

    afterRender() {
        Statusbar.setColor('#d7487d');
    }

    destroy() {
        this.element = null;
        this.navbar.destroy();
        this.navbar = null;
        this.showTabs.destroy();
        this.showTabs = null;
    }
}

export default HomePage;
