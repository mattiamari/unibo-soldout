<?php

session_start();
if(!$_SESSION["login"]) {
  header("location: ./login.php?error=nolog");
}
require "db.php";


function eventRow($evento) {
  $stato = "in attesa di approvazione";
  if($evento['enabled']) {
    $stato = "approvato";
  }
  return "<tr>"
      . "<td>{$evento['title']}</td>"
      . "<td>{$evento['date']}</td>"
      . "<td>{$stato}</td>"
      . "<td>{$evento['tickets_sold']}/{$evento['tickets_total']}</td>"
      . "<td><a class=button href=statisticheEvento.php?id={$evento['id']}>Dettagli</button></td>"
      . "<td><a class=button href=formEventi.php?id={$evento['id']}>Modifica</td>"
      . "<td><a class=button href=eliminaEvento.php?id={$evento['id']}>Elimina</td>"
      . "</tr>";
}
$events = $db->getEventList();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
  </head>
  <body>
    <?php require "navbar.php";
    if(isset($_GET["error"])) {
      echo "<article class=\"message is-danger\">
      <div class=\"message-header\">
        <p>Danger</p>
        <button class=\"delete\" aria-label=\"delete\"></button>
      </div>
      <div class=\"message-body\">
       Non è possibile eliminare un evento per il quale sono già stati acquistati biglietti.
      </div>
    </article>";
    } ?>
    <section class="section">
      <a href="./formEventi.php" class="button">Aggiungi evento</a>
    </section>
    <section>
      <div class="table-container">
        <table class="table">
         <thead>
          <tr>
            <th>Titolo</th>
            <th>Data</th>
            <th>Stato</th>
            <th>Biglietti venduti/biglietti totali</th>
          </tr>
        </thead>
        <tbody>
          <?php
            echo join("\n", array_map('eventRow', $events));
          ?>
        </tbody>
    </table>
  </div>
  </section>
  </body>
</html>