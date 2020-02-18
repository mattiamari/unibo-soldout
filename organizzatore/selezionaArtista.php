<?php

require "db.php";
/* query di ricerca che può essere con AJAX oppure si può fare con un tasto cerca
Bisogna passare id dell'evento a cui associare l'artista e si aggiunge il parametro in get di ricerca
(query da fare solo se si passa quel valore in get)
Possibilità di associare l'immagine*/
if(!isset($_GET["id"])) {
  die("Nessun evento selezionato");
}
$id = $_GET["id"];

if(isset($_POST["artist"])) {
    $db->updateArtist($_POST["artist"],  $_GET["id"]);
    header("location: ./formEventi.php?id={$_GET['id']}");
}

if(isset($_GET["search"]) && $_GET["search"]!="") {
  $artists = $db->searchArtist($_GET["search"]);
}
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
    <h1 class="title">Seleziona artista</h1>
    <form>
      <div class="field">
        <div class="control">
          <input type="hidden" name="id" value="<?php echo $id ?>">
          <input id="search" name="search" class="input" type="text" placeholder="Cerca artista">
          <button type="submit" class="button is-right">Cerca</button>
        </div>
      </div>
    </form>
    <form method="POST">
    <?php
      if(isset($artists)) {
        foreach($artists as $artist) {
          echo "<label class=\"radio\">
            <input type=\"radio\" name=\"artist\" value=\"{$artist['id']}\">
            {$artist['name']}<br>
          </label><br>";
        }
      }
      
    ?>
    <button class="button" type="submit">Conferma</button>
    </form>
  </body>
</html>