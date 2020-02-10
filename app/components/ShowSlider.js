'use strict';

const Card = show => {
    return /*html*/`
        <div class="slider-slide">
            <a class="card card--big" href="#/show/${show.id}">
                <img alt="${show.coverImage.alt}" src="${show.coverImage.url}">

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
        </div>
    `;
};

const ShowSlider = {
    render: async params => {
        const shows = params.shows.map(e => Card(e)).join('\n');

        return /*html*/`
            <div class="slider" id="${params.id}">
                <div class="slider-header">
                    <span class="slider-title">${params.title}</span>
                    <a class="slider-showMore button button--flat" href="#">Vedi tutti</a>
                </div>
                <div class="slider-content">
                    ${shows}
                </div>
            </div>
        `;
    },

    afterRender: node => {
    },
};

export default ShowSlider;
