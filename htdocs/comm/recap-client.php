<?php
/* Copyright (C) 2001-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *   	\file       htdocs/comm/recap-client.php
 *		\ingroup    societe
 *		\brief      Page de fiche recap client
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';

$langs->load("companies");
if (! empty($conf->facture->enabled)) $langs->load("bills");

// Security check
$socid = $_GET["socid"];
if ($user->societe_id > 0)
{
  $action = '';
  $socid = $user->societe_id;
}



/*
 *	View
 */

llxHeader();

if ($socid > 0)
{
    $societe = new Societe($db);
    $societe->fetch($socid);

    /*
     * Affichage onglets
     */
	$head = societe_prepare_head($societe);

    dol_fiche_head($head, 'customer', $langs->trans("ThirdParty"), 0, 'company');


    print "<table width=\"100%\">\n";
    print '<tr><td valign="top" width="50%">';

    print '<table class="border" width="100%">';

    // Nom
    print '<tr><td width="20%">'.$langs->trans("Name").'</td><td width="80%" colspan="3">'.$societe->nom.'</td></tr>';

    // Prefix
    if (! empty($conf->global->SOCIETE_USEPREFIX))  // Old not used prefix field
    {
        print '<tr><td>'.$langs->trans("Prefix").'</td><td colspan="3">';
        print ($societe->prefix_comm?$societe->prefix_comm:'&nbsp;');
        print '</td></tr>';
    }

    print "</table>";

    print "</td></tr></table>\n";

    print '</div>';


	print $langs->trans("FeatureNotYetAvailable");
}
else
{
  	dol_print_error($db);
}


$db->close();

llxFooter();
?>
