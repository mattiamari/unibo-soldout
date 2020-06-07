$(document).ready(function () {
    var search = document.querySelector("#search");
    var id = document.querySelector("#artistId").value;

    var btn = `<button class=\"button is-primary\" type=\"submit\">
        <span class=\"icon is-small\">
            <i class=\"fas fa-check\"></i>
        </span>
        <span>Conferma</span>
    </button>
    <a class=\"button is-danger is-light\" href=\"./formEventi.php?id=${id}\">Annulla</a>`;

    search.onkeyup = function () {
        document.querySelector("#control").classList.add("is-loading");
        var artist = document.querySelector("#artistId");
        var form = document.querySelector("#form");
        var allArtistSet = document.querySelectorAll("input[type=radio]");

        var html = "";
        //form.innerHTML = "";
        $.ajax({
            type: "GET",
            url: "./cercaArtista.php?q=" + search.value,
            data: search.value,
            success: function (artists) {
                if (search.value != "") {
                    var myObj = JSON.parse(artists);
                    var arrayId = new Array();
                    allArtistSet.forEach((e) => arrayId.push(e.value));
                    var newArrayId = new Array();
                    myObj.forEach((artist) => newArrayId.push(artist["id"]));
                    arrayId.sort();
                    newArrayId.sort();
                    if (arrayId.sort().join(",") !== newArrayId.sort().join(",")) {
                        myObj.forEach((artist) => {
                            html += `<div class='box'>
                                    <article class=\"media\">
                                        <input type=\"radio\" name=\"artist\" value=${artist["id"]}>
                                        
                                        <div class='media-left'>
                                            <figure class='image is-64x64'>
                                                <img src=\"${artist["imageUrl"]}\" alt=\"Image\">
                                            </figure>
                                        </div>
                                        <div class=\"media-content\">
                                            <div class=\"content\">
                                                <p><strong>${artist["name"]}</strong><br>
                                                    ${artist["description"]}
                                                </p>
                                            </div>
                                        </div>
                                    </article>
                                </div>`;
                        });
                        form.innerHTML = html + btn;
                    }
                } else {
                    form.innerHTML = btn;
                }
                document.querySelector("#control").classList.remove("is-loading");

                var box = document.querySelectorAll(".box");
                box.forEach(function (item, index) {
                    item.onclick = function (e) {
                        e.target
                            .closest(".box")
                            .querySelector("[type=radio]").checked = true;
                    };
                });
            },
        });
    };
});
