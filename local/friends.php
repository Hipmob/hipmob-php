<?php

require_once("lib/Hipmob.php");
$hipmob = new Hipmob('femi', 'a9c2ad73241a936ba30ccf5a34e66e01414633aa83db072b0bf1753b');

$dev = $hipmob->get_device('488b7ecc3a764176b50717278c6a9ea0','21040b27-8ce4-4cd9-9aa5-8ccd3730d648', false);
print_r($argv);
if($argc == 2){
  if($argv[1] == 'add'){
    // add a friend
    $dev1 = $hipmob->get_device('488b7ecc3a764176b50717278c6a9ea0','486585E1-1E66-4b34-9CB7-8E6E04D647EB', false);
    echo "----------->Added [".$dev->add_friend($dev1)."]\n";
  }else if($argv[1] == 'addall'){
    $devs = array($hipmob->get_device('488b7ecc3a764176b50717278c6a9ea0','A82F3CFA-C914-42da-A9C7-58133D5A6731', false), $hipmob->get_device('488b7ecc3a764176b50717278c6a9ea0','809F4CCD-BAA2-48e9-83A4-EC34F4FDC193', false));
    echo "----------->Added [".$dev->add_friends($devs)."]\n";
  }else if($argv[1] == 'set'){
    $devs = array($hipmob->get_device('488b7ecc3a764176b50717278c6a9ea0','858F43AB-B386-497d-9100-A0A11E064269', false), $hipmob->get_device('488b7ecc3a764176b50717278c6a9ea0','45981ADF-A147-44cc-8F31-CE01AFE7613F', false));
    echo "----------->Set [".$dev->set_friends($devs)."]\n";
  }else if($argv[1] == 'removeall'){
    echo "----------->Remove All [".$dev->remove_all_friends()."]\n";
  }else if($argv[1] == 'remove'){
    echo "----------->Remove All [".$dev->remove_friend($hipmob->get_device('488b7ecc3a764176b50717278c6a9ea0','858F43AB-B386-497d-9100-A0A11E064269', false))."]\n";
  }else{
    // list all the device's friends
    print_r($dev->list_friends());
  }
 }
