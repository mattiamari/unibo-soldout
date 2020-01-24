'use strict';

const dummyEvents = [
    {
        title: "Linkin Park @ Firenze Rocks",
        date: "2 agosto 2020",
        locationShort: "Firenze, Italia",
        coverImage: "i/p-linkin-park.jpg"
    },
    {
        title: "Rise Against",
        date: "10 luglio 2020",
        locationShort: "Monza, Italia",
        coverImage: "i/p-rise-against.jpg"
    },
    {
        title: "System Of A Down",
        date: "20 luglio 2020",
        locationShort: "Monza, Italia",
        coverImage: "i/p-soad.jpg"
    },
    {
        title: "NF",
        date: "5 agosto 2020",
        locationShort: "Monza, Italia",
        coverImage: "i/p-nf.jpg"
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
