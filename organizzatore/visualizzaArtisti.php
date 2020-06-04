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

function artistRow($artist)
{
  $status = "";
  $stringa = "Modifica";
  if($artist['show_count'] > 0 && $_SESSION["is_admin"]==0) {
    $status = "disabled";
    $stringa = "Visualizza";
  }
    return "<tr>"
        . "<td headers='nome'>{$artist['name']}</td>"
        . "<td headers='descrizione'>{$artist['description']}</td>"
        . "<td headers='azione'><a class=\"button is-info is-light \" href=creazioneArtista.php?id={$artist['id']}>$stringa</td>"
        . "<td headers='azione'><a class=\"button is-danger is-light {$status} \"  {$status} href=visualizzaArtisti.php?id={$artist['id']}&action=deleteArtist>Elimina</td>"
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
    <script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
    <script src="./navbar.js"></script>
    <script src="./disabilitaBottone.js"></script>
    
</head>

<body>
    <?php  require "navbarArtista.php"?>
    <section class="section container">
        <a id="newElementButton" href="creazioneArtista.php" class="button is-primary">Aggiungi artista</a>
    </section>
    <section class="section container">
        <div class="table-container is-bordered is-striped is-narrow is-hoverable columns is-centered">
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