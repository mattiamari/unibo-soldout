'use strict';

const dummyUser = {
    firstname: 'Pinco',
    lastname: 'Pallino',
    email: 'pinco@pallino.com',
    birthdate: '1980-03-20',
    ordersCount: 3,
    coinsBalance: 340,
};

const dummyOrders = [
    {
        id: 'abcd1234',
        reference: '1234',
        date: '2020-01-10',
        contentSummary: 'Linkin Park @ Firenze Rocks',
        hasMoreTickets: true
    },
    {
        id: 'afgh2314',
        reference: '5719',
        date: '2020-01-02',
        contentSummary: 'System Of A Down',
        hasMoreTickets: false
    },
    {
        id: 'ijklm1234',
        reference: '2876',
        date: '2020-01-12',
        contentSummary: 'NF',
        hasMoreTickets: false
    }
];

const Account = {
    _user: null,

    init() {
        this._user = JSON.parse(localStorage.getItem('user'));
    },

    get isLoggedIn() {
        return !!this._user;
    },

    get user() {
        return this._user;
    },

    async login(loginData) {
        // TODO login to API
        this._user = dummyUser;
        this.updateLocalStorage();

        return Promise.resolve();
    },

    async signup(signupData) {
        // TODO signup with API
        this._user = dummyUser;
        this.updateLocalStorage();

        return Promise.resolve();
    },

    async getOrders() {
        return dummyOrders;
    },

    updateLocalStorage() {
        localStorage.setItem('user', JSON.stringify(this._user));
    },
};

export default Account;
