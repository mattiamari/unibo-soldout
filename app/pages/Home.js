'use strict';

import Events from '../services/Events.js';
import NavBar from '../components/NavBar.js';
import EventSlider from '../components/EventSlider.js';
import EventTabs from '../components/EventTabs.js';

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
                    <div class="searchbar-inner">
                        <input type="search" placeholder="Trova un evento...">
                    </div>
                </div>
            </div>
        </header>
    `;
};

const HomePage = {
    render: async params => {
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

        return /*html*/`
            <div class="page page--home">
            ${header}

            <main class="pageContent">
                ${sliderNewEvents}
                ${sliderCiaoEvents}
                ${eventTabs}
            </main>
            </div>
        `;
    },

    afterRender: async () => {
        const dom = {
            navbar: document.getElementById('navbar'),
            sliderNewEvents: document.getElementById('sliderNewEvents'),
            sliderCiaoEvents: document.getElementById('sliderCiaoEvents'),
            eventTabs: document.getElementById('eventTabs'),
        };

        NavBar.afterRender(dom.navbar);
        EventTabs.afterRender(dom.eventTabs);
    }
};

export default HomePage;
