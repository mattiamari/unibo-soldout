<?php

require "db.php";

$a=$db->insertNewArtist(generateId(),"Diodato","Vinto sanremo 2020");
var_dump($a);