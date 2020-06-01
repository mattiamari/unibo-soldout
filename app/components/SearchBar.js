'use strict';

import htmlToElement from '../utils/htmlToElement.js';
import { Shows } from '../model/Shows.js';
import Language from '../model/Language.js';

const ResultEntry = show => {
    return /*html*/`
        <li>
            <span>${show.title}</span>
            <span>${Language.formatDateMedium(show.date)}</span>
            <span>${show.location}</span>
        </li>
    `;
};

class SearchBar {
    render() {
        const template = /*html*/`
            <div class="searchbar-wrap">
                <div class="searchbar">
                    <input type="search" placeholder="Trova un evento...">
                </div>
                <ul class="searchbar-results searchbar-results--hidden">
                </ul>
            </div>
        `;

        this.element = htmlToElement(template);
        const input = this.element.querySelector('input');
        this.results = this.element.querySelector('.searchbar-results');

        input.addEventListener('input', () => this.search(input.value));

        return this.element;
    }

    async search(query) {
        if (query.length == 0) {
            this.hideResults();
            return;
        }
        if (query.length < 2) {
            return;
        }

        const res = await Shows.search(query);
        this.showResults(res);
    }

    showResults(shows) {
        if (!shows.length) {
            this.results.innerHTML = /*html*/`<span>Nessun risultato :(</span>`;
            return;
        }

        this.results.innerHTML = shows.map(s => ResultEntry(s)).join("\n");
        this.results.classList.remove('searchbar-results--hidden');
    }

    hideResults() {
        this.results.classList.add('searchbar-results--hidden');
    }
}

export default SearchBar;