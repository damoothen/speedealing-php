<?php
/* Copyright (C) 2007-2008 Jeremie Ollivier <jeremie.o@laposte.net>
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

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/Facturation.class.php';

$obj_facturation = unserialize($_SESSION['serObjFacturation']);
unset($_SESSION['serObjFacturation']);


require ('tpl/ticket.tpl.php');


$_SESSION['serObjFacturation'] = serialize($obj_facturation);

?>



