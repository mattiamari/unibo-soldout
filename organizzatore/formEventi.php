<?php 
  require "db.php";

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
  if(isset($_GET["id"])) {
    $id = $_GET["id"];
    $ticketTypes = $db->getTicketTypesByEventId($id);
    $event = $db->getEventById($id);
    $artist = $db->getArtistById($event["artist_id"]);
    if($artist) {
      $isArtistSet = true;
    }
    $venue = $db->getVenueById($event["venue_id"]);
    if($venue) {
      $isVenueSet = true;
    }
    $date = date_create($event["date"]);
    $date = date_format($date, 'Y-m-d\TH:i');
    $showCategory = $db->getShowCategoryList();
    $isEventSet = true;
  }



  
  function ticketTypeRow($ticketType) {
    return 
      "<tr>"
        ."<td>{$ticketType['name']}</td>"
        ."<td>{$ticketType['price']}</td>"
        ."<td>{$ticketType['max_tickets']}</td>"
        ."<td><button type=\"submit\" formaction=\"salvaEvento.php?redir=popupBiglietto.php%3Fid={$ticketType['show_id']}%26idTicket={$ticketType['id']}\">Modifica</td>"
        ."<td><button type=\"submit\" formaction=\"salvaEvento.php?id={$ticketType['show_id']}&idTicket={$ticketType['id']}&action=deleteTicketType&redir=formEventi.php%3Fid={$ticketType['show_id']}\">Elimina</td>"
      ."</tr>";
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
    <script src="./app.js"></script>
    
  </head>
  <body>
    <h1 class="title">Nuovo evento</h1>
    <form enctype="multipart/form-data" action="salvaEvento.php?redir=formEventi.php%3Fid=<?php echo $id?>" method="POST">
      <?php 
        if($id) {
          echo "<input type=\"hidden\" name=\"id\" value=\"$id\"";
        } 
      ?>
      <div class="field">
        <label for="title" class="label">Titolo</label>
        <div class="control">
          <input id="title" class="input" type="text" name="title" required value="<?php if($isEventSet){echo $event["title"];} ?>">
        </div>
      </div>
      <div class="field">
        <label for="description" class="label">Descrizione</label>
        <div class="control">
          <textarea name="description" require id="description" ><?php if($isEventSet){echo $event["description"];} ?></textarea>
        </div>
      </div>
      <label for="show_category" class="label">Categoria Evento</label>
      <select required name="show_category" id="">
        <?php 
          foreach($showCategory as $category) {
            echo "<option value={$category['id']}>{$category['name']}</option>";
          }
        ?>
      </select>
      <div class="field">
        <label for="date" class="label">Data</label>
        <input id="date" type="datetime-local" name="date" required value=<?php if($isEventSet) { echo $date;} ?>> 
      </div>
      <div class="field">
      <h2>Artista</h2>
      <label for="artist"></label>
      <label for="buttonArtist"><?php if($isArtistSet) echo $artist["name"]?></label>
        <button id="buttonArtist" type="submit" formaction="./salvaEvento.php?redir=selezionaArtista.php%3Fid=<?php echo $id ?>" class="button">Scegli</a>
      </div>

      <div class="field">
      <h2>Luogo</h2>
      <label for="venue"></label>
      <label for="buttonVenue"><?php if($isVenueSet) echo $venue["name"]?></label>
        <button id="buttonVenue" type="submit" formaction="./salvaEvento.php?redir=popupLuogo.php&<?php echo $id ?> " class="button">Scegli</a>
      </div>
      <label for="imgV"class="label">Immagine verticale</label>
      <div class="file has-name">
        <label class="file-label">
        <input id="imgV" class="file-input" type="file" name="imgV" accept=".jpg, .jpeg, .jpg" name="img1">  
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
        <input id="imgO" class="file-input" type="file" name="imgO" accept=".jpg, .jpeg, .jpg" name="img2">
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
      <div class="field">
      <div class="field">
        <label for="max_ticket" class="label">Massimo numero di biglietti per ordine</label>
        <div class="control">
          <input id="max_ticket" class="input" type="number" min="1" name="max_ticket" required value="<?php if($isEventSet){echo $event["max_tickets_per_order"];} ?>">
        </div>
      </div>
        <label class="label">Biglietti</label>
        <table class="table">
            <thead>
            <button type="submit" formaction="salvaEvento.php?redir=popupBiglietto.php%3Fid=<?php echo $id ?> " class="button">Nuova tipologia</a>
            </thead>
            <th>Nome</th>
            <th>Prezzo</th>
            <th>Biglietti totali</th>
            <tbody>
                <?php 
                if ($isEventSet) {
                  echo join("\n", array_map('ticketTypeRow', $ticketTypes));
                }
                ?>
            </tbody>
        </table>
      </div>
      <button type="submit">Salva</button>
    </form>
  </body>
</html>