'use strict';

const Error404 = {
    render: async () => {
        return /* html */`
            <div class="page page--error404">
                <img src="res/404.png">
                <h2>404 Pagina non trovata</h2>
                <a href="#/" class="button button--flat">Torna alla pagina principale</a>
            </div>
        `;
    }
};

export default Error404;
