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


$status = "";

$isArtistSet = false;
if (isset($_GET["id"])) {
	$id = $_GET["id"];
	$img = $db->getImageById($id);
	$isArtistSet = true;
	$artist = $db->getArtistById($id);

	
	$image = $db->getImageById($id);

	$imageName = $image["name"];

	$countArtist = $db->countArtistWithShowById($id);
	
	
  	if($countArtist['show_count'] > 0 && $_SESSION["is_admin"]==0) {
    	$status = "disabled";
  }
}

?>
<!DOCTYPE html>
<html lang="it">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Creazione artista</title>
	<link rel="stylesheet" href="style.css">
	<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script src="app.js"></script>
	<script src="./jquery-3.4.1.min.js"></script>
	<script src="./navbar.js"></script>
	<script src="./disabilitaBottone.js"></script>
	<script src="./cercaNotifiche.js"></script>
</head>

<body>
	<?php require "navbarArtista.php"?>
	<br>
	<div id="container">
	<h1 class="title"><?php if (isset($id)) echo "Modifica artista"; else echo "Crea un nuovo artista"?></h1>
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
		<ul id="fileList"><?php if ($isArtistSet) {echo "<img width=\"200\" height=\"180\" src=\"/i/$id/horizontal/$imageName\">";} ?></ul>
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
			<button type="submit" class="button is-primary <?php echo $status?>" <?php echo $status?>>
    		<span class="icon is-small">
      			<i class="fas fa-check"></i>
    		</span>
			<span><?php if (isset($id)) echo "Salva"; else echo "Crea"?></span>
			
  		</button>
		  <a class="button" href="./visualizzaArtisti.php">Torna agli artisti</a>
		</div>
	</form>
	</div>
	</div>
</body>
</html>