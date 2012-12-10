<?php
/* Copyright (C) 2007-2008 Jeremie Ollivier    <jeremie.o@laposte.net>
 * Copyright (C) 2008-2010 Laurent Destailleur <eldy@uers.sourceforge.net>
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
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';

$obj_facturation = unserialize($_SESSION['serObjFacturation']);
unset ($_SESSION['serObjFacturation']);


switch ( $_GET['action'] )
{
	default:
		if ( $_POST['hdnSource'] != 'NULL' )
		{
			$sql = "SELECT p.rowid, p.ref, p.price, p.tva_tx";
			if (! empty($conf->stock->enabled) && !empty($conf_fkentrepot)) $sql.= ", ps.reel";
			$sql.= " FROM ".MAIN_DB_PREFIX."product as p";
			if (! empty($conf->stock->enabled) && !empty($conf_fkentrepot)) $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."product_stock as ps ON p.rowid = ps.fk_product AND ps.fk_entrepot = ".$conf_fkentrepot;
			$sql.= " WHERE p.entity IN (".getEntity('product', 1).")";

			// Recuperation des donnees en fonction de la source (liste deroulante ou champ texte) ...
			if ( $_POST['hdnSource'] == 'LISTE' )
			{
				$sql.= " AND p.rowid = ".$_POST['selProduit'];
			}
			else if ( $_POST['hdnSource'] == 'REF' )
			{
				$sql.= " AND p.ref = '".$_POST['txtRef']."'";
			}

			$result = $db->query($sql);

			if ($result)
			{
				// ... et enregistrement dans l'objet
				if ( $db->num_rows($result) )
				{
					$ret=array();
					$tab = $db->fetch_array($result);
					foreach ( $tab as $key => $value )
					{
						$ret[$key] = $value;
					}

					/** add Ditto for MultiPrix*/
					if (! empty($conf->global->PRODUIT_MULTIPRICES))
					{
						$thirdpartyid = $_SESSION['CASHDESK_ID_THIRDPARTY'];
						$productid = $ret['rowid'];

						$societe = new Societe($db);
						$societe->fetch($thirdpartyid);

						$product = new Product($db);
                        $product->fetch($productid);

						if(isset($product->multiprices[$societe->price_level]))
						{
							$ret['price'] = $product->multiprices[$societe->price_level];
							$ret['price_ttc'] = $product->multiprices_ttc[$societe->price_level];
							// $product->multiprices_min[$societe->price_level];
							// $product->multiprices_min_ttc[$societe->price_level];
							// $product->multiprices_base_type[$societe->price_level];
							$ret['tva_tx'] = $product->multiprices_tva_tx[$societe->price_level];
						}
					}
					/** end add Ditto */

					$obj_facturation->id($ret['rowid']);
					$obj_facturation->ref($ret['ref']);
					$obj_facturation->stock($ret['reel']);
					$obj_facturation->prix($ret['price']);
					$obj_facturation->tva($ret['tva_tx']);

					// Definition du filtre pour n'afficher que le produit concerne
					if ( $_POST['hdnSource'] == 'LISTE' )
					{
						$filtre = $ret['ref'];
					}
					else if ( $_POST['hdnSource'] == 'REF' )
					{
						$filtre = $_POST['txtRef'];
					}

					$redirection = DOL_URL_ROOT.'/cashdesk/affIndex.php?menu=facturation&filtre='.$filtre;
				}
				else
				{
					$obj_facturation->raz();

					if ( $_POST['hdnSource'] == 'REF' )
					{
						$redirection = DOL_URL_ROOT.'/cashdesk/affIndex.php?menu=facturation&filtre='.$_POST['txtRef'];
					}
					else
					{
						$redirection = DOL_URL_ROOT.'/cashdesk/affIndex.php?menu=facturation';
					}
				}
			}
			else
			{
				dol_print_error($db);
			}
		}
		else
		{
			$redirection = DOL_URL_ROOT.'/cashdesk/affIndex.php?menu=facturation';
		}

		break;

	case 'ajout_article':	// We have clicked on button "Add product"

		//var_dump($obj_facturation);
		//exit;

		if (! empty($obj_facturation->id))	// A product has been selected and stored in session
		{
			$obj_facturation->qte($_POST['txtQte']);
			$obj_facturation->tva($_POST['selTva']);
			$obj_facturation->remisePercent($_POST['txtRemise']);
			$obj_facturation->ajoutArticle();

		}

		$redirection = DOL_URL_ROOT.'/cashdesk/affIndex.php?menu=facturation';
		break;

	case 'suppr_article':
		$obj_facturation->supprArticle($_GET['suppr_id']);

		$redirection = DOL_URL_ROOT.'/cashdesk/affIndex.php?menu=facturation';
		break;

}


$_SESSION['serObjFacturation'] = serialize($obj_facturation);

header('Location: '.$redirection);
exit;

?>
