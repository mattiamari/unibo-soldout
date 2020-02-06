'use strict';

const Tab = {
    render: name => {
        const id = formatId(name);
    
        return /*html*/`
            <button class="tabbedContainer-tab" data-slide-id="${id}">${name}</button>
            
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

const Card = show => {
    return /*html*/`
        <div class="card card--small" style="background-image: url(${show.coverImage.url});">
            <div class="card-content">
                <div class="showSummary">
                    <div class="showSummary-title">${show.title}</div>
                    <div>
                        <span class="showSummary-date">${show.date}</span>
                        <span class="showSummary-location">${show.locationShort}</span>
                    </div>
                </div>
            </div>
        </div>
    `;
};

const Slide = (name, shows) => {
    const id = formatId(name);
    const content = shows.map(e => Card(e)).join('\n');

    return /*html*/`
        <div class="tabbedContainer-slide" id="${id}">
            ${content}
        </div>
    `;
};

const ShowTabs = {
    render: async params => {
        const tabs = params.tabs.map(e => Tab.render(e.tabName)).join('\n');
        const slides = params.tabs.map(e => Slide(e.tabName, e.shows)).join('\n');

        return /*html*/`
            <div class="tabbedContainer" id="${params.id}">
                <nav class="tabbedContainer-tabs">
                    ${tabs}
                </nav>

                <div class="tabbedContainer-content">
                    ${slides}
                </div>
            </div>
        `;
    },
    afterRender: node => {
        const container = node.querySelector('.tabbedContainer-content');

        node.querySelectorAll('.tabbedContainer-tab').forEach(tab => {
            const slide = document.getElementById(tab.dataset.slideId);

            tab.onclick = () => {
                Tab.select(tab);
                container.scrollTo(slide.offsetLeft, 0);
            };
            
        });
    }
};

function formatId(name) {
    return 'tab-' + name.toLowerCase().replace(' ', '');
}

export default ShowTabs;
