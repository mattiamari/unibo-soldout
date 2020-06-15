'use strict';

import htmlToElement from "../utils/htmlToElement.js";
import Language from "../model/Language.js";

const CardSmall = show => {
    return htmlToElement(/*html*/`
        <a class="card card--small" style="background-image: url(${show.image.url});" href="#/show/${show.id}">
            <div class="card-content">
                <div class="showSummary">
                    <div class="showSummary-title">${show.title}</div>
                    <div class="showSummary-info">
                        <i class="showSummary-icon fa fa-calendar-alt"></i>
                        <span class="showSummary-date">${Language.formatDateMedium(show.date)}</span>

                        <i class="showSummary-icon fa fa-map-marker-alt"></i>
                        <span class="showSummary-location">${show.location}</span>
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
                <img alt="${show.image.alt}" src="${show.image.url}">

                <div class="card-content">
                    <div class="showSummary">
                        <div class="showSummary-title">${show.title}</div>
                        <div class="showSummary-info">
                            <i class="showSummary-icon fa fa-calendar-alt"></i>
                            <span class="showSummary-date">${Language.formatDateMedium(show.date)}</span>

                            <i class="showSummary-icon fa fa-map-marker-alt"></i>
                            <span class="showSummary-location">${show.location}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    `);
};

export { CardSmall, CardBig };
