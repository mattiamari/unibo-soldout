'use strict';

function throttle(delay, func) {
    let tmr = null;

    return () => {
        if (tmr) {
            return;
        }

        tmr = setTimeout(() => {
            func();
            tmr = null;
        }, delay);
    };
}

export default throttle;
