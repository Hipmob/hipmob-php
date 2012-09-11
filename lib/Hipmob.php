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
require(dirname(__FILE__) . '/Hipmob/FormatNotSupportedError.php');

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
    if(!$username || !$apikey) throw new Hipmob_AuthenticationError(401, "Authentication required"); 
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
    if($fp == FALSE) throw new Hipmob_AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      if(is_array($md)){
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
      throw new Hipmob_AuthenticationError(401, "Unauthorized"); 
    }else if(preg_match($pattern1, $statusline, $matches) == 1){
      throw new Hipmob_AuthenticationError(401, "Authentication required"); 
    }else if(preg_match($pattern1, $statusline, $matches) == 1){
      throw new Hipmob_ApplicationNotSpecifiedError(400, "No application specified"); 
    }else if(preg_match($pattern2, $statusline, $matches) == 1){
      throw new Hipmob_DeviceNotSpecifiedError(400, "No device specified"); 
    }else if(preg_match($pattern3, $statusline, $matches) == 1){
      throw new Hipmob_InvalidRequestError(402, "Invalid request");
    }else if(preg_match($pattern4, $statusline, $matches) == 1){
      throw new Hipmob_DeviceNotFoundError(404, "Device not found");
    }else if(preg_match($pattern5, $statusline, $matches) == 1){
      throw new Hipmob_ApplicationNotFoundError(404, "Application not found");
    }else if(preg_match($pattern6, $statusline, $matches) == 1){
      throw new Hipmob_FriendsNotSpecifiedError(400, "No friends specified"); 
    }
  }

  public function get_application($id)
  {
    $res = false;
    
    // make the request
    $val = trim($id);
    if($val == "") throw new Hipmob_ApplicationNotSpecifiedError(400, "No application specified"); 
    $url = $this->baseurl . "apps/" . $val;
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'GET',
							   'header' => $header,
							   'timeout' => 10,
							   )));
    $fp = @fopen($url, 'r', false, $context);
    if($fp == FALSE) throw new Hipmob_AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      if(is_array($md)){
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
    if($val == "") throw new Hipmob_ApplicationNotSpecifiedError(400, "No application specified"); 
    $device = trim($id);
    if($device == "") throw new Hipmob_DeviceNotSpecifiedError(400, "No device specified"); 

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
    if($fp == FALSE) throw new Hipmob_AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      if(is_array($md)){
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
    if($app == "") throw new Hipmob_ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new Hipmob_DeviceNotSpecifiedError(400, "No device specified"); 

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
    if($fp == FALSE) throw new Hipmob_AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      if(is_array($md)){
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
    }
    fclose($fp);
    
    if($res && isset($res->count)) return $res->count;
    return 0;
  }

  public function _get_device_friends($appid, $deviceid)
  {
    $app = trim($appid);
    if($app == "") throw new Hipmob_ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new Hipmob_DeviceNotSpecifiedError(400, "No device specified"); 

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
    if($fp == FALSE) throw new Hipmob_AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      if(is_array($md)){
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
    if($app == "") throw new Hipmob_ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new Hipmob_DeviceNotSpecifiedError(400, "No device specified"); 
    foreach($devices as $val){
      if(!$val || gettype($val) != "object" || get_class($val) != "Hipmob_Device")
	throw new Hipmob_FriendNotSpecifiedError(400, "No friends specified"); 
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
    if($fp == FALSE) throw new Hipmob_AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      if(is_array($md)){
	$this->_check_for_errors($md[0]);
	$responsedata = $md[0];
      }
    }
    fclose($fp);
    
    if($responsedata){
      $pattern1 = "/HTTP\/1\.1 200 Friend list updated \((\d*) friends added\)\./";
      $pattern2 = "/HTTP\/1\.1 200 No changes made\./";

      if(preg_match($pattern1, $responsedata, $matches) == 1){
	$res = $matches[1];
      }else if(preg_match($pattern2, $responsedata, $matches) == 1){
	$res = 0;
      }
    }
    return $res;
  }

  public function _remove_device_friend($appid, $deviceid, $device)
  {
    $app = trim($appid);
    if($app == "") throw new Hipmob_ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new Hipmob_DeviceNotSpecifiedError(400, "No device specified"); 
    if(!$device || gettype($device) != "object" || get_class($device) != "Hipmob_Device")
      throw new Hipmob_FriendNotSpecifiedError(400, "No friends specified"); 
    
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
    if($fp == FALSE) throw new Hipmob_AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      if(is_array($md)){
	$this->_check_for_errors($md[0]);
	$responsedata = $md[0];
      }
    }
    fclose($fp);
    
    if($responsedata){
      $pattern1 = "/HTTP\/1\.1 200 Friend removed\./";
      $pattern2 = "/HTTP\/1\.1 200 No changes made\./";

      if(preg_match($pattern1, $responsedata, $matches) == 1){
	$res = 1;
      }else if(preg_match($pattern2, $responsedata, $matches) == 1){
	$res = 0;
      }
    }
    return $res;
  }

  public function _remove_device_friends($appid, $deviceid)
  {
    $app = trim($appid);
    if($app == "") throw new Hipmob_ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new Hipmob_DeviceNotSpecifiedError(400, "No device specified"); 

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
    if($fp == FALSE) throw new Hipmob_AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      if(is_array($md)){
	$this->_check_for_errors($md[0]);
	$responsedata = $md[0];
      }
    }
    fclose($fp);
    
    if($responsedata){
      $pattern1 = "/HTTP\/1\.1 200 Friend list cleared \((\d*) friends removed\)\./";
      $pattern2 = "/HTTP\/1\.1 200 No changes made\./";

      if(preg_match($pattern1, $responsedata, $matches) == 1){
	$res = $matches[1];
      }else if(preg_match($pattern2, $responsedata, $matches) == 1){
	$res = 0;
      }
    }
    return $res;
  }

  public function _set_device_friends($appid, $deviceid, $devices)
  {
    $app = trim($appid);
    if($app == "") throw new Hipmob_ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new Hipmob_DeviceNotSpecifiedError(400, "No device specified"); 

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
    if($fp == FALSE) throw new Hipmob_AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      if(is_array($md)){
	$this->_check_for_errors($md[0]);
	$responsedata = $md[0];
      }
    }
    fclose($fp);

    if($responsedata){
      $pattern1 = "/HTTP\/1\.1 200 Friend list updated \((\d*) friends added\)\./";
      $pattern2 = "/HTTP\/1\.1 200 No changes made\./";

      if(preg_match($pattern1, $responsedata, $matches) == 1){
	$res = $matches[1];
      }else if(preg_match($pattern2, $responsedata, $matches) == 1){
	$res = 0;
      }
    }
    return $res;
  }

  public function _send_text_message($appid, $deviceid, $text, $autocreate)
  {
    $app = trim($appid);
    if($app == "") throw new Hipmob_ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new Hipmob_DeviceNotSpecifiedError(400, "No device specified"); 

    $res = false;
    
    $responsedata = false;

    // make the request
    $url = $this->baseurl . "apps/" . $app . "/devices/" . $id . "/messages";
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $header .= "\r\nContent-Type: application/x-www-form-urlencoded";
    
    // build the content
    $content = "text=".urlencode($text);
    if($autocreate) $content .= "&autocreate=true";
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'POST',
							   'header' => $header,
							   'timeout' => 10,
							   'content' => $content
							   )));
    $fp = @fopen($url, 'r', false, $context);
    if($fp == FALSE) throw new Hipmob_AuthenticationError(401, "Unauthorized"); 
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
      if(is_array($md)){
	$this->_check_for_errors($md[0]);
	$responsedata = $md[0];
      }
    }
    fclose($fp);
    
    if($responsedata){
      $pattern = "/HTTP\/1\.1 200 Message sent\./";

      if(preg_match($pattern, $responsedata, $matches) == 1){
	return true;
      }
    }
    return false;
  }

  public function _send_file_message($appid, $deviceid, $file, $mime_type, $autocreate)
  {
    $app = trim($appid);
    if($app == "") throw new Hipmob_ApplicationNotSpecifiedError(400, "No application specified"); 
    $id = trim($deviceid);
    if($id == "") throw new Hipmob_DeviceNotSpecifiedError(400, "No device specified"); 
    
    $mime_types = array('image/gif','image/png','image/jpeg','audio/mp3','audio/wav');
    if(!in_array($mime_type, $mime_types)) throw new Hipmob_FormatNotSupportedError(400, "Invalid message content-type.");

    // verify that the file exists
    $len = 0;
    $isfile = false;
    if(file_exists($file)){
      // need to return an object that will override toString
      $len = filesize($file);
      $isfile = true;
    }else{
      $len = strlen($data);
    }
    if($len == 0) throw new Hipmob_FormatNotSupportedError(400, "Invalid message content-type.");

    $responsedata = false;    
    $url = $this->baseurl . "apps/" . $app . "/devices/" . $id . "/messages";
    if(function_exists('curl_init')){
      // use curl if it is available so we don't have to load the entire file
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE); 
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->apikey);
      curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HEADER, 1);
      $fp = false;
      if($isfile){
	$fp = fopen($file, 'rb');
	curl_setopt($ch, CURLOPT_INFILE, $fp);
	curl_setopt($ch, CURLOPT_INFILESIZE, $len);
	curl_setopt($ch, CURLOPT_READFUNCTION, array(&$this, '_read_cb'));
	curl_setopt($ch, CURLOPT_UPLOAD, 1);
      }else{
	curl_setopt($ch, CURLOPT_POSTFIELDS, $file);
      }
      $headers = array('Expect:', 'Content-Length: '.$len, 'Content-Type: '.$mime_type);
      if(autocreate) $headers[] = 'X-Hipmob-Autocreate: true';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt ($ch, CURLOPT_TIMEOUT, 10); 
      
      $res = curl_exec($ch);
      if(!curl_errno($ch)){
	$info = curl_getinfo($ch);
	$res = substr($res, 0, $info['header_size']);
	$responsedata = preg_split('/\r\n|\r|\n/', $res, 2);
	$responsedata = $responsedata[0];
      }
      curl_close($ch);
      fclose($fp);
    }else{
      // no curl: ugh...well, we use file_get_contents. Not the best memory usage, but...
      if($isfile) $data = file_get_contents($file);
      $data = $file;
      
      // make the request
      $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
      $header .= "\r\nContent-Type: " . $mime_type . "\r\nContent-Length: ".$len;
      
      // build the content
      $context = stream_context_create(array('http' => array(
							     // set HTTP method
							     'method' => 'POST',
							     'header' => $header,
							     'timeout' => 10,
							     'content' => $data
							     )));
      $fp = @fopen($url, 'r', false, $context);
      if($fp == FALSE) throw new Hipmob_AuthenticationError(401, "Unauthorized"); 
      $md = stream_get_meta_data($fp);
      if(isset($md['wrapper_data'])){
	$md = $md['wrapper_data'];
	if(is_array($md)){
	  $this->_check_for_errors($md[0]);
	  $responsedata = $md[0];
	}
      }
      fclose($fp);
    }
    
    if($responsedata){
      $pattern = "/HTTP\/1\.1 200 Message sent\./";
      
      if(preg_match($pattern, $responsedata, $matches) == 1){
	return true;
      }
    }
    return false;
  }
  
  function _read_cb($ch, $fp, $len)
  {
    return fread($fp, $len);
  }
}
