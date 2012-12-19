<?php
/* Copyright (C) 2006-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2007      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2010      Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2010      Juanjo Menent        <jmenent@2byte.es>
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
 *  \file       htdocs/core/lib/order.lib.php
 *  \brief      Ensemble de fonctions de base pour le module commande
 *  \ingroup    commande
 */

/**
 * Function to return list of tabs for import pages
 *
 * @param	string		$param		Params to add on url links
 * @param	int			$maxstep	Limit steps to maxstep or no limit if 0
 * @return	array					Array of tabs
 */
function import_prepare_head($param, $maxstep=0)
{
	global $langs;

	if (empty($maxstep)) $maxstep=6;

	$h=0;
	$head = array();
	$i=1;
	while($i <= $maxstep)
	{
    	$head[$h][0] = $_SERVER["PHP_SELF"].'?step='.$i.$param;
    	$head[$h][1] = $langs->trans("Step")." ".$i;
    	$head[$h][2] = 'step'.$i;
    	$h++;
    	$i++;
	}

	return $head;
}

?>
