<?php
/* query di ricerca che può essere con AJAX oppure si può fare con un tasto cerca
Bisogna passare id dell'evento a cui associare l'artista e si aggiunge il parametro in get di ricerca
(query da fare solo se si passa quel valore in get)
Possibilità di associare l'immagine*/
$artisti = [
  ['name' => 'dio'],
  ['name' => 'mari'],
];
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
  </head>
  <body>
    <h1 class="title">Seleziona artista</h1>
    <div>
      <input class="input" type="text" placeholder="Cerca artista...">
    </div>
    <?php foreach($artisti as $artista): ?>
    <div class="box">
      <article class="media">
        <div class="media-left">
          <figure class="image is-64x64">
            <img src="https://bulma.io/images/placeholders/128x128.png" alt="Image">
          </figure>
        </div>
        <div class="media-content">
          <div class="content">
            <span><?php echo $artista['name'] ?></span><br>
          </article>
    </div>
    <?php endforeach; ?>
  </body>
</html>