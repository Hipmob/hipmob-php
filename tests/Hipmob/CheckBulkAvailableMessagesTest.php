<?php

require_once("lib/Hipmob.php");

// check for available messages
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$dev = $hipmob->get_device($_SERVER['hipmob_app'],'7819157D-41BA-4214-A35D-8B48E1930FDF', false);
echo "Message count ('7819157D-41BA-4214-A35D-8B48E1930FDF'): ". $dev->available_message_count() . "\r\n";
$dev = $hipmob->get_device($_SERVER['hipmob_app'],'5813cd68-402f-4836-8f9e-acf1aaaf937d', false);
echo "Message count ('5813cd68-402f-4836-8f9e-acf1aaaf937d'): ". $dev->available_message_count() . "\r\n";

