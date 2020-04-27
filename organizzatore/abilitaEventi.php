<?php

require "db.php";
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $db->enabledEventById($id, 1);
}


function eventRow($evento)
{
    $stato = "in attesa di approvazione";
    if ($evento['enabled']) {
        $stato = "approvato";
    }
    return "<tr>"
        . "<td>{$evento['title']}</td>"
        . "<td>{$evento['date']}</td>"
        . "<td>{$stato}</td>"
        . "<td>{$evento['tickets_sold']}/{$evento['tickets_total']}</td>"
        . "<td><a class=button href=abilitaEventi.php?id={$evento['id']}>Abilita</button></td>"
        // . "<td><a class=button href=abilitaEventi.php?id={$evento['id']}>Disabilita</td>"
        . "</tr>";
}
$events = $db->getDontEnabledEventList(0);
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
    <?php require "navbar.php";
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
    <section>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Titolo</th>
                        <th>Data</th>
                        <th>Stato</th>
                        <th>Biglietti venduti/biglietti totali</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($events != null) {
                        echo join("\n", array_map('eventRow', $events));
                    } else {
                        echo 
                        "<article class=\"message is-success\">
                            <div class=\"message-body\">
                                Non ci sono eventi da abilitare.
                            </div>
                        </article>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</body>

</html>