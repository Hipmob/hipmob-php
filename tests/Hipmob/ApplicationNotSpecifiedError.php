<?php

require_once("lib/Hipmob.php");

// test the application not specified error.
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password'])){
  echo "Please provide the username and password as environment variables.\r\n";
  return;
 }

try {
  $hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
  $app = $hipmob->get_application('');
  print_r($app);
} catch (Hipmob_ApplicationNotSpecifiedError $e) {
  //echo 401 == $e->getHttpStatus();
  echo "PASSED: ApplicationNotSpecifiedErrorTest\r\n";
  }



