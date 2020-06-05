$(document).ready(function() {
    $(".elimina").click(function() {
        button = $(this);
        console.log(button);
        id = button.data("showid");
        console.log(id);
        $(".modal").addClass("is-active");
        $(".conferma").click(function() {
            button.attr("href", "./eliminaEvento.php?id=" + id);
        });
    });

    

    $(".delete, .annulla").click(function() {
        $(".modal").removeClass("is-active");
    });
});

