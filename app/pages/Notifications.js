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

    return /*html*/ `
        <li class="list-item">
            ${link}
            <span class="text--small">${Language.formatDateTime(notification.date)}</span>
        </li>
    `;
};

class NotificationsPage {
    constructor() {

    }

    async render() {
        let notifications = await Account.getNotifications();
        notifications = notifications.map(e => {
            e.date = new Date(e.date);
            return e;
        });
        notifications.sort((a,b) => b.date.getTime() - a.date.getTime());

        const listRows = notifications.map(e => ListRow(e)).join('\n');

        const template = /*html*/ `
            <div class="page page--notifications">
                <header class="header">
                    <div class="header-content">
                        <h1>Notifiche</h1>
                    </div>
                </header>
                <main>
                    <ul class="list list--flat">
                        ${listRows}
                    </ul>
                </main>
            </div>
        `;

        this.element = htmlToElement(template);
        const header = this.element.querySelector('header');

        this.navbar = new NavBar();
        header.insertBefore(this.navbar.render(), header.firstChild);

        return this.element;
    }

    destroy() {
        this.navbar.destroy();
        this.element = null;
    }
}

export default NotificationsPage;
