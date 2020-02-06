'use strict';

import Events from '../model/Events.js';
import NavBar from '../components/NavBar.js';
import EventSlider from '../components/EventSlider.js';
import EventTabs from '../components/EventTabs.js';
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

        const sliderNewEvents = await EventSlider.render({
            id: 'sliderNewEvents',
            title: 'Nuovi',
            events: await Events.getNewEvents()
        });

        const sliderCiaoEvents = await EventSlider.render({
            id: 'sliderCiaoEvents',
            title: 'Ciao',
            events: await Events.getNewEvents()
        });

        const eventTabs = await EventTabs.render({
            id: 'eventTabs',
            tabs: await Events.getEventsCategorized()
        });

        const template = /*html*/`
            <div class="page page--home">
            ${header}

            <main class="pageContent">
                ${sliderNewEvents}
                ${sliderCiaoEvents}
                ${eventTabs}
            </main>
            </div>
        `;

        return htmlToElement(template);
    }

    afterRender() {
        let dom = document.getElementById.bind(document);

        // FIXME Bindings like this result in detached HTMLElements every time page is changed
        NavBar.afterRender(dom('navbar'));
        EventSlider.afterRender(dom('sliderNewEvents'));
        EventSlider.afterRender(dom('sliderCiaoEvents'));
        EventTabs.afterRender(dom('eventTabs'));

        Statusbar.setColor('#d7487d');
    }
}

export default HomePage;
