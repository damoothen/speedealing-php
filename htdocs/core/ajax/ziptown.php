<?php
/* Copyright (C) 2011-2013	Regis Houssin		<regis.houssin@capnetworks.com>
 * Copyright (C) 2011		Laurent Destailleur	<eldy@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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
 *       \file      core/ajax/ziptown.php
 *       \ingroup	core
 *       \brief     File to return Ajax response on zipcode or town request
 */

if (! defined('NOTOKENRENEWAL'))
	define('NOTOKENRENEWAL',1); // Disables token renewal
if (! defined('NOREQUIREMENU'))
	define('NOREQUIREMENU','1');
if (! defined('NOREQUIREHTML'))
	define('NOREQUIREHTML','1');
if (! defined('NOREQUIREAJAX'))
	define('NOREQUIREAJAX','1');
if (! defined('NOREQUIRESOC'))
	define('NOREQUIRESOC','1');
if (! defined('NOCSRFCHECK'))
	define('NOCSRFCHECK','1');

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/admin/class/dict.class.php';
//require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';

$zipcode = GETPOST('zipcode', 'alpha');
$town = GETPOST('town', 'alpha');


/*
 * View
 */

top_httphead('json');

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

//var_dump($_GET);

// Generation of list of zip-town
if (!empty($zipcode) || !empty($town))
{
	$dict = new Dict($db);

	if (!empty($zipcode)) {
		$startkey = array($zipcode);
		if (is_numeric($zipcode))
			$endkey = array($zipcode . '9999');
		else
			$endkey = array($zipcode . '\uffff');
		$params = array('group' => true, 'startkey' => $startkey, 'endkey' => $endkey);
		$result = $dict->getView('townByZip', $params);
	} else {
		$startkey = array($town);
		$endkey = array($town . '\uffff');
		$params = array('group' => true, 'startkey' => $startkey, 'endkey' => $endkey);
		$result = $dict->getView('zipByTown', $params);
	}

	$return_arr = array();
	$row_array = array();

	if (!empty($result->rows)) {
		foreach ($result->rows as $aRow) {

			$zipcodeval = (!empty($zipcode)?$aRow->key[0]:$aRow->key[1]);
			$townval = (!empty($zipcode)?$aRow->key[1]:$aRow->key[0]);

			$country = ($aRow->key[2]?$langs->trans('Country'.$aRow->key[2]):'');
			$county = ($aRow->key[3]?$langs->trans($aRow->key[3]):'');

			$row_array['label'] = $zipcodeval.' '.$townval;
			$row_array['label'] .= ($county || $country)?' (':'';
			$row_array['label'] .= $county;
			$row_array['label'] .= ($county && $country?' - ':'');
			$row_array['label'] .= $country;
			$row_array['label'] .= ($county || $country)?')':'';

			if (!empty($zipcode)) {
				$row_array['value'] = $zipcodeval;
				$row_array['town'] = $townval;
			} else {
				$row_array['value'] = $townval;
				$row_array['zipcode'] = $zipcodeval;
			}

			$row_array['selectcountry_id'] = $aRow->key[2];

			array_push($return_arr, $row_array);
		}
	}

/*
	if ($resql)
	{
		while ($row = $db->fetch_array($resql))
		{
			$row_array['departement_id'] = $row['fk_county'];    // deprecated
			$row_array['selectcountry_id'] = $row['fk_country'];
			$row_array['state_id'] = $row['fk_county'];

			$row_array['states'] = $formcompany->select_state('',$row['fk_country'],'');

			array_push($return_arr,$row_array);
		}
	}
	*/

	echo json_encode($return_arr);
}

?>
