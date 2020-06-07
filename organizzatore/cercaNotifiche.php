<?php
require "db.php";

session_start();

$notification = "";
$id = "";
if(isset($_SESSION["manager_id"])) {
    $id = $_SESSION["manager_id"];
    $notification = $db->getNotificationsUnreadByManagerId($id, 0);
}


echo json_encode($notification);