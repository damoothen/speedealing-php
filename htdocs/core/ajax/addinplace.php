<?php

/* Copyright (C) 2011-2012 Regis Houssin    <regis.houssin@capnetworks.com>
 * Copyright (C) 2011-2012 Herve Prot       <herve.prot@symeos.com>
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

if (!defined('NOTOKENRENEWAL'))
	define('NOTOKENRENEWAL', '1'); // Disables token renewal
if (!defined('NOREQUIREMENU'))
	define('NOREQUIREMENU', '1');
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (!defined('NOREQUIREAJAX'))
	define('NOREQUIREAJAX', '1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/genericobject.class.php';

$json = GETPOST('json', 'alpha');
$class = GETPOST('class', 'alpha');
//$id = GETPOST('id', 'alpha');

/*
 * View
 */

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";
//print_r($_POST);
error_log(print_r($_GET, true));
error_log(print_r($_POST, true));

if (!empty($json) && !empty($class)) {
	dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php");

	$object = new $class($db);
	$obj = new stdClass();

	if ($json == "add") {

		foreach ($object->fk_extrafields->fields as $key => $row) {
			if ($row->enable) {
				if (isset($row->class)) {
					$class_tmp = $row->class;
					dol_include_once("/" . strtolower($class_tmp) . "/class/" . strtolower($class_tmp) . ".class.php");
					$object_tmp = new $class_tmp($db);

					$object->$key = new stdClass();
					$obj->$key = new stdClass();

					if (!empty($_POST[$key])) {
						$object_tmp->fetch($_POST[$key]);
						$object->$key->id = $object_tmp->id;
						$object->$key->name = $object_tmp->name;

						$obj->$key->id = $object_tmp->id;
						$obj->$key->name = $object_tmp->name;
					}
				} else {
					if (!empty($_POST[$key])) {
						$object->$key = $_POST[$key];
						$obj->$key = $_POST[$key];
					} else {
						$object->$key = $row->default;
						$obj->$key = $row->default;
					}
				}
			}
		}

		if (method_exists($object, 'addInPlace'))
			$object->addInPlace($obj);

		try {
			$res = $object->record();
			$obj->_id = $res->id;
		} catch (Exception $exc) {
			error_log($exc->getMessage());
			exit;
		}
	} else if ($json == 'addline') {

		$line = new Line();
		$id = GETPOST('fk_invoice', 'alpha');
		$object->load($id);

		$idProduct = GETPOST('product');
		$productData = null;

		if (!empty($idProduct)) {
			dol_include_once("/product/class/product.class.php");
			$product = new Product($db);
			$product->load($idProduct);
			$line->description = $product->description;
			$line->pu = $product->price->price;
			$line->tva_tx = $product->price->tva_tx;
			$product_type = $product->type;
			$productData = new stdClass();
			$productData->id = $idProduct;
			$productData->label = $product->label;
			//$productData->description = $product->description;
		} else {
			$line->pu = GETPOST('pu_ht', "float");
			$line->tva_tx = GETPOST('tva_tx', "float");
			$line->product_type = GETPOST('product_type');
		}
		$line->description = GETPOST('description');
		$line->qty = GETPOST('qty');
		$line->remise = GETPOST('remise');
		$line->product = $productData;
		$line->product_type = $product_type;
		$line->verify();

		$object->updateline(count($object->lines), $line);
		$object->record();

		$obj->_id = $id . '#' . (intval($idline - 1));
		$obj->description = $line->description;
		$obj->pu_ht = $line->pu_ht;
		$obj->qty = $line->qty;
		$obj->remise = $line->remise;
		$obj->tva_tx = $line->tva_tx;
		$obj->total_ht = $line->total_ht;
		$obj->product = $productData;
	}
}

//error_log(json_encode($res));
echo json_encode($obj);
?>
