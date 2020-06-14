'use strict';

import htmlToElement from '../utils/htmlToElement.js';
import { Shows } from '../model/Shows.js';
import Language from '../model/Language.js';

const ResultEntry = show => {
    return /*html*/`
        <li class="searchbar-entry showSummary">
            <div class="showSummary-title"><a href="#/show/${show.id}">${show.title}</a></div>
            <div class="showSummary-info">
                <i class="showSummary-icon fa fa-calendar-alt"></i>
                <span class="showSummary-date">${Language.formatDateMedium(show.date)}</span>

                <i class="showSummary-icon fa fa-map-marker-alt"></i>
                <span class="showSummary-location">${show.location}</span>
            </div>
        </li>
    `;
};

class SearchBar {
    render() {
        // searchbar-results-wrap has the additional "hidden" class to prevent
        // the box from flashing on page load
        const template = /*html*/`
            <div class="searchbar-wrap">
                <div class="searchbar">
                    <input type="search" placeholder="Trova un evento..." aria-label="Trova un evento">
                </div>
                <div class="searchbar-results-wrap searchbar-results--hidden hidden">
                    <ul class="searchbar-results">
                    </ul>
                </div>
            </div>
        `;

        this.element = htmlToElement(template);
        const input = this.element.querySelector('input');
        this.resultsWrap = this.element.querySelector('.searchbar-results-wrap');
        this.results = this.resultsWrap.querySelector('.searchbar-results');

        input.addEventListener('input', () => this.search(input.value));
        input.addEventListener('focusin', () => this.search(input.value));
        input.addEventListener('focusout', () => this.hideResults());

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
        this.resultsWrap.classList.remove('searchbar-results--hidden', 'hidden', 'noPointerEvents');
        
        if (!shows.length) {
            this.results.innerHTML = /*html*/`<span>Nessun risultato :(</span>`;
            return;
        }

        this.results.innerHTML = shows.map(s => ResultEntry(s)).join("\n");
    }

    hideResults() {
        this.resultsWrap.classList.add('searchbar-results--hidden');
        
        // timeout should be greater than .searchbar-results--hidden animation
        setTimeout(() => {
            this.resultsWrap.classList.add('noPointerEvents');
        }, 100);
    }
}

export default SearchBar;