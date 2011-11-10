<?php
/* Copyright (C) 2010-2011 Laurent Destailleur <ely@users.sourceforge.net>
 * Copyright (C) 2010-2011 Herve Prot          <herve.prot@symeos.com>
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
 *	\file       htdocs/core/modules/societe/doc/doc_generic_odt.modules.php
 *	\ingroup    societe
 *	\brief      File of class to build ODT documents for third parties
 *	\author	    Laurent Destailleur
 */

require_once(DOL_DOCUMENT_ROOT."/core/modules/contract/modules_contract.php");
require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/company.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/files.lib.php");


/**
 *	\class      doc_generic_odt
 *	\brief      Class to build documents using ODF templates generator
 */
class doc_generic_contract_odt extends ModeleContract
{
	var $emetteur;	// Objet societe qui emet

	var $phpmin = array(5,2,0);	// Minimum version of PHP required by module


	/**
	 *	Constructor
	 *
	 *  @param		DoliDB		$DB      Database handler
	 */
	function doc_generic_contract_odt($db)
	{
		global $conf,$langs,$mysoc;

		$langs->load("main");
		$langs->load("companies");

		$this->db = $db;
		$this->name = "ODT templates";
		$this->description = $langs->trans("DocumentModelOdt");
		$this->scandir = 'COMPANY_ADDON_PDF_ODT_PATH';	// Name of constant that is used to save list of directories to scan

		// Dimension page pour format A4
		$this->type = 'odt';
		$this->page_largeur = 0;
		$this->page_hauteur = 0;
		$this->format = array($this->page_largeur,$this->page_hauteur);
		$this->marge_gauche=0;
		$this->marge_droite=0;
		$this->marge_haute=0;
		$this->marge_basse=0;

		$this->option_logo = 1;                    // Affiche logo

		// Recupere emmetteur
		$this->emetteur=$mysoc;
		if (! $this->emetteur->pays_code) $this->emetteur->pays_code=substr($langs->defaultlang,-2);    // Par defaut, si n'etait pas defini
	}


	/**
	 * Return description of a module
	 *
	 * @param	Translate	$langs		Object language
	 * @return	string      			Description
	 */
	function info($langs)
	{
		global $conf,$langs;

		$langs->load("companies");
		$langs->load("errors");

		$form = new Form($db);

		$texte = $this->description.".<br>\n";
		$texte.= '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">';
		$texte.= '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
		$texte.= '<input type="hidden" name="action" value="setModuleOptions">';
		$texte.= '<input type="hidden" name="param1" value="COMPANY_ADDON_PDF_ODT_PATH">';
		$texte.= '<table class="nobordernopadding" width="100%">';

		// List of directories area
		$texte.= '<tr><td>';
		$texttitle=$langs->trans("ListOfDirectories");
		$listofdir=explode(',',preg_replace('/[\r\n]+/',',',trim($conf->global->COMPANY_ADDON_PDF_ODT_PATH)));
		$listoffiles=array();
		foreach($listofdir as $key=>$tmpdir)
		{
			$tmpdir=trim($tmpdir);
			$tmpdir=preg_replace('/DOL_DATA_ROOT/',DOL_DATA_ROOT,$tmpdir);
			if (! $tmpdir) { unset($listofdir[$key]); continue; }
			if (! is_dir($tmpdir)) $texttitle.=img_warning($langs->trans("ErrorDirNotFound",$tmpdir),0);
			else
			{
				$tmpfiles=dol_dir_list($tmpdir,'files',0,'\.odt');
				if (count($tmpfiles)) $listoffiles=array_merge($listoffiles,$tmpfiles);
			}
		}
		$texthelp=$langs->trans("ListOfDirectoriesForModelGenODT");
		// Add list of substitution keys
		$texthelp.='<br>'.$langs->trans("FollowingSubstitutionKeysCanBeUsed").'<br>';
		$texthelp.=$langs->transnoentitiesnoconv("FullListOnOnlineDocumentation");    // This contains an url, we don't modify it

		$texte.= $form->textwithpicto($texttitle,$texthelp,1,'help','',1);
        $texte.= '<table><tr><td>';
		$texte.= '<textarea class="flat" cols="60" name="value1">';
		$texte.=$conf->global->COMPANY_ADDON_PDF_ODT_PATH;
		$texte.= '</textarea>';
        $texte.= '</td>';
        $texte.= '<td align="center">&nbsp; ';
        $texte.= '<input type="submit" class="button" value="'.$langs->trans("Modify").'" name="Button">';
        $texte.= '</td>';
        $texte.= '</tr>';
        $texte.= '</table>';

		// Scan directories
		if (count($listofdir)) $texte.=$langs->trans("NumberOfModelFilesFound").': <b>'.count($listoffiles).'</b>';

		$texte.= '</td>';


		$texte.= '<td valign="top" rowspan="2">';
		$texte.= $langs->trans("ExampleOfDirectoriesForModelGen");
		$texte.= '</td>';
		$texte.= '</tr>';

		/*$texte.= '<tr><td align="center">';
		$texte.= '<input type="submit" class="button" value="'.$langs->trans("Modify").'" name="Button">';
		$texte.= '</td>';
		$texte.= '</tr>';*/

		$texte.= '</table>';
		$texte.= '</form>';

		return $texte;
	}

	/**
	 *	Function to build a document on disk using the generic odt module.
	 *
	 *	@param	Societe		$object				Object source to build document
	 *	@param	Translate	$outputlangs		Lang output object
	 * 	@param	string		$srctemplatepath	Full path of source filename for generator using a template file
	 *	@return	int         					1 if OK, <=0 if KO
	 */
	function write_file($object,$outputlangs,$srctemplatepath)
	{
		global $user,$langs,$conf,$mysoc;

		if (empty($srctemplatepath))
		{
			dol_syslog("doc_generic_odt::write_file parameter srctemplatepath empty", LOG_WARNING);
			return -1;
		}

		if (! is_object($outputlangs)) $outputlangs=$langs;
		$sav_charset_output=$outputlangs->charset_output;
		$outputlangs->charset_output='UTF-8';

		$outputlangs->load("main");
		$outputlangs->load("dict");
		$outputlangs->load("companies");
		$outputlangs->load("projects");

		if ($conf->contrat->dir_output)
		{
                        $soc = new Societe($this->db);
			$soc->fetch($object->socid);
                    
			$dir = $conf->contrat->dir_output;
			$objectref = dol_sanitizeFileName($object->ref);
			if (! preg_match('/specimen/i',$objectref)) $dir.= "/" . $objectref;

			if (! file_exists($dir))
			{
				if (create_exdir($dir) < 0)
				{
					$this->error=$langs->transnoentities("ErrorCanNotCreateDir",$dir);
					return -1;
				}
			}

			if (file_exists($dir))
			{
				//print "srctemplatepath=".$srctemplatepath;	// Src filename
				$newfile=basename($srctemplatepath);
				$newfiletmp=preg_replace('/\.odt/i','',$newfile);
				$newfiletmp=preg_replace('/template_/i','',$newfiletmp);
				$newfiletmp=preg_replace('/modele_/i','',$newfiletmp);
				$file=$dir.'/'.$newfiletmp.'.'.dol_print_date(dol_now(),'%Y%m%d%H%M%S').'.odt';
				//print "newdir=".$dir;
				//print "newfile=".$newfile;
				//print "file=".$file;
				//print "conf->societe->dir_temp=".$conf->societe->dir_temp;

				create_exdir($conf->contrat->dir_temp);
                                
                                // If BILLING contact defined on invoice, we use it
                $usecontact=false;
                $arrayidcontact=$object->getIdContact('external','SALESREPSIGN');
                if (count($arrayidcontact) > 0)
                {
                    $usecontact=true;
                    $result=$soc->fetch_contact($arrayidcontact[0]);
                }

                // Recipient name
                if (! empty($usecontact))
                {
                    // On peut utiliser le nom de la societe du contact
                    if ($conf->global->MAIN_USE_COMPANY_NAME_OF_CONTACT) $socobject = $object->contact;
                    else $socobject = $soc->client;
                }
                else
                {
                    $socobject=$soc->client;
                }
                
                // Make substitution
                $substitutionarray=array(
                    '__FROM_NAME__' => $this->emetteur->nom,
                    '__FROM_EMAIL__' => $this->emetteur->email,
                    '__TOTAL_TTC__' => $object->total_ttc,
                    '__TOTAL_HT__' => $object->total_ht,
                    '__TOTAL_VAT__' => $object->total_vat,
                    'date' => dol_print_date($object->date_contrat,"%d %b %Y")
                );
                complete_substitutions_array($substitutionarray, $langs, $object);
                
                	

				// Open and load template
				require_once(ODTPHP_PATH.'odf.php');
				$odfHandler = new odf($srctemplatepath, array(
						'PATH_TO_TMP'	  => $conf->contrat->dir_temp,
						'ZIP_PROXY'		  => 'PclZipProxy',	// PhpZipProxy or PclZipProxy. Got "bad compression method" error when using PhpZipProxy.
						'DELIMITER_LEFT'  => '{',
						'DELIMITER_RIGHT' => '}')
				);
                                
				//print $odfHandler->__toString()."\n";
                                //
                             
                foreach($substitutionarray as $key=>$value)
                {
                    try {
                        if (preg_match('/logo$/',$key)) // Image
                        {
                            //var_dump($value);exit;
                            if (file_exists($value)) $odfHandler->setImage($key, $value);
                            else $odfHandler->setVars($key, 'ErrorFileNotFound', true, 'UTF-8');
                        }
                        else    // Text
                        {
                            //print $key.' '.$value;exit;
                            $odfHandler->setVars($key, $value, true, 'UTF-8');
                        }
                    }
                    catch(OdfException $e)
                    {
                    }
                }
				// Make substitutions into odt of user info
			    $tmparray=$this->get_substitutionarray_user($user,$outputlangs);
                //var_dump($tmparray); exit;
                foreach($tmparray as $key=>$value)
                {
                    try {
                        if (preg_match('/logo$/',$key)) // Image
                        {
                            //var_dump($value);exit;
                            if (file_exists($value)) $odfHandler->setImage($key, $value);
                            else $odfHandler->setVars($key, 'ErrorFileNotFound', true, 'UTF-8');
                        }
                        else    // Text
                        {
                            //print $key.' '.$value;exit;
                            $odfHandler->setVars($key, $value, true, 'UTF-8');
                        }
                    }
                    catch(OdfException $e)
                    {
                    }
                }
                // Make substitutions into odt of mysoc info
                $tmparray=$this->get_substitutionarray_mysoc($mysoc,$outputlangs);
				//var_dump($tmparray); exit;
				foreach($tmparray as $key=>$value)
				{
					try {
						if (preg_match('/logo$/',$key))	// Image
						{
							//var_dump($value);exit;
							if (file_exists($value)) $odfHandler->setImage($key, $value);
							else $odfHandler->setVars($key, 'ErrorFileNotFound', true, 'UTF-8');
						}
						else	// Text
						{
							$odfHandler->setVars($key, $value, true, 'UTF-8');
						}
					}
					catch(OdfException $e)
					{
					}
				}
                // Make substitutions into odt of thirdparty + external modules
				$tmparray=$this->get_substitutionarray_thirdparty($soc,$outputlangs);
                //complete_substitutions_array($tmparray, $langs, $object);
                //var_dump($object->id); exit;
				foreach($tmparray as $key=>$value)
				{
					try {
						if (preg_match('/logo$/',$key))	// Image
						{
							if (file_exists($value)) $odfHandler->setImage($key, $value);
							else $odfHandler->setVars($key, 'ErrorFileNotFound', true, 'UTF-8');
						}
						else	// Text
						{
							$odfHandler->setVars($key, $value, true, 'UTF-8');
						}
					}
					catch(OdfException $e)
					{
					}
				}
                                 // Get extra fields for contractid
                                include_once(DOL_DOCUMENT_ROOT.'/core/class/hookmanager.class.php');
                                $hookmanager=new HookManager($this->db);
                                $hookmanager->callHooks(array('contrat_extrafields'));
                                $parameters=array('id'=>$object->id);
                                $values=$hookmanager->executeHooks('getFields',$parameters,$object,GETPOST('action'));    // Note that $action and $object may have been modified by hook
				foreach($values as $key => $value)
				{
					try {
						if (preg_match("/^options_/",$key))
						{
                                                    $var=substr($key, 8,strlen($key)); // retire options_
                                                    $odfHandler->setVars($var, $values[$key], true, 'UTF-8');
						}
					}
					catch(OdfException $e)
					{
					}
				}
                                
                                // Get extra fields for socid
                                include_once(DOL_DOCUMENT_ROOT.'/core/class/hookmanager.class.php');
                                $hookmanager=new HookManager($this->db);
                                $hookmanager->callHooks(array('thirdparty_extrafields'));
                                $parameters=array('id'=>$soc->id);
                                $values=$hookmanager->executeHooks('getFields',$parameters,$soc,GETPOST('action'));    // Note that $action and $object may have been modified by hook
				foreach($values as $key => $value)
				{
					try {
						if (preg_match("/^options_/",$key))
						{
                                                    $var=substr($key, 8,strlen($key)); // retire options_
                                                    $odfHandler->setVars($var, $values[$key], true, 'UTF-8');
						}
					}
					catch(OdfException $e)
					{
					}
				}

				// Write new file
				//$result=$odfHandler->exportAsAttachedFile('toto');
				$odfHandler->saveToDisk($file);

				if (! empty($conf->global->MAIN_UMASK))
				@chmod($file, octdec($conf->global->MAIN_UMASK));

				$odfHandler=null;	// Destroy object

				return 1;   // Success
			}
			else
			{
				$this->error=$langs->transnoentities("ErrorCanNotCreateDir",$dir);
				return -1;
			}
		}

		return -1;
	}

}

?>
