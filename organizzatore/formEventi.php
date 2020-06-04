<?php
require "db.php";

session_start();
if(!$_SESSION["login"]) {
  header("location: ./login.php?error=nolog");
}

$ticketTypes = [];

$isEventSet = false;
$isVenueSet = false;
$isArtistSet = false;
$id = "";

/* quando modifico un evento per l alt orizzontale e verticale sarà necessario fare una query
  la tabella sarà questa
  idShow
  type: horizontal | vertical
  name: nome file
  alt
  [
    [type => "horizontal", name => "", alt => ""],
    [type => "vertical", name => "", alt => ""],
  ] */


/* per inserire le categorie bisogna fare una query al db per prendere tutte le categorie(sono statiche non cambiano)
    Si tratta di una section con piu option e quando si fa submit viene inviato il valore selezionato */
if (isset($_GET["id"])) {
  $id = $_GET["id"];
  $isEventSet = true;
  $img = $db->getImageById($id);
  $ticketTypes = $db->getTicketTypesByEventId($id);
  $event = $db->getEventById($id);
  $artist = $db->getArtistById($event["artist_id"]);
  if ($artist) {
    $isArtistSet = true;
    $image = $db->getImageById($id);

	$imageName = $image["name"];
  }
  $venue = $db->getVenueById($event["venue_id"]);
  if ($venue) {
    $isVenueSet = true;
  }
  $dateAndTime = date_create($event["date"]);
  $date = date_format($dateAndTime, 'Y-m-d');
  $time = date_format($dateAndTime,"H:i");
  $isEventSet = true;
}

$showCategory = $db->getShowCategoryList();




function ticketTypeRow($ticketType)
{
  return
    "<tr>"
    . "<td headers='nome'>{$ticketType['name']}</td>"
    . "<td headers='prezzo'>{$ticketType['price']}</td>"
    . "<td headers='biglietti'>{$ticketType['max_tickets']}</td>"
    . "<td headers='azioni'><button class=\"button is-warning is-light\" type=\"submit\" formaction=\"salvaEvento.php?redir=popupBiglietto.php%3FidTicket={$ticketType['id']}%26\">Modifica</td>"
    . "<td headers='azioni'><button class=\"button is-danger is-light\" type=\"submit\" formaction=\"salvaEvento.php?id={$ticketType['show_id']}&idTicket={$ticketType['id']}&action=deleteTicketType&redir=formEventi.php%3F\">Elimina</td>"
    . "</tr>";
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Creazione Eventi</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
  <script src="app.js"></script>
  <script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
  <script src="./navbar.js"></script>

</head>

<body>
  <?php require "navbarEvento.php"; ?>
  <br>
    <div id="container">
        <h1 class="title"><?php if ($isEventSet) echo "Modifica evento"; else echo "Crea un nuovo evento"?></h1>
        <form enctype="multipart/form-data" action="salvaEvento.php?redir=formEventi.php%3F" method="POST">
    <?php
    if ($id) {
      echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
    }
    ?>
    <div class="field">
        <label for="title" class="label">Titolo</label>
        <div class="control">
            <input id="title" class="input" type="text" name="title" required value="<?php if ($isEventSet) {
                                                                                    echo $event["title"];
                                                                                  } ?>">
        </div>
    </div>
    <div class="field">
        <label for="description" class="label">Descrizione</label>
        <div class="control">
            <textarea class="textarea" name="description" require id="description"><?php if ($isEventSet) {
                                                                                  echo $event["description"];
                                                                                } ?></textarea>
        </div>
    </div>
    <label for="show_category" class="label">Categoria Evento</label>
    <div class="select">
        <select required name="show_category" id="">
        <?php
        if ($showCategory != NULL) {
          foreach ($showCategory as $category) {
            echo "<option value={$category['id']}>{$category['name']}</option>";
          }
        }
        ?>
        </select>
    </div>

    <div class="field">
        <label for="date" class="label">Data</label>
        <input id="date" class="button" type="date" name="date" required value=<?php if ($isEventSet) {
                                                                          echo $date;
                                                                        } ?>>
    </div>
    <div class="field">
        <label for="time" class="label">Ora</label>
        <input id="time" class="button" type="time" name="time" required value=<?php if ($isEventSet) {
                                                                          echo $time;
                                                                        } ?>>
    </div>

    <div class="field">
        <label class="label">Artista</label>
        <label for="artist"></label>
        <label for="buttonArtist"><?php if ($isArtistSet) echo $artist["name"] ?></label>
        <button id="buttonArtist" type="submit" formaction="./salvaEvento.php?redir=selezionaArtista.php%3F" class="button">Scegli</button>
    </div>

    <div class="field">
        <label class="label">Luogo</label>
        <label for="venue"></label>
        <label for="buttonVenue"><?php if ($isVenueSet) echo $venue["name"] ?></label>
        <button id="buttonVenue" type="submit" formaction="./salvaEvento.php?redir=popupLuogo.php%3F" class="button">Scegli</button>
    </div>

    <label for="img" class="label">Immagine</label>
    <ul id="fileList"><?php if ($isEventSet) {echo "<img width=\"200\" height=\"180\" src=\"/i/$id/horizontal/$imageName\">";} ?></ul>
    <div class="file has-name">
        <label class="file-label">
            <input id="img" class="file-input" type="file" name="img"  accept=".jpg, .jpeg, .jpg">
            <span class="file-cta">
                <span class="file-icon">
                    <i class="fas fa-upload"></i>
                </span>
                <span class="file-label">
                Scegli un file…
                </span>
            </span>
            <span class="file-name">
                <?php if($isEventSet && $img != null) echo $img["name"] ?>
            </span>
        </label>
    </div>
    <div class="field">
        <label for="alt" class="label">Alt</label>
        <div class="control">
            <input id="alt" class="input" type="text" value="">
        </div>
    </div>
    <div class="field">
        <div class="field">
            <label for="max_ticket" class="label">Massimo numero di biglietti per ordine</label>
            <div class="control">
                <input id="max_ticket" class="input" type="number" min="1" name="max_ticket" required value="<?php if ($isEventSet) {
                        echo $event["max_tickets_per_order"];} ?>">
            </div>
        </div>
    </div>
    <div>
        <label class="label">Biglietti</label>
        <button type="submit" formaction="salvaEvento.php?redir=popupBiglietto.php%3F" class="button">Nuova tipologia</button>
    </div>
    <br>
    <div class="table is-bordered is-striped is-narrow is-hoverable columns is-centered">
        <table class="table-container">
            <thead>
                <?php 
                    if ($ticketTypes!=null) {
                        echo
                            "<th id='nome' scope='col'>Nome</th>
                            <th id='prezzo' scope='col'>Prezzo</th>
                            <th id='biglietti' scope='col'>Biglietti totali</th>
                            <th id='azioni' scope='col' colspan=\"2\">Azioni</th>";
                    }
                ?>
                
            </thead>
            <tbody>
                <?php
                if ($isEventSet) {
                    echo join("\n", array_map('ticketTypeRow', $ticketTypes));
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="buttons">
        <button class="button is-primary" type="submit">
            <span class="icon is-small">
      			<i class="fas fa-check"></i>
    		</span>
            <span>Salva</span>
         </button>
        <a class="button" href="./visualizzaEventi.php">Torna agli eventi</a>
    </div>
</form>
</div>

</body>

</html>