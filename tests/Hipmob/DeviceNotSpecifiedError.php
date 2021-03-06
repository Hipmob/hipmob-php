<?php

require_once("lib/Hipmob.php");

// test the device not specified error
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and app as environment variables.\r\n";
  return;
 }

try{
  $hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
  $device = $hipmob->get_device($_SERVER['hipmob_app'], '');
  print_r($device);
} catch (Hipmob_DeviceNotSpecifiedError $e) {
  //echo 401 == $e->getHttpStatus();
  echo "PASSED: DeviceNotSpecifiedErrorTest\r\n";
  }
