<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Navbar</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="./navbar.js"></script>
  </head>
  <div class="navbar-item has-dropdown is-hoverable">
  <a class="navbar-link is-arrowless">
    Menù
  </a>
  <div class="navbar-dropdown">
    <a href="./login.php" class="navbar-item">
      Login
    </a>
    <a href="./visualizzaEventi.php" class="navbar-item">
      Eventi
    </a>
    <a href="./visualizzaArtisti.php" class="navbar-item">
      Artisti
    </a>
    <a href="./visualizzaLuoghi.php" class="navbar-item">
      Luoghi
    </a>
    <a href="./login.php?action=logout" class="navbar-item">
      Logout
    </a>
  </div>
</div>