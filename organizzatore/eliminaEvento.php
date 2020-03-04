<?php
require "db.php";

if(isset($_GET["id"])) {
    $id = $_GET["id"];

    // elimino i ticket type prima di eliminare l evento per una questione di vincoli non ancora gestiti
    $tickets = $db->getTicketTypesByEventId($id);
    foreach($tickets as $ticket) {
        $db->deleteTicketTypeById($ticket["id"]);
    }
    if($db->deleteEventById($_GET["id"])) {
        $error = "error";
    }
}

if(isset($error)) {
    header("location: ./visualizzaEventi.php?error=$error");
}

header("location: ./visualizzaEventi.php?");

?>