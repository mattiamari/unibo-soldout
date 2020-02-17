'use strict';

const formToObject = form => {
    const formdata = new FormData(form);
    const formdataObj = {};
    for (let pair of formdata.entries()) {
        formdataObj[pair[0]] = pair[1];
    }
    return formdataObj;
};

export default formToObject;
