<?php


session_start();
if (!$_SESSION["login"]) {
    header("location: ./login.php?error=nolog");
}

require "db.php";
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if(isset($_GET["toggle_admin"]) && $_GET["toggle_admin"]==1) {
      $db->enabledAdminById($id, 1);
    } else {
      $db->enabledManagerById($id, 1);
    }
    
}


function managerDontEnabledRow($organizzatore)
{

    return "<tr>"
        . "<td headers='id'>{$organizzatore['id']}</td>"
        . "<td headers='email'>{$organizzatore['email']}</td>"
        . "<td headers='azioni'><a class=\"button is-primary is-outlined\" href=abilitaOrganizzatori.php?id={$organizzatore['id']}>Abilita</button></td>"
        // . "<td><a class=button href=abilitaEventi.php?id={$evento['id']}>Disabilita</td>"
        . "</tr>";
}

function managerEnabledRow($organizzatore)
{
  if($organizzatore['id']==$_SESSION["manager_id"]) {
    return null;
  }
    $stato = $organizzatore["is_admin"] ? "Disabilita" : "Rendi amministratore";
    return "<tr>"
        . "<td headers='id'>{$organizzatore['id']}</td>"
        . "<td headers='email'>{$organizzatore['email']}</td>"
        . "<td headers='azioni'><a class=\"button is-primary is-outlined\" href=abilitaOrganizzatori.php?id={$organizzatore['id']}&toggle_admin=1>{$stato}</button></td>"
        . "</tr>";
}
$managersDontEnabled = $db->getDontEnabledManagerList(0);
$managersEnabled = $db->getDontEnabledManagerList(1);


?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Abilita organizzatore</title>
    <link rel="stylesheet" href="style.css">
    <script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
    <script src="./navbar.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="./cercaNotifiche.js"></script>
</head>

<body>
    <?php /*
    if (isset($_GET["error"])) {
        echo "<article class=\"message is-info\">
      <div class=\"message-header\">
        <p>Danger</p>
        <button class=\"delete\" aria-label=\"delete\"></button>
      </div>
      <div class=\"message-body\">
       Non è possibile eliminare un evento per il quale sono già stati acquistati biglietti.
      </div>
    </article>";
    } */?>

<section class="hero is-primary">
  <!-- Hero head: will stick at the top -->
  <div class="hero-head">
    <header class="navbar">
      <div class="container">
        <div class="navbar-brand">
        <img width="200" height="200" id="logo" src="./logo.svg" alt="Logo">
          <span id="navbarMenuBurger" class="navbar-burger burger" data-target="navbarMenuHeroC">
            <span></span>
            <span></span>
            <span></span>
          </span>
        </div>
        <div id="navbarMenuHeroC" class="navbar-menu">
          <div class="navbar-end">
          <a href="./notifiche.php" class="navbar-item">
              Notifiche
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
        Abilita organizzatori
      </h1>
      <h2 class="subtitle">
        Elenco degli organizzatori che possono essere abilitati o resi amministratori.
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
          <?php if($_SESSION["is_admin"]==1) echo "<li><a href=\"./abilitaEventi.php\">Abilita eventi</a></li>
          <li class=\"is-active\"><a href=\"./abilitaOrganizzatori.php\">Abilita organizzatori</a></li>" ?>
        </ul>
      </div>
    </nav>
  </div>
</section>
<br>
<br>
    <section class="section container">
      <div class="columns is-centered">
        <h1 class="title">Da abilitare</h1>
      </div>
      <br>
        <div class="table is-bordered is-striped is-narrow is-hoverable columns is-centered">
            <table class="table is-striped">
                <thead>
                    <?php 
                        if($managersDontEnabled!=null) {
                            echo 
                            "<tr>
                                <th id='id' scope='col'>Id</th>
                                <th id='email' scope='col'>Email</th>
                                <th id='azioni' scope='col' colspan=\"2\">Azione</th>
                            </tr>";
                        } else {
                            echo 
                                "<article class=\"message is-info\">
                                <div class=\"message-body\">
                                    Non ci sono organizzatori da abilitare.
                                    </div>
                            </article>";
                        }
                    ?>
                    
                </thead>
                <tbody>
                    <?php
                    if ($managersDontEnabled != null) {
                        echo join("\n", array_map('managerDontEnabledRow', $managersDontEnabled));
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
<br><br>
    <section class="section container">
      <div class="columns is-centered">
        <h1 class="title">Organizzatori</h1>
      </div>
      <br>
        <div class="table is-bordered is-striped is-narrow is-hoverable columns is-centered">
            <table class="table is-striped">
                <thead>
                    <?php 
                        if($managersEnabled!=null) {
                            echo 
                            "<tr>
                                <th id='id' scope='col'>Id</th>
                                <th id='email' scope='col'>Email</th>
                                <th id='azioni' scope='col' colspan=\"2\">Azione</th>
                            </tr>";
                        } else {
                            echo 
                                "<article class=\"message is-info\">
                                <div class=\"message-body\">
                                    Non ci sono organizzatori da poter rendere admin.
                                    </div>
                            </article>";
                        }
                    ?>
                    
                </thead>
                <tbody>
                    <?php
                    if ($managersEnabled != null) {
                        echo join("\n", array_map('managerEnabledRow', $managersEnabled));
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</body>

</html>