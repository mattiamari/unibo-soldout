'use strict';

import Order from "./Order.js";

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
        const req = new Request('/api/account', {
            method: 'POST',
            body: JSON.stringify(userInfo),
            headers: this.authHeaders
        });
        const res = await fetch(req);

        if (!res.ok) {
            return new Error('API returned ' + res.status);
        }

        let account = await fetch('/api/account', {headers: this.authHeaders});
        account = await account.json();

        this._user = account.account;
        this.updateLocalStorage();
    },

    async getOrders() {
        let res = await fetch('/api/orders', {headers: this.authHeaders});
        res = await res.json();
        return res.orders;
    },

    async getOrderDetails(orderId) {
        let res = await fetch('/api/order/' + orderId, {headers: this.authHeaders});
        res = await res.json();
        return Object.assign(new Order(), res.order);
    },

    async getNotifications() {
        let res = await fetch('/api/notifications', {headers: this.authHeaders});
        res = await res.json();
        return res.notifications;
    },

    async getUnreadNotificationsCount() {
        let res = await fetch('/api/notifications/unread', {headers: this.authHeaders});
        res = await res.json();
        return res.unread_notifications_count;
    },

    async markReadNotifications() {
        const req = new Request('/api/notifications/read', {
            method: 'POST',
            body: JSON.stringify({}),
            headers: this.authHeaders
        });
        const res = await fetch(req);

        if (!res.ok) {
            return new Error('API returned ' + res.status);
        }
    },

    updateLocalStorage() {
        localStorage.setItem('user', JSON.stringify(this._user));
        localStorage.setItem('login', JSON.stringify(this._login));
    },
};

export default Account;
