'use strict';

const Language = {
    lang: 'it-IT',
    currency: 'EUR',

    formatCurrency(num) {
        const formatter = new Intl.NumberFormat(this.lang, {style: 'currency', currency: this.currency});

        return formatter.format(num);
    },
};

export default Language;
