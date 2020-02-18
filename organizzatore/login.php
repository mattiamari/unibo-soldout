<?php

require "../api/auth.php";
require "db.php";
session_start();
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $user = $db->checkEmail($_POST["email"]);
    if (!$user) {
        die("Login fallito");
    }

    $password = hashPassword($_POST['password'], $user['salt']);
    if ($password !== $user['password']) {
        die("Login fallito");
    }

    $_SESSION["login"] = true;
    $_SESSION["manager_id"] = $user["id"];
        header("location: ./visualizzaEventi.php");
}



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
        <h1 class="title">Login</h1>
      <form action="./login.php" method="POST">
        <div class="field">
            <label class="label" for="email">Email</label>
            <div class="control">
                <input class="input" id="email" name="email" type="email">
            </div>
        </div>
        <div class="field">
            <label class="label" for="password">Password</label>
            <div class="control">
                <input class="input" id="password" name="password" type="password">
            </div>
        </div>
        <div class="field">
            <div class="control">
                <button class="button">Accedi</button>
            </div>
        </div>
      </form>
  </body>