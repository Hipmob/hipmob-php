<?php

require_once("lib/Hipmob.php");

// generic test: get a single application
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

if(!$argv || count($argv) != 2){
  echo "Please specify the new status to use.\r\n";
  return;
 }

// get a user
$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$user = $hipmob->get_user($_SERVER['hipmob_username']);

// Set the status
if($user->set_status($argv[1])){
  // check it out
  $user = $hipmob->get_user($_SERVER['hipmob_username']);
  echo "The new status for ".$user->get_username()." is ".$user->get_status().".\r\n";
 }else{
  echo "Status update failed.\r\n";
 }
