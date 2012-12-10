<?php
/* Copyright (C) 2011 Patrick Mary  <laube@hotmail.fr>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 *     	\file       htdocs/public/mailjet/evenements.php
 *		\ingroup    core
 *		\brief      Get the json objet for triggers.
 *		\author	    Patrick Mary
 *		\version    $Id: evenements.php,v 1 2011/11/27 13:44:21 synry63 Exp $
 */

define("NOLOGIN",1);
define("NOCSRFCHECK",1);
$res=@include("../../main.inc.php");// For "custom" directory
if (! $res) $res=@include("../main.inc.php");
dol_include_once('/mailjet/class/mailjet.class.php');

$data = json_decode(file_get_contents('php://input'));
$mailJet = new Mailjet($db);
$mailJet->event($data);
?>
