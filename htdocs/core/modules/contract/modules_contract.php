<?php
/* Copyright (C) 2003-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2007 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2006      Andre Cianfarani     <acianfa@free.fr>
 * Copyright (C) 2011      Juanjo Menent	    <jmenent@2byte.es>
 * Copyright (C) 2011      Herve Prot          <herve.prot@symeos.com>
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
 * or see http://www.gnu.org/
 */

/**
 *  \file       htdocs/core/modules/contract/modules_contract.php
 *  \ingroup    contract
 *  \brief      File of class to manage contract numbering
 */

require_once(DOL_DOCUMENT_ROOT."/core/class/commondocgenerator.class.php");

class ModelNumRefContracts
{
	var $error='';

	/**     
	 *	Return if a module can be used or not
	 * @return		boolean     true if module can be used
	 */
	function isEnabled()
	{
		return true;
	}

	/**
	 *	Return default description of numbering model
	 *	@return     string      text description
	 */
	function info()
	{
		global $langs;
		$langs->load("contracts");
		return $langs->trans("NoDescription");
	}

	/**     
	 *	Return numbering example
	 *	@return     string      Example
	 */
	function getExample()
	{
		global $langs;
		$langs->load("contracts");
		return $langs->trans("NoExample");
	}

	/**     
	 *	Test if existing numbers make problems with numbering
	 *	@return     boolean     false if conflit, true if ok
	 */
	function canBeActivated()
	{
		return true;
	}

	/** 
	 *	Return next value
	 *	@return     string      Value
	 */
	function getNextValue()
	{
		global $langs;
		return $langs->trans("NotAvailable");
	}

	/**
	 *	Return numbering version module
	 *	@return     string      Value
	 */
	function getVersion()
	{
		global $langs;
		$langs->load("admin");

		if ($this->version == 'development') return $langs->trans("VersionDevelopment");
		if ($this->version == 'experimental') return $langs->trans("VersionExperimental");
		if ($this->version == 'dolibarr') return DOL_VERSION;
		return $langs->trans("NotAvailable");
	}
}
/**
 *	\class      ModelePDFFactures
 *	\brief      Classe mere des modeles de facture
 */
abstract class ModeleContract extends CommonDocGenerator
{
	var $error='';

	/**
	 *  Return list of active generation modules
	 * 	@param		$db		Database handler
	 */
	function liste_modeles($db,$maxfilenamelength=0)
	{
		global $conf;

		$type='contrat';
		$liste=array();

		include_once(DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php');
		$liste=getListOfModels($db,$type,$maxfilenamelength);

		return $liste;
	}
}

/**
 *  Create a document onto disk accordign to template module.
 *
 *	@param   	DoliDB		$db  			Database handler
 *	@param   	Object		$object			Object invoice
 *	@param	    string		$message		message
 *	@param	    string		$modele			Force le modele a utiliser ('' to not force)
 *	@param		Translate	$outputlangs	objet lang a utiliser pour traduction
 *  @param      int			$hidedetails    Hide details of lines
 *  @param      int			$hidedesc       Hide description
 *  @param      int			$hideref        Hide ref
 *  @param      HookManager	$hookmanager	Hook manager instance
 *	@return  	int        					<0 if KO, >0 if OK
 */
function contrat_pdf_create($db, $object, $message, $modele, $outputlangs, $hidedetails=0, $hidedesc=0, $hideref=0, $hookmanager=false)
{
	global $conf,$user,$langs;

	$langs->load("contrat");

	// Increase limit for PDF build
    $err=error_reporting();
    error_reporting(0);
    @set_time_limit(120);
    error_reporting($err);

    $dir = "/core/modules/contract/";
    $srctemplatepath='';

    // If selected modele is a filename template (then $modele="modelname:filename")
	$tmp=explode(':',$modele,2);
    if (! empty($tmp[1]))
    {
        $modele=$tmp[0];
        $srctemplatepath=$tmp[1];
    }

	// Search template file
	$file=''; $classname=''; $filefound=0;
	foreach(array('doc','pdf') as $prefix)
	{
        $file = $prefix."_".$modele.".modules.php";
        
        // On verifie l'emplacement du modele
        $file = dol_buildpath($dir.'doc/'.$file);
        
        if (file_exists($file))
	    {
	        $filefound=1;
	        $classname=$prefix.'_'.$modele;
	        break;
	    }
	}

	// Charge le modele
	if ($filefound)
	{
		require_once($file);

		$obj = new $classname($db);
		$obj->message = $message;

		// We save charset_output to restore it because write_file can change it if needed for
		// output format that does not support UTF8.
		$sav_charset_output=$outputlangs->charset_output;
		if ($obj->write_file($object, $outputlangs, $srctemplatepath, $hidedetails, $hidedesc, $hideref, $hookmanager) > 0)
		{
			// Success in building document. We build meta file.
			//facture_meta_create($db, $object->id);
			// et on supprime l'image correspondant au preview
			//facture_delete_preview($db, $object->id);

			$outputlangs->charset_output=$sav_charset_output;

			// Appel des triggers
			include_once(DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php");
			$interface=new Interfaces($db);
			$result=$interface->run_triggers('BILL_BUILDDOC',$object,$user,$langs,$conf);
			if ($result < 0) { $error++; $this->errors=$interface->errors; }
			// Fin appel triggers

			return 1;
		}
		else
		{
			$outputlangs->charset_output=$sav_charset_output;
			dol_print_error($db,"contract_create Error: ".$obj->error);
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
