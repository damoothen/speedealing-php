<?php
/* Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 * 		\file   	htdocs/compta/resultat/bilan.php
 * 		\ingroup    compta
 * 		\brief  	Fichier page bilan compta
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/compta/tva/class/tva.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/sociales/class/chargesociales.class.php';

if (!$user->rights->compta->resultat->lire) accessforbidden();


/*
 *	Views
 */

llxHeader();

$year=$_GET["year"];
$month=$_GET["month"];
if (! $year) { $year = strftime("%Y", time()); }


/* Le compte de r�sultat est un document officiel requis par l'administration selon le status ou activit� */

print_titre("Bilan".($year?" annee $year":""));

print '<br>';

print $langs->trans("FeatureNotYetAvailable");


llxFooter();

$db->close();
?>
