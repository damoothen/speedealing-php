<?php
/* Copyright (C) 2003-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
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
 *	\file       htdocs/core/modules/cheque/pdf/modules_chequereceipts.php
 *	\ingroup    facture
 *	\brief      File with parent class of check receipt document generators
 */

require_once DOL_DOCUMENT_ROOT.'/core/class/commondocgenerator.class.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';   // Requis car utilise dans les classes qui heritent


/**
 *	\class      ModeleChequeReceipts
 *	\brief      Classe mere des modeles de facture
 */
abstract class ModeleChequeReceipts extends CommonDocGenerator
{
	var $error='';

	/**
	 *  Return list of active generation modules
	 *
     *  @param	DoliDB	$db     			Database handler
     *  @param  string	$maxfilenamelength  Max length of value to show
     *  @return	array						List of templates
	 */
	static function liste_modeles($db,$maxfilenamelength=0)
	{
		global $conf;

		$type='chequereceipt';
		$liste=array();

		include_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
		$liste=getListOfModels($db,$type,$maxfilenamelength);
		// TODO Remove this to use getListOfModels only
		$liste = array('blochet'=>'blochet');

		return $liste;
	}
}


/**
 *	Cree un bordereau remise de cheque
 *
 * 	@param	DoliDB		$db				Database handler
 *	@param	int			$id				Object invoice (or id of invoice)
 *	@param	string		$message		Message
 *	@param	string		$modele			Force le modele a utiliser ('' to not force)
 *	@param	Translate	$outputlangs	Object lang a utiliser pour traduction
 *	@return int        					<0 if KO, >0 if OK
 * 	TODO
 */
function chequereceipt_pdf_create($db, $id, $message, $modele, $outputlangs)
{
	global $conf,$langs;
	$langs->load("bills");

	$dir = DOL_DOCUMENT_ROOT . "/core/modules/cheque/pdf/";

	// Positionne modele sur le nom du modele a utiliser
	if (! dol_strlen($modele))
	{
		if (! empty($conf->global->FACTURE_ADDON_PDF))
		{
			$modele = $conf->global->FACTURE_ADDON_PDF;
		}
		else
		{
			//print $langs->trans("Error")." ".$langs->trans("Error_FACTURE_ADDON_PDF_NotDefined");
			//return 0;
			$modele = 'crabe';
		}
	}

	// Charge le modele
	$file = "pdf_".$modele.".modules.php";
	if (file_exists($dir.$file))
	{
		$classname = "pdf_".$modele;
		require_once $dir.$file;

		$obj = new $classname($db);
		$obj->message = $message;

		// We save charset_output to restore it because write_file can change it if needed for
		// output format that does not support UTF8.
		$sav_charset_output=$outputlangs->charset_output;
		if ($obj->write_file($id, $outputlangs) > 0)
		{
			$outputlangs->charset_output=$sav_charset_output;
			return 1;
		}
		else
		{
			$outputlangs->charset_output=$sav_charset_output;
			dol_print_error($db,"chequereceipt_pdf_create Error: ".$obj->error);
			return -1;
		}

	}
	else
	{
		dol_print_error('',$langs->trans("Error")." ".$langs->trans("ErrorFileDoesNotExists",$dir.$file));
		return -1;
	}
}

?>