<?php

session_start();
if (!$_SESSION["login"]) {
    header("location: ./login.php?error=nolog");
}

/* redirect alla pagina giusta*/
require "db.php";
/* controllo se sto modificando un evento o lo sto creando */
$isEventNew = false;
if (isset($_POST["id"])) {
    $eventId = $_POST["id"];
} else {
    $eventId = generateId();
    $isEventNew = true;
}


/*controllo se devo eliminare un tickeType*/
if (isset($_GET["idTicket"])) {
    if ($_GET["action"] == "deleteTicketType") {
        echo $_GET["idTicket"];
        $result = $db->deleteTicketTypeById($_GET["idTicket"]);
        if (!$result) {
            echo 'alert(\"Impossibile eliminare\")';
        }
    } else {
        /* devo modificare*/
    }
}

$title = trim($_POST["title"]);

$description = $_POST["description"];


$artist_id = NULL;
$show_category_id = $_POST["show_category"];

var_dump($_POST["date"]);
var_dump($_POST["time"]);

if (isset($_POST["date"]) && isset($_POST["time"])) {
    $date = DateTime::createFromFormat("Y-m-d", $_POST["date"]);
    $time = DateTime::createFromFormat("H:i", $_POST["time"]);
   
    $merge = new DateTime($date->format('Y-m-d') .' ' .$time->format('H:i'));
    $date = $merge->format("Y-m-d H:i:s");
}




if (isset($_POST["artist"])) {
    $artist_id = $_POST["artist"];
}

$venue_id = NULL;
if (isset($_POST["venue"])) {
    $venue_id = $_POST["venue"];
}

if (isset($_POST["max_ticket"])) {
    $max_tickets = $_POST["max_ticket"];
}

$alt = "";
if (isset($_POST["alt"])) {
    $alt = $_POST["alt"];
}
if (isset($_FILES["img"])) {
    $name = saveImg($_FILES["img"], $eventId, "horizontal");
}


if (isset($name)) {
    $db->updateImage($eventId, "show", $name, "horizontal", $alt);
}

if (isset($eventId) && !$isEventNew) {
    if (!$isEventNew) {
        $oldEvent = $db->getEventById($eventId);
        $isVenueChanged = false;
        $isDateChanged = false;
        if ((isset($_POST["venue_id"])) && $oldEvent["venue_id"] != $_POST["venue_id"]) {
            $isVenueChanged = true;
        }
        if (isset($_POST["date"])) {
            $oldDate = date_create($oldEvent["date"]);
            $oldDate = date_format($oldDate, 'Y-m-d\TH:i');
            if ($oldDate != $_POST["date"]) {
                $isDateChanged = true;
            }
        }
        if ($isVenueChanged) {
            $notificationId = generateId();
            $db->insertNewNotification($notificationId, "Il luogo dell'evento \"$title\" è cambiato.", "/show/$eventId");
            $customer = $db->getCustomerByEvent($eventId);
            $db->insertNewUserNotification($notificationId, $customer);
        }

        if ($isDateChanged) {
            $notificationId = generateId();
            $db->insertNewNotification($notificationId, "La data o l'ora dell'evento \"$title\" è cambiata.", "/show/$eventId");
            $customer = $db->getCustomerByEvent($eventId);
            if ($customer) {
                $db->insertNewUserNotification($notificationId, $customer);    
            }
        }
    }
    $db->updateEventById($eventId, $title, $date, $description, $_SESSION["manager_id"], $show_category_id, $max_tickets, 0);
} else {
    $db->insertNewShow($eventId, $title, $date, $description, $_SESSION["manager_id"], $show_category_id, $max_tickets, 0);
}

header("location: ./{$_GET["redir"]}id={$eventId}");
