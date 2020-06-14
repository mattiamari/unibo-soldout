$(document).ready(function() {
    var box = document.querySelectorAll(".box");
    box.forEach(function(item, index) {
        item.onclick = function(e) {
            e.target.closest(".box").querySelector("[type=radio]").checked = true; ;
        }
    })
})