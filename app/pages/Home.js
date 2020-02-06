'use strict';

import Shows from '../model/Shows.js';
import NavBar from '../components/NavBar.js';
import ShowSlider from '../components/ShowSlider.js';
import ShowTabs from '../components/ShowTabs.js';
import htmlToElement from '../utils/htmlToElement.js';
import Statusbar from '../utils/statusbar.js';

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

        const showTabs = await ShowTabs.render({
            id: 'showTabs',
            tabs: await Shows.getShowsCategorized()
        });

        const template = /*html*/`
            <div class="page page--home">
            ${header}

            <main class="pageContent">
                ${sliderNewShows}
                ${sliderCiaoShows}
                ${showTabs}
            </main>
            </div>
        `;

        return htmlToElement(template);
    }

    afterRender() {
        let dom = document.getElementById.bind(document);

        // FIXME Bindings like this result in detached HTMLElements every time page is changed
        NavBar.afterRender(dom('navbar'));
        ShowSlider.afterRender(dom('sliderNewShows'));
        ShowSlider.afterRender(dom('sliderCiaoShows'));
        ShowTabs.afterRender(dom('showTabs'));

        Statusbar.setColor('#d7487d');
    }
}

export default HomePage;
