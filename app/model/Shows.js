'use strict';

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
        const res = await fetch('/api/shows');
        const shows = await res.json();
        const tmp = {};

        for (let show of shows.shows) {
            if (!tmp[show.category]) {
                tmp[show.category] = [];
            }
            tmp[show.category].push(show);
        }

        return Object.entries(tmp).map(([k,v]) => {return {name: k, shows: v}});
    },

    getVenueDetails: async venueId => {
        const res = await fetch('/api/venue/' + venueId);
        return (await res.json()).venue;
    },

    getArtistDetails: async artistId => {
        const res = await fetch('/api/artist/' + artistId);
        return (await res.json()).artist;
    },
};

export {Shows, Show};
