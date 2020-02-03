'use strict';

function htmlToElement(htmlString) {
    let template = document.createElement('template');
    template.innerHTML = htmlString;
    return template.content.firstElementChild;
}

export default htmlToElement;
