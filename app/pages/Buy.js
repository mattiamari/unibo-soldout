'use strict';

import NavBar from "../components/NavBar.js";



const BuyPage = {
    render: async params => {
        const navbar = await NavBar.render();

        return /*html*/`

        `;
    },

    afterRender: () => {
        NavBar.afterRender();
    },
};

export default BuyPage;
