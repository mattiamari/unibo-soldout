<?php
require "db.php";

session_start();
if(!$_SESSION["login"]) {
  header("location: ./login.php?error=nolog");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $venueId = $_POST["id"];

  if ($_POST["id"] != NULL) {
    $db->updateVenueById($_POST["id"], $_POST["name"], $_POST["description"], $_POST["address"], $_POST["city"]);
  } else {
    $venueId = generateId();
    $db->insertNewVenue($venueId, $_POST["name"], $_POST["description"], $_POST["address"], $_POST["city"]);
  }

  $alt = "";
  if (isset($_POST["alt"])) {
    $altO = $_POST["alt"];
  }


  if (isset($_FILES["img"])) {
    $name = saveImg($_FILES["img"], $venueId, "horizontal");
  }

  if (isset($name)) {
    $db->updateImage($venueId, "venue", $name, "horizontal", $alt);
  }
}
$status = "";
$isVenueSet = false;
if (isset($_GET["id"])) {
  $id = $_GET["id"];
  $isVenueSet = true;
  $venue = $db->getVenueById($id);
  $img = $db->getImageById($id);
  if (!$venue) {
    die("Selezionato un luogo non valido");
  }
  $state = $db->getStateByCityId($venue["city_id"]);
  $country = $db->getCountryByStateId($state["state_id"]);
  $countries = $db->getCountries();
  $states = $db->getStates($country["country_id"]);
  $cities = $db->getCities($state["state_id"]);

  $countVenue = $db->countVenueWithShowById($id);

  $image = $db->getImageById($id);

	$imageName = $image["name"];
	
	
  	if($countVenue['show_count'] > 0 && $_SESSION["is_admin"]==0) {
    	$status = "disabled";
  }
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
  <script src="app.js"></script>
  <script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
  <script defer src="./jquery-3.4.1.min.js"></script>
  <script src="./navbar.js"></script>
  <script defer src="./location.js"></script>
  <script src="./disabilitaBottone.js"></script>
  <script src="./cercaNotifiche.js"></script>
</head>

<body>
    <?php require "navbarLuogo.php"; ?>
    <br>
    <div id="container">
  <h1 class="title"><?php if (isset($id)) echo "Modifica luogo"; else echo "Crea un nuovo luogo"?></h1>
  <form enctype="multipart/form-data" action="?<?php if (isset($_GET["id"])) echo "id=" . $id ?>" method="POST">
    <label for="id"></label>
    <input type="hidden" name="id" id="id" value="<?php if ($isVenueSet) {echo $venue["id"];} ?>">
    <div class="field">
      <label for="name" class="label">Nome</label>
      <div class="control">
        <input class="input" type="text" name="name" id="name" required value="<?php if ($isVenueSet) {echo $venue["name"];} ?>">
      </div>
    </div>
    <div class="field">
      <label for="description" class="label">Descrizione</label>
      <textarea class="textarea" name="description" id="description"><?php if ($isVenueSet) {echo $venue["description"];} ?></textarea>
    </div>
    <label  class="label">Seleziona Città</label>
    <div class="select">
    <select required name="country" class="countries" id="countryId">
      <option value="">Seleziona Paese</option>
      <?php
      if ($id) {
        foreach ($countries as $c) {
          $selected = $c['id'] == $country['country_id'] ? "selected" : "";
          echo "<option $selected value=\"{$c['id']}\">{$c['name']}</option>";
        }
      }
      ?>
    </select>
    </div>
    <div class="select">
    <select required name="state" class="states" id="stateId">
      <option value="">Seleziona Stato/Regione</option>
      <?php
      if ($id) {
        foreach ($states as $s) {
          $selected = $s['id'] == $state['state_id'] ? "selected" : "";
          echo "<option $selected value=\"{$s['id']}\">{$s['name']}</option>";
        }
      }
      ?>
    </select>
    </div>
    <div class="select">
    <select required name="city" class="cities" id="cityId">
      <option value="">Seleziona città</option>
      <?php
      if ($id) {
        foreach ($cities as $c) {
          $selected = $c['id'] == $venue['city_id'] ? "selected" : "";
          echo "<option $selected value=\"{$c['id']}\">{$c['name']}</option>";
        }
      }
      ?>
    </select>
    </div>
    <div class="field">
      <label for="address" class="label">Indirizzo</label>
      <div class="control">
        <input class="input" type="text" name="address" id="address" required value="<?php if ($isVenueSet) {echo $venue["address"];} ?>">
      </div>
    </div>
    <label for="img" class="label">Immagine</label>
    <ul id="fileList"><?php if ($isVenueSet) {echo "<img width=\"200\" height=\"180\" src=\"/i/$id/horizontal/$imageName\">";} ?></ul>
    <div class="file has-name">
      <label class="file-label">
        <input id="img" class="file-input" type="file" name="img" accept=".jpg, .jpeg, .jpg">
        <span class="file-cta">
          <span class="file-icon">
            <i class="fas fa-upload"></i>
          </span>
          <span class="file-label">
            Scegli un file…
          </span>
        </span>
        <span class="file-name">
          <?php if($isVenueSet && $img != null) echo $img["name"] ?>
        </span>
      </label>
    </div>
    <div class="field">
      <label for="alt" class="label">Alt</label>
      <div class="control">
        <input id="alt" class="input" type="text"  value="">
      </div>
    </div>
    <div class="buttons">
        <button class="button is-primary <?php echo $status?>" <?php echo $status?> type="submit">
    		<span class="icon is-small">
      			<i class="fas fa-check"></i>
    		</span>
    		<span><?php if (isset($id)) echo "Salva"; else echo "Crea"?></span>        
        </button>
        <a class="button" href="./visualizzaLuoghi.php">Torna ai luoghi</a>
    </div>
  </form>
    </div>
  
</body>
</html>