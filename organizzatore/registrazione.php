<?php 
if(isset($_GET["action"])) {
    echo "<article class=\"message is-danger\">
    <div class=\"message-body\">
    Tentativo di registrazione fallita.
    </div>
  </article>";
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrazione</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>

<body>
<div id="container">
    <h1 class="title">Registrazione</h1>
    <form action="./signup.php" method="POST">
        <div class="field">
            <label class="label" for="email">Email</label>
            <div class="control">
                <p class="control has-icons-left has-icons-right">
                    <input class="input" id="email" name="email" type="email" required>
                    <span class="icon is-small is-left">
                        <em class="fas fa-envelope"></em>
                    </span>
                </p>
            </div>
        </div>
        <div class="field">
            <label class="label" for="password">Password</label>
            <div class="control">
                <p class="control has-icons-left">
                    <input class="input" id="password" name="password" type="password" required>
                    <span class="icon is-small is-left">
                        <em class="fas fa-lock"></em>
                    </span>
                </p>
            </div>
        </div>
        <div class="field">
            <div class="control">
                <button class="button is-info">Conferma</button>
                <a class="button" href="./login.php">Sei già registrato?</a>
            </div>
        </div>
    </form>
    </div>
</body>