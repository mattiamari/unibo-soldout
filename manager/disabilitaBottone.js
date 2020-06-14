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
    $entity = "evento"
    if (window.location.href.includes("Artisti")) {
        $entity = "artista";
    } else if(window.location.href.includes("Luoghi")) {
        $entity = "luogo";
    }

    
    if($entity == "evento") {
        ttBox.innerHTML = "Non è permesso eliminare " + "<br/>" + "un evento di cui sono stati già" +
                 " acquistati biglietti.";
    }else {
        ttBox.innerHTML = "Non è permesso eliminare questo " + "<br/>" + $entity +
                 " perché già associato ad un evento.";
    }
    

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