$(document).ready(function() {
    var search = document.querySelector("#search");

    var id = document.querySelector("#venueId").value;

    var btn = `<button class=\"button is-primary\" type=\"submit\">
    <span class=\"icon is-small\">
      			<i class=\"fas fa-check\"></i>
    		</span>
    		<span>Conferma</span>
    </button>
    <a class=\"button is-danger is-light\" href=\"./formEventi.php?id=${id}\">Annulla</a>`;
    
    search.onkeyup = function() {

        document.querySelector("#control").classList.add("is-loading");
        var venue = document.querySelector("#venueId");
        var form = document.querySelector("#form");
        var allVenueSet = document.querySelectorAll("input[type=radio]");

        var html="";
        //form.innerHTML = "";
        $.ajax({
            type: "GET",
            url: "./cercaLuogo.php?q=" + search.value,
            data: search.value,
            success: function(venues){

                if(search.value != "") {
                    var myObj = JSON.parse(venues);
                    var arrayId = new Array();
                    allVenueSet.forEach(e => arrayId.push(e.value));
                    var newArrayId = new Array();
                    myObj.forEach(venue => newArrayId.push(venue["id"]));
                    arrayId.sort();
                    newArrayId.sort();
                    if(arrayId.sort().join(',')!== newArrayId.sort().join(',')) {
                        myObj.forEach(venue => {
                        /*var node = document.createElement("div");                  
                        form.appendChild(node);
                        node.addClass="box";*/
                        html +=
                                `<div class='box'>
                                <article class=\"media\">
                                    <input type=\"radio\" name=\"venue\" value=${venue['id']}>
                                    <br>
                                    <div class='media-left'>
                                        <figure class='image is-64x64'>
                                        <img src=\"${venue['imageUrl']}\" alt=\"Image\">
                                        </figure>
                                    </div>
                                    <div class=\"media-content\">
                                        <div class=\"content\">
                                            <p><strong>${venue['name']}</strong><br>
                                                ${venue['address']}
                                            </p>
                                        </div>
                                    </div>
                                </article>
                            </div>`;
                    });
                    console.log(btn);
                    form.innerHTML = html + btn;
                }
                
                } else {
                    form.innerHTML = btn;
                }

                document.querySelector("#control").classList.remove("is-loading");
                
                var box = document.querySelectorAll(".box");
                box.forEach(function(item, index) {
                    item.onclick = function(e) {
                    e.target.closest(".box").querySelector("[type=radio]").checked = true; ;
        }
    })
            }
          });
    }

});

