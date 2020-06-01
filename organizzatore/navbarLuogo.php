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
        Luoghi
      </h1>
      <h2 class="subtitle">
        Elenco dei luoghi con descrizione e possibili azioni da compiere.
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
          <li class="is-active"><a href="./visualizzaLuoghi.php">Luoghi</a></li>
          <?php if($_SESSION["is_admin"]==1) echo "<li><a href=\"./abilitaEventi.php\">Abilita eventi</a></li>
          <li><a href=\"./abilitaOrganizzatori.php\">Abilita organizzatori</a></li>" ?>
        </ul>
      </div>
    </nav>
  </div>
</section>