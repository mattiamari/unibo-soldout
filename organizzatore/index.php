<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>soldOUT - Pannello organizzatore</title>
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
