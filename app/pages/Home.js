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
    render: async (params) => {
        const header = await Header();

        const sliderNewEvents = await EventSlider.render({
            title: 'Nuovi',
            events: await Events.getNewEvents()
        });

        const sliderCiaoEvents = await EventSlider.render({
            title: 'Ciao',
            events: await Events.getNewEvents()
        });

        const eventTabs = await EventTabs.render(await Events.getEventsCategorized());

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
            'navbar': document.querySelector('.navbar'),
        };

        initNavbar(dom.navbar, 56);
        EventTabs.afterRender();
    }
};

function initNavbar(node, raiseAt) {
    window.addEventListener('scroll', () => {
        if (document.documentElement.scrollTop < raiseAt) {
            window.requestAnimationFrame(() => NavBar.onScrollTop(node));
            return;
        }

        window.requestAnimationFrame(() => NavBar.onScrollMiddle(node));
    });
}

export default HomePage;
