<?php


session_start();
if (!$_SESSION["login"]) {
    header("location: ./login.php?error=nolog");
}

require "db.php";
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $db->enabledEventById($id, 1);
}


function eventRow($evento)
{
    if($evento['tickets_sold']=="") {
        $evento['tickets_sold']=0;
    }
    if($evento['tickets_total']=="") {
        $evento['tickets_total']=0;
    }
    return "<tr>"
        . "<td>{$evento['title']}</td>"
        . "<td>{$evento['date']}</td>"
        . "<td>{$evento['tickets_sold']}/{$evento['tickets_total']}</td>"
        . "<td><a class=\"button is-primary\" href=abilitaEventi.php?id={$evento['id']}>Abilita</button></td>"
        . "</tr>";
}
$events = $db->getDontEnabledEventList(0);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="style.css">
    <script src="http://code.jquery.com/jquery-1.6.4.min.js" type="text/javascript"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="./navbar.js" type="text/javascript"></script>
</head>

<body>
    <?php 
    if (isset($_GET["error"])) {
        echo "<article class=\"message is-danger\">
      <div class=\"message-header\">
        <p>Danger</p>
        <button class=\"delete\" aria-label=\"delete\"></button>
      </div>
      <div class=\"message-body\">
       Non è possibile eliminare un evento per il quale sono già stati acquistati biglietti.
      </div>
    </article>";
    } ?>

<section class="hero is-success">
  <!-- Hero head: will stick at the top -->
  <div class="hero-head">
    <header class="navbar">
      <div class="container">
        <div class="navbar-brand">
          <a class="navbar-item">
              <!-- Inserire immagine soldout -->
              <h1>SOLDOUT</h1>
            <!--<img src="https://bulma.io/images/bulma-type-white.png" alt="Logo">-->
          </a>
          <span id="navbarMenuBurger" class="navbar-burger burger" data-target="navbarMenuHeroC">
            <span></span>
            <span></span>
            <span></span>
          </span>
        </div>
        <div id="navbarMenuHeroC" class="navbar-menu">
          <div class="navbar-end">
            <a href="./login.php" class="navbar-item is-active">
                Login
            </a>
            <a href="./login.php?action=logout" class="navbar-item">
              Logout
            </a>
          </div>
        </div>
      </div>
    </header>
  </div>

  <!-- Hero content: will be in the middle -->
  <div class="hero-body">
    <div class="container has-text-centered">
      <h1 class="title">
        Abilita eventi
      </h1>
      <h2 class="subtitle">
        Elenco degli eventi che necessitano ancora di un'abilitazione da parte dell'amministratore.
      </h2>
    </div>
  </div>

  <!-- Hero footer: will stick at the bottom -->
  <div class="hero-foot">
    <nav class="tabs is-boxed">
      <div class="container">
        <ul>
          <li><a href="./visualizzaEventi.php">Eventi</a></li>
          <li><a href="./visualizzaArtisti.php">Artisti</a></li>
          <li><a href="./visualizzaLuoghi.php">Luoghi</a></li>
          <?php if($_SESSION["is_admin"]==1) echo "<li class=\"is-active\"><a href=\"./abilitaEventi.php\">Abilita eventi</a></li>
          <li><a href=\"./abilitaOrganizzatori.php\">Abilita organizzatori</a></li>" ?>
        </ul>
      </div>
    </nav>
  </div>
</section>
<br>
<br>

    <section>
        <div class="table is-bordered is-striped is-narrow is-hoverable columns is-centered">
            <table class="table is-striped">
                <thead>
                    <?php 
                    if ($events != null) {
                        echo 
                            "<tr>
                                <th>Titolo</th>
                                <th>Data</th>
                                <th>Biglietti venduti/biglietti totali</th>
                                <th>Azioni</th>
                            </tr>";
                    } else {
                        echo "<article class=\"message is-info\">
                              <div class=\"message-body\">
                                Tutti gli eventi disponibili sono già stati abilitati.
                                </div>
                          </article>";
                    }
                    ?>
                </thead>
                <tbody>
                    <?php
                    if ($events != null) {
                        echo join("\n", array_map('eventRow', $events));
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</body>

</html>