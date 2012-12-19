<?php
/* Copyright (C) 2012 Regis Houssin  <regis@dolibarr.fr>
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
 *       \file       htdocs/core/ajax/price.php
 *       \brief      File to get ht and ttc
 */

if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1'); // Disables token renewal
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');

require('../../main.inc.php');

$output		= GETPOST('output','alpha');
$amount		= price2num(GETPOST('amount','alpha'));
$tva_tx		= str_replace('*','',GETPOST('tva_tx','alpha'));

/*
 * View
 */

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

// Load original field value
if (! empty($output) && isset($amount) && isset($tva_tx))
{
	$return=array();
	$price='';

	if (is_numeric($amount) && $amount != '')
	{
		if ($output == 'price_ttc') {

			$price = price2num($amount * (1 + ($tva_tx/100)), 'MU');
			$return['price_ht'] = $amount;
			$return['price_ttc'] = (isset($price) && $price != '' ? price($price) : '');

		}
		else if ($output == 'price_ht') {

			$price = price2num($amount / (1 + ($tva_tx/100)), 'MU');
			$return['price_ht'] = (isset($price) && $price != '' ? price($price) : '');
			$return['price_ttc'] = ($tva_tx == 0 ? $price : $amount);
		}
	}

	echo json_encode($return);
}

?>
