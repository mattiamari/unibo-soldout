<?php

session_start();
if(!$_SESSION["login"]) {
  header("location: ./login.php?error=nolog");
} else {
    header("location: ./visualizzaArtisti.php");
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>soldOUT - Pannello organizzatore</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if ($_SESSION['login']): ?>
    <nav>
        <ul>
            <li><a href="visualizzaEventi.php">Eventi</a></li>
        </ul>
    </nav>
    <?php else: ?>
    <a href="login.php">Accedi</a> o <a href="registrazione.php">Registrati</a>
    <?php endif; ?>
</body>
</html>
