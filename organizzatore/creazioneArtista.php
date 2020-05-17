<?php
require "db.php";

session_start();
if(!$_SESSION["login"]) {
  header("location: ./login.php?error=nolog");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$artistId = $_POST["id"];
	if ($_POST["id"] != NULL) {
		
		$db->updateArtistById($_POST["id"], $_POST["name"], $_POST["description"]);
	} else {
		$artistId = generateId();
		$db->insertNewArtist($artistId, $_POST["name"], $_POST["description"]);
	}

	$alt = "";
	if (isset($_POST["alt"])) {
		$alt = $_POST["alt"];
	}

	if (isset($_FILES["img"])) {
		$name = saveImg($_FILES["img"], $artistId, "horizontal");
	}

	if (isset($name)) {
		$db->updateImage($artistId, "artist", $name, "horizontal", $alt);
	}
}

$isArtistSet = false;
if (isset($_GET["id"])) {
	$id = $_GET["id"];
	$img = $db->getImageById($id);
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
	<link rel="stylesheet" href="style.css">
	<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script type="text/javascript" src="app.js"></script>
	<script src="http://code.jquery.com/jquery-1.6.4.min.js" type="text/javascript"></script>
	<script src="./navbar.js" type="text/javascript"></script>
</head>

<body>
	<?php require "navbarArtista.php"?>
	<br>
	<div id="container">
	<h1 class="title">Crea un nuovo artista</h1>
	<form enctype="multipart/form-data" method="POST" action="creazioneArtista.php?<?php if ($isArtistSet) echo "id=" . $artist["id"]; ?>">
		<label for="id"></label>
		<input class="input" type="hidden" name="id" id="id" value="<?php if ($isArtistSet) {echo $artist["id"];} ?>">
		<div class="field">
			<label for="name" class="label">Nome</label>
			<div class="control">
				<input class="input" type="text" name="name" id="name" required value="<?php if ($isArtistSet) {echo $artist["name"];} ?>">
			</div>
		</div>
		<div class="field">
			<label for="description" class="label">Descrizione</label>
			<textarea class="textarea" name="description" id="description"><?php if ($isArtistSet) {echo $artist["description"];} ?></textarea>
		</div>
		<label for="img" class="label">Immagine</label>
		<div class="file has-name">
  		<label class="file-label">
    		<input id="img" class="file-input" type="file" name="img" accept=".jpg, .jpeg, .jpg">
    		<span class="file-cta">
      		<span class="file-icon">
       		 <i class="fas fa-upload"></i>
     			</span>
      		<span class="file-label">
        		Scegli un file...
      		</span>
    		</span>
				<span class="file-name">
				<?php if($isArtistSet && $img !=null) echo $img["name"] ?>
    		</span>
  		</label>
		</div>
		<div class="field">
			<label for="alt" class="label">Alt</label>
			<div class="control">
				<input id="alt" class="input" type="text" value="">
			</div>
		</div>
		<div class="buttons">		
			<button class="button is-primary" type="submit">
    		<span class="icon is-small">
      			<i class="fas fa-check"></i>
    		</span>
			<span>Crea</span>
			
  		</button>
		  <a class="button" href="./visualizzaArtisti.php">Torna agli artisti</a>
		</div>
	</form>
	</div>
	</div>
</body>
</html>