<?php
/* Copyright (C) 2011 Patrick Mary  <laube@hotmail.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
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
