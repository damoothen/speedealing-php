<?php
/* Copyright (C) 2003-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2007 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2011 Regis Houssin        <regis@dolibarr.fr>
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
 *   \file       htdocs/core/modules/barcode/modules_barcode.php
 *   \ingroup    barcode
 *   \brief      Fichier contenant la classe mere de generation des codes barres
 */
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions.lib.php';


/**
 *  \class      ModeleBarCode
 *	\brief      Classe mere des modeles de code barre
 */
abstract class ModeleBarCode
{
	var $error='';


	/**
	 * Return if a module can be used or not
	 *
	 * @return		boolean     true if module can be used
	 */
	function isEnabled()
	{
		return true;
	}

}

?>
