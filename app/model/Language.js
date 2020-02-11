'use strict';

const Language = {
    lang: 'it-IT',
    currency: 'EUR',

    formatCurrency(num) {
        const formatter = new Intl.NumberFormat(this.lang, {style: 'currency', currency: this.currency});

        return formatter.format(num);
    },

    formatDate(date, options) {
        const formatter = new Intl.DateTimeFormat(this.lang, options);
        return formatter.format(Date.parse(date));
    },

    formatDateLong(date) {
        return this.formatDate(date, {weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'});
    },

    formatDateMedium(date) {
        return this.formatDate(date, {day: 'numeric', month: 'long', year: 'numeric'});
    },

    formatDateShort(date) {
        return this.formatDate(date, {day: 'numeric', month: '2-digit', year: 'numeric'});
    }

};

export default Language;
