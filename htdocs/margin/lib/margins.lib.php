<?php
/* Copyright (C) 2012	Christophe Battarel	<christophe.battarel@altairis.fr>
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
 *	\file			/htdocs/margin/lib/margins.lib.php
 *  \ingroup		margin
 *  \brief			Library for common margin functions
 */

/**
 *  Define head array for tabs of marges tools setup pages
 *
 *  @return			Array of head
 */
function marges_admin_prepare_head()
{
	global $langs, $conf;

	$h = 0;
	$head = array();

	$head[$h][0] = DOL_URL_ROOT."/margin/admin/margin.php";
	$head[$h][1] = $langs->trans("Parameters");
	$head[$h][2] = 'parameters';
	$h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf,$langs,'',$head,$h,'margesadmin');

    return $head;
}

function marges_prepare_head($user)
{
	global $langs, $conf;
	$langs->load("marges@marges");

	$h = 0;
	$head = array();

	$head[$h][0] = DOL_URL_ROOT."/margin/productMargins.php";
	$head[$h][1] = $langs->trans("ProductMargins");
	$head[$h][2] = 'productMargins';
	$h++;

	$head[$h][0] = DOL_URL_ROOT."/margin/customerMargins.php";
	$head[$h][1] = $langs->trans("CustomerMargins");
	$head[$h][2] = 'customerMargins';
	$h++;

	$head[$h][0] = DOL_URL_ROOT."/margin/agentMargins.php";
	$head[$h][1] = $langs->trans("AgentMargins");
	$head[$h][2] = 'agentMargins';
	$h++;

	return $head;
}

/**
 * getMarginInfos
 *
 * @param 	float 	$pvht				Buying price with tax
 * @param 	float	$remise_percent		Discount percent
 * @param 	float	$tva_tx				Vat rate
 * @param 	float	$localtax1_tx		Vat rate special 1
 * @param 	float	$localtax2_tx		Vat rate special 2
 * @param 	int		$fk_pa				???
 * @param 	float	$paht				Buying price without tax
 * @return	array						Array of margin info
 */
function getMarginInfos($pvht, $remise_percent, $tva_tx, $localtax1_tx, $localtax2_tx, $fk_pa, $paht)
{
  global $db, $conf;

  $marge_tx_ret='';
  $marque_tx_ret='';

  if($fk_pa > 0) {
  	require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.product.class.php';
  	$product = new ProductFournisseur($db);
  	if ($product->fetch_product_fournisseur_price($fk_pa)) {
  		$paht_ret = $product->fourn_unitprice;
  		if ($conf->global->MARGIN_TYPE == "2" && $product->fourn_unitcharges > 0)
  			$paht_ret += $product->fourn_unitcharges;
  	}
  	else
  		$paht_ret = $paht;
  }
  else
  	$paht_ret	= $paht;

  require_once DOL_DOCUMENT_ROOT.'/core/lib/price.lib.php';
  // calcul pu_ht remisés
  $tabprice=calcul_price_total(1, $pvht, $remise_percent, $tva_tx, $localtax1_tx, $localtax2_tx, 0, 'HT');
  $pu_ht_remise = $tabprice[0];
  // calcul taux marge
  if ($paht_ret != 0)
  	$marge_tx_ret = round((100 * ($pu_ht_remise - $paht_ret)) / $paht_ret, 3);
  // calcul taux marque
  if ($pu_ht_remise != 0)
  	$marque_tx_ret = round((100 * ($pu_ht_remise - $paht_ret)) / $pu_ht_remise, 3);

  return array($paht_ret, $marge_tx_ret, $marque_tx_ret);
}
?>