<?php

require_once("lib/Hipmob.php");

// get my apps
$hipmob = new Hipmob('femi','e66686de5bbfec2a4010e1124deae3e0852442ac1292276c7b2ce1f9');
$apps = $hipmob->get_applications();
print_r($apps);

