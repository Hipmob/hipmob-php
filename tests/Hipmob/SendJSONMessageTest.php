<?php

require_once("lib/Hipmob.php");

// send a single JSON message
if(!isset($_SERVER['hipmob_url'])){
  echo "Please provide the Hipmob URL as an environment variable.\r\n";
  return;
 }

$hipmob = new Hipmob($_SERVER['hipmob_url']);
$dev = $hipmob->get_device($_SERVER['hipmob_app'],'7819157D-41BA-4214-A35D-8B48E1930FDF', false);
$message = json_decode('{ "id": "json test", "value": ["1", "2", "3"] }');
echo "Sending message: ". $dev->send_json_message($message) . "\r\n";

