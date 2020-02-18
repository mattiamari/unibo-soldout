<?php 
  require "db.php";
  
  $id = false;

  if (isset($_GET['id'])) {
    $id = $_GET["id"];
    $ticketSoldByCategories = $db->getQtyTicketSoldByCategory($id);
  }

  $profit = $db->getProfitByEventId($id);
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
<!-- query da fare
    1 numero di biglietti venduti per categoria (nome,numero biglietti venduti,ultima riga biglietti venduti totali)
    2 incasso di un evento
    3 biglietti acquistati ma non entrati
    4 optional gente che entra con orario
     -->

<body>
  <?php if ($id): ?>
  <table class="table">
    <thead>
      <th>Nome tipologia</th>
      <th>Biglietti venduti/Totale</th>
      <th>Profitto</th>
    </thead>
    <tbody>
     <?php 
        foreach($ticketSoldByCategories as $ticketSoldByCategory) {
          echo "<tr>
                  <td>{$ticketSoldByCategory['name']}</td>
                  <td>{$ticketSoldByCategory['total_sold']}/{$ticketSoldByCategory['max_tickets']}</td>
                  <td>{$ticketSoldByCategory['profit']}€</td>
                </tr>";
        }
        echo "<tr>
        <td></td>
        <td></td>
        <td>{$profit}€</td>
      </tr>";
     ?>
    </tbody>
  </table>
  <?php else: ?>
    <p>Nessun evento selezionato</p>
    <?php endif; ?>

    <a class="button" href="./visualizzaEventi.php">Torna agli eventi</a>
</body>