'use strict';

import htmlToElement from '../utils/htmlToElement.js';

class Profile {
    constructor() {
        this.page = null;
    }

    async render() {
        const template = /*html*/`
            <div class="page page--profile">
                <header>
                    <h1>Il tuo profilo</h1>

                    <div class="profileCard">
                        <div class="profilePic">
                            <img src="../app/res/default-profile.jpg" alt="Profile image">
                        </div>
                        <div>
                            <span class="profileCard-name">Pinco Pallino</span>
                            <div class="profileCard-moreInfo">
                                <span>Ordini effettuati: </span><span class="profileCard-ordersCount">7</span><br>
                                <span>soldOUT coins: </span><span class="profileCard-coinsCount">383</span><br>
                            </div>
                        </div>
                    </div>
                </header>

                <main>
                    <div class="tabbedContainer">
                        <nav>
                            <button class="button button--tab">I tuoi dati</button>
                            <button class="button button--tab">Ordini</button>
                        </nav>
                        <div class="tabbedContainer-slides">
                            <article class="tabbedContainer-slide" active>
                                
                                <ul class="orderList">
                                    <li class="orderList-row">
                                        <div>
                                            <div>
                                                <span class="order-contentSummary">Linkin Park @ Firenze Rocks</span>
                                                <span class="order-theresMore"> e altri</span>
                                            </div>
                                            <div class="order-moreInfo">
                                                <span>Data ordine: </span><span class="order-date">01/01/2020</span><br>
                                                <span>Codice ordine: </span><span class="order-id">#1234</span>
                                            </div>
                                        </div>
                                        
                                        <i class="fa fa-arrow-right"></i>
                                    </li>
                                </ul>

                            </article>

                            <article class="tabbedContainer-slide">
                                mondo
                            </article>
                        </div>
                    </div>
                </main>
            </div>
        `;

        this.page = htmlToElement(template);
    }
}

export default Profile;
