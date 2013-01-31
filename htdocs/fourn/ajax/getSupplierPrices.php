<?php
/* Copyright (C) 2012      Christophe Battarel  <christophe.battarel@altairis.fr>
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
 *
 */

/**
 *	\file       /htdocs/fourn/ajax/getSupplierPrices.php
 *	\brief      File to return Ajax response on get supplier prices
 */

if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1'); // Disables token renewal
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');

require '../../main.inc.php';
require_once(DOL_DOCUMENT_ROOT . '/product/class/product.class.php');

$idprod=GETPOST('idprod','alpha');

$prices = array();

$langs->load('stocks');

/*
 * View
*/

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

if (! empty($idprod))
{
//	$sql = "SELECT p.rowid, p.label, p.ref, p.price, p.duration,";
//	$sql.= " pfp.ref_fourn,";
//	$sql.= " pfp.rowid as idprodfournprice, pfp.price as fprice, pfp.quantity, pfp.unitprice, pfp.charges, pfp.unitcharges,";
//	$sql.= " s.nom";
//	$sql.= " FROM ".MAIN_DB_PREFIX."product_fournisseur_price as pfp";
//	$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."product as p ON p.rowid = pfp.fk_product";
//	$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."societe as s ON s.rowid = pfp.fk_soc";
//	$sql.= " WHERE pfp.fk_product = ".$idprod;
//	$sql.= " AND p.tobuy = 1";
//	$sql.= " AND s.fournisseur = 1";
//	$sql.= " ORDER BY s.nom, pfp.ref_fourn DESC";
//
//	dol_syslog("Ajax::getSupplierPrices sql=".$sql, LOG_DEBUG);
//	$result=$db->query($sql);

    $product = new Product($db);
    $result = $product->getView('list', array('startkey' => $idprod, 'endkey' => $idprod . 'Z'));

	if (!empty($result->rows))
	{
		$num = count($result->rows);

		if ($num)
		{
			$i = 0;
			while ($i < $num)
			{
                                $objp = new Product($db);
                                $objp->fecth($result->$rows[$i]->key);

				$title = $objp->nom.' - '.$objp->ref_fourn.' - ';
				$label = '';

				if ($objp->quantity == 1)
				{
					$label.= price($objp->fprice).$langs->getCurrencySymbol($conf->currency)."/".strtolower($langs->trans("Unit"));

					$title.= price($objp->fprice);
					$title.= $langs->getCurrencySymbol($conf->currency)."/";

					$price = $objp->fprice;
				}

				$title.= $objp->quantity.' ';

				if ($objp->quantity == 1)
				{
					$title.= strtolower($langs->trans("Unit"));
				}
				else
				{
					$title.= strtolower($langs->trans("Units"));
				}
				if ($objp->quantity > 1)
				{
					$title.=" - ";
					$title.= price($objp->unitprice).$langs->getCurrencySymbol($conf->currency)."/".strtolower($langs->trans("Unit"));

					$label.= price($objp->unitprice).$langs->getCurrencySymbol($conf->currency)."/".strtolower($langs->trans("Unit"));

					$price = $objp->unitprice;
				}
				if ($objp->unitcharges > 0 && ($conf->global->MARGIN_TYPE == "2")) {
					$title.=" + ";
					$title.= price($objp->unitcharges).$langs->getCurrencySymbol($conf->currency);
					$price += $objp->unitcharges;
				}
				if ($objp->duration) $label .= " - ".$objp->duration;

				$prices[] = array("id" => $objp->idprodfournprice, "price" => price($price,0,'',0), "label" => $label, "title" => $title);
				$i++;
			}

			$db->free($result);
		}
	}

	echo json_encode($prices);
}

?>
