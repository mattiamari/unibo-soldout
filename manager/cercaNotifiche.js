$(document).ready(function() {

    function test() {
        
        elemento = $("#notifica");
    

        $.ajax({
            type: "GET",
            url: "./cercaNotifiche.php",
            success: function(notifications){
                
                var myObj = JSON.parse(notifications);
                if(myObj.length > 0) {
                    elemento.addClass("hasBadge");
                }
            }
        });
    }
    setInterval(test, 5000);
});

