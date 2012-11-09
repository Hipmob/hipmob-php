<?php

echo "Running the Hipmob PHP bindings test suite.\nIf you're trying to use the Hipmob PHP bindings you'll probably want to require('lib/Hipmob.php'); instead of this file\n";

/*
var run_test_function = function(index, total)
{
  if(index >= total){
    console.log("------>Complete.");
    return;
  }
  var nextIndex = index + 1;
  if(typeof functions[process.argv[index]] == 'function'){
    console.log("------>Running ["+process.argv[index]+"]");
    functions[process.argv[index]](function(){
	setTimeout(function(){ run_test_function(nextIndex, total); }, 0);
      });
  }else{
    console.log("------>"+process.argv[index]+" could not be found in the test functions object ("+util.inspect(functions)+").");
  }
}

  var len = process.argv.length;
if(len > 2){
  run_test_function(2, len);
 }else{
  console.log("------>Please specify one or more functions to run.");
  var i = 1;
  for(var x in functions){
    console.log(i+": "+x);
  }
 }


*/
if(!isset($_SERVER['hipmob_username']) || !isset($_SERVER['hipmob_password']) || !isset($_SERVER['hipmob_app'])){
  echo "Please provide the username, password and application mobile key as environment variables.\r\n";
  return;
 }

require_once(dirname(__FILE__) . '/../lib/Hipmob.php');

// build out the tests to be run
$tests = array();
for($i=1;$i<$argc;$i++){
  $tests[$argv[$i]] = TRUE;
 }

if(isset($tests['all'])){
  echo "==== Authentication Error Test ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/AuthenticationErrorTest.php');
 }

if(isset($tests['all'])){
  echo "==== Application Not Specified Error Test ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/ApplicationNotSpecifiedError.php');
 }

if(isset($tests['all'])){
  echo "==== Device Not Specified Error Test ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/DeviceNotSpecifiedError.php');
 }

if(isset($tests['all'])){
  echo "==== Friend Not Specified Error Test ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/FriendNotSpecifiedError.php');
 }

if(isset($tests['all']) || isset($tests['get_apps'])){
  echo "==== Get Applications Test ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/GetApplicationsTest.php');
 }

if(isset($tests['all']) || isset($tests['get_app'])){
  echo "==== Get Application Test ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/GetApplicationTest.php');
 }

if(isset($tests['all']) || isset($tests['list_friends'])){
  echo "==== List Friends Test ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/ListFriendsTest.php');
 }

if(isset($tests['all']) || isset($tests['add_one_friend'])){
  echo "==== Add Friend Test ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/AddFriendTest.php');
  include(dirname(__FILE__) . '/Hipmob/ListFriendsTest.php');
 }

if(isset($tests['all']) || isset($tests['remove_one_friend'])){
  echo "==== Remove Friend Test ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/RemoveFriendTest.php');
  include(dirname(__FILE__) . '/Hipmob/ListFriendsTest.php');
 }

if(isset($tests['all']) || isset($tests['add_multiple_friends'])){
  echo "==== Add Friends Test ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/AddFriendsTest.php');
  include(dirname(__FILE__) . '/Hipmob/ListFriendsTest.php');
 }

if(isset($tests['all']) || isset($tests['remove_all_friends'])){
  echo "==== Remove All Friends Test ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/RemoveAllFriendsTest.php');
  include(dirname(__FILE__) . '/Hipmob/ListFriendsTest.php');
 }

if(isset($tests['all']) || isset($tests['set_friends'])){
  echo "==== Set Friends Test ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/AddFriendsTest.php');
  include(dirname(__FILE__) . '/Hipmob/SetFriendsTest.php');
  include(dirname(__FILE__) . '/Hipmob/ListFriendsTest.php');
  include(dirname(__FILE__) . '/Hipmob/RemoveAllFriendsTest.php');
 }

if(isset($tests['all']) || isset($tests['get_pending_message_count'])){
  echo "==== Check Available Messages ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/CheckAvailableMessagesTest.php');
 }

if(isset($tests['all']) || isset($tests['send_text'])){
  echo "==== Send Text Message ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/SendTextMessageTest.php');
  include(dirname(__FILE__) . '/Hipmob/CheckAvailableMessagesTest.php');
 }

if(isset($tests['all']) || isset($tests['send_json'])){
  echo "==== Send JSON Message ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/SendJSONMessageTest.php');
  include(dirname(__FILE__) . '/Hipmob/CheckAvailableMessagesTest.php');
 }

if(isset($tests['all']) || isset($tests['send_binary'])){
  echo "==== Send Binary Message ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/SendBinaryMessageTest.php');
  include(dirname(__FILE__) . '/Hipmob/CheckAvailableMessagesTest.php');
 }

if(isset($tests['all']) || isset($tests['send_picture'])){
  echo "==== Send Picture Message ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/SendImageMessageTest.php');
  include(dirname(__FILE__) . '/Hipmob/CheckAvailableMessagesTest.php');
 }

if(isset($tests['all']) || isset($tests['send_audio'])){
  echo "==== Send Audio Message ====\r\n";
  include(dirname(__FILE__) . '/Hipmob/SendAudioMessageTest.php');
  include(dirname(__FILE__) . '/Hipmob/CheckAvailableMessagesTest.php');
 }
