'use strict';

import NavBar from '../components/NavBar.js';
import EventSlider from '../components/EventSlider.js';
import Events from '../services/Events.js';

const Header = async () => {
    const navbar = await NavBar.render();

    return /* html */`
        <header class="pageHeader">
            <div class="pageHeader-background"></div>
            <div class="pageHeader-background-filter"></div>
            <div class="pageHeader-background-blend"></div>

            ${navbar}

            <div class="pageHeader-content">
                <div class="app-logo">
                    <span class="logo-sold">sold</span><span class="logo-out">OUT</span>
                </div>

                <div class="searchbar">
                    <div class="searchbar-inner">
                        <input type="text" placeholder="Trova un evento...">
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


        return /* html */`
            <div class="page page--home">
            ${header}

            <main class="pageContent">
                ${sliderNewEvents}
            </main>
            </div>
        `;
    },

    afterRender: async () => {
        const dom = {
            'navbar': document.querySelector('.navbar'),
        };

        initNavbar(dom.navbar, 56);
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
