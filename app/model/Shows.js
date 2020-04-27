'use strict';

const defaultImage = {url: '/app/res/default-image.jpg', alt: 'Missing image'};

class Show {
    constructor() {}

    get lowestPrice() {
        return Math.min(...this.ticketTypes.map(e => e.price));
    }

    getImage(type = false) {
        if (!this.images.length) {
            return defaultImage;
        }
        if (!type) {
            return this.images.find(Boolean);
        }

        const image = this.images.find(e => e.type === type);

        return !!image ? image : defaultImage;
    }
}

class ShowSummary {
    constructor() {}

    get image() {
        return !!this.imageUrl ? {url: this.imageUrl, alt: this.imageAlt} : defaultImage;
    }
}

class Venue {
    constructor() {}

    get image() {
        return !!this.imageUrl ? {url: this.imageUrl, alt: this.imageAlt} : defaultImage;
    }
}

class Artist {
    constructor() {}

    get image() {
        return !!this.imageUrl ? {url: this.imageUrl, alt: this.imageAlt} : defaultImage;
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
        return shows.map(e => Object.assign(new ShowSummary, e));
    },

    getShowsCategorized: async () => {
        const res = await fetch('/api/shows');
        let shows = await res.json();
        const tmp = {};

        shows = shows.shows.map(e => Object.assign(new ShowSummary, e));

        for (let show of shows) {
            if (!tmp[show.category]) {
                tmp[show.category] = [];
            }
            tmp[show.category].push(show);
        }

        return Object.entries(tmp).map(([k,v]) => {return {name: k, shows: v}});
    },

    getVenueDetails: async venueId => {
        const res = await fetch('/api/venue/' + venueId);
        const venue = (await res.json()).venue;
        return Object.assign(new Venue, venue);
    },

    getArtistDetails: async artistId => {
        const res = await fetch('/api/artist/' + artistId);
        const artist = (await res.json()).artist;
        return Object.assign(new Artist, artist);
    },
};

export {Shows, Show};
