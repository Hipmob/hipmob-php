<?php

require_once("lib/Hipmob.php");

// generic test: get all applications
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password'])){
  echo "Please provide the username and password as environment variables.\r\n";
  return;
 }

// get my apps
$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$apps = $hipmob->get_applications();
print_r($apps);

