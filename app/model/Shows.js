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

const d = {
    id: 'abcd1',
    title: "Linkin Park @ Firenze Rocks",
    date: '2020-08-02T21:00',
    location: 'Ippodromo del Visarno, Firenze, Italia',
    images: [
        {type: 'horizontal', url: "i/l-linkin-park.jpg", alt: "linkin park band photo"},
        {type: 'vertical', url: "i/p-linkin-park.jpg", alt: "linkin park band photo"}
    ],
    description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ante in nibh mauris cursus mattis molestie. Magna fermentum iaculis eu non diam phasellus. Massa tincidunt dui ut ornare lectus. Duis tristique sollicitudin nibh sit amet commodo nulla. Sit amet justo donec enim diam. Proin sagittis nisl rhoncus mattis rhoncus urna. Sit amet est placerat in egestas. Gravida arcu ac tortor dignissim convallis aenean et. Magna ac placerat vestibulum lectus mauris ultrices eros in. Id eu nisl nunc mi. Pharetra vel turpis nunc eget lorem dolor sed viverra. Auctor neque vitae tempus quam pellentesque nec nam. Commodo viverra maecenas accumsan lacus vel. Aliquam malesuada bibendum arcu vitae. Egestas maecenas pharetra convallis posuere morbi leo urna.",
    ticketTypes: [
        {id: 'abc1', name: "Poltrona", price: 100.00},
        {id: 'abc2', name: "Poltrona Gold", price: 140.00},
        {id: 'abc3', name: "Poltrona Platinum", price: 200.00},
        {id: 'abc4', name: "Tribuna", price: 80.00},
    ],
    maxTicketsPerOrder: 5,
};

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

const dummyShowDetails = Object.assign(new Show(), d);

const Shows = {
    getShowDetails: async showId => {
        return dummyShowDetails;
    },

    getNewShows: async () => {
        return dummyEvents;
    },

    getShowsCategorized: async () => {
        return dummyEventTabs;
    },
};

export default Shows;
