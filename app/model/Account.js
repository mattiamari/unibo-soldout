'use strict';

const headers = new Headers();
headers.set('Content-Type', 'application/json');

const Account = {
    _user: null,

    init() {
        this._user = JSON.parse(localStorage.getItem('user'));
        this._login = JSON.parse(localStorage.getItem('login'));
    },

    get isLoggedIn() {
        return !!this._user;
    },

    get user() {
        return this._user;
    },

    get authHeaders() {
        const headers = new Headers();
        headers.set('Content-Type', 'application/json');
        headers.set('X-Auth', btoa(this._login.userId + ':' + this._login.key));
        return headers;
    },

    async login(credentials) {
        const req = new Request('/api/login', {
            method: 'POST',
            body: JSON.stringify(credentials),
            headers: headers
        });
        const res = await fetch(req);

        if (!res.ok) {
            return new Error('API returned ' + res.status);
        }

        this._login = (await res.json()).login;

        let account = await fetch('/api/account', {headers: this.authHeaders});
        account = await account.json();

        this._user = account.account;
        this.updateLocalStorage();
    },

    async logout() {
        const req = new Request('/api/logout', {
            method: 'GET',
            headers: this.authHeaders
        });

        const res = await fetch(req);

        this._user = null;
        this._login = null;
        this.updateLocalStorage();
        window.location = '/app/';
    },

    async signup(signupData) {
        const req = new Request('/api/signup', {
            method: 'POST',
            body: JSON.stringify(signupData),
            headers: headers
        });
        const res = await fetch(req);

        if (!res.ok) {
            return new Error('API returned ' + res.status);
        }

        this._login = (await res.json()).login;

        let account = await fetch('/api/account', {headers: this.authHeaders});
        account = await account.json();
        
        this._user = account.account;
        this.updateLocalStorage();
    },

    async updateUserInfo(userInfo) {
        // TODO send new user info to API

        return Promise.resolve();
    },

    async getOrders() {
        let res = await fetch('/api/orders', {headers: this.authHeaders});
        res = await res.json();
        return res.orders;
    },

    async getNotifications() {
        let res = await fetch('/api/notifications', {headers: this.authHeaders});
        res = await res.json();
        return res.notifications;
    },

    updateLocalStorage() {
        localStorage.setItem('user', JSON.stringify(this._user));
        localStorage.setItem('login', JSON.stringify(this._login));
    },
};

export default Account;
