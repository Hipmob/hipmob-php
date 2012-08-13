<?php

require_once("lib/Hipmob.php");

// test the friendnotspecified error
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and app as environment variables.\r\n";
  return;
 }

try{
  $hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
  $device = $hipmob->get_device($_SERVER['hipmob_app'], 'C2342640-9C7B-4ef3-B894-BF12D5399301', false);
  $dev2 = false;
  $val = $device->add_friend($dev2);
} catch (Hipmob_FriendNotSpecifiedError $e) {
  //echo 401 == $e->getHttpStatus();
  echo "PASSED: FriendNotSpecifiedErrorTest\r\n";
  }
