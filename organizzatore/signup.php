<?php

require "db.php";
require "../api/auth.php";
session_start();

$salt = generateSalt();
$password = hashPassword($_POST["password"], $salt);
$userId = generateId();

$sql = "INSERT INTO user (id, email, `password`, salt) VALUES (:id, :email, :password, :salt)";
$userQ = $pdo->prepare($sql);
$userQ->bindValue(':id', $userId);
$userQ->bindValue(':email', $_POST['email']);
$userQ->bindValue(':password', $password);
$userQ->bindValue(':salt', $salt);

$sql = "INSERT INTO manager (user_id, `enabled`) VALUES (:user_id, 1)";
$managerQ = $pdo->prepare($sql);
$managerQ->bindValue(':user_id', $userId);

if (!$userQ->execute()) {
    header("location: ./registrazione.php?action=fail");
}
if (!$managerQ->execute()) {
    die("Registrazione fallita. 1");
}

$_SESSION['login'] = true;
$_SESSION['manager_id'] = $userId;

header("location: ./visualizzaEventi.php");
