'use strict';

function throttle(delay, func) {
    let t = Date.now();

    return () => {
        if (Date.now() - t > delay) {
            func();
            t = Date.now();
        }
    };
}

export default throttle;
