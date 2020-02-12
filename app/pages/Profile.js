'use strict';

import htmlToElement from '../utils/htmlToElement.js';
import Account from '../model/Account.js';
import Language from '../model/Language.js';
import TabbedContainer from '../components/TabbedContainer.js';
import NavBar from '../components/NavBar.js';
import formToObject from '../utils/formToObject.js';

const ProfileCard = profile => {
    return /*html*/ `
        <div class="profileCard">
            <div class="profilePic">
                <img src="../app/res/default-profile.jpg" alt="Profile image">
            </div>
            <div>
                <span class="profileCard-name">${profile.firstname} ${profile.lastname}</span>
                <div class="profileCard-moreInfo">
                    <span>Ordini effettuati: </span><span class="profileCard-ordersCount">${profile.ordersCount}</span><br>
                    <span>soldOUT coins: </span><span class="profileCard-coinsCount">${profile.coinsBalance}</span><br>
                </div>
            </div>
        </div>
    `;
};

const UserPersonalInfo = profile => {
    return htmlToElement(/*html*/ `
        <form class="form" action="#">
            <label for="firstname">Nome</label>
            <input type="text" id="firstname" name="firstname" required value="${profile.firstname}">

            <label for="lastname">Cognome</label>
            <input type="text" id="lastname" name="lastname" required value="${profile.lastname}">

            <label for="birthdate">Data di nascita</label>
            <input type="date" id="birthdate" name="birthdate" required value="${profile.birthdate}">
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="${profile.email}">

            <label for="password">Password</label>
            <input type="password" id="password" name="password">

            <label for="passwordRepeat">Ripeti password</label>
            <input type="password" id="passwordRepeat" name="passwordRepeat">

            <button type="submit" class="button button--raised">Salva</button>
        </form>
    `);
};

const OrderRow = order => {
    return /*html*/ `
        <li>
            <a class="orderList-row" href="#/order/${order.id}">
                <div>
                    <div>
                        <span class="order-contentSummary">${order.contentSummary}</span>
                        ${order.hasMoreTickets ? '<span class="order-theresMore"> e altri</span>' : ''}
                    </div>
                    <div class="order-moreInfo">
                        <span class="order-date">${Language.formatDateShort(order.date)}</span>
                        <span class="order-id">#${order.reference}</span>
                    </div>
                </div>
                
                <i class="fa fa-arrow-right"></i>
            </a>
        </li>
    `;
};

const OrderList = orders => {
    const element = document.createElement('ul');
    element.classList.add('orderList');
    element.innerHTML = orders.map(e => OrderRow(e)).join('\n');
    return element;
};

class ProfilePage {
    constructor() {
        this.page = null;
    }

    async render() {
        const profileCard = ProfileCard(Account.user);

        const template = /*html*/`
            <div class="page page--profile">
                <header class="header">
                    <div class="header-content">
                        <h1>Il tuo profilo</h1>
                        ${profileCard}
                    </div>
                </header>

                <main></main>
            <div>
        `;

        // Header
        this.page = htmlToElement(template);
        const header = this.page.querySelector('header');
        header.insertBefore((new NavBar()).render(), header.firstChild);
        
        // Tabs
        const userInfo = UserPersonalInfo(Account.user);
        const orderList = OrderList(await Account.getOrders());

        const tabs = [
            {name: 'I tuoi dati', content: userInfo},
            {name: 'Ordini', content: orderList},
        ];

        const tabContainer = new TabbedContainer(tabs);
        this.page.querySelector('main').append(tabContainer.render());

        userInfo.addEventListener('submit', e => {
            e.preventDefault();
            Account.updateUserInfo(formToObject(userInfo));
        });

        return this.page;
    }

    afterRender() {

    }
}

export default ProfilePage;
