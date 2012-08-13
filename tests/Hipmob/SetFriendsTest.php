<?php

require_once("lib/Hipmob.php");

// set multiple friends
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$dev = $hipmob->get_device($_SERVER['hipmob_app'],'C2342640-9C7B-4ef3-B894-BF12D5399301', false);
$newfriends = array($hipmob->get_device($_SERVER['hipmob_app'],'09D6FC37-203D-4ea9-9B91-36F8F1ECA6D8', false),
		    $hipmob->get_device($_SERVER['hipmob_app'],'3EDBC6BD-B06E-47a0-BC06-B54F74C1E9B8', false));
echo "Friends added : ". $dev->set_friends($newfriends). "\r\n";

