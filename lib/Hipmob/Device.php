<?php

class Hipmob_Device
{
  private $hipmob;
  private $id;
  private $platform;
  private $version;
  private $created;
  private $modified;
  private $userdata;
  private $app;

  function __construct($hipmob, $app, $sourcedata, $full = true)
  {
    $this->hipmob = $hipmob;
    $this->app = $app;
    $this->id = $sourcedata->id;
    if($full){
      $this->platform = $sourcedata->platform;
      $this->version = $sourcedata->version;
      $this->created = strtotime($sourcedata->created);
      if(isset($sourcedata->modified)) $this->modified = strtotime($sourcedata->modified);
      else $this->modified = false;
      if(isset($sourcedata->userdata)) $this->userdata = $sourcedata->userdata;
      else $this->userdata = array();
    }else{
      $this->platform = false;
      $this->version = false;
      $this->created = false;
      $this->modified = false;
      $this->userdata = array();
    }    
  }
  
  public function get_id(){ return $this->id; }

  public function get_application(){ return $this->app; }

  public function get_platform(){ return $this->platform; }

  public function get_version(){ return $this->version; }

  public function get_created(){ return $this->created; }

  public function get_modified(){ return $this->modified; }

  public function get_userdata($key){
    if($this->userdata && isset($this->userdata[$key])) return $this->userdata[$key];
    return false;
  }

  public function __toString()
  {
    $res = array();
    $res['id'] = $this->id;
    $res['app'] = $this->app;
    $res['platform'] = $this->platform;
    $res['version'] = $this->version;
    $res['created'] = date("U", $this->created);
    if($this->modified) $res[''] = date('U', $this->modified);
    $res['userdata'] = $this->userdata;
    return json_encode($res);
  }
  
  public function available_message_count()
  {
    return $this->hipmob->_get_available_message_count($this->app, $this->id);
  }

  public function check_device_status()
  {
    return $this->hipmob->_check_device_status($this->app, $this->id);
  }

  public function list_friends()
  {
    return $this->hipmob->_get_device_friends($this->app, $this->id);
  }

  private function _add_friends($devices)
  {
    if(!is_array($devices)) $f = array($devices);
    else $f = $devices;
    return $this->hipmob->_add_device_friends($this->app, $this->id, $f);
  }
  
  public function add_friend($device)
  {
    return $this->_add_friends($device);
  }
  
  public function add_friends($devices)
  {
    return $this->_add_friends($devices);
  }

  private function _set_friends($devices)
  {
    if(!is_array($devices)) $f = array($devices);
    else $f = $devices;
    return $this->hipmob->_set_device_friends($this->app, $this->id, $f);
  }
  
  public function set_friend($device)
  {
    return $this->_set_friends($device);
  }
  
  public function set_friends($devices)
  {
    return $this->_set_friends($devices);
  }

  public function remove_friend($device)
  {
    return $this->hipmob->_remove_device_friend($this->app, $this->id, $device);
  }
  
  public function remove_all_friends()
  {
    return $this->hipmob->_remove_device_friends($this->app, $this->id);
  }

  public function send_text_message($text, $autocreate = false)
  {
    return $this->hipmob->_send_text_message($this->app, $this->id, $text, $autocreate);
  }

  public function send_json_message($content, $autocreate = false)
  {
    return $this->hipmob->_send_json_message($this->app, $this->id, $content, $autocreate);
  }

  public function send_binary_message($content, $autocreate = false)
  {
    return $this->hipmob->_send_binary_message($this->app, $this->id, $content, $autocreate);
  }
  
  public function send_picture_message($file, $mime_type, $autocreate = false)
  {
    return $this->hipmob->_send_file_message($this->app, $this->id, $file, $mime_type, $autocreate);
  }
  
  public function send_audio_message($file, $mime_type, $autocreate = false)
  {
    return $this->hipmob->_send_file_message($this->app, $this->id, $file, $mime_type, $autocreate);
  }

  public function generate_peer_token($secret, $friend)
  {
    $now = time();
    return $now . "|" . hash('sha512', $this->id . "|" . $friend->get_id() . "|" . $now . "|" . $secret);
  }

  public function generate_auth_token($secret)
  {
    $now = time();
    return $now . "|" . hash('sha512', $this->id . "|" . $now . "|" . $secret);
  }
}
