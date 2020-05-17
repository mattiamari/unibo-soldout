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
  if($artist['show_count'] > 0) {
    $status = "disabled";
  }
    return "<tr>"
        . "<td>{$artist['name']}</td>"
        . "<td>{$artist['description']}</td>"
        . "<td><a class=\"button is-info is-light {$status}\" {$status} href=creazioneArtista.php?id={$artist['id']}>Modifica</td>"
        . "<td><a class=\"button is-danger is-light {$status}\"  {$status} href=visualizzaArtisti.php?id={$artist['id']}&action=deleteArtist>Elimina</td>"
        . "</tr>";
}
$artist = $db->countArtistWithShow();



?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="http://code.jquery.com/jquery-1.6.4.min.js" type="text/javascript"></script>
    <script src="./navbar.js" type="text/javascript"></script>
    <script src="./disabilitaBottone.js" type="text/javascript"></script>
    
</head>

<body>
    <?php  require "navbarArtista.php"?>
    <section class="section">
        <a href="creazioneArtista.php" class="button is-primary">Aggiungi artista</a>
    </section>
    <section>
        <div class="table is-bordered is-striped is-narrow is-hoverable columns is-centered">
            <table class="table is-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrizione</th>
                        <th></th>
                        <th></th>
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