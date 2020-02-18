<?php 
  require "db.php";

  if($_SERVER["REQUEST_METHOD"]=="POST") {
    $artistId = $_POST["id"];

    if($_POST["id"]!=NULL) {
        $db->updateArtistById($_POST["id"], $_POST["name"], $_POST["description"]);
    } else{
      $artistId = generateId();
      $db->insertNewArtist($artistId, $_POST["name"], $_POST["description"]);
    }

    $altO = "";
    if(isset($_POST["altO"])){
        $altO = $_POST["altO"];
    }

    $altV = "";
    if(isset($_POST["altV"])){
        $altV = $_POST["altV"];
    }

    if(isset($_FILES["imgO"])) {
        $nameo = saveImg($_FILES["imgO"], $artistId, "horizontal");
    }

    if(isset($_FILES["imgV"])) {
        $namev = saveImg($_FILES["imgV"], $artistId, "vertical");
    }

    if(isset($nameo)) {
        $db->updateImage($artistId, "artist", $nameo, "horizontal", $altO);
    }
    if(isset($namev)) {
        $db->updateImage($artistId, "artist", $namev, "vertical", $altV);
    }
  }

  $isArtistSet = false;
  if(isset($_GET["id"])) {
    $id = $_GET["id"];
    $isArtistSet = true;
    $artist = $db->getArtistById($id);
    /*if(!$artist) {
      die ("Selezionato un artista non valido");
    }*/
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
      <form enctype="multipart/form-data" action="?<?php if(isset($_GET["id"])) echo "id=" . $artistId?>" method="POST">
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
        <label for="imgV"class="label">Immagine verticale</label>
        <div class="file has-name">
          <label class="file-label">
          <input id="imgV" class="file-input" type="file" name="imgV" accept=".jpg, .jpeg, .jpg">  
          <span class="file-cta">
              <span class="file-icon">
                <i class="fas fa-upload"></i>
              </span>
              <span class="file-label">
                Scegli un file...
              </span>
            </span>
            <span class="file-name">
            </span>
          </label>
        </div>
        <div class="field">
          <label for="altV" class="label">Alt</label>
          <div class="control">
            <input id="altV" class="input" type="text" name="altV" value="">
          </div>
        </div>
        <label for="imgO" class="label">Immagine orizzontale</label>
        <div class="file has-name">
          <label class="file-label">
          <input id="imgO" class="file-input" type="file" name="imgO" accept=".jpg, .jpeg, .jpg">
            <span class="file-cta">
              <span class="file-icon">
                <i class="fas fa-upload"></i>
              </span>
              <span class="file-label">
                Scegli un file…
              </span>
            </span>
            <span class="file-name">
            </span>
          </label>
        </div>
        <div class="field">
          <label for="altO" class="label">Alt</label>
          <div class="control">
            <input id="altO" class="input" type="text" alto="title" value="">
          </div>
        </div>
          <button class="button" type="submit">Crea</button>
      </form>
      </div>

      <a class="button" href="./visualizzaArtisti.php">Torna agli artisti</a>
  </body>