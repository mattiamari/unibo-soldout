<?php

require "db.php";

session_start();
if(!$_SESSION["login"]) {
  header("location: ./login.php?error=nolog");
}
/* query di ricerca che può essere con AJAX oppure si può fare con un tasto cerca
Bisogna passare id dell'evento a cui associare il luogo e si aggiunge il parametro in get di ricerca
(query da fare solo se si passa quel valore in get)*/
if (!isset($_GET["id"])) {
  die("Nessun evento selezionato");
}
$id = $_GET["id"];

if (isset($_POST["venue"])) {
  $db->updateVenue($_POST["venue"], $_GET["id"]);
  header("location: ./formEventi.php?id={$_GET['id']}");
}


if (isset($_GET["search"]) && $_GET["search"] != "") {
  $venues = $db->searchVenue($_GET["search"]);
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Creazione luogo</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
  <script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
  <script src="./selezionaArtista.js"></script>
  <script src="./cercaLuogo.js"></script>
  <script src="./cercaNotifiche.js"></script>
</head>

<body>

<?php require "navbarLuogo.php" ?>
<br>
<div id="container">

  <h1 class="title">Seleziona luogo</h1>
  <form>
    <div class="field">
      <div id="control" class="control">
        <input id="venueId" type="hidden" name="id" value="<?php echo $id ?>">
        <label for="search" class="label">Cerca luogo</label>
        <p class="control has-icons-left">
        <input id="search" name="search" class="input" type="text">
          <span class="icon is-left">
              <em class="fas fa-search" aria-hidden="true"></em>
          </span>
        </p>
      </div>
    </div>
  </form>
  <form id="form" method="POST">

    <br>
    <a class="button is-danger is-light" href="./formEventi.php?id=<?php echo $id ?>">Annulla</a>
    </form>
    </div>
</body>

</html>