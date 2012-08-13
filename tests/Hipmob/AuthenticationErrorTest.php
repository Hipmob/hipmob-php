<?php

require_once("lib/Hipmob.php");

// test the AuthenticationError
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

try {
  $hipmob = new Hipmob('invalid', 'invalid');
  $apps = $hipmob->get_applications();
  print_r($apps);
} catch (Hipmob_AuthenticationError $e) {
  //echo 401 == $e->getHttpStatus();
  echo "PASSED: AuthenticationErrorTest\r\n";
  }

