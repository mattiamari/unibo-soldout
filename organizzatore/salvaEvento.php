<?php 
/* redirect alla pagina giusta*/
require "db.php";
/* controllo se sto modificando un evento o lo sto creando */
if(isset($_POST["id"])) {
    $eventId = $_POST["id"];
}else {
    $eventId = generateId();
}

/*controllo se devo eliminare un tickeType*/
if(isset($_GET["idTicket"])) {
    if($_GET["action"]=="deleteTicketType") {
        echo $_GET["idTicket"];
        $result = $db->deleteTicketTypeById($_GET["idTicket"]);
        if(!$result) {
            echo 'alert(\"Impossibile eliminare\")';
        }
    }else {
        /* devo modificare*/ 
    }
}

$title= trim($_POST["title"]);

$description = $_POST["description"];

$date = $_POST["date"];
$date = DateTime::createFromFormat("Y-m-d\TH:i",$date);
$date = $date->format("Y-m-d H:i:s");

$artist_id = NULL;
$show_category_id = $_POST["show_category"];
if(isset( $_POST["artist"])) {
    $artist_id = $_POST["artist"];
}

$venue_id = NULL;
if(isset($_POST["venue"])) {
    $venue_id = $_POST["venue"];
}

$altO = "";
if(isset($_POST["alt0"])){
    $altO = $_POST["alt0"];
}

if(isset($_POST["max_ticket"])) {
    $max_tickets = $_POST["max_ticket"];
}

$altV = "";
if(isset($_POST["altV"])){
    $altV = $_POST["altV"];
}
if(isset($_FILES["imgo"])) {
    $nameo = saveImg($_FILES["imgo"], $eventId);
}

if(isset($_FILES["imgv"])) {
    $namev = saveImg($_FILES["imgv"], $eventId);
}

if(isset($nameo)) {
    $db->updateImage($eventId, "show", $nameo, "horizontal", $altO);
}
if(isset($namev)) {
    $db->updateImage($eventId, "show", $namev, "vertical", $altV);
}

if(isset($eventId)) {
     $oldEvent = $db->getEventById($eventId);
     $isVenueChanged = false;
     $isDateChanged = false;
     if((isset($_POST["venue_id"])) && $oldEvent["venue_id"]!=$_POST["venue_id"]){
        $isVenueChanged = true;
     }
     if(isset($_POST["date"])){
        $oldDate = date_create($oldEvent["date"]);
        $oldDate = date_format($oldDate, 'Y-m-d\TH:i');
        if($oldDate != $_POST["date"]) {
            $isDateChanged = true;
        }
     }
     if($isVenueChanged) {
        $notificationId = generateId();
        $db->insertNewNotification($notificationId,"Il luogo dell'evento \"$title\" è cambiato.", "/show/$eventId");
        $customer = $db->getCustomerByEvent($eventId);
        $db->insertNewUserNotification($notificationId, $customer);
     }

     if($isDateChanged) {
        $notificationId = generateId();
        $db->insertNewNotification($notificationId,"La data o l'ora dell'evento \"$title\" è cambiata.", "/show/$eventId");
        $customer = $db->getCustomerByEvent($eventId);
        $db->insertNewUserNotification($notificationId, $customer);
     }
     
     $db->updateEventById($eventId, $title, $date, $description, $show_category_id, $max_tickets, $artist_id, $venue_id);
 }else {
     $db->insertNewShow($id, $title, $date, $description, $show_category, $venue_id, $max_tickets, $artist_id, $enabled);
}

header("location: ./{$_GET["redir"]}");
function saveImg($img, $eventId) {
    if ($img['error']) {
        return null;
    }

    $imgName = urlencode($img["name"]);
    $destPath = str_replace("/", DIRECTORY_SEPARATOR, "../app/i/" . $eventId . "/");
    
    if (!file_exists($destPath)) {
        mkdir($destPath);
    }

    move_uploaded_file($img["tmp_name"], $destPath . $imgName);

    return $imgName;
}