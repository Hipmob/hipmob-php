<?php

echo "Running the Hipmob PHP bindings test suite.\nIf you're trying to use the Hipmob PHP bindings you'll probably want to require('lib/Hipmob.php'); instead of this file\n";

if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

require_once(dirname(__FILE__) . '/../lib/Hipmob.php');

echo "==== Authentication Error Test ====\r\n";
include(dirname(__FILE__) . '/Hipmob/AuthenticationErrorTest.php');

echo "==== Application Not Specified Error Test ====\r\n";
include(dirname(__FILE__) . '/Hipmob/ApplicationNotSpecifiedError.php');

echo "==== Device Not Specified Error Test ====\r\n";
include(dirname(__FILE__) . '/Hipmob/DeviceNotSpecifiedError.php');

echo "==== Friend Not Specified Error Test ====\r\n";
include(dirname(__FILE__) . '/Hipmob/FriendNotSpecifiedError.php');

echo "==== Get Applications Test ====\r\n";
include(dirname(__FILE__) . '/Hipmob/GetApplicationsTest.php');

echo "==== Get Application Test ====\r\n";
include(dirname(__FILE__) . '/Hipmob/GetApplicationTest.php');

echo "==== List Friends Test ====\r\n";
include(dirname(__FILE__) . '/Hipmob/ListFriendsTest.php');

echo "==== Add Friend Test ====\r\n";
include(dirname(__FILE__) . '/Hipmob/AddFriendTest.php');
include(dirname(__FILE__) . '/Hipmob/ListFriendsTest.php');

echo "==== Remove Friend Test ====\r\n";
include(dirname(__FILE__) . '/Hipmob/RemoveFriendTest.php');
include(dirname(__FILE__) . '/Hipmob/ListFriendsTest.php');

echo "==== Add Friends Test ====\r\n";
include(dirname(__FILE__) . '/Hipmob/AddFriendsTest.php');
include(dirname(__FILE__) . '/Hipmob/ListFriendsTest.php');

echo "==== Remove All Friends Test ====\r\n";
include(dirname(__FILE__) . '/Hipmob/RemoveAllFriendsTest.php');
include(dirname(__FILE__) . '/Hipmob/ListFriendsTest.php');

echo "==== Set Friends Test ====\r\n";
include(dirname(__FILE__) . '/Hipmob/AddFriendsTest.php');
include(dirname(__FILE__) . '/Hipmob/SetFriendsTest.php');
include(dirname(__FILE__) . '/Hipmob/ListFriendsTest.php');
include(dirname(__FILE__) . '/Hipmob/RemoveAllFriendsTest.php');

echo "==== Check Available Messages ====\r\n";
include(dirname(__FILE__) . '/Hipmob/CheckAvailableMessagesTest.php');

echo "==== Send Messages ====\r\n";
include(dirname(__FILE__) . '/Hipmob/SendTextMessageTest.php');
include(dirname(__FILE__) . '/Hipmob/SendImageMessageTest.php');
include(dirname(__FILE__) . '/Hipmob/SendAudioMessageTest.php');
include(dirname(__FILE__) . '/Hipmob/CheckAvailableMessagesTest.php');

