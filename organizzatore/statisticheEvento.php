<?php 

    session_start();
    if(!$_SESSION["login"]) {
    header("location: ./login.php?error=nolog");
  }

  require "db.php";
  
  $id = false;

  if (isset($_GET['id'])) {
    $id = $_GET["id"];
    $evento = $db->getEventById($id);
    $ticketSoldByCategories = $db->getQtyTicketSoldByCategory($id);
  }

  $profit = $db->getProfitByEventId($id);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Statistiche evento</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="./jquery-3.4.1.min.js"></script>
    <script src="./cercaNotifiche.js"></script>
    <script src="./navbar.js"></script>
</head>
<!-- query da fare
    1 numero di biglietti venduti per categoria (nome,numero biglietti venduti,ultima riga biglietti venduti totali)
    2 incasso di un evento
    3 biglietti acquistati ma non entrati
    4 optional gente che entra con orario
     -->

<body>
    <?php if ($id):
        require "navbarEvento.php";?>
        <section id="secttionTable" class="section container">
            <h1 class="title">Statistiche dell'evento <?php  echo $evento["title"]; ?> </h1>
            <section id="sectionTable"  class="section container">
                <div class="table-container is-bordered is-striped is-narrow is-hoverable columns is-centered">
                    <table class="table is-striped">
                        <thead>
                            <?php
                                if($ticketSoldByCategories!= null) {
                                    echo 
                                    "<tr>
                                    <th id='nome' scope='col'>Nome tipologia</th>
                                    <th id='biglietti' scope='col'>Biglietti venduti/Totale</th>
                                    <th id='profitto' scope='col'>Profitto</th>
                                    </tr>";
                                }  
                            ?>
                        </thead>
                        <tbody>
                            <?php 
                                if($ticketSoldByCategories!= null) {
                                    $total = 0;
                                    foreach($ticketSoldByCategories as $ticketSoldByCategory) {
                                        $total += $ticketSoldByCategory['profit'];
                                        echo "<tr>
                                        <td headers='nome'>{$ticketSoldByCategory['name']}</td>
                                        <td headers='biglietti'>{$ticketSoldByCategory['total_sold']}/{$ticketSoldByCategory['max_tickets']}</td>
                                        <td headers='profitto'>{$ticketSoldByCategory['profit']}€</td>
                                        </tr>";
                                    }
                                    echo "<tr><td></td><td></td><td><p>Totale: {$total}€</p></td></tr>";
                                } else {
                                    echo "<article class=\"message is-info\">
                                    <div class=\"message-body\">
                                    Non sono ancora stati venduti biglietti per questo evento.
                                    </div>
                                    </article>";
                                }
                            ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <p>Nessun evento selezionato</p>
                    <?php endif; ?>
                </div>
            </section>
        </section>
</body>
</html>