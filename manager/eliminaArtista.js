$(document).ready(function() {
    $(".elimina").not(".disabled").click(function() {
        button = $(this);
        
        id = button.data("artistid");
        
        $(".modal").addClass("is-active");
        $(".conferma").attr("href", "./visualizzaArtisti.php?id=" + id + "&action=deleteArtist");
        
    });

    $(".conferma").click(function() {
        $(".modal").removeClass("is-active");
    });

    

    $(".delete, .annulla").click(function() {
        $(".modal").removeClass("is-active");
    });
});

