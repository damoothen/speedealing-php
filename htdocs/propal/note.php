<?php
/* Copyright (C) 2004      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2012 David Moothen        <dmoothen@websitti.fr>
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
 *	\file       htdocs/comm/propal/note.php
 *	\ingroup    propale
 *	\brief      Fiche d'information sur une proposition commerciale
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/propal/class/propal.class.php';
require_once DOL_DOCUMENT_ROOT.'/propal/lib/propal.lib.php';

$langs->load('propal');
$langs->load('compta');
$langs->load('bills');

$id = GETPOST('id','alpha');
$ref=GETPOST('ref','alpha');
$action=GETPOST('action','alpha');

// Security check
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'propal', $id, 'propal');

$object = new Propal($db);


/******************************************************************************/
/*                     Actions                                                */
/******************************************************************************/

if ($action == 'setnote_public' && $user->rights->propal->creer)
{
	$object->fetch($id);
	$result=$object->update_note_public(dol_html_entity_decode(GETPOST('note_public'), ENT_QUOTES));
	if ($result < 0) dol_print_error($db,$object->error);
}

else if ($action == 'setnote' && $user->rights->propal->creer)
{
	$object->fetch($id);
	$result=$object->update_note(dol_html_entity_decode(GETPOST('note'), ENT_QUOTES));
	if ($result < 0) dol_print_error($db,$object->error);
}


/******************************************************************************/
/* Affichage fiche                                                            */
/******************************************************************************/

llxHeader('',$langs->trans('Proposal'),'EN:Commercial_Proposals|FR:Proposition_commerciale|ES:Presupuestos');

$form = new Form($db);

if (!(empty($id)) || ! empty($ref))
{
	if ($mesg) print $mesg;

	$now=dol_now();

	if ($object->fetch($id, $ref))
	{
		$societe = new Societe($db);
		if ( $societe->fetch($object->socid) )
		{
			$head = propal_prepare_head($object);
			dol_fiche_head($head, 'note', $langs->trans('Proposal'), 0, 'propal');

			print '<table class="border" width="100%">';

			$linkback = '<a href="'.DOL_URL_ROOT.'/comm/propal/list.php'.(! empty($socid)?'?socid='.$socid:'').'">'.$langs->trans('BackToList').'</a>';

			// Ref
			print '<tr><td width="25%">'.$langs->trans('Ref').'</td><td colspan="3">';
//			print $form->showrefnav($object,'ref',$linkback,1,'ref','ref','');
			print $object->ref;
                        print '</td></tr>';

			// Ref client
			print '<tr><td>';
			print '<table class="nobordernopadding" width="100%"><tr><td nowrap>';
			print $langs->trans('RefCustomer').'</td><td align="left">';
			print '</td>';
			print '</tr></table>';
			print '</td><td colspan="3">';
			print $object->ref_client;
			print '</td>';
			print '</tr>';

			// Customer
			if ( is_null($object->client) )
				$object->fetch_thirdparty();
			print "<tr><td>".$langs->trans("Company")."</td>";
			print '<td colspan="3">'.$object->client->getNomUrl(1).'</td></tr>';

			// Ligne info remises tiers
			print '<tr><td>'.$langs->trans('Discounts').'</td><td colspan="3">';
			if ($societe->remise_client) print $langs->trans("CompanyHasRelativeDiscount",$societe->remise_client);
			else print $langs->trans("CompanyHasNoRelativeDiscount");
			$absolute_discount=$societe->getAvailableDiscounts();
			print '. ';
			if ($absolute_discount) print $langs->trans("CompanyHasAbsoluteDiscount",price($absolute_discount),$langs->trans("Currency".$conf->currency));
			else print $langs->trans("CompanyHasNoAbsoluteDiscount");
			print '.';
			print '</td></tr>';

			// Date
			print '<tr><td>'.$langs->trans('Date').'</td><td colspan="3">';
			print dol_print_date($object->date,'daytext');
			print '</td>';
			print '</tr>';

			// Date fin propal
			print '<tr>';
			print '<td>'.$langs->trans('DateEndPropal').'</td><td colspan="3">';
			if ($object->fin_validite)
			{
				print dol_print_date($object->fin_validite,'daytext');
				if ($object->statut == 1 && $object->fin_validite < ($now - $conf->propal->cloture->warning_delay)) print img_warning($langs->trans("Late"));
			}
			else
			{
				print $langs->trans("Unknown");
			}
			print '</td>';
			print '</tr>';

			print "</table>";

			print '<br>';

			include DOL_DOCUMENT_ROOT.'/core/tpl/notes.tpl.php';

			dol_fiche_end();
		}
	}
}


llxFooter();
$db->close();
?>
