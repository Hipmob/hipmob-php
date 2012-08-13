<?php

require_once("lib/Hipmob.php");

// test removing all the test device's friends
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$dev = $hipmob->get_device($_SERVER['hipmob_app'],'C2342640-9C7B-4ef3-B894-BF12D5399301', false);
echo "Friends removed : ". $dev->remove_all_friends(). "\r\n";

