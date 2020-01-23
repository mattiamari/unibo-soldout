'use strict';

function translate(dom, lang) {
    dom.querySelectorAll('.translate').forEach(e => {
        e.innerHTML = langs[lang][e.dataset.lang];
    });
}

function translatePage(lang) {
    return translate(document, lang);
}

export {translate, translatePage};
