<?php
/* Copyright (C) 2011-2012 Regis Houssin  <regis@dolibarr.fr>
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
 *       \file       htdocs/core/ajax/loadinplace.php
 *       \brief      File to load field value
 */

if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1'); // Disables token renewal
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');

require('../../main.inc.php');

$json = GETPOST('json','alpha');
$class = GETPOST('class','alpha');

$field			= GETPOST('field','alpha');
$element		= GETPOST('element','alpha');
$table_element	= GETPOST('table_element','alpha');
$fk_element		= GETPOST('fk_element','alpha');

/*
 * View
 */

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

if (! empty($json) && ! empty($class))
{
	if (!empty($json)) {

		$return=array();

		//if (empty($_SESSION['SelectCompanyStatus']))
		//{
			$langs->load("companies");
			dol_include_once("/".strtolower($class)."/class/".strtolower($class).".class.php");
				
			$object = new $class($db);
				
			foreach ($object->fk_extrafields->fields->$json->values as $key => $aRow)
			{
				if($aRow->enable)
				{
					if(isset($aRow->label))
						$return[$key] = $langs->trans($aRow->label);
					else
						$return[$key] = $langs->trans($key);
				}
			}
				
			$return['selected'] = $object->fk_extrafields->fields->$json->default;
			
			echo json_encode($return);
				
			//$_SESSION['SelectCompanyStatus'] = json_encode($return);
		//}

		//echo $_SESSION['SelectCompanyStatus'];
	}
}
// Load original field value
else if (! empty($field) && ! empty($element) && ! empty($table_element) && ! empty($fk_element))
{
	$ext_element	= GETPOST('ext_element','alpha');
	$field			= substr($field, 8); // remove prefix val_
	$type			= GETPOST('type','alpha');
	$loadmethod		= (GETPOST('loadmethod','alpha') ? GETPOST('loadmethod','alpha') : 'getValueFrom');
	
	if ($element != 'order_supplier' && $element != 'invoice_supplier' && preg_match('/^([^_]+)_([^_]+)/i',$element,$regs))
	{
		$element = $regs[1];
		$subelement = $regs[2];
	}
	
	if ($element == 'propal') $element = 'propale';
	else if ($element == 'fichinter') $element = 'ficheinter';
	else if ($element == 'product') $element = 'produit';
	else if ($element == 'order_supplier') {
		$element = 'fournisseur';
		$subelement = 'commande';
	}
	else if ($element == 'invoice_supplier') {
		$element = 'fournisseur';
		$subelement = 'facture';
	}
	
	if ($user->rights->$element->lire || $user->rights->$element->read
	|| (isset($subelement) && ($user->rights->$element->$subelement->lire || $user->rights->$element->$subelement->read))
	|| ($element == 'payment' && $user->rights->facture->lire)
	|| ($element == 'payment_supplier' && $user->rights->fournisseur->facture->lire))
	{
		if ($type == 'select')
		{
			$methodname	= 'load_cache_'.$loadmethod;
			$cachename = 'cache_'.GETPOST('loadmethod','alpha');
			
			$form = new Form($db);
			if (method_exists($form, $methodname))
			{
				$ret = $form->$methodname();
				if ($ret > 0) echo json_encode($form->$cachename);
			}
			else if (! empty($ext_element))
			{
				dol_include_once('/'.$ext_element.'/class/actions_'.$ext_element.'.class.php');
				$classname = 'Actions'.ucfirst($ext_element);
				$object = new $classname($db);
				$ret = $object->$methodname();
				if ($ret > 0) echo json_encode($object->$cachename);
			}
		}
		else
		{
			$object = new GenericObject($db);
			$value=$object->$loadmethod($table_element, $fk_element, $field);
			echo $value;
		}
	}
	else
	{
		echo $langs->transnoentities('NotEnoughPermissions');
	}
}

?>
