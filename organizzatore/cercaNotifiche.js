$(document).ready(function() {

    function test() {
        
        elemento = $("#notifica");
    

        $.ajax({
            type: "GET",
            url: "./cercaNotifiche.php",
            success: function(notifications){
                
                console.log(notifications);
                var myObj = JSON.parse(notifications);
                console.log(myObj.length);
                if(myObj.length > 0) {
                    elemento.addClass("hasBadge");
                }
            }
        });
    }
    setInterval(test, 5000);
});

