'use strict';

import htmlToElement from "../utils/htmlToElement.js";
import Statusbar from "../utils/statusbar.js";

class Error404 {
    constructor() {
    }

    async render() {
        const template = /* html */`
            <div class="page page--error404">
                <img src="res/404.png">
                <h2>404 Pagina non trovata</h2>
                <a href="#/" class="button button--flat">Torna alla pagina principale</a>
            </div>
        `;

        return htmlToElement(template);
    }

    afterRender() {
        Statusbar.setColor('#d7487d');
    }
};

export default Error404;
