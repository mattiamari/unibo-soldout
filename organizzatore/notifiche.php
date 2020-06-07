<?php

require "db.php";

session_start();
if (!$_SESSION["login"]) {
    header("location: ./login.php?error=nolog");
}

$manager_id = $_SESSION["manager_id"];

$notification = $db->getNotificationsByManagerId($manager_id);

$db->updateNotification(1);



function notificationRow($notification) {

    

    $event = str_replace("/show/", "", $notification["action"]);
    
    $read = "";
    if($notification["read"] == 0) {
        $read = "<span class=\"notification-bullet\"></span>";
    }

    return "<tr>"
        . "<td>$read</td>"
        .   "<td headers='contenuto'>{$notification['content']}</td>"
        . "<td headers='data'>{$notification['date']}</td>"
        . "<td headers='azione'><a class=\"button is-info is-light \" href=./statisticheEvento.php?id={$event} </a>Dettagli evento</td>"
        . "</tr>";
}

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Abilita evento</title>
    <link rel="stylesheet" href="style.css">
    <script src="./jquery-3.4.1.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="./navbar.js"></script>
    <script src="./cercaNotifiche.js"></script>
</head>

<body>
    <?php  require "navbarNotifica.php"?>

    <section class="section container">
        <div class="table-container is-bordered is-striped is-narrow is-hoverable columns is-centered">
            <table class="table is-striped">
                
                <?php
                    if ($notification != null) {
                        echo "<thead>
                        <tr>
                        <th></th>
                        <th id=\"contenuto\" scope=\"col\">Contenuto</th>
                        <th id=\"data\" scope=\"col\">Data</th>
                        <th id=\"azione\" scope=\"col\">Azioni</th>
                    </tr>
                    </thead>";
                    } else {echo "<article class=\"message is-info\">
                        <div class=\"message-body\">
                          Non ci sono notifiche da visualizzare per questo organizzatore.
                          </div>
                    </article>";}
                ?>
                
                <tbody>
                    <?php
                    if ($notification != null) {
                        echo join("\n", array_map('notificationRow', $notification));
                    }
                    
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>