$(document).ready(function() {
    var navbarMenu = document.querySelector("#navbarMenuHeroC");
    var navbarMenuBurger = document.querySelector("#navbarMenuBurger");
    var notifiche = document.querySelector("#notifica");

    navbarMenuBurger.onclick = function(){

    if (window.innerWidth < 1024 && navbarMenuBurger.className == "navbar-burger burger") {
        navbarMenuBurger.classList.add("is-active");
        navbarMenu.classList.add("is-active");
        notifiche.classList.remove("hasBadge");
    } else {

        navbarMenuBurger.classList.remove("is-active");
        navbarMenu.classList.remove("is-active");
    }
    }

    $(window).resize(function() {
        if (window.innerWidth > 1024) {
            notifiche.classList.add("hasBadge");
            navbarMenuBurger.classList.remove("is-active");
            navbarMenu.classList.remove("is-active");
        }
    }); 
});
