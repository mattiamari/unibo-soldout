'use strict';

import htmlToElement from "../utils/htmlToElement.js";
import Language from "../model/Language.js";

const CardSmall = show => {
    return htmlToElement(/*html*/`
        <a class="card card--small" style="background-image: url(${show.coverImage.url});" href="#/show/${show.id}">
            <div class="card-content">
                <div class="showSummary">
                    <div class="showSummary-title">${show.title}</div>
                    <div>
                        <span class="showSummary-date">${Language.formatDateMedium(show.date)}</span>
                        <span class="showSummary-location">${show.locationShort}</span>
                    </div>
                </div>
            </div>
        </a>
    `);
};

const CardBig = show => {
    return htmlToElement(/*html*/`
        <div class="slider-slide">
            <a class="card card--big" href="#/show/${show.id}">
                <img alt="${show.imageAlt}" src="${show.imageUrl}">

                <div class="card-content">
                    <div class="showSummary">
                        <div class="showSummary-title">${show.title}</div>
                        <div>
                            <span class="showSummary-date">${Language.formatDateMedium(show.date)}</span>
                            <span class="showSummary-location">${show.location}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    `);
};

export { CardSmall, CardBig };
