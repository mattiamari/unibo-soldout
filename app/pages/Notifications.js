'use strict';

import Account from "../model/Account.js";
import htmlToElement from "../utils/htmlToElement.js";
import NavBar from "../components/NavBar.js";
import Language from "../model/Language.js";

const ListRow = notification => {
    return /*html*/ `
        <li class="list-item">
            <p>${notification.content}</p>
            <span class="text--small">${Language.formatDateShort(notification.date)}</span>
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
                <header>
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
        header.insertBefore((new NavBar).render(), header.firstChild);

        return this.element;
    }

    destroy() {
        this.element = null;
    }
}

export default NotificationsPage;
