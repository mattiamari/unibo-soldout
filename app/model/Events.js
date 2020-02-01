'use strict';

const dummyEvents = [
    {
        id: 'abcdef',
        title: "Linkin Park @ Firenze Rocks",
        date: "2 agosto 2020",
        locationShort: "Firenze, Italia",
        coverImage: {url: "i/l-linkin-park.jpg", alt: "linkin park band photo"}
    },
    {
        id: 'abcdef',
        title: "Rise Against",
        date: "10 luglio 2020",
        locationShort: "Monza, Italia",
        coverImage: {url: "i/l-rise-against.jpg", alt: "rise against band photo"}
    },
    {
        id: 'abcdef',
        title: "System Of A Down",
        date: "20 luglio 2020",
        locationShort: "Monza, Italia",
        coverImage: {url: "i/l-soad.jpg", alt: "system of a down band logo"}
    },
    {
        id: 'abcdef',
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

const dummyEventDetails = {
    id: 'abcdefghij',
    title: "Linkin Park @ Firenze Rocks",
    date: "10 luglio 2020",
    location: 'Ippodromo del Visarno, Firenze, Italia',
    basePrice: 49.00,
    images: {
        card: {url: "i/l-linkin-park.jpg", alt: "linkin park band photo"},
        detailsBackground: {url: "i/p-linkin-park.jpg", alt: "linkin park band photo"}
    },
    description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ante in nibh mauris cursus mattis molestie. Magna fermentum iaculis eu non diam phasellus. Massa tincidunt dui ut ornare lectus. Duis tristique sollicitudin nibh sit amet commodo nulla. Sit amet justo donec enim diam. Proin sagittis nisl rhoncus mattis rhoncus urna. Sit amet est placerat in egestas. Gravida arcu ac tortor dignissim convallis aenean et. Magna ac placerat vestibulum lectus mauris ultrices eros in. Id eu nisl nunc mi. Pharetra vel turpis nunc eget lorem dolor sed viverra. Auctor neque vitae tempus quam pellentesque nec nam. Commodo viverra maecenas accumsan lacus vel. Aliquam malesuada bibendum arcu vitae. Egestas maecenas pharetra convallis posuere morbi leo urna.",
    ticketTypes: [
        {id: 'abc1', name: "Poltrona", price: 100.00},
        {id: 'abc2', name: "Poltrona Gold", price: 140.00},
        {id: 'abc3', name: "Poltrona Platinum", price: 200.00},
        {id: 'abc4', name: "Tribuna", price: 80.00},
    ],
    maxTicketsPerPurchase: 5,
};

const Events = {
    getEventDetails: async eventId => {
        return dummyEventDetails;
    },

    getNewEvents: async () => {
        return dummyEvents;
    },

    getEventsCategorized: async () => {
        return dummyEventTabs;
    },
};

export default Events;
