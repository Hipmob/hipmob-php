<?php

require_once("lib/Hipmob.php");

// get my apps
$hipmob = new Hipmob($_SERVER['hipmob_username'], $_SERVER['hipmob_password']);
$apps = $hipmob->get_applications();
print_r($apps);

// get a specific app
$app = $hipmob->get_application($_SERVER['hipmob_app']);
echo $app;

// get a specific device, then figure out how many messages are pending for it
$dev = $hipmob->get_device($_SERVER['hipmob_app'],'21040b27-8ce4-4cd9-9aa5-8ccd3730d648');
echo $dev;
echo "\n----------->[".$dev->available_message_count()."]\n";

// figure out how many messages are pending without retrieving the device info
$dev = $hipmob->get_device($_SERVER['hipmob_app'],'21040b27-8ce4-4cd9-9aa5-8ccd3730d648', false);
echo "\n----------->[".$dev->available_message_count()."]\n";

// list all the device's friends
print_r($dev->list_friends());

// add a friend

$dev1 = $hipmob->get_device($_SERVER['hipmob_app'],'486585E1-1E66-4b34-9CB7-8E6E04D647EB', false);
echo "----------->Added [".$dev->add_friend($dev1)."]\n";

$devs = array($hipmob->get_device($_SERVER['hipmob_app'],'A82F3CFA-C914-42da-A9C7-58133D5A6731', false), $hipmob->get_device('488b7ecc3a764176b50717278c6a9ea0','809F4CCD-BAA2-48e9-83A4-EC34F4FDC193', false));
echo "----------->Added [".$dev->add_friends($devs)."]\n";

