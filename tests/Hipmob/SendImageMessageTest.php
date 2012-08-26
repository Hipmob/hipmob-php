<?php

require_once("lib/Hipmob.php");

// Add a single friend
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$dev = $hipmob->get_device($_SERVER['hipmob_app'],'7819157D-41BA-4214-A35D-8B48E1930FDF', false);
$file = "testdata/wheel.png";
echo "Sending message: ". $dev->send_picture_message($file, 'image/png') . "\r\n";

