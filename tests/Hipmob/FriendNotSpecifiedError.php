<?php

require_once("lib/Hipmob.php");

if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and app as environment variables.\r\n";
  return;
 }

if($argc != 2){
  echo "Please provide the device id on the command line.\r\n";
  return;
 }

$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$device = $hipmob->get_device($_SERVER['hipmob_app'], $argv[1], false);
$dev2 = false;
$val = $device->add_friend($dev2);
print_r($val);

