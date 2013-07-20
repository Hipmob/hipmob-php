<?php

require_once("lib/Hipmob.php");

// send a bulk message
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$app = $hipmob->get_application($_SERVER['hipmob_app'], false);
$message = "This is a text message from PHP.";
echo "Sending message: ". $app->send_text_messages($message, array('7819157D-41BA-4214-A35D-8B48E1930FDF','5813cd68-402f-4836-8f9e-acf1aaaf937d')) . "\r\n";

