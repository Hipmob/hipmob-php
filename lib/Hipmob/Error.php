<?php

class Hipmob_Error extends Exception
{
  public function __construct($message=null, $http_status=null)
  {
    parent::__construct($message);
    $this->http_status = $http_status;
  }

  public function getHttpStatus()
  {
    return $this->http_status;
  }
}