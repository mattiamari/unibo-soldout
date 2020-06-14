<?php
require "db.php";

$venues = "";
if($_GET["q"]!="") {
    $venues = $db->searchVenue($_GET["q"]);
}

echo json_encode($venues);