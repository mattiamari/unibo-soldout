'use strict';

const Card = event => {
    return /*html*/`
        <div class="slider-slide">
            <div class="card card--big">
                <img alt="${event.coverImage.alt}" src="${event.coverImage.url}">

                <div class="card-content">
                    <div class="eventQuickInfo">
                        <div class="eventQuickInfo-title">${event.title}</div>
                        <div>
                            <span class="eventQuickInfo-date">${event.date}</span>
                            <span class="eventQuickInfo-location">${event.locationShort}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
};

const EventSlider = {
    render: async params => {
        const events = params.events.map(e => Card(e)).join('\n');

        return /*html*/`
            <div class="slider" id="${params.id}">
                <div class="slider-header">
                    <span class="slider-title">${params.title}</span>
                    <a class="slider-showMore button button--flat" href="#">Vedi tutti</a>
                </div>
                <div class="slider-content">
                    ${events}
                </div>
            </div>
        `;
    }
};

export default EventSlider;
