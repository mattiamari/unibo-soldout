'use strict';

const headers = new Headers();
headers.set('Content-Type', 'application/json');

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

    async login(credentials) {
        const req = new Request('/api/login', {
            method: 'POST',
            body: JSON.stringify(credentials),
            headers: headers
        });
        const res = await fetch(req);

        if (!res.ok) {
            return;
        }

        let account = await fetch('/api/account');
        account = await account.json();

        this._user = account.account;
        this.updateLocalStorage();
    },

    async signup(signupData) {
        const req = new Request('/api/signup', {
            method: 'POST',
            body: JSON.stringify(signupData),
            headers: headers
        });
        const res = await fetch(req);

        if (!res.ok) {
            return;
        }

        let account = await fetch('/api/account');
        account = await account.json();
        
        this._user = account.account;
        this.updateLocalStorage();
    },

    async updateUserInfo(userInfo) {
        // TODO send new user info to API

        return Promise.resolve();
    },

    async getOrders() {
        let res = await fetch('/api/orders');
        res = await res.json();
        return res.orders;
    },

    async getNotifications() {
        let res = await fetch('/api/notifications');
        res = await res.json();
        return res.notifications;
    },

    updateLocalStorage() {
        localStorage.setItem('user', JSON.stringify(this._user));
    },
};

export default Account;
