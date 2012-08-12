<?php

class Hipmob_App
{
  private $hipmob;
  private $id;
  private $name;
  private $url;
  private $created;
  private $modified;

  function __construct($hipmob, $sourcedata)
  {
    $this->hipmob = $hipmob;
    $this->id = $sourcedata->id;
    $this->name = $sourcedata->name;
    $this->url = $sourcedata->url;    
    $this->created = strtotime($sourcedata->created);
    if(isset($sourcedata->modified)) $this->modified = strtotime($sourcedata->modified);
    else $this->modified = false;
  }
  
  public function get_id(){ return $this->id; }

  public function get_name(){ return $this->name; }

  public function get_url(){ return $this->url; }

  public function get_created(){ return $this->created; }

  public function get_modified(){ return $this->modified; }

  public function __toString()
  {
    $res = array();
    $res['id'] = $this->id;
    $res['name'] = $this->name;
    $res['url'] = $this->url;
    $res['created'] = date("U", $this->created);
    if($this->modified) $res[''] = date('U', $this->modified);
    return json_encode($res);
  }
}