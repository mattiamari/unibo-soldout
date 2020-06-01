$(document).ready(function() {
    buttonList = document.querySelectorAll(".disabled");
    buttonList.forEach(btn => btn.removeAttribute("href"));
    const ttBox = document.createElement("div");
    b = document.querySelector(".disabled");
    // set style
    ttBox.id = "tooltip88493";
    ttBox.style.visibility = "hidden"; // make it hidden till mouse over
    ttBox.style.position = "fixed";
    ttBox.style.top = "1ex";
    ttBox.style.left = "1ex";
    ttBox.style.padding = "0.5em";
    ttBox.style.width = "16em";
    ttBox.style.borderRadius = "1em";
    ttBox.style.border = "solid thin gray";
    ttBox.style.backgroundColor = "white";

    // insert into DOM
    document.body.appendChild(ttBox);

    const ttTurnOn = ((evt) => {
    // get the position of the hover element
    const boundBox = evt.target.getBoundingClientRect();
    const coordX = boundBox.left;
    const coordY = boundBox.top;

    // adjust bubble position
    ttBox.style.left = (coordX-200).toString() + "px";
    ttBox.style.top = (coordY-100).toString() + "px";

    // add bubble content. Can include image or link
    console.log(window.location.href);
    $entity = window.location.href == "http://localhost/soldout/organizzatore/visualizzaArtisti.php" ? "artista" : "luogo";  
    
    ttBox.innerHTML = "Non è permesso modificare" + "<br/>" + "i dati o eliminare un " + $entity + "<br/>" +
                 "se questo è già associato ad un evento.";

    // make bubble VISIBLE
    ttBox.style.visibility = "visible";
});

    const ttTurnOff = (() => { ttBox.style.visibility = "hidden"; });

    buttonList.forEach(
        e => {
            e.addEventListener("mouseover", ttTurnOn , false);
            e.addEventListener("mouseout", ttTurnOff , false);
            document.getElementById("tooltip88493") . addEventListener("click", ttTurnOff , false);
        }); 

});