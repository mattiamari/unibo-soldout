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
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>

<body>
    <h1 class="title">Registrazione</h1>
    <form action="./signup.php" method="POST">
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
                <button class="button">Conferma</button>
                <a class="button" href="./login.php">Sei già registrato?</a>
            </div>
        </div>
    </form>
</body>