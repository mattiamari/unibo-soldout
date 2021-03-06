'use strict';

import Account from "../model/Account.js";
import htmlToElement from "../utils/htmlToElement.js";
import NavBar from "../components/NavBar.js";
import Language from "../model/Language.js";

const ListRow = notification => {
    let link = /*html*/`<p>${notification.content}</p>`;
    if (notification.action) {
        link = /*html*/`<a href="#${notification.action}">${notification.content}</a>`;
    }

    let bullet = /*html*/`<span class="notification-bullet"></span>`;

    return /*html*/ `
        <li class="list-item">
            ${link}
            <div class="text--small">
                <span>${Language.formatDateTime(notification.date)}</span>
                ${notification.read ? "" : bullet}
            </div>
        </li>
    `;
};

class NotificationsPage {
    constructor() {

    }

    async render() {
        const template = /*html*/ `
            <div class="page page--notifications">
                <header class="header">
                    <div class="header-content">
                        <h1>Notifiche</h1>
                    </div>
                </header>
                <main>
                    <ul class="list list--flat">
                    </ul>
                </main>
            </div>
        `;

        this.element = htmlToElement(template);
        const header = this.element.querySelector('header');

        this.list = this.element.querySelector('ul');

        this.navbar = new NavBar();
        header.insertBefore(this.navbar.render(), header.firstChild);

        await this.updateList();
        Account.markReadNotifications();

        this.updateTimer = setInterval(() => this.updateList(), 5000);

        return this.element;
    }

    async updateList() {
        let notifications = await Account.getNotifications();
        notifications = notifications.map(e => {
            e.date = new Date(e.date);
            return e;
        });
        notifications.sort((a,b) => b.date.getTime() - a.date.getTime());

        const listRows = notifications.map(e => ListRow(e)).join('\n');
        this.list.innerHTML = listRows;
    }

    destroy() {
        this.navbar.destroy();
        clearInterval(this.updateTimer);
        this.element = null;
    }
}

export default NotificationsPage;
