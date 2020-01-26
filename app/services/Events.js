'use strict';

const dummyEvents = [
    {
        title: "Linkin Park @ Firenze Rocks",
        date: "2 agosto 2020",
        locationShort: "Firenze, Italia",
        coverImage: {url: "i/l-linkin-park.jpg", alt: "linkin park band photo"}
    },
    {
        title: "Rise Against",
        date: "10 luglio 2020",
        locationShort: "Monza, Italia",
        coverImage: {url: "i/l-rise-against.jpg", alt: "rise against band photo"}
    },
    {
        title: "System Of A Down",
        date: "20 luglio 2020",
        locationShort: "Monza, Italia",
        coverImage: {url: "i/l-soad.jpg", alt: "system of a down band logo"}
    },
    {
        title: "NF",
        date: "5 agosto 2020",
        locationShort: "Monza, Italia",
        coverImage: {url: "i/l-nf.jpg", alt: "nf's 'therapy session' album cover"}
    },
];

const dummyEventTabs = [
    {
        tabName: 'Teatro',
        events: dummyEvents.slice(1)
    },
    {
        tabName: 'Concerti',
        events: dummyEvents.slice(0, 3)
    },
    {
        tabName: 'Maremma maiala',
        events: dummyEvents.slice(1)
    },
];

const Events = {
    getNewEvents: async () => {
        return dummyEvents;
    },

    getEventsCategorized: async () => {
        return dummyEventTabs;
    },
};

export default Events;
