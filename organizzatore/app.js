document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll("input[type=file]").forEach(e => e.addEventListener('change', setFileName));
});

function setFileName(e) {
    e.srcElement.parentElement.querySelector('.file-name').innerHTML = e.srcElement.files[0].name;
}

