<?php
  // Tested on PHP 5.2, 5.3

if (!function_exists('json_decode')) {
  throw new Exception('Hipmob requires the JSON PHP extension.');
 }

// Errors
require(dirname(__FILE__) . '/Hipmob/Error.php');
require(dirname(__FILE__) . '/Hipmob/AuthenticationError.php');
require(dirname(__FILE__) . '/Hipmob/ApplicationNotSpecifiedError.php');
require(dirname(__FILE__) . '/Hipmob/DeviceNotSpecifiedError.php');
require(dirname(__FILE__) . '/Hipmob/FriendNotSpecifiedError.php');
require(dirname(__FILE__) . '/Hipmob/InvalidRequestError.php');
require(dirname(__FILE__) . '/Hipmob/ApplicationNotFoundError.php');
require(dirname(__FILE__) . '/Hipmob/DeviceNotFoundError.php');

// Hipmob API Resources
require(dirname(__FILE__) . '/Hipmob/App.php');
require(dirname(__FILE__) . '/Hipmob/Device.php');

class Hipmob
{
  private $username;
  private $apikey;

  private $baseurl = 'https://api.hipmob.com/';
  private static $verifySslCerts = true;
  
  const VERSION = '0.1.0';

  public function __construct($username, $apikey)
  {
    if(!$username || !$apikey) throw new AuthenticationError(401, "Authentication required"); 
    $this->username = $username;
    $this->apikey = $apikey;
    if(isset($_SERVER['hipmob_server'])) $this->baseurl = $_SERVER['hipmob_server'];
  }
  
  public function getAPIKey()
  {
    return $this->apikey;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public static function getVerifySslCerts() {
    return self::$verifySslCerts;
  }

  public static function setVerifySslCerts($verify) {
    self::$verifySslCerts = $verify;
  }

  private static function _get_header_value($header)
  {
    $vals = explode(': ', $header, 2);
    if(count($vals) == 2) return $vals[1];
    return false;
  }

  public function get_applications()
  {
    $res = array();
    
    $responsedata = false;
    
    // make the request
    $url = $this->baseurl . "apps";
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'GET',
							   'header' => $header,
							   'timeout' => 10,
							   )));
    $fp = @fopen($url, 'r', false, $context);
    if($fp == FALSE) throw new AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      $this->_check_for_errors($md[0]);
      $contentlength = false;
      $contenttype = false;
      foreach($md as $header){
	if(strpos($header, 'Content-Length: ') === 0) $contentlength = self::_get_header_value($header);
	else if(strpos($header, 'Content-Type: ') === 0) $contenttype = self::_get_header_value($header);
	if($contenttype && $contentlength) break;
      }
      if(!$contenttype || $contenttype != 'application/vnd.com.hipmob.App-list+json; version=1.0'){

      }else if(!$contentlength){
	
      }else{
	$responsedata = json_decode(fread($fp, intval($contentlength)));
      }
    }
    fclose($fp);
    
    if($responsedata && $responsedata->count > 0){
      foreach($responsedata->values as $sourcedata){
	$res[] = new Hipmob_App($this, $sourcedata);
      }
    }
    return $res;
  }

  private function _check_for_errors($statusline)
  {
    $pattern1 = "/HTTP\/1\.1 400 No application specified\./";
    $pattern2 = "/HTTP\/1\.1 400 No device specified\./";
    $pattern3 = "/HTTP\/1\.1 402 API Request Failed\./";
    $pattern4 = "/HTTP\/1\.1 404 Device not found\./";
    $pattern5 = "/HTTP\/1\.1 404 Application not found\./";
    $pattern6 = "/HTTP\/1\.1 400 No friends specified\./";
    $pattern7 = "/HTTP\/1\.1 401 Unauthorized/";
    $pattern8 = "/HTTP\/1\.1 401 Authentication required/";
    
    if(preg_match($pattern1, $statusline, $matches) == 1){
      throw new AuthenticationError(401, "Unauthorized"); 
    }else if(preg_match($pattern1, $statusline, $matches) == 1){
      throw new AuthenticationError(401, "Authentication required"); 
    }else if(preg_match($pattern1, $statusline, $matches) == 1){
      throw new ApplicationNotSpecifiedError(400, "No application specified"); 
    }else if(preg_match($pattern2, $statusline, $matches) == 1){
      throw new DeviceNotSpecifiedError(400, "No device specified"); 
    }else if(preg_match($pattern3, $statusline, $matches) == 1){
      throw new InvalidRequestError(402, "Invalid request");
    }else if(preg_match($pattern4, $statusline, $matches) == 1){
      throw new DeviceNotFoundError(404, "Device not found");
    }else if(preg_match($pattern5, $statusline, $matches) == 1){
      throw new ApplicationNotFoundError(404, "Application not found");
    }else if(preg_match($pattern6, $statusline, $matches) == 1){
      throw new FriendsNotSpecifiedError(400, "No friends specified"); 
    }
  }

  public function get_application($id)
  {
    $res = false;
    
    // make the request
    $val = trim($id);
    if($val == "") throw new ApplicationNotSpecifiedError(400, "No application specified"); 
    $url = $this->baseurl . "apps/" . $val;
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'GET',
							   'header' => $header,
							   'timeout' => 10,
							   )));
    $fp = @fopen($url, 'r', false, $context);
    if($fp == FALSE) throw new AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      $this->_check_for_errors($md[0]);
      $contentlength = false;
      $contenttype = false;
      foreach($md as $header){
	if(strpos($header, 'Content-Length: ') === 0) $contentlength = self::_get_header_value($header);
	else if(strpos($header, 'Content-Type: ') === 0) $contenttype = self::_get_header_value($header);
	if($contenttype && $contentlength) break;
      }
      if(!$contenttype || $contenttype != 'application/vnd.com.hipmob.App+json; version=1.0'){

      }else if(!$contentlength){
	
      }else{
	$res = json_decode(fread($fp, intval($contentlength)));
      }
    }
    fclose($fp);
    
    if($res){
      $res = new Hipmob_App($this, $res);
    }
    return $res;
  }

  public function get_device($app, $id, $verify = true)
  {
    if(!$verify){
      return new Hipmob_Device($this, $app, (object)array('id' => $id), false);      
    }
    $res = false;
    
    $val = trim($app);
    if($val == "") throw new ApplicationNotSpecifiedError(400, "No application specified"); 
    $device = trim($id);
    if($device == "") throw new DeviceNotSpecifiedError(400, "No device specified"); 

    // make the request
    $url = $this->baseurl . "apps/" . $val . "/devices/". $device;
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'GET',
							   'header' => $header,
							   'timeout' => 10,
							   )));
    $fp = @fopen($url, 'r', false, $context);
    if($fp == FALSE) throw new AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      $this->_check_for_errors($md[0]);
      $contentlength = false;
      $contenttype = false;
      foreach($md as $header){
	if(strpos($header, 'Content-Length: ') === 0) $contentlength = self::_get_header_value($header);
	else if(strpos($header, 'Content-Type: ') === 0) $contenttype = self::_get_header_value($header);
	if($contenttype && $contentlength) break;
      }
      if(!$contenttype || $contenttype != 'application/vnd.com.hipmob.Device+json; version=1.0'){
	
      }else if(!$contentlength){
	
      }else{
	$res = json_decode(fread($fp, intval($contentlength)));
      }
    }
    fclose($fp);
    
    if($res){
      $res = new Hipmob_Device($this, $app, $res);
    }
    return $res;
  }

  public function _get_available_message_count($appid, $deviceid)
  {
    $res = false;
    
    $app = trim($appid);
    if($app == "") throw new ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new DeviceNotSpecifiedError(400, "No device specified"); 

    // make the request
    $url = $this->baseurl . "apps/" . $app . "/devices/" . $id . "/messagecount";
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'GET',
							   'header' => $header,
							   'timeout' => 10,
							   )));
    $fp = @fopen($url, 'r', false, $context);
    if($fp == FALSE) throw new AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      $this->_check_for_errors($md[0]);
      $contentlength = false;
      $contenttype = false;
      foreach($md as $header){
	if(strpos($header, 'Content-Length: ') === 0) $contentlength = self::_get_header_value($header);
	else if(strpos($header, 'Content-Type: ') === 0) $contenttype = self::_get_header_value($header);
	if($contenttype && $contentlength) break;
      }
      if(!$contenttype || $contenttype != 'application/vnd.com.hipmob.Device.pendingmessagecount+json; version=1.0'){

      }else if(!$contentlength){
	
      }else{
	$res = json_decode(fread($fp, intval($contentlength)));
      }
    }
    fclose($fp);
    
    if($res && isset($res->count)) return $res->count;
    return 0;
  }

  public function _get_device_friends($appid, $deviceid)
  {
    $app = trim($appid);
    if($app == "") throw new ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new DeviceNotSpecifiedError(400, "No device specified"); 

    $res = array();
    
    $responsedata = false;

    // make the request
    $url = $this->baseurl . "apps/" . $app . "/devices/" . $id . "/friends";
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'GET',
							   'header' => $header,
							   'timeout' => 10,
							   )));
    $fp = @fopen($url, 'r', false, $context);
    if($fp == FALSE) throw new AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      $this->_check_for_errors($md[0]);
      $contentlength = false;
      $contenttype = false;
      foreach($md as $header){
	if(strpos($header, 'Content-Length: ') === 0) $contentlength = self::_get_header_value($header);
	else if(strpos($header, 'Content-Type: ') === 0) $contenttype = self::_get_header_value($header);
	if($contenttype && $contentlength) break;
      }
      if(!$contenttype || $contenttype != 'application/vnd.com.hipmob.DeviceFriends+json; version=1.0'){

      }else if(!$contentlength){
	
      }else{
	$responsedata = json_decode(fread($fp, intval($contentlength)));
      }
    }
    fclose($fp);
    
    if($responsedata && $responsedata->pagesize > 0){
      foreach($responsedata->friends as $sourcedata){
	$res[] = new Hipmob_Device($this, $app, (object)array('id' => $sourcedata), false);
      }
    }
    return $res;
  }

  public function _add_device_friends($appid, $deviceid, $devices)
  {
    $app = trim($appid);
    if($app == "") throw new ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new DeviceNotSpecifiedError(400, "No device specified"); 
    foreach($devices as $val){
      if(!$val || gettype($val) != "object" || get_class($val) != "HipmobDevice")
	throw new FriendNotSpecifiedError(400, "No friends specified"); 
    }

    $res = false;
    
    $responsedata = false;

    // make the request
    $url = $this->baseurl . "apps/" . $app . "/devices/" . $id . "/friends";
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $header .= "\r\nContent-Type: application/x-www-form-urlencoded";
    
    // build the content
    $contents = array();
    foreach($devices as $dev){
      $contents[] = "friend=".urlencode($dev->get_id());
    }
    $content = implode("&", $contents);
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'POST',
							   'header' => $header,
							   'timeout' => 10,
							   'content' => $content
							   )));
    $fp = @fopen($url, 'r', false, $context);
    if($fp == FALSE) throw new AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      $this->_check_for_errors($md[0]);
      $responsedata = $md[0];
    }
    fclose($fp);
    
    if($responsedata){
      $pattern1 = "/HTTP\/1\.1 200 Friend list updated \((\d*) friends added\)\./";
      $pattern2 = "/HTTP\/1\.1 200 No changes made\./";

      if(preg_match($pattern1, $md[0], $matches) == 1){
	$res = $matches[1];
      }else if(preg_match($pattern2, $md[0], $matches) == 1){
	$res = 0;
      }
    }
    return $res;
  }

  public function _remove_device_friend($appid, $deviceid, $device)
  {
    $app = trim($appid);
    if($app == "") throw new ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new DeviceNotSpecifiedError(400, "No device specified"); 
    if(!$device || gettype($device) != "object" || get_class($device) != "HipmobDevice")
      throw new FriendNotSpecifiedError(400, "No friends specified"); 
    
    $res = false;
    
    $responsedata = false;

    // make the request
    $url = $this->baseurl . "apps/" . $app . "/devices/" . $id . "/friends/" . $device->get_id();
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $header .= "\r\nContent-Type: application/x-www-form-urlencoded";
    
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'DELETE',
							   'header' => $header,
							   'timeout' => 10
							   )));
    $fp = @fopen($url, 'r', false, $context);
    if($fp == FALSE) throw new AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      $this->_check_for_errors($md[0]);
      $responsedata = $md[0];
    }
    fclose($fp);
    
    if($responsedata){
      $pattern1 = "/HTTP\/1\.1 200 Friend removed\./";
      $pattern2 = "/HTTP\/1\.1 200 No changes made\./";

      if(preg_match($pattern1, $md[0], $matches) == 1){
	$res = 1;
      }else if(preg_match($pattern2, $md[0], $matches) == 1){
	$res = 0;
      }
    }
    return $res;
  }

  public function _remove_device_friends($app, $id)
  {
    $app = trim($appid);
    if($app == "") throw new ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new DeviceNotSpecifiedError(400, "No device specified"); 

    $res = false;
    
    $responsedata = false;

    // make the request
    $url = $this->baseurl . "apps/" . $app . "/devices/" . $id . "/friends";
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $header .= "\r\nContent-Type: application/x-www-form-urlencoded";
    
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'DELETE',
							   'header' => $header,
							   'timeout' => 10
							   )));
    $fp = @fopen($url, 'r', false, $context);
    if($fp == FALSE) throw new AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      $this->_check_for_errors($md[0]);
      $responsedata = $md[0];
    }
    fclose($fp);
    
    if($responsedata){
      $pattern1 = "/HTTP\/1\.1 200 Friend list cleared \((\d*) friends removed\)\./";
      $pattern2 = "/HTTP\/1\.1 200 No changes made\./";

      if(preg_match($pattern1, $md[0], $matches) == 1){
	$res = $matches[1];
      }else if(preg_match($pattern2, $md[0], $matches) == 1){
	$res = 0;
      }
    }
    return $res;
  }

  public function _set_device_friends($app, $id, $devices)
  {
    $app = trim($appid);
    if($app == "") throw new ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new DeviceNotSpecifiedError(400, "No device specified"); 

    $res = false;
    
    $responsedata = false;

    // make the request
    $url = $this->baseurl . "apps/" . $app . "/devices/" . $id . "/friends";
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $header .= "\r\nContent-Type: application/x-www-form-urlencoded";
    
    // build the content
    $contents = array();
    foreach($devices as $dev){
      $contents[] = "friend=".urlencode($dev->get_id());
    }
    $content = implode("&", $contents);
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'PUT',
							   'header' => $header,
							   'timeout' => 10,
							   'content' => $content
							   )));
    $fp = @fopen($url, 'r', false, $context);
    if($fp == FALSE) throw new AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      $this->_check_for_errors($md[0]);
      $responsedata = $md[0];
    }
    fclose($fp);

    if($responsedata){
      $pattern1 = "/HTTP\/1\.1 200 Friend list updated \((\d*) friends added\)\./";
      $pattern2 = "/HTTP\/1\.1 200 No changes made\./";

      if(preg_match($pattern1, $md[0], $matches) == 1){
	$res = $matches[1];
      }else if(preg_match($pattern2, $md[0], $matches) == 1){
	$res = 0;
      }
    }
    return $res;
  }
}
