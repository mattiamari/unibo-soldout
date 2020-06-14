$(document).ready(function() {
    $(".elimina").not(".disabled").click(function() {
        button = $(this);
        
        id = button.data("showid");
        
        $(".modal").addClass("is-active");
        $(".conferma").attr("href", "./eliminaEvento.php?id=" + id);
        
    });

    $(".conferma").click(function() {
        $(".modal").removeClass("is-active");
    });

    

    $(".delete, .annulla").click(function() {
        $(".modal").removeClass("is-active");
    });
});

