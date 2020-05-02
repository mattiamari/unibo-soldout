<?php

require "../api/auth.php";
require "db.php";
session_start();
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $user = $db->checkEmail($_POST["email"]);
    $isEmailCorrect = true;
    if (!$user) {
      $isEmailCorrect = false;
      header("location: ./login.php?fail=faillog");
    }

    $isPasswordCorrect = true;
    $password = hashPassword($_POST['password'], $user['salt']);
    if ($password !== $user['password']) {
      $isPasswordCorrect = false;
      header("location: ./login.php?fail=faillog");
    }

    
    if($isEmailCorrect && $isPasswordCorrect) {
      $_SESSION["login"] = true;
      $_SESSION["manager_id"] = $user["id"];
      header("location: ./visualizzaEventi.php");
    }
}

if(isset($_GET["fail"])) {
    echo "<article class=\"message is-danger\">
    <div class=\"message-body\">
    Email o password non corretti.
    </div>
  </article>";
}

if(isset($_GET["error"])) {
    echo "<article class=\"message is-danger\">
    <div class=\"message-body\">
    Per favore effetturare il login.
    </div>
  </article>";
}

if(isset($_GET["action"])) {
    session_destroy();
    echo "<article class=\"message is-success\">
    <div class=\"message-body\">
        Logout effettuato con successo.
    </div>
  </article>";
}


?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="style.css">
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
                <a class="button" href="./registrazione.php">Non sei registrato?</a>
                <a class="button" href="./adminLogin.php">Accedi come amministratore</a>
            </div>
        </div>
      </form>
  </body>