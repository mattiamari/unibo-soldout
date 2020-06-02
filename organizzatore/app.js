document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll("input[type=file]").forEach(e => e.addEventListener('change', function() {
        setFileName(e);
        
    }));
});



function setFileName(e) {
    var container = document.getElementById('fileList');

    e.parentElement.querySelector('.file-name').innerHTML = e.files[0].name;

    img = document.createElement('img');
    img.width = 200;
    img.src = window.URL.createObjectURL(e.files[0]);
    img.alt = e.files[0].name;

    li = document.createElement('li');
    li.appendChild(img);
    container.innerHTML="";
    container.appendChild(li);
    
}

