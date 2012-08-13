<?php

require_once("lib/Hipmob.php");

// get my apps
$hipmob = new Hipmob('invalid', 'invalid');
$apps = $hipmob->get_applications();
print_r($apps);

