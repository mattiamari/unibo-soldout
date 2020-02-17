<?php

require "db.php";

error_reporting(0);
ob_start();
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

try {
  if(!isset($_GET['type']) || empty($_GET['type'])) {
  	throw new exception("Type is not set.");
  }
  $type = $_GET['type'];
  if($type=='getCountries') {
    $countries = $db->getCountries();
    $a = [];
    foreach($countries as $country) {
      $a[$country["id"]] = $country["name"];
    }
    $data = ["tp"=>1, "result"=>$a];
  } 

  if($type=='getStates') {
  	 if(!isset($_GET['countryId']) || empty($_GET['countryId'])) {
  	 	throw new exception("Country Id is not set.");
  	 }
  	 $countryId = $_GET['countryId'];
     $states = $db->getStates($countryId);
     $a = [];
    foreach($states as $state) {
      $a[$state["id"]] = $state["name"];
    }
    $data = ["tp"=>1, "result"=>$a];
  }

   if($type=='getCities') {
  	 if(!isset($_GET['stateId']) || empty($_GET['stateId'])) {
  	 	throw new exception("State Id is not set.");
  	 }
     $stateId = $_GET['stateId'];
     $cities = $db->getCities($stateId);
     $a = [];
    foreach($cities as $city) {
      $a[$city["id"]] = $city["name"];
    }
    $data = ["tp"=>1, "result"=>$a];
  }

} catch (Exception $e) {
   $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
} finally {
  echo json_encode($data);
}

ob_flush();






