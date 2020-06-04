<?php
require "db.php";
session_start();
if (!$_SESSION["login"]) {
    header("location: ./login.php?error=nolog");
}

if($_SESSION["manager_id"]) {
    $dontEnableManagers = $db->getDontEnabledManagerList(0);
    foreach($dontEnableManagers as $manager) {
        if($manager["id"] == $_SESSION["manager_id"]) {
          echo "Questo utente non è ancora abilitato";
          die();
        }
    }
}



function eventRow($evento)
{
    

    /*$artist = $db->getArtistById($evento["artist_id"]);
    $venue = $db->getArtistById($evento["venue_id"]);*/
    $stato = "in attesa di approvazione";
    if ($evento['enabled']) {
        $stato = "approvato";
    }

    if ($evento['tickets_sold'] == null) {
        $evento['tickets_sold'] = 0;
    }
    if ($evento['tickets_total'] == null) {
      $evento['tickets_total'] = 0;
  }
    return "<tr>"
        . "<td headers='titolo'>{$evento['title']}</td>"
        . "<td headers='artista'>{$evento['artist_name']}</td>"
        . "<td headers='luogo'>{$evento['venue_name']}</td>"
        . "<td headers='data'>{$evento['date']}</td>"
        . "<td headers='stato'>{$stato}</td>"
        . "<td headers='biglietti'>{$evento['tickets_sold']}/{$evento['tickets_total']}</td>"
        . "<td headers='azioni'><a class='button is-info is-light' href=statisticheEvento.php?id={$evento['id']}>Dettagli</button></td>"
        . "<td headers='azioni'><a class='button is-warning is-light' href=formEventi.php?id={$evento['id']}>Modifica</td>"
        . "<td headers='azioni'><a class='button is-danger is-light' href=eliminaEvento.php?id={$evento['id']}>Elimina</td>"
        . "</tr>";
}

$events="";

if($_SESSION["is_admin"]==1) {
  $events = $db->getEventList();
}else {
  $events = $db->getEventListByManagerId($_SESSION["manager_id"]);
  
}


?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Elenco eventi</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
    <script src="./navbar.js"></script>
</head>

<body>
    <!-- la navbar -->
    <?php
    if (isset($_GET["error"])) {
        echo "<article class=\"message is-danger\">
      <div class=\"message-header\">
        <p>Danger</p>
        <button class=\"delete\" aria-label=\"delete\"></button>
      </div>
      <div class=\"message-body\">
       Non è possibile eliminare un evento per il quale sono già stati acquistati biglietti.
      </div>
    </article>";
    } ?>
<?php require "navbarEvento.php";?>
    <section class="section container">
        <a id="newElementButton" href="./formEventi.php" class="button is-primary">Aggiungi evento</a>
    </section>
    <section id="secttionTable"  class="section container">
        <div class="table-container is-bordered is-striped is-narrow is-hoverable columns is-centered">
            <table class="table is-striped">
                <thead>
                <?php
                    if($events==null) {
                      echo "<article class=\"message is-info\">
                              <div class=\"message-body\">
                                Non ci sono eventi da visualizzare per questo organizzatore.
                                </div>
                          </article>";
                    } else {
                      echo "<tr>
                      <th id='titolo' scope='col'>Titolo</th>
                      <th id='artista' scope='col'>Artista</th>
                      <th id='luogo' scope='col'>Luogo</th>
                      <th id='data' scope='col'>Data</th>
                      <th id='stato' scope='col'>Stato</th>
                      <th id='biglietti' scope='col'>Biglietti venduti/biglietti totali</th>
                      <th id='azioni' scope='col'colspan=\"3\">Azioni</th>
                  </tr>
              </thead>";
                    }
                          ?>
                <tbody>
                    <?php
                    if($events!=null) {
                      echo join("\n", array_map('eventRow', $events));
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</body>

</html>