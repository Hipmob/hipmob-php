<?php
  // Tested on PHP 5.2, 5.3

if (!function_exists('json_decode')) {
  throw new Exception('Hipmob requires the JSON PHP extension.');
 }

// Hipmob API Resources
require(dirname(__FILE__) . '/Hipmob/App.php');
require(dirname(__FILE__) . '/Hipmob/Device.php');

class Hipmob
{
  private $username;
  private $apikey;

  private static $baseurl = 'https://api.hipmob.com/';
  private static $verifySslCerts = true;
  
  const VERSION = '0.1.0';

  public function __construct($username, $apikey)
  {
    $this->username = $username;
    $this->apikey = $apikey;
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
    $url = self::$baseurl . "apps";
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'GET',
							   'header' => $header,
							   'timeout' => 10,
							   )));
    $fp = fopen($url, 'r', false, $context);
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
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

  public function get_application($id)
  {
    $res = false;
    
    // make the request
    $url = self::$baseurl . "apps/" . $id;
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'GET',
							   'header' => $header,
							   'timeout' => 10,
							   )));
    $fp = fopen($url, 'r', false, $context);
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
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
    
    // make the request
    $url = self::$baseurl . "apps/" . $app . "/devices/". $id;
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'GET',
							   'header' => $header,
							   'timeout' => 10,
							   )));
    $fp = fopen($url, 'r', false, $context);
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
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

  public function _get_available_message_count($app, $id)
  {
    $res = false;
    
    // make the request
    $url = self::$baseurl . "apps/" . $app . "/devices/" . $id . "/messagecount";
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'GET',
							   'header' => $header,
							   'timeout' => 10,
							   )));
    $fp = fopen($url, 'r', false, $context);
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
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

  public function _get_device_friends($app, $id)
  {
    $res = array();
    
    $responsedata = false;

    // make the request
    $url = self::$baseurl . "apps/" . $app . "/devices/" . $id . "/friends";
    $header = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->apikey));
    $context = stream_context_create(array('http' => array(
							   // set HTTP method
							   'method' => 'GET',
							   'header' => $header,
							   'timeout' => 10,
							   )));
    $fp = fopen($url, 'r', false, $context);
    $md = stream_get_meta_data($fp);
    if(isset($md['wrapper_data'])){
      $md = $md['wrapper_data'];
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
}
