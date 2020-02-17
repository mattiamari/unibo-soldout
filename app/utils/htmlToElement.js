'use strict';

function htmlToElement(htmlString) {
    const template = document.createElement('template');
    template.innerHTML = htmlString;
    return template.content.firstElementChild;
}

export default htmlToElement;
