<?php

require "db.php";

session_start();
if(!$_SESSION["login"]) {
  header("location: ./login.php?error=nolog");
}
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
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seleziona artista</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
    <script src="./selezionaArtista.js"></script>
    <script src="./cercaArtista.js"></script>
  </head>
  <body>
  <div id="container">
    <h1 class="title">Seleziona artista</h1>
    <form>
      <div class="field">
        <div id="control" class="control">
          <input id="artistId" type="hidden" name="id" value="<?php echo $id ?>">
          <label for="search" class="label">Cerca artista</label>
          <p class="control has-icons-left">
          <input id="search" name="search" class="input" type="text">
            <span class="icon is-left">
              <i class="fas fa-search" aria-hidden="true"></i>
            </span>
          </p>
          
        </div>
      </div>
    </form>
    <form id="form" method="POST">
    <div>
  
    </div>
    <br>
    <a class="button is-danger is-light" href="./formEventi.php?id=<?php echo $id ?>">Annulla</a>
    </form>
    

    </div>
  </body>
  
</html>