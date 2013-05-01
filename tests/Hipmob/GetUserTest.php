<?php

require_once("lib/Hipmob.php");

// generic test: get a single application
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

// get a user
$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$user = $hipmob->get_user($_SERVER['hipmob_username']);
print_r($user);
