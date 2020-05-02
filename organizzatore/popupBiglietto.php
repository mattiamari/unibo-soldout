<?php
require 'db.php';

session_start();
if(!$_SESSION["login"]) {
  header("location: ./login.php?error=nolog");
}

/* query per la modifica di un biglietto associato a un id e un idticket
    query per l'aggiunta di una nuova tipologia di biglietto
    RICORDA: almeno l id dell'evento deve essere passato; la presenza del solo id evento
    indica che stiamo aggiungendo una nuova tipologia se invece è presente anche l id ticket 
    la stiamo modificando */

$isIdSet = false;

if (isset($_GET['id'])) {
  $id = $_GET["id"];
  $event = $db->getEventById($id);
} else {
  die("nessun evento riferito alla tipologia di biglietto");
}

$isTicketTypeSet = false;

if (isset($_GET["idTicket"])) {
  $idTicket = $_GET["idTicket"];
  $ticketType = $db->getTicketTypeByTicketId($idTicket);
  $isTicketTypeSet = true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($_POST["idTicket"] != NULL) {
    $db->updateTicketTypeById($_POST["idTicket"], $_POST["name"], $_POST["description"], $_POST["price"], $_POST["max_tickets"]);
  } else {
    $db->insertNewTicketType(generateId(), $_POST["id"], $_POST["name"], $_POST["description"], $_POST["price"], $_POST["max_tickets"]);
  }
}


?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>

<body>
  <?php if ($id) : ?>
    <h1 class="title">Nuova tipologia</h1>
    <form action="./popupBiglietto.php?id=<?php echo $id ?>" method="POST">
      <label for="id"></label>
      <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
      <label for="idTicket"></label>
      <input type="hidden" name="idTicket" id="idTicket" value="<?php if (isset($idTicket)) echo $idTicket; ?>">
      <div class="field">
        <label for="name" class="label">Nome</label>
        <div class="control">
          <input name="name" id="name" class="input" type="text" required value="<?php if ($isTicketTypeSet) echo $ticketType['name']; ?>">
        </div>
        <div class="field">
          <label for="description" class="label">Descrizione</label>
          <div class="control">
            <input name="description" id="description" class="input" type="text" required value="<?php if ($isTicketTypeSet) echo $ticketType['description']; ?>">
          </div>
        </div>
        <div class="field">
          <label for="price" class="label">Prezzo</label>
          <div class="control">
            <input name="price" id="price" class="input" required value="<?php if ($isTicketTypeSet) echo $ticketType['price']; ?>">
          </div>
        </div>
        <div class="field">
          <label for="max_tickets" class="label">Totale biglietti</label>
          <div class="control">
            <input name="max_tickets" id="max_tickets" class="input" type="number" required value="<?php if ($isTicketTypeSet) echo $ticketType['max_tickets']; ?>">
          </div>
        </div>
        <button class="button" type="submit">Salva</button>
        <a class="button" href="formEventi.php?id=<?php echo $id ?>">Torna indietro</a>
    </form>
  <?php else : ?>
    <p>Nessun evento selezionato</p>
  <?php endif; ?>
</body>

</html>