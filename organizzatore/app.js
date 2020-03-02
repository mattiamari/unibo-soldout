document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll("input[type=file]").forEach(e => e.addEventListener('change', function() {
        setFileName(e);
    }));
});

function setFileName(e) {
    e.parentElement.querySelector('.file-name').innerHTML = e.files[0].name;
}

