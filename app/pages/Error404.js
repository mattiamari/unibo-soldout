'use strict';

class Error404 {
    constructor() {
    }

    async render() {
        return /* html */`
            <div class="page page--error404">
                <img src="res/404.png">
                <h2>404 Pagina non trovata</h2>
                <a href="#/" class="button button--flat">Torna alla pagina principale</a>
            </div>
        `;
    }

    afterRender() {}
};

export default Error404;
