$(document).ready(function() {
    var search = document.querySelector("#search");
    
    search.onkeyup = function() {
        
        var artist = document.querySelector("#artistId");
        var form = document.querySelector("#form");
        var allArtistSet = document.querySelectorAll("input[type=radio]");
        
        var html="";
        //form.innerHTML = "";
        $.ajax({
            type: "GET",
            url: "./cercaArtista.php?q=" + search.value,
            data: search.value,
            success: function(artists){

                console.log(arrayId);
                if(search.value != "") {
                    var myObj = JSON.parse(artists);
                    var arrayId = new Array();
                    allArtistSet.forEach(e => arrayId.push(e.value));
                    var newArrayId = new Array();
                    myObj.forEach(artist => newArrayId.push(artist["id"]));
                    arrayId.sort();
                    newArrayId.sort();
                    console.log(arrayId.sort().join(',')!== newArrayId.sort().join(','));
                    if(arrayId.sort().join(',')!== newArrayId.sort().join(',')) {
                        myObj.forEach(artist => {
                            /*var node = document.createElement("div");                  
                            form.appendChild(node);
                            node.addClass="box";*/
                            html +=
                                    `<div class='box'>
                                    <article class=\"media\">
                                        <input type=\"radio\" name=\"artist\" value=${artist['id']}>
                                        <div class='media-left'>
                                            <figure class='image is-64x64'>
                                               <img src=\"https://bulma.io/images/placeholders/128x128.png\" alt=\"Image\">
                                            </figure>
                                        </div>
                                        <div class=\"media-content\">
                                            <div class=\"content\">
                                                <p><strong>${artist['name']}</strong><br>
                                                    ${artist['description']}
                                                </p>
                                            </div>
                                        </div>
                                    </article>
                                </div>`;
                        });
                        form.innerHTML = html;
                    }
                    
                } else {
                    form.innerHTML = "";
            }
                
                
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

