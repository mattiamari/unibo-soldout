'use strict';

import htmlToElement from "../utils/htmlToElement.js";

const CardSmall = show => {
    return htmlToElement(/*html*/`
        <a class="card card--small" style="background-image: url(${show.coverImage.url});" href="#/show/${show.id}">
            <div class="card-content">
                <div class="showSummary">
                    <div class="showSummary-title">${show.title}</div>
                    <div>
                        <span class="showSummary-date">${show.date}</span>
                        <span class="showSummary-location">${show.locationShort}</span>
                    </div>
                </div>
            </div>
        </a>
    `);
};

export { CardSmall };