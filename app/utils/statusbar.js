'use strict';

const Statusbar = {
    setColor(color) {
        document.querySelector('meta[name=theme-color]').setAttribute('content', color);
    }
}

export default Statusbar;
