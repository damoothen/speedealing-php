<?php

define("NOLOGIN",1);
define("NOCSRFCHECK",1);
$res=@include("../../main.inc.php");// For "custom" directory
if (! $res) $res=@include("../main.inc.php");
require_once('mailjet.class.php');
$data = json_decode(file_get_contents('php://input'));
$mailJet = new Mailjet($db);
$mailJet->event($data);
?>
