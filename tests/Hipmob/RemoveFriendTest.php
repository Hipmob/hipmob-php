<?php

require_once("lib/Hipmob.php");

// test removing specific friends: this uses one from the SetFriends test, and one from the AddFriend/AddFriends test.
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$dev = $hipmob->get_device($_SERVER['hipmob_app'],'C2342640-9C7B-4ef3-B894-BF12D5399301', false);
$newfriend = $hipmob->get_device($_SERVER['hipmob_app'],'09D6FC37-203D-4ea9-9B91-36F8F1ECA6D8', false);
echo "Removing [09D6FC37-203D-4ea9-9B91-36F8F1ECA6D8]: ". $dev->remove_friend($newfriend). "\r\n";
$newfriend = $hipmob->get_device($_SERVER['hipmob_app'],'CF1E3A84-482E-4646-8F1E-EE7A383DD051', false);
echo "Removing [CF1E3A84-482E-4646-8F1E-EE7A383DD051]: ". $dev->remove_friend($newfriend). "\r\n";

