<?php

require_once("lib/Hipmob.php");

if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password'])){
  echo "Please provide the username and password as environment variables.\r\n";
  return;
 }

$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$app = $hipmob->get_application('');
print_r($app);

