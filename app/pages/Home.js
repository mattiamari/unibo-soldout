'use strict';

import { Shows } from '../model/Shows.js';
import NavBar from '../components/NavBar.js';
import SearchBar from '../components/SearchBar.js';
import ShowSlider from '../components/ShowSlider.js';
import htmlToElement from '../utils/htmlToElement.js';
import Statusbar from '../utils/statusbar.js';
import TabbedContainer from '../components/TabbedContainer.js';
import { CardSmall } from '../components/ShowCard.js';
import AppLogo from '../components/AppLogo.js';

class HomePage {
    constructor() {
    }

    async render() {
        const logo = AppLogo();

        const template = /*html*/`
            <div class="page page--home">
            <header class="header">
                <div class="header-layer header-bg"></div>
                <div class="header-layer header-bg-filter"></div>
                <div class="header-layer header-bg-blend"></div>

                <div class="header-layer header-content">
                    ${logo}
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

        const searchbar = new SearchBar();
        header.querySelector('.header-content').appendChild(searchbar.render());

        const sliderNewShows = new ShowSlider({
            title: 'Nuovi',
            shows: await Shows.getNewShows()
        });

        const sliderNearYouShows = new ShowSlider({
            title: 'Popolari',
            shows: await Shows.getHotShows()
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
