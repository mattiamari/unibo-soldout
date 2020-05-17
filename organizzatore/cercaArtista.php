<?php
require "db.php";

$artists = "";
if($_GET["q"]!="") {
    $artists = $db->searchArtist($_GET["q"]);
}

echo json_encode($artists);