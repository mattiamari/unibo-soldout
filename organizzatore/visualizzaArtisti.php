<?php

session_start();
if (!$_SESSION["login"]) {
    header("location: ./login.php?error=nolog");
}

require "db.php";
/* query per prendere gli eventi con tutti i campi(titolo,categoria,data,biglietti)
query per modificare un evento e eliminarlo(fai una delete per eliminare e update per aggiornare 
e poi aggiorni la tabella).
Tramite il tasto dettagli evento vado sulla pagina "statisticheEvento.php"
Valutare di inserire query per le statistiche di tutti gli eventi ad esempio query per la top 10*/

if (isset($_GET["id"]) && isset($_GET["action"])) {
    if ($_GET["action"] == "deleteArtist") {
        $db->deleteArtistById($_GET["id"]);
    }
}

function artistRow($artist) {
  $status = "";
  $statusElimina = "";
  $stringa = "Modifica";
  if($artist['show_count'] > 0) {
    $statusElimina = "disabled";
    if ($_SESSION["is_admin"]==0) {
        $stringa = "Visualizza";
        $status = "";
    }
  }
  //href=visualizzaArtisti.php?id={$artist['id']}&action=deleteArtist
    return "<tr>"
        . "<td headers='nome'>{$artist['name']}</td>"
        . "<td headers='descrizione'>{$artist['description']}</td>"
        . "<td headers='azione'><a class=\"button \" href=creazioneArtista.php?id={$artist['id']}>$stringa</td>"
        . "<td headers='azione'><a data-artistid='{$artist['id']}' class=\"button elimina is-danger is-light is-outlined {$statusElimina} \"  {$statusElimina}>Elimina</td>"
        . "</tr>";
}
$artist = $db->countArtistWithShow();



?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Elenco artisti</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="./jquery-3.4.1.min.js"></script>
    <script src="./navbar.js"></script>
    <script src="./eliminaArtista.js"></script>
    <script src="./disabilitaBottone.js"></script>
    <script src="./cercaNotifiche.js"></script>
    
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
                <p>Sicuro di voler eliminare questo artista?</p>
            </section>
            <footer class="modal-card-foot">
                <a class="button conferma is-success">Conferma</a>
                <button class="button annulla">Annulla</button>
            </footer>
        </div>
    </div>

    <?php  require "navbarArtista.php"?>
    <section class="section container">
        <a id="newElementButton" href="creazioneArtista.php" class="button is-primary">Aggiungi artista</a>
    </section>
    <section class="section container">
        <div class="table-container">
            <table class="table is-striped">
                <thead>
                    <tr>
                        <th id="nome" scope="col">Nome</th>
                        <th id="descrizione" scope="col">Descrizione</th>
                        <th id="azione" scope="col" colspan="2">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    echo join("\n", array_map('artistRow', $artist));
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>