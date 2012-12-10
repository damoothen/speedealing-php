<?php
/* Copyright (C) 2005-2007 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 * or see http://www.gnu.org/
 */

/**
 *		\file       htdocs/core/antispamimage.php
 *		\brief      Return antispam image
 */

define('NOLOGIN',1);

if (! defined('NOREQUIREUSER'))   define('NOREQUIREUSER',1);
if (! defined('NOREQUIREDB'))     define('NOREQUIREDB',1);
if (! defined('NOREQUIRETRAN'))   define('NOREQUIRETRAN',1);
if (! defined('NOREQUIRESOC'))    define('NOREQUIRESOC',1);
if (! defined('NOTOKENRENEWAL'))  define('NOTOKENRENEWAL',1);

require_once '../main.inc.php';


/*
 * View
 */

$length=5;
$letters = 'aAbBCDeEFgGhHJKLmMnNpPqQRsStTuVwWXYZz2345679';
$number = strlen($letters);
$string = '';
for($i = 0; $i < $length; $i++)
{
    $string .= $letters{mt_rand(0, $number - 1)};
}
//print $string;


$sessionkey='dol_antispam_value';
$_SESSION[$sessionkey]=$string;

header("Content-type: image/png");

$img = imagecreate(80,32);
if (empty($img))
{
    dol_print_error('',"Problem with GD creation");
    exit;
}

$background_color = imagecolorallocate($img, 250, 250, 250);
$ecriture_color = imagecolorallocate($img, 0, 0, 0);
imagestring($img, 4, 24, 8, $string, $ecriture_color);
imagepng($img);

?>