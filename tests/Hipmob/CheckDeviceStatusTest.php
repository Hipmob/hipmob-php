<?php

require_once("lib/Hipmob.php");

// check for available messages
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$dev = $hipmob->get_device($_SERVER['hipmob_app'],'7819157D-41BA-4214-A35D-8B48E1930FDF', false);
$status = $dev->check_device_status();
if($status == TRUE) $status = "yes"; else $status = "no";
echo "Device Online? ". $status . "\r\n";

