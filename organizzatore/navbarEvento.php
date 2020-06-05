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
        Eventi
      </h1>
      <h2 class="subtitle">
        Elenco degli eventi con informazioni sulla data, sullo stato,sui biglietti venduti
        e possibili azioni da compiere.
      </h2>
    </div>
  </div>

  <!-- Hero footer: will stick at the bottom -->
  <div class="hero-foot">
    <nav class="tabs is-boxed">
      <div class="container">
        <ul>
          <li class="is-active"><a href="./visualizzaEventi.php">Eventi</a></li>
          <li><a href="./visualizzaArtisti.php">Artisti</a></li>
          <li><a href="./visualizzaLuoghi.php">Luoghi</a></li>
          <?php if($_SESSION["is_admin"]==1) echo "<li><a href=\"./abilitaEventi.php\">Abilita eventi</a></li>
          <li><a href=\"./abilitaOrganizzatori.php\">Abilita organizzatori</a></li>" ?>
        </ul>
      </div>
    </nav>
  </div>
</section>