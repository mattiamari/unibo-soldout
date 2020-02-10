'use strict';

import Shows from '../model/Shows.js';
import NavBar from '../components/NavBar.js';
import ShowSlider from '../components/ShowSlider.js';
import htmlToElement from '../utils/htmlToElement.js';
import Statusbar from '../utils/statusbar.js';
import TabbedContainer from '../components/TabbedContainer.js';
import { CardSmall } from '../components/ShowCard.js';

const Header = async () => {
    const navbar = await NavBar.render();

    return /*html*/`
        <header class="header">
            <div class="header-layer header-bg"></div>
            <div class="header-layer header-bg-filter"></div>
            <div class="header-layer header-bg-blend"></div>

            ${navbar}

            <div class="header-layer header-content">
                <div class="app-logo">
                    <span class="logo-sold">sold</span><span class="logo-out">OUT</span>
                </div>

                <div class="searchbar">
                    <input type="search" placeholder="Trova un evento...">
                </div>
            </div>
        </header>
    `;
};

class HomePage {
    constructor(params) {
        this.page = null;
    }

    async render() {
        const header = await Header();

        const sliderNewShows = await ShowSlider.render({
            id: 'sliderNewShows',
            title: 'Nuovi',
            shows: await Shows.getNewShows()
        });

        const sliderCiaoShows = await ShowSlider.render({
            id: 'sliderCiaoShows',
            title: 'Ciao',
            shows: await Shows.getNewShows()
        });

        const showsCategorized = (await Shows.getShowsCategorized()).map(category => {
            return {
                name: category.name,
                content: category.shows.map(e => CardSmall(e))
            };
        });

        const showTabs = new TabbedContainer(showsCategorized);

        const template = /*html*/`
            <div class="page page--home">
            ${header}

            <main class="pageContent">
                ${sliderNewShows}
                ${sliderCiaoShows}
            </main>
            </div>
        `;

        this.page = htmlToElement(template);
        const content = this.page.querySelector('main');

        content.append(showTabs.render());

        return this.page;
    }

    afterRender() {
        let dom = document.getElementById.bind(document);

        // FIXME Bindings like this result in detached HTMLElements every time page is changed
        NavBar.afterRender(dom('navbar'));
        ShowSlider.afterRender(dom('sliderNewShows'));
        ShowSlider.afterRender(dom('sliderCiaoShows'));

        Statusbar.setColor('#d7487d');
    }
}

export default HomePage;
