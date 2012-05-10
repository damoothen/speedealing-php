<?php
/* Copyright (C) 2001-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005      Brice Davoleau       <brice.davoleau@gmail.com>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2006-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2007      Patrick Raguin  		<patrick.raguin@gmail.com>
 * Copyright (C) 2010      Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2010-2011 Herve Prot           <herve.prot@symeos.com>
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
 */

/**
 *  \file       htdocs/categories/categorie.php
 *  \ingroup    category
 *  \brief      Page to show category tab
 */

require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT."/categories/class/categorie.class.php");
require_once(DOL_DOCUMENT_ROOT."/comm/action/class/actioncomm.class.php");

$langs->load("categories");
$langs->load("products");

$socid	= GETPOST('socid','int');
$id		= GETPOST('id','int');
$ref	= GETPOST('ref');
$type	= GETPOST('type');
$mesg	= GETPOST('mesg');

$removecat = GETPOST('removecat','int');

$dbtablename = '';


// For categories on third parties
if (! empty($socid)) $id = $socid;
if (! isset($type)) $type = 0;
if ($type == 1 || $type == 2) $socid = $id;

if ($id || $ref)
{
	if ($type == 0) {
		$elementtype = 'product';
		$objecttype = 'produit|service&categorie';
		$objectid = isset($id)?$id:(isset($ref)?$ref:'');
		$dbtablename = 'product';
		$fieldid = isset($ref)?'ref':'rowid';
	}
	if ($type == 1) {
		$elementtype = 'fournisseur';
		$objecttype = 'societe&categorie';
		$objectid = isset($id)?$id:(isset($socid)?$socid:'');
		$fieldid = 'rowid';
	}
	if ($type == 2) {
		$elementtype = 'societe';
		$objecttype = 'societe&categorie';
		$objectid = isset($id)?$id:(isset($socid)?$socid:'');
		$dbtablename = '&societe';
		$fieldid = 'rowid';
	}
	if ($type == 3) {
		$elementtype = 'member';
		$objecttype = 'adherent&categorie';
		$objectid = isset($id)?$id:(isset($ref)?$ref:'');
		$dbtablename = 'adherent';
		$fieldid = ! empty($ref)?'ref':'rowid';
	}
        if ($_REQUEST["type"] == 4) {
		$type = 'lead';
		$objecttype = 'lead&categorie';
		$objectid = isset($_REQUEST["id"])?$_REQUEST["id"]:(isset($_REQUEST["ref"])?$_REQUEST["ref"]:'');
		$dbtablename = 'lead';
		$fieldid = isset($_REQUEST["ref"])?'ref':'rowid';
	}
	if ($_REQUEST["type"] == 5) {
        $type = 'contact';
        $objecttype = 'contact&categorie';
        $objectid = isset($_REQUEST["id"])?$_REQUEST["id"]:(isset($_REQUEST["ref"])?$_REQUEST["ref"]:'');
        $dbtablename = 'socpeople';
        $fieldid = isset($_REQUEST["ref"])?'ref':'rowid';
    }
}

// Security check
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user,$objecttype,$objectid,$dbtablename,'','',$fieldid);


/*
 *	Actions
 */

//Suppression d'un objet d'une categorie
if ($removecat > 0)
{
	if ($type==0 && ($user->rights->produit->creer || $user->rights->service->creer))
	{
		require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");
		$object = new Product($db);
		$result = $object->fetch($id, $ref);
		$elementtype = 'product';
	}
	if ($type==1 && $user->rights->societe->creer)
	{
		$object = new Societe($db);
		$result = $object->fetch($objectid);
	}
	if ($type==2 && $user->rights->societe->creer)
	{
		$object = new Societe($db);
		$result = $object->fetch($objectid);
	}
	if ($type == 3 && $user->rights->adherent->creer)
	{
		require_once(DOL_DOCUMENT_ROOT."/adherents/class/adherent.class.php");
		$object = new Adherent($db);
		$result = $object->fetch($objectid);
	}
        if ($_REQUEST["type"] == 4 && $user->rights->lead->creer)
	{
		require_once(DOL_DOCUMENT_ROOT."/lead/class/lead.class.php");
		$object = new lead($db);
		$result = $object->fetch($objectid);
	}
        if ($_REQUEST["type"] == 5 && $user->rights->societe->creer)
	{
		require_once(DOL_DOCUMENT_ROOT."/contact/class/contact.class.php");
		$object = new Contact($db);
		$result = $object->fetch($objectid);
	}
	$cat = new Categorie($db);
	$result=$cat->fetch($removecat);

	$result=$cat->del_type($object,$elementtype);
}

// Add object into a category
if (isset($_REQUEST["catMere"]) && $_REQUEST["catMere"]>=0)
{
	if ($type==0 && ($user->rights->produit->creer || $user->rights->service->creer))
	{
		require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");
		$object = new Product($db);
		$result = $object->fetch($id, $ref);
		$elementtype = 'product';
	}
	if ($type==1 && $user->rights->societe->creer)
	{
		$object = new Societe($db);
		$result = $object->fetch($objectid);
		$elementtype = 'fournisseur';
	}
	if ($type==2 && $user->rights->societe->creer)
	{
		$object = new Societe($db);
		$result = $object->fetch($objectid);
		$elementtype = 'societe';
	}
	if ($type==3 && $user->rights->adherent->creer)
	{
		require_once(DOL_DOCUMENT_ROOT."/adherents/class/adherent.class.php");
		$object = new Adherent($db);
		$result = $object->fetch($objectid);
		$elementtype = 'member';
	}
        if ($_REQUEST["type"] == 4 && $user->rights->lead->creer)
	{
		require_once(DOL_DOCUMENT_ROOT."/lead/class/lead.class.php");
		$object = new lead($db);
		$result = $object->fetch($objectid);
                $type='lead';
	}
        if ($_REQUEST["type"] == 5 && $user->rights->societe->creer)
	{
		require_once(DOL_DOCUMENT_ROOT."/contact/class/contact.class.php");
		$object = new Contact($db);
		$result = $object->fetch($objectid);
	}
	$cat = new Categorie($db);
	$result=$cat->fetch($_REQUEST["catMere"]);

	$result=$cat->add_type($object,$elementtype);
	if ($result >= 0)
	{
		$mesg='<div class="ok">'.$langs->trans("WasAddedSuccessfully",$cat->label).'</div>';
                if($_REQUEST["type"]==2)
                {
                    $actioncomm = new ActionComm($db);
                    $actioncomm->addAutoTask('AC_QUALIF',"Qualification prospect/client/contact : ".$cat->label,$_GET["socid"],'','');
                }
                if($_REQUEST["type"]==5)
                {
                    $actioncomm = new ActionComm($db);
                    $actioncomm->addAutoTask('AC_QUALIF',"Qualification prospect/client/contact : ".$cat->label,$object->socid,'','',$object->id);
                }
	}
	else
	{
		if ($cat->error == 'DB_ERROR_RECORD_ALREADY_EXISTS') $mesg='<div class="error">'.$langs->trans("ObjectAlreadyLinkedToCategory").'</div>';
		else $mesg=$langs->trans("Error").' '.$cat->error;
	}

}


/*
 *	View
 */

$form = new Form($db);


/*
 * Fiche categorie de client et/ou fournisseur
 */
if ($socid)
{
	require_once(DOL_DOCUMENT_ROOT."/core/lib/company.lib.php");
	require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");

	$langs->load("companies");
	if ($conf->notification->enabled) $langs->load("mails");

	$soc = new Societe($db);
	$result = $soc->fetch($socid);

	llxHeader("","",$langs->trans("Category"));

	// Show tabs
	$head = societe_prepare_head($soc);

	dol_fiche_head($head, 'category', $langs->trans("ThirdParty"),0,'company');

	print '<table class="border" width="100%">';

	print '<tr><td width="25%">'.$langs->trans("ThirdPartyName").'</td><td colspan="3">';
	print $form->showrefnav($soc,'socid','',($user->societe_id?0:1),'rowid','nom','','&type='.$type);
	print '</td></tr>';

	// Name
	print '<tr '.$bc[$var].'><td id="label" width="20%">'.$langs->trans('Name').'</td>';
	print '<td colspan="1" id="value" width="30%">';
	print $soc->getNomUrl(1);
	print '</td>';

    if (! empty($conf->global->SOCIETE_USEPREFIX))  // Old not used prefix field
	{
	   print '<tr><td>'.$langs->trans('Prefix').'</td><td colspan="3">'.$soc->prefix_comm.'</td></tr>';
	}

	if ($soc->client)
	{
		print '<tr><td>';
		print $langs->trans('CustomerCode').'</td><td colspan="3">';
		print $soc->code_client;
		if ($soc->check_codeclient() <> 0) print ' <font class="error">('.$langs->trans("WrongCustomerCode").')</font>';
		print '</td></tr>';
	}

	if ($soc->fournisseur)
	{
		print '<tr><td>';
		print $langs->trans('SupplierCode').'</td><td colspan="3">';
		print $soc->code_fournisseur;
		if ($soc->check_codefournisseur() <> 0) print ' <font class="error">('.$langs->trans("WrongSupplierCode").')</font>';
		print '</td></tr>';
	}

	if ($conf->global->MAIN_MODULE_BARCODE)
	{
		print '<tr><td>'.$langs->trans('Gencod').'</td><td colspan="3">'.$soc->barcode.'</td></tr>';
	}

	// Address
	print '<tr><td valign="top">'.$langs->trans('Address').'</td><td colspan="3">'.nl2br($soc->address).'</td></tr>';

	// Zip / Town
	print '<tr><td width="25%">'.$langs->trans('Zip').'</td><td width="25%">'.$soc->cp."</td>";
	print '<td width="25%">'.$langs->trans('Town').'</td><td width="25%">'.$soc->ville."</td></tr>";

	// Country
	if ($soc->pays)
	{
		print '<tr><td>'.$langs->trans('Country').'</td><td colspan="3">';
		$img=picto_from_langcode($soc->country_code);
		print ($img?$img.' ':'');
		print $soc->pays;
		print '</td></tr>';
	}

	// Phone
	print '<tr><td>'.$langs->trans('Phone').'</td><td>'.dol_print_phone($soc->tel,$soc->country_code,0,$soc->id,'AC_TEL').'</td>';
	print '<td>'.$langs->trans('Fax').'</td><td>'.dol_print_phone($soc->fax,$soc->country_code,0,$soc->id,'AC_FAX').'</td></tr>';

	// EMail
	print '<tr><td>'.$langs->trans('EMail').'</td><td>';
	print dol_print_email($soc->email,0,$soc->id,'AC_EMAIL');
	print '</td>';

	// Web
	print '<td>'.$langs->trans('Web').'</td><td>';
	print dol_print_url($soc->url);
	print '</td></tr>';

	// Assujeti a TVA ou pas
	print '<tr>';
	print '<td nowrap="nowrap">'.$langs->trans('VATIsUsed').'</td><td colspan="3">';
	print yn($soc->tva_assuj);
	print '</td>';
	print '</tr>';

	print '</table>';
        print '</td>';
        print '<td valign="top" width="50%">';

        print '</td>';
        print '<td>';
        print '</tr>';
        print '</table>';

	print '</div>';

	dol_htmloutput_mesg($mesg);

	if ($soc->client) formCategory($db,$soc,2,$socid);

	if ($soc->client && $soc->fournisseur) print '<br><br>';

	if ($soc->fournisseur) formCategory($db,$soc,1,$socid);
}
else if ($id || $ref)
{
	if ($type == 0)
	{
		$langs->load("products");

		/*
		 * Fiche categorie de produit
		 */
		require_once(DOL_DOCUMENT_ROOT."/core/lib/product.lib.php");
		require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");

		// Produit
		$product = new Product($db);
		$result = $product->fetch($id, $ref);

		llxHeader("","",$langs->trans("CardProduct".$product->type));


		$head=product_prepare_head($product, $user);
		$titre=$langs->trans("CardProduct".$product->type);
		$picto=($product->type==1?'service':'product');
		dol_fiche_head($head, 'category', $titre,0,$picto);


		print '<table class="border" width="100%">';

		// Ref
		print "<tr>";
		print '<td width="15%">'.$langs->trans("Ref").'</td><td>';
		print $form->showrefnav($product,'ref','',1,'ref');
		print '</td>';
		print '</tr>';

		// Label
		print '<tr><td>'.$langs->trans("Label").'</td><td>'.$product->libelle.'</td>';
		print '</tr>';

		// Status (to sell)
		print '<tr><td>'.$langs->trans("Status").' ('.$langs->trans("Sell").')</td><td>';
		print $product->getLibStatut(2,0);
		print '</td></tr>';

		// Status (to buy)
		print '<tr><td>'.$langs->trans("Status").' ('.$langs->trans("Buy").')</td><td>';
		print $product->getLibStatut(2,1);
		print '</td></tr>';

		print '</table>';

		print '</div>';

		dol_htmloutput_mesg($mesg);

		formCategory($db,$product,0);
	}

	if ($type == 3)
	{
		$langs->load("members");

		/*
		 * Fiche categorie d'adherent
		 */
		require_once(DOL_DOCUMENT_ROOT."/core/lib/member.lib.php");
		require_once(DOL_DOCUMENT_ROOT."/adherents/class/adherent.class.php");
		require_once(DOL_DOCUMENT_ROOT."/adherents/class/adherent_type.class.php");

		// Produit
		$member = new Adherent($db);
		$result = $member->fetch($id, $ref);

		$membert = new AdherentType($db);
		$membert->fetch($member->typeid);

		llxHeader("","",$langs->trans("Member"));


		$head=member_prepare_head($member, $user);
		$titre=$langs->trans("Member");
		$picto='user';
		dol_fiche_head($head, 'category', $titre,0,$picto);

        $rowspan=5;
        if (empty($conf->global->ADHERENT_LOGIN_NOT_REQUIRED)) $rowspan+=1;
        if ($conf->societe->enabled) $rowspan++;

		print '<table class="border" width="100%">';

		// Ref
		print '<tr><td width="20%">'.$langs->trans("Ref").'</td>';
		print '<td class="valeur">';
		print $form->showrefnav($member,'rowid');
		print '</td></tr>';

        // Login
        if (empty($conf->global->ADHERENT_LOGIN_NOT_REQUIRED))
        {
    		print '<tr><td>'.$langs->trans("Login").' / '.$langs->trans("Id").'</td><td class="valeur">'.$member->login.'&nbsp;</td></tr>';
        }

        // Morphy
        print '<tr><td>'.$langs->trans("Nature").'</td><td class="valeur" >'.$member->getmorphylib().'</td>';
        /*print '<td rowspan="'.$rowspan.'" align="center" valign="middle" width="25%">';
        print $form->showphoto('memberphoto',$member);
        print '</td>';*/
        print '</tr>';

        // Type
        print '<tr><td>'.$langs->trans("Type").'</td><td class="valeur">'.$membert->getNomUrl(1)."</td></tr>\n";

        // Company
        print '<tr><td>'.$langs->trans("Company").'</td><td class="valeur">'.$member->societe.'</td></tr>';

        // Civility
        print '<tr><td>'.$langs->trans("UserTitle").'</td><td class="valeur">'.$member->getCivilityLabel().'&nbsp;</td>';
        print '</tr>';

        // Nom
		print '<tr><td>'.$langs->trans("Lastname").'</td><td class="valeur">'.$member->nom.'&nbsp;</td>';
		print '</tr>';

		// Prenom
		print '<tr><td>'.$langs->trans("Firstname").'</td><td class="valeur">'.$member->prenom.'&nbsp;</td>';
		print '</tr>';

		// Status
		print '<tr><td>'.$langs->trans("Status").'</td><td class="valeur">'.$member->getLibStatut(4).'</td></tr>';

		print '</table>';

		print '</div>';

		dol_htmloutput_mesg($mesg);

		formCategory($db,$member,3);
	}
        if ($_GET["type"] == 4)
	{
            $langs->load("lead@lead");

		/*
		 * Fiche categorie produit lié à une affaire
		 */

            require_once(DOL_DOCUMENT_ROOT."/lead/lib/lead.lib.php");
            require_once(DOL_DOCUMENT_ROOT."/lead/class/lead.class.php");

            llxHeader('',$langs->trans('Product'),'EN:Commercial_Products|FR:Liste_des_produits|ES:Presupuestos');

            $id = GETPOST('id');
            $ref= GETPOST('ref');

            if ($id > 0 || ! empty($ref))
            {
                /*
                * Show object in view mode
                */

                if ($mesg)
                {
                	if (! preg_match('/div class=/',$mesg)) print '<div class="ok">'.$mesg.'</div><br>';
                        else print $mesg."<br>";
                }

                $object = new Lead($db);

                $object->fetch($id,$ref);

                $soc = new Societe($db);
                $soc->fetch($object->socid);

                $head = lead_prepare_head($object);
                dol_fiche_head($head, 'category', $langs->trans('Products'), 0, 'lead');

                /*
                * Fiche Affaire
                *
                */

                print '<table class="border" width="100%">';

                $linkback="<a href=\"product.php?page=$page&socid=$socid&viewstatut=$viewstatut&sortfield=$sortfield&$sortorder\">".$langs->trans("BackToList")."</a>";

                // Ref
                print '<tr><td>'.$langs->trans('Ref').'</td><td colspan="5">';
                print $html->showrefnav($object,'ref','',1,'ref','ref','');
                print '</td></tr>';

                $rowspan=9;

                // Company
                print '<tr><td>'.$langs->trans('Company').'</td><td colspan="5">'.$soc->getNomUrl(1).'</td>';
                print '</tr>';

                // Date of proposal
                print '<tr>';
                print '<td>';
                print '<table class="nobordernopadding" width="100%"><tr><td>';
                print $langs->trans('DateStart');
                print '</td>';
                if ($_GET['action'] != 'editdate' && $object->brouillon) print '<td align="right"><a href="'.$_SERVER["PHP_SELF"].'?action=editdate&amp;id='.$object->id.'">'.img_edit($langs->trans('SetDate'),1).'</a></td>';
                print '</tr></table>';
                print '</td><td colspan="3">';
                if ($object->brouillon && $_GET['action'] == 'editdate')
                {
                    print '<form name="editdate" action="'.$_SERVER["PHP_SELF"].'?id='.$object->id.'" method="post">';
                    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
                    print '<input type="hidden" name="action" value="setdate">';
                    $html->select_date($object->date,'re','','',0,"editdate");
                    print '<input type="submit" class="button" value="'.$langs->trans('Modify').'">';
                    print '</form>';
                }
                else
                {
                    if ($object->date_start)
                    {
			print dol_print_date($object->date_start,'daytext');
                    }
                    else
                    {
			print '&nbsp;';
                    }
                }
                print '</td>';

                // Notes
                print '<td valign="top" colspan="2" width="50%" rowspan="'.$rowspan.'">'.$langs->trans('Note').' :<br>'. nl2br($object->description).'</td>';
                print '</tr>';

                // Date fin Lead
                print '<tr>';
                print '<td>';
                print '<table class="nobordernopadding" width="100%"><tr><td>';
                print $langs->trans('DateEnd');
                print '</td>';
                if ($_GET['action'] != 'editecheance' && $object->brouillon) print '<td align="right"><a href="'.$_SERVER["PHP_SELF"].'?action=editecheance&amp;id='.$object->id.'">'.img_edit($langs->trans('SetConditions'),1).'</a></td>';
                print '</tr></table>';
                print '</td><td colspan="3">';
                if ($object->brouillon && $_GET['action'] == 'editecheance')
                {
                    print '<form name="editecheance" action="'.$_SERVER["PHP_SELF"].'?id='.$object->id.'" method="post">';
                    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
                    print '<input type="hidden" name="action" value="setecheance">';
                    $html->select_date($object->fin_validite,'ech','','','',"editecheance");
                    print '<input type="submit" class="button" value="'.$langs->trans('Modify').'">';
                    print '</form>';
                }
                else
                {
                    if ($object->fin_validite)
                    {
			print dol_print_date($object->fin_validite,'daytext');
			if ($object->statut == 1 && $object->fin_validite < ($now - $conf->Lead->cloture->warning_delay)) print img_warning($langs->trans("Late"));
                    }
                    else
                    {
			print '&nbsp;';
                    }
                }
                print '</td>';
                print '</tr>';

                // Amount HT
                print '<tr><td height="10">'.$langs->trans('CAHT').'</td>';
                print '<td align="right" colspan="2" nowrap>'.$object->getCALevel().'</td>';
                print '<td>'.$langs->trans("Currency".$conf->monnaie).'</td></tr>';

                // Statut
                print '<tr><td height="10">'.$langs->trans('Status').'</td><td align="left" colspan="3">'.$object->getLibStatut(4).'</td></tr>';
                print '</table><br>';

                print '</div>';
                print "\n";

                formCategory($db,$object,4);

            }
            else
            {
                dol_print_error($db);
            }
        }
        if ($_GET["type"] == 5)
	{
		$langs->load("contact");

		/*
		 * Fiche categorie Contact
		 */
		require_once(DOL_DOCUMENT_ROOT."/lib/contact.lib.php");
		require_once(DOL_DOCUMENT_ROOT."/contact/class/contact.class.php");

                // Produit
		$contact = new Contact($db);
		$objsoc = new Societe($db);
		if ($_GET["ref"]) $result = $contact->fetch('',$_GET["ref"]);
		if ($_GET["id"]) $result = $contact->fetch($_GET["id"]);

                llxHeader('',$langs->trans("ContactsAddresses"),'EN:Module_Third_Parties|FR:Module_Tiers|ES:M&oacute;dulo_Empresas');

		$head = contact_prepare_head($contact);

		dol_fiche_head($head, 'category', $langs->trans("ContactsAddresses"), 0, 'contact');

		print '<table class="border" width="100%">';

        	// Name
	        print '<tr><td width="20%">'.$langs->trans("Lastname").'</td><td width="30%">'.$contact->name.'</td>';
	        print '<td width="25%">'.$langs->trans("Firstname").'</td><td width="25%">'.$contact->firstname.'</td></tr>';

	        // Company
	        print '<tr><td>'.$langs->trans("Company").'</td><td colspan="3">';
	        if ($contact->socid > 0)
        	{
                	$objsoc->fetch($contact->socid);
	                print $objsoc->getNomUrl(1);
	        }
	        else
	        {
	                print $langs->trans("ContactNotLinkedToCompany");
	        }
	        print '</td></tr>';

	        // Civility
	        print '<tr><td width="15%">'.$langs->trans("UserTitle").'</td><td colspan="3">';
	        print $contact->getCivilityLabel();
	        print '</td></tr>';

	        print '<tr><td>'.$langs->trans("PostOrFunction" ).'</td><td colspan="3">'.$contact->poste.'</td>';

	        // Address
	        print '<tr><td>'.$langs->trans("Address").'</td><td colspan="3">'.nl2br($contact->address).'</td></tr>';

	        // Zip Town
	        print '<tr><td>'.$langs->trans("Zip").' / '.$langs->trans("Town").'</td><td colspan="3">';
	        print $contact->cp;
	        if ($contact->cp) print '&nbsp;';
	        print $contact->ville.'</td></tr>';

	        // Country
	        print '<tr><td>'.$langs->trans("Country").'</td><td colspan="3">';
	        $img=picto_from_langcode($contact->pays_code);
	        if ($img) print $img.' ';
	        print $contact->pays;
	        print '</td></tr>';

	        // Department
	        print '<tr><td>'.$langs->trans('State').'</td><td colspan="3">'.$contact->departement.'</td>';

	        // Phone
	        print '<tr><td>'.$langs->trans("PhonePro").'</td><td>'.dol_print_phone($contact->phone_pro,$contact->pays_code,$contact->id,$contact->socid,'AC_TEL').'</td>';
	        print '<td>'.$langs->trans("PhonePerso").'</td><td>'.dol_print_phone($contact->phone_perso,$contact->pays_code,$contact->id,$contact->socid,'AC_TEL').'</td></tr>';

	        print '<tr><td>'.$langs->trans("PhoneMobile").'</td><td>'.dol_print_phone($contact->phone_mobile,$contact->pays_code,$contact->id,$contact->socid,'AC_TEL').'</td>';
	        print '<td>'.$langs->trans("Fax").'</td><td>'.dol_print_phone($contact->fax,$contact->pays_code,$contact->id,$contact->socid,'AC_FAX').'</td></tr>';

	        // Email
	        print '<tr><td>'.$langs->trans("EMail").'</td><td>'.dol_print_email($contact->email,$contact->id,$contact->socid,'AC_EMAIL').'</td>';
	        if ($conf->mailing->enabled)
	        {
        	        $langs->load("mails");
	                print '<td nowrap>'.$langs->trans("NbOfEMailingsReceived").'</td>';
	                print '<td><a href="'.DOL_URL_ROOT.'/comm/mailing/liste.php?filteremail='.urlencode($contact->email).'">'.$contact->getNbOfEMailings().'</a></td>';
	        }
	        else
	        {
	                print '<td colspan="2">&nbsp;</td>';
	        }
	        print '</tr>';

		print '</table>';

		print '</div>';

		if ($mesg) print($mesg);

		formCategory($db,$contact,5);
	}
}


/**
 * 	Function to output a HTML select for a category
 *
 * 	@param		DoliDb		$db			Database handler
 * 	@param		Object		$object		Object we want to see categories it can be classified into
 * 	@param		int			$typeid		Type of category (0, 1, 2, 3)
 *  @param		int			$socid		Id thirdparty
 *  @return		int			0
 */
function formCategory($db,$object,$typeid,$socid=0)
{
	global $user,$langs,$form,$bc;

	if ($typeid == 0) $title = $langs->trans("ProductsCategoriesShort");
	if ($typeid == 1) $title = $langs->trans("SuppliersCategoriesShort");
	if ($typeid == 2) $title = $langs->trans("CustomersProspectsCategoriesShort");
	if ($typeid == 3) $title = $langs->trans("MembersCategoriesShort");
        if ($typeid == 4) $title = $langs->trans("ProductsCategoriesShort");
        if ($typeid == 5) $title = $langs->trans("ContactsCategoriesShort");

	// Formulaire ajout dans une categorie
	print '<br>';
	print_fiche_titre($title,'','');
	print '<form method="post" action="'.DOL_URL_ROOT.'/categories/categorie.php">';
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print '<input type="hidden" name="typeid" value="'.$typeid.'">';
	print '<input type="hidden" name="type" value="'.$typeid.'">';
	print '<input type="hidden" name="id" value="'.$object->id.'">';
	print '<table class="noborder" width="100%">';
	print '<tr class="liste_titre"><td class="liste_titre" width="25%">';
	print $langs->trans("ClassifyInCategory").'</td><td>';
        if($typeid==4) print $form->select_all_categories(0);
        else
            print $form->select_all_categories($typeid);
	print '<input type="submit" class="button" value="'.$langs->trans("Classify").'"></td>';
	if ($user->rights->categorie->creer)
	{
		print '<td align="right">';
		print '<a href="'.DOL_URL_ROOT.'/categories/fiche.php?action=create&amp;origin='.$object->id.'&type='.$typeid.'&urlfrom='.urlencode($_SERVER["PHP_SELF"].'?'.(($typeid==1||$typeid==2)?'socid':'id').'='.$object->id.'&type='.$typeid).'">';
		print $langs->trans("CreateCat").' ';
		print img_picto($langs->trans("Create"),'filenew');
		print "</a>";
		print '</td>';
	}
	print '</tr>';
	print '</table>';
	print '</form>';
	print '<br/>';


	$c = new Categorie($db);
	$cats = $c->containing($object->id,$typeid);

	if (count($cats) > 0)
	{
		if ($typeid == 0) $title=$langs->trans("ProductIsInCategories");
		if ($typeid == 1) $title=$langs->trans("CompanyIsInSuppliersCategories");
		if ($typeid == 2) $title=$langs->trans("CompanyIsInCustomersCategories");
		if ($typeid == 3) $title=$langs->trans("MemberIsInCategories");
                if ($typeid == 4) $title=$langs->trans("LeadProductIsInCategories");
		if ($typeid == 5) $title=$langs->trans("ContactIsInCategories");;
		print "\n";
		print '<table class="noborder" width="100%">';
		print '<tr class="liste_titre"><td colspan="2">'.$title.':</td></tr>';

		$var = true;
		foreach ($cats as $cat)
		{
			$ways = $cat->print_all_ways();
			foreach ($ways as $way)
			{
				$var = ! $var;
				print "<tr ".$bc[$var].">";

				// Categorie
				print "<td>";
				//$c->id=;
				//print $c->getNomUrl(1);
				print img_object('','category').' '.$way."</td>";

				// Lien supprimer
				print '<td align="right">';
				$permission=0;
				if ($typeid == 0) $permission=($user->rights->produit->creer || $user->rights->service->creer);
				if ($typeid == 1) $permission=$user->rights->societe->creer;
				if ($typeid == 2) $permission=$user->rights->societe->creer;
				if ($typeid == 3) $permission=$user->rights->adherent->creer;
                                if ($typeid == 4) $permission=$user->rights->lead->creer;
				if ($typeid == 5) $permission=$user->rights->societe->creer;
				if ($permission)
				{
					print "<a href= '".$_SERVER['PHP_SELF']."?".(empty($socid)?'id':'socid')."=".$object->id."&amp;type=".$typeid."&amp;removecat=".$cat->id."'>";
					print img_delete($langs->trans("DeleteFromCat")).' ';
					print $langs->trans("DeleteFromCat")."</a>";
				}
				else
				{
					print '&nbsp;';
				}
				print "</td>";

				print "</tr>\n";
			}
		}
		print "</table>\n";
	}
	else if ($cats < 0)
	{
		print $langs->trans("ErrorUnknown");
	}
	else
	{
		if ($typeid == 0) $title=$langs->trans("ProductHasNoCategory");
		if ($typeid == 1) $title=$langs->trans("CompanyHasNoCategory");
		if ($typeid == 2) $title=$langs->trans("CompanyHasNoCategory");
		if ($typeid == 3) $title=$langs->trans("MemberHasNoCategory");
                if ($typeid == 4) $title=$langs->trans("LeadHasNoCategory");
		if ($typeid == 5) $title=$langs->trans("ContactHasNoCategory");
		print $title;
		print "<br/>";
	}
	return 0;
}


llxFooter();
$db->close();
?>