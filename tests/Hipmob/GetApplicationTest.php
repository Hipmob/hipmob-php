<?php

require_once("lib/Hipmob.php");

// generic test: get a single application
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

// get an application
$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$app = $hipmob->get_application($_SERVER['hipmob_app']);
print_r($app);

