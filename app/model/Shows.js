'use strict';

const dummyEvents = [
    {
        id: 'abcd1',
        title: "Linkin Park @ Firenze Rocks",
        date: '2020-08-02T21:00',
        locationShort: "Firenze, Italia",
        coverImage: {url: "i/l-linkin-park.jpg", alt: "linkin park band photo"}
    },
    {
        id: 'abcd2',
        title: "Rise Against",
        date: '2020-06-10T21:00',
        locationShort: "Monza, Italia",
        coverImage: {url: "i/l-rise-against.jpg", alt: "rise against band photo"}
    },
    {
        id: 'abcd3',
        title: "System Of A Down",
        date: '2020-07-21T21:00',
        locationShort: "Monza, Italia",
        coverImage: {url: "i/l-soad.jpg", alt: "system of a down band logo"}
    },
    {
        id: 'abcd4',
        title: "NF",
        date: '2020-08-13T21:00',
        locationShort: "Monza, Italia",
        coverImage: {url: "i/l-nf.jpg", alt: "nf's 'therapy session' album cover"}
    },
];

const dummyEventTabs = [
    {
        name: 'Teatro',
        shows: dummyEvents.slice(1)
    },
    {
        name: 'Concerti',
        shows: dummyEvents.concat(dummyEvents)
    },
    {
        name: 'Maremma maiala',
        shows: dummyEvents.slice(1)
    },
    {
        name: 'Qualcos\'altro',
        shows: dummyEvents.slice(1)
    },
];

class Show {
    constructor() {
    }

    get lowestPrice() {
        return Math.min(...this.ticketTypes.map(e => e.price));
    }

    getImage(type) {
        return this.images.find(e => e.type === type);
    }
}

const Shows = {
    getShowDetails: async showId => {
        const res = await fetch('/api/show/' + showId);
        const show = (await res.json()).show;
        return Object.assign(new Show(), show);
    },

    getNewShows: async () => {
        const res = await fetch('/api/shows/concerts');
        const shows = (await res.json()).shows;
        return shows;
    },

    getShowsCategorized: async () => {
        return dummyEventTabs;
    },
};

export default Shows;
