<?php

session_start();
if(!$_SESSION["login"]) {
  header("location: ./login.php?error=nolog");;
}

require "db.php";
/* query per prendere gli eventi con tutti i campi(titolo,categoria,data,biglietti)
query per modificare un evento e eliminarlo(fai una delete per eliminare e update per aggiornare 
e poi aggiorni la tabella).
Tramite il tasto dettagli evento vado sulla pagina "statisticheEvento.php"
Valutare di inserire query per le statistiche di tutti gli eventi ad esempio query per la top 10*/ 

if(isset($_GET["id"]) && isset($_GET["action"])) {
  if($_GET["action"]=="deleteVenue") {
    $db->deleteVenueById($_GET["id"]);
  }
}

function venueRow($venue) {
  return "<tr>"
      . "<td>{$venue['name']}</td>"
      . "<td>{$venue['description']}</td>"
      . "<td><a class=button href=creazioneLuogo.php?id={$venue['id']}>Modifica</td>"
      . "<td><a class=button href=visualizzaLuoghi.php?id={$venue['id']}&action=deleteVenue>Elimina</td>"
      . "</tr>";
}
$venue = $db->getVenueList();


?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
  </head>
  <body>
  <?php require "navbar.php"; ?>
    <section class="section">
      <a href="creazioneLuogo.php" class="button">Aggiungi luogo</a>
    </section>
  <section>
    <div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrizione</th>
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