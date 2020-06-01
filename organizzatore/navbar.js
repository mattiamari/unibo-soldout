$(document).ready(function() {
    var navbarMenu = document.querySelector("#navbarMenuHeroC");
    var navbarMenuBurger = document.querySelector("#navbarMenuBurger");

    navbarMenuBurger.onclick = function(){
    if (window.innerWidth < 1024 && navbarMenuBurger.className == "navbar-burger burger") {
        navbarMenuBurger.classList.add("is-active");
        navbarMenu.classList.add("is-active");
    } else {

        navbarMenuBurger.classList.remove("is-active");
        navbarMenu.classList.remove("is-active");
    }
    }

    $(window).resize(function() {
        if (window.innerWidth > 1024) {
            navbarMenuBurger.classList.remove("is-active");
            navbarMenu.classList.remove("is-active");
        }
    }); 
});
