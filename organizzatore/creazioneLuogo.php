<?php 
  require "db.php";

  if($_SERVER["REQUEST_METHOD"]=="POST") {
    if(isset($_GET["id"])) {
      $db->updateVenueById($_POST["id"], $_POST["name"], $_POST["description"], $_POST["address"], $_POST["city"]);
    }else{
      $db->insertNewVenue(generateId(), $_POST["name"], $_POST["description"], $_POST["address"], $_POST["city"]);
    }
  }

  $isVenueSet = false;
  if(isset($_GET["id"])) {
    $id = $_GET["id"];
    $isVenueSet = true;
    $venue = $db->getVenueById($id);
    if(!$venue) {
      die("Selezionato un luogo non valido");
    }
    $state = $db->getStateByCityId($venue["city_id"]);
    $country = $db->getCountryByStateId($state["state_id"]);
    $countries = $db->getCountries();
    $states = $db->getStates($country["country_id"]);
    $cities = $db->getCities($state["state_id"]);
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
  <h1 class="title">Inserisci nuovo luogo</h1>
  <form action="" method="POST">
    <label for="id"></label>
    <input type="hidden" name="id" id="id" value="<?php if($isVenueSet) { echo $venue["id"];}?>">
    <div class="field">
      <label for="name" class="label">Nome</label>
      <div class="control">
        <input type="text" name="name" id="name" required value="<?php if($isVenueSet) { echo $venue["name"];} ?>">
      </div>
    </div>
    <div class="field">
      <label for="description" class="label">Descrizione</label>
      <textarea name="description" id="description"><?php if($isVenueSet) { echo $venue["description"];} ?></textarea>
    </div>
    <select name="country" class="countries" id="countryId">
      <option value="">Seleziona Paese</option>
      <?php
      if($id) {
        foreach($countries as $c) {
          $selected = $c['id'] == $country['country_id'] ? "selected" : "";
          echo "<option $selected value=\"{$c['id']}\">{$c['name']}</option>";
        }
      }
      ?>
  </select>
  <select name="state" class="states" id="stateId">
       <option value="">Seleziona Stato/Regione</option>
       <?php
       if($id) {
         foreach($states as $s) {
           $selected = $s['id'] == $state['state_id'] ? "selected" : "";
           echo "<option $selected value=\"{$s['id']}\">{$s['name']}</option>";
         }
       }
      ?>
  </select>
  <select name="city" class="cities" id="cityId">
     <option value="">Seleziona città</option>
       <?php 
       if($id){
        foreach($cities as $c) {
          $selected = $c['id'] == $venue['city_id'] ? "selected" : "";
          echo "<option $selected value=\"{$c['id']}\">{$c['name']}</option>";
        }
       }
      ?>
  </select>
    <div class="field">
      <label for="address" class="label">Indirizzo</label>
      <div class="control">
        <input type="text" name="address" id="address" required value="<?php if($isVenueSet) { echo $venue["address"];} ?>">
      </div>
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
  <a href="./visualizzaLuoghi.php">Torna ai luoghi</a>
  <script defer src="./jquery-3.4.1.min.js"></script>
  <script defer src="./location.js"></script>
</body>