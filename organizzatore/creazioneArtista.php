<?php 
  require "db.php";

  if($_SERVER["REQUEST_METHOD"]=="POST") {
    if($_POST["id"]!=NULL) {
        $db->updateArtistById($_POST["id"], $_POST["name"], $_POST["description"]);
    }else{
      $db->insertNewArtist(generateId(), $_POST["name"], $_POST["description"]);
    }
  }

  $isArtistSet = false;
  if(isset($_GET["id"])) {
    $id = $_GET["id"];
    $isArtistSet = true;
    $artist = $db->getArtistById($id);
    if(!$artist) {
      die ("Selezionato un artista non valido");
    }
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
      <h1 class="title">Crea un nuovo artista</h1>
      <form action="./creazioneArtista.php" method="POST">
        <label for="id"></label>
        <input type="hidden" name="id" id="id"  value="<?php if($isArtistSet) { echo $artist["id"];}?>">
          <div class="field">
              <label for="name" class="label">Nome</label>
              <div class="control">
                  <input type="text" name="name" id="name"  required value="<?php if($isArtistSet) { echo $artist["name"];} ?>">
              </div>
          </div>
          <div class="field">
            <label for="description" class="label">Descrizione</label>
            <textarea name="description" id="description"><?php if($isArtistSet) { echo $artist["description"];} ?></textarea>
        </div>
        <label class="label">Immagine verticale</label>
        <div class="file has-name">
            <label class="file-label">
              <input class="file-input" type="file" accept=".jpg, .jpeg, .jpg" name="img1">
              <span class="file-cta">
                <span class="file-icon">
                  <i class="fas fa-upload"></i>
                </span>
                <span class="file-label">
                  Choose a file…
                </span>
              </span>
              <span class="file-name"> 
              </span>
            </label>
          </div>
          <label class="label">Immagine orizzontale</label>
        <div class="file has-name">
            <label class="file-label">
              <input class="file-input" type="file" accept=".jpg, .jpeg, .jpg" name="img1">
              <span class="file-cta">
                <span class="file-icon">
                  <i class="fas fa-upload"></i>
                </span>
                <span class="file-label">
                  Choose a file…
                </span>
              </span>
              <span class="file-name"> 
              </span>
            </label>
          </div>
          <div><Button type="submit">Crea</Button>
      </form>
      </div>
      <a href="./visualizzaArtisti.php">Torna agli artisti</a>
  </body>