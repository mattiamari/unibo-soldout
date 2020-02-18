'use strict';

import { Shows } from '../model/Shows.js';

import NavBar from '../components/NavBar.js';
import htmlToElement from '../utils/htmlToElement.js';
import Statusbar from '../utils/statusbar.js';

class ArtistPage {
    constructor(params) {
        this.artistId = params.id;
    }

    async render() {
        const artist = await Shows.getArtistDetails(this.artistId);

        const template = /*html*/`
            <div class="page page--artist">
                <header class="header">
                    <div class="header-layer header-bg" style="background-image: url(${artist.imageUrl})"></div>
                    <div class="header-layer header-bg-blend"></div>

                    <div class="header-layer header-content">
                        <h1 class="header-title">${artist.name}</h1>
                    </div>
                </header>

                <main>
                    <article>
                        <p class="showDescription">${artist.description}</p>
                    </article>
                </main>
            </div>`;

        const page = htmlToElement(template);
        const header = page.querySelector('header');
        header.insertBefore((new NavBar()).render(), header.firstChild);

        return page;
    }

    afterRender() {
        Statusbar.setColor('#323232');
    }
};

export default ArtistPage;
