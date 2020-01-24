'use strict';

const Tab = {
    render: name => {
        const id = formatId(name);
    
        return /*html*/`
            <a class="tabbedContainer-tab" href="#${id}">${name}</a>
        `;
    },
    select: node => {
        node.parentElement.querySelectorAll('.tabbedContainer-tab').forEach(e => Tab.deselect(e));
        node.classList.add('tabbedContainer-tab--active');
    },
    deselect: node => {
        node.classList.remove('tabbedContainer-tab--active');
    }
};

const Card = event => {
    return /*html*/`
        <div class="card card--small" style="background-image: url(${event.coverImage});">
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
    `;
};

const Slide = (name, events) => {
    const id = formatId(name);
    const content = events.map(e => Card(e)).join('\n');

    return /*html*/`
        <div class="tabbedContainer-slide" id="${id}">
            ${content}
        </div>
    `;
};

const EventTabs = {
    render: async params => {
        const tabs = params.map(e => Tab.render(e.tabName)).join('\n');
        const slides = params.map(e => Slide(e.tabName, e.events)).join('\n');

        return /*html*/`
            <div class="tabbedContainer">
                <nav class="tabbedContainer-tabs">
                    ${tabs}
                </nav>

                <div class="tabbedContainer-content">
                    ${slides}
                </div>
            </div>
        `;
    },
    afterRender: () => {
        document.querySelectorAll('.tabbedContainer-tab').forEach(e => {
            e.onclick = () => Tab.select(e)
        });
    }
};

function formatId(name) {
    return 'tab-' + name.toLowerCase().replace(' ', '');
}

export default EventTabs;