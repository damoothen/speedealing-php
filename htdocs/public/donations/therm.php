<?php
/* Copyright (C) 2002 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2008 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *	    \file       htdocs/public/donations/therm.php
 *      \ingroup    donation
 *		\brief      Screen with thermometer
 */

define("NOLOGIN",1);		// This means this output page does not require to be logged.
define("NOCSRFCHECK",1);	// We accept to go on this page from external web site.

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT.'/compta/dons/class/don.class.php';

// Security check
if (empty($conf->don->enabled)) accessforbidden('',1,1,1);



/*
 * 	View (output an image)
 */

$dontherm = new Don($db);

$intentValue  = $dontherm->sum_donations(1);
$pendingValue = $dontherm->sum_donations(2);
$actualValue  = $dontherm->sum_donations(3);

$db->close();


/*
 * Graph thermometer
 */
print moneyMeter($actualValue, $pendingValue, $intentValue);

?>
