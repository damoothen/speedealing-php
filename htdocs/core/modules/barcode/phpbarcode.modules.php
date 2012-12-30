<?php
/* Copyright (C) 2005-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005      Regis Houssin        <regis.houssin@capnetworks.com>
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
 *	\file       htdocs/core/modules/barcode/phpbarcode.modules.php
 *	\ingroup    barcode
 *	\brief      Fichier contenant la classe du modele de generation code barre phpbarcode
 */

require_once DOL_DOCUMENT_ROOT.'/core/modules/barcode/modules_barcode.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/barcode.lib.php';    // This is to include def like $genbarcode_loc and $font_loc


/**		\class      modPhpbarcode
 *		\brief      Classe du modele de numerotation de generation code barre phpbarcode
 */
class modPhpbarcode extends ModeleBarCode
{
	var $version='dolibarr';		// 'development', 'experimental', 'dolibarr'
	var $error='';


	/**
	 * 	Return if a module can be used or not
	 *
	 *  @return		boolean     true if module can be used
	 */
	function isEnabled()
	{
		return true;
	}


	/**
	 * 	Return description
	 *
	 * 	@return     string      Texte descripif
	 */
	function info()
	{
		global $langs;

		return 'Internal engine';
	}

	/**
	 *  Test si les numeros deja en vigueur dans la base ne provoquent pas de
	 *  de conflits qui empechera cette numerotation de fonctionner.
	 *
	 *	@return     boolean     false si conflit, true si ok
	 */
	function canBeActivated()
	{
		global $langs;

		return true;
	}


	/**
	 *	Return true if encodinf is supported
	 *
	 *	@param	string	$encoding		Encoding norm
	 *	@return	int						>0 if supported, 0 if not
	 */
	function encodingIsSupported($encoding)
	{
		global $genbarcode_loc;
        //print 'genbarcode_loc='.$genbarcode_loc.' encoding='.$encoding;exit;

		$supported=0;
		if ($encoding == 'EAN13') $supported=1;
		if ($encoding == 'ISBN')  $supported=1;
		// Formats that hangs on Windows (when genbarcode.exe for Windows is called, so they are not
		// activated on Windows)
		if (file_exists($genbarcode_loc) && empty($_SERVER["WINDIR"]))
		{
			if ($encoding == 'EAN8')  $supported=1;
			if ($encoding == 'UPC')   $supported=1;
			if ($encoding == 'C39')   $supported=1;
			if ($encoding == 'C128')  $supported=1;
		}
		return $supported;
	}

    /**
	 *	Return an image file on the fly (no need to write on disk)
	 *
	 *	@param	string   	$code			Value to encode
	 *	@param  string	 	$encoding		Mode of encoding
	 *	@param  string	 	$readable		Code can be read
	 *	@return	int							<0 if KO, >0 if OK
     */
	function buildBarCode($code,$encoding,$readable='Y')
	{
		global $_GET,$_SERVER;
		global $conf;
		global $genbarcode_loc, $bar_color, $bg_color, $text_color, $font_loc;

		if (! $this->encodingIsSupported($encoding)) return -1;

		if ($encoding == 'EAN8' || $encoding == 'EAN13') $encoding = 'EAN';
		if ($encoding == 'C39' || $encoding == 'C128')   $encoding = substr($encoding,1);

		$scale=1; $mode='png';

		$_GET["code"]=$code;
		$_GET["encoding"]=$encoding;
		$_GET["scale"]=$scale;
		$_GET["mode"]=$mode;

		dol_syslog(get_class($this)."::buildBarCode $code,$encoding,$scale,$mode");
		if ($code) $result=barcode_print($code,$encoding,$scale,$mode);

		if (! is_array($result))
		{
			$this->error=$result;
			print $this->error;
			return -1;
		}

		return 1;
	}

	/**
	 *	Save an image file on disk (with no output)
	 *
	 *	@param	string   	$code			Value to encode
	 *	@param	string   	$encoding		Mode of encoding
	 *	@param  string	 	$readable		Code can be read
	 *	@return	int							<0 if KO, >0 if OK
	 */
	function writeBarCode($code,$encoding,$readable='Y')
	{
		global $conf,$filebarcode;

		dol_mkdir($conf->barcode->dir_temp);

		$file=$conf->barcode->dir_temp.'/barcode_'.$code.'_'.$encoding.'.png';

		$filebarcode=$file;	// global var to be used in barcode_outimage called by barcode_print in buildBarCode

		$result=$this->buildBarCode($code,$encoding,$readable);

		return $result;
	}

}

?>
