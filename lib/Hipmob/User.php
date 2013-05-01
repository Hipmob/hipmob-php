<?php

class Hipmob_User
{
  private $hipmob;
  private $username;
  private $first_name;
  private $last_name;
  private $status;

  function __construct($hipmob, $sourcedata)
  {
    $this->hipmob = $hipmob;
    $this->username = $sourcedata->username;
    $this->first_name = $sourcedata->first_name;
    $this->last_name = $sourcedata->last_name;
    $this->status = $sourcedata->status;
  }
  
  public function get_username(){ return $this->username; }

  public function get_first_name(){ return $this->first_name; }
  
  public function get_last_name(){ return $this->last_name; }

  public function get_status(){ return $this->status; }
  
  public function __toString()
  {
    $res = array();
    $res['username'] = $this->username;
    $res['first_name'] = $this->first_name;
    $res['last_name'] = $this->last_name;
    $res['status'] = $this->status;
    return json_encode($res);
  }

  public function set_status($status)
  {
    if($this->hipmob->_set_user_status($this->username, $status)){
      $this->status = $status;
      return true;
    }
    return false;
  }
}