$(document).ready(function() {
    $(".elimina").not(".disabled").click(function() {
        button = $(this);
        
        id = button.data("venueid");
        
        $(".modal").addClass("is-active");
        $(".conferma").attr("href", "./visualizzaLuoghi.php?id=" + id + "&action=deleteVenue");
        
    });

    $(".conferma").click(function() {
        $(".modal").removeClass("is-active");
    });

    

    $(".delete, .annulla").click(function() {
        $(".modal").removeClass("is-active");
    });
});

