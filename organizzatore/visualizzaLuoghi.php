<?php

session_start();
if (!$_SESSION["login"]) {
    header("location: ./login.php?error=nolog");;
}

require "db.php";
/* query per prendere gli eventi con tutti i campi(titolo,categoria,data,biglietti)
query per modificare un evento e eliminarlo(fai una delete per eliminare e update per aggiornare 
e poi aggiorni la tabella).
Tramite il tasto dettagli evento vado sulla pagina "statisticheEvento.php"
Valutare di inserire query per le statistiche di tutti gli eventi ad esempio query per la top 10*/

if (isset($_GET["id"]) && isset($_GET["action"])) {
    if ($_GET["action"] == "deleteVenue") {
        $db->deleteVenueById($_GET["id"]);
    }
}

function venueRow($venue)
{
    $stringa = "Modifica";
  $statusElimina = "";
  $status = "";
  if($venue['show_count'] > 0) {
    $statusElimina = "disabled";
    if ($_SESSION["is_admin"]==0) {
        $stringa = "Visualizza";
        $status = "";
    }
  }//href=visualizzaLuoghi.php?id={$venue['id']}&action=deleteVenue

    return "<tr>"
        . "<td headers='nome'>{$venue['name']}</td>"
        . "<td headers='descrizione'>{$venue['description']}</td>"
        . "<td headers='indirizzo'>{$venue['address']}</td>"
        . "<td headers='azioni'><a class=\"button\"  href=creazioneLuogo.php?id={$venue['id']}>$stringa</td>"
        . "<td headers='azioni'><a data-venueid='{$venue['id']}' class=\"button {$statusElimina} elimina is-danger is-light is-outlined {$statusElimina}\" {$statusElimina}>Elimina</td>"
        . "</tr>";
}
$venue = $db->countVenueWithShow();


?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Elenco luoghi</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="./jquery-3.4.1.min.js"></script>
    <script src="./navbar.js"></script>
    <script src="./disabilitaBottone.js"></script>
    <script src="./eliminaLuogo.js"></script>
</head>

<body>

<div class="modal">
        <div class="modal-background">

        </div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Conferma</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
    <!-- Content ... -->
                <p>Sicuro di voler eliminare questo luogo?</p>
            </section>
            <footer class="modal-card-foot">
                <a class="button conferma is-success">Conferma</a>
                <button class="button annulla">Annulla</button>
            </footer>
        </div>
    </div>

<?php require "navbarLuogo.php";?>
    <section class="section container">
        <a id="newElementButton" href="creazioneLuogo.php" class="button is-primary">Aggiungi luogo</a>
    </section>
    <section class="section container">
        <div class="table-container is-bordered is-striped is-narrow is-hoverable columns is-centered">
            <table class="table is-striped">
                <thead>
                    <tr>
                        <th id="nome" scope="col">Nome</th>
                        <th id="descrizione" scope="col">Descrizione</th>
                        <th id="indirizzo" scope="col">Indirizzo</th>
                        <th id="azioni" scope="col" colspan="2">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    echo join("\n", array_map('venueRow', $venue));
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</body>

</html>