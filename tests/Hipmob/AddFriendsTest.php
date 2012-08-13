<?php

require_once("lib/Hipmob.php");

// add multiple friends
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$dev = $hipmob->get_device($_SERVER['hipmob_app'],'C2342640-9C7B-4ef3-B894-BF12D5399301', false);
$newfriends = array($hipmob->get_device($_SERVER['hipmob_app'],'CF1E3A84-482E-4646-8F1E-EE7A383DD051', false),
		    $hipmob->get_device($_SERVER['hipmob_app'],'17BBDD25-115E-424e-AD1A-978F4381E705', false));
echo "Friends added : ". $dev->add_friends($newfriends). "\r\n";

