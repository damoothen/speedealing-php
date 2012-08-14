<?PHP

/* Copyright (C) 2004		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2005-2011	Laurent Destailleur		<eldy@uers.sourceforge.net>
 * Copyright (C) 2005-2012	Regis Houssin			<regis@dolibarr.fr>
 * Copyright (C) 2010-2011 Patrick Mary  <laube@hotmail.fr>
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
 *       \file       htdocs/comm/mailing/fiche.php
 *       \ingroup    mailing
 *       \brief      Fiche mailing, onglet general
 */
require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php');
require_once(DOL_DOCUMENT_ROOT . "/core/class/CMailFile.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/functions2.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/adherent/class/adherent_card.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/class/html.formother.class.php");

$langs->load("members");
$langs->load("mails");

if (!$user->rights->adherent->configurer)
	accessforbidden();

$id = "licenseCard";
$action = GETPOST('action', 'alpha');
$confirm = GETPOST('confirm', 'alpha');
$message = '';

$object = new Adherent($db);

// Tableau des substitutions possibles
$substitutionarray = array(
	'__ID__' => 'IdRecord',
	'__CAMPAGNEID__' => 'IdCampagne',
	'__LASTNAME__' => 'Lastname',
	'__FIRSTNAME__' => 'Firstname',
	'__OTHER1__' => 'Other1',
	'__OTHER2__' => 'Other2',
	'__OTHER3__' => 'Other3',
	'__OTHER4__' => 'Other4',
	'__OTHER5__' => 'Other5',
	'__SIGNATURE__' => 'Signature',
	'__PERSONALIZED__' => 'Personalized'
);
$substitutionarrayfortest = array(
	'__ID__' => 'TESTIdRecord',
	'__CAMPAGNEID' => 'TESTIdCampagne',
	'__LASTNAME__' => 'TESTLastname',
	'__FIRSTNAME__' => 'TESTFirstname',
	'__OTHER1__' => 'TESTOther1',
	'__OTHER2__' => 'TESTOther2',
	'__OTHER3__' => 'TESTOther3',
	'__OTHER4__' => 'TESTOther4',
	'__OTHER5__' => 'TESTOther5',
	'__SIGNATURE__' => 'TESTSignature',
	'__PERSONALIZED__' => 'TESTPersonalized'
);
/*
 * Add file in email form
 */
if (!empty($_POST['addfile'])) {

	$object->load($id);
	if (!empty($_FILES['addedfile']['tmp_name'])) {
		$object->storeFile();
		//dol_add_file_process($upload_dir, 0, 0);
	}

	$action = "edit";
}

// Action update emailing
if (!empty($_POST["removedfile"])) {
	$object->load($id);

	if (!empty($_POST['removedfile'])) {
		$object->deleteFile($_POST['removedfile']);
		//$mesg = dol_remove_file_process($_POST['removedfile'], 0);
	}

	$action = "edit";
}

// Action update emailing
if ($action == 'update' && empty($_POST["removedfile"]) && empty($_POST["cancel"])) {
	require_once(DOL_DOCUMENT_ROOT . "/core/lib/files.lib.php");

	try {
		$object->load($id);

		$object->sujet = trim($_POST["sujet"]);
		$object->resume = trim($_POST["resume"]);
		$object->body = trim($_POST["body"]);
		$object->tms = dol_now();
		$object->mod = trim($_POST["msgtype"]);
		$object->urgent = $_POST["urgent"];

		if (!$object->sujet)
			$message.=($message ? '<br>' : '') . $langs->trans("ErrorFieldRequired", $langs->trans("MailTopic"));
		if (!$object->body)
			$message.=($message ? '<br>' : '') . $langs->trans("ErrorFieldRequired", $langs->trans("MailBody"));

		if (!$message) {
			$object->record();
			Header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $object->id());
			exit;
		}
	} catch (Exception $e) {
		$message = "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
		dol_syslog("Ego::Update " . $message, LOG_ERR);
		$action = "edit";
	}

	$message = '<div class="error">' . $message . '</div>';
	$action = "edit";
}

if (!empty($_POST["cancel"])) {
	$action = '';
}



/*
 * View
 */

$help_url = 'EN:Module_EMailing|FR:Module_Mailing|ES:M&oacute;dulo_Mailing';
llxHeader('', $langs->trans("Messaging"), $help_url);

$form = new Form($db);
$htmlother = new FormOther($db);

try {
	$object->load($id);
} catch (Exception $e) {
	$message = "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
	dol_syslog("Ego::Create " . $message, LOG_ERR);
	print '<div class="error">' . $message . '</div>';
	exit;
}

dol_htmloutput_mesg($message);

if ($action != 'edit') {
	/*
	 * Mailing en mode visu
	 */

	if ($mesg)
		print $mesg;

	// Print mail content
	print '<div class="row">';
	print start_box($langs->trans("CardMember"), "six centered", "16-iPhone-4.png");
	print '<table class="border" width="100%">';

	// Subject
	print '<tr><td width="25%">' . $langs->trans("MsgTopic") . '</td><td colspan="3">' . $object->sujet . '</td></tr>';

	// Resume
	print '<tr><td width="25%">' . $langs->trans("Resume") . '</td><td colspan="3">' . $object->resume . '</td></tr>';

	// Joined files
	print '<tr><td>' . $langs->trans("File") . '</td><td colspan="3">';
	// List of files
	if (count($object->_attachments)) {
		foreach ($object->_attachments as $key => $aRow) {
			print img_mime($key) . ' ' . $key;
			print '<br>';
		}
	} else {
		print $langs->trans("NoAttachedFiles") . '<br>';
	}
	print '</td></tr>';

	// Background color
	/* print '<tr><td width="25%">'.$langs->trans("BackgroundColorByDefault").'</td><td colspan="3">';
	  $htmlother->select_color($object->bgcolor,'bgcolor','edit_mailing',0);
	  print '</td></tr>'; */

	// Message
	print '<tr><td valign="top">' . $langs->trans("Message") . '</td>';
	print '<td colspan="3" bgcolor="' . ($object->bgcolor ? (preg_match('/^#/', $object->bgcolor) ? '' : '#') . $object->bgcolor : 'white') . '">';
	print dol_htmlentitiesbr($object->body);
	print '</td>';
	print '</tr>';

	print '</table>';
	
	/*
	 * Boutons d'action
	 */

	if (GETPOST("cancel") || $confirm == 'no' || $action == '' || in_array($action, array('valid', 'delete', 'sendall'))) {
		print "\n\n<div class=\"tabsAction\">\n";

		print '<a class="butAction" href="' . $_SERVER['PHP_SELF'] . '?action=edit&amp;id=' . $object->id() . '">' . $langs->trans("Edit") . '</a>';

		print '<br><br></div>';
	}
	
	print end_box();

	print '</div>';
} else {
	/*
	 * Mailing en mode edition
	 */

	if ($mesg)
		print $mesg . "<br>";
	if ($message)
		print $message . "<br>";

	print '<div class="row">';
	print start_box($langs->trans("CardMember"), "six centered", "16-iPhone-4.png");
	
	print "\n";
	print '<form name="edit_mailing" action="' . $_SERVER['PHP_SELF'] . '" method="post" enctype="multipart/form-data">' . "\n";
	print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
	print '<input type="hidden" name="action" value="update">';
	print '<input type="hidden" name="id" value="' . $object->id . '">';

	// Print mail content
	
	//print_fiche_titre($langs->trans("Message"), '', '');
	print '<table class="border" width="100%">';

	// Subject
	print '<tr><td width="25%" class="fieldrequired">' . $langs->trans("MsgTopic") . '</td><td colspan="3"><input class="flat" type="text" size=40 name="sujet" value="' . $object->sujet . '"></td></tr>';

	// Resume
	print '<tr><td width="25%" class="fieldrequired">' . $langs->trans("Resume") . '</td><td colspan="3"><input class="flat" type="text" size=40 name="resume" value="' . $object->resume . '"></td></tr>';

	dol_init_file_process($upload_dir);

	// MesgType
	print '<tr><td width="25%"><span class="fieldrequired">' . $langs->trans('MsgType') . '</span></td><td width="25%"><select class="flat" name="msgtype">';
	$selected = $object->mod;
	print '<option value="0"' . ($selected == 0 ? ' selected="selected"' : '') . '>' . $langs->trans('News') . '</option>';
	print '<option value="1"' . ($selected == 1 ? ' selected="selected"' : '') . '>' . $langs->trans('Msg') . '</option>';
	print '<option value="3"' . ($selected == 3 ? ' selected="selected"' : '') . '>' . $langs->trans('Sign') . '</option>';
	print '</select> <input type="checkbox" name="urgent" value="1" ' . ($object->urgent ? "checked" : "") . '> Urgent</td>';

	// Joined files
	$addfileaction = 'addfile';
	print '<tr><td>' . $langs->trans("File") . '</td>';
	print '<td colspan="3">';
	// List of files
	// TODO Trick to have param removedfile containing nb of image to delete. But this does not works without javascript
	$out.= '<input type="hidden" class="removedfilehidden" name="removedfile" value="">' . "\n";
	$out.= '<script type="text/javascript" language="javascript">';
	$out.= 'jQuery(document).ready(function () {';
	$out.= '    jQuery(".removedfile").click(function() {';
	$out.= '        jQuery(".removedfilehidden").val(jQuery(this).val());';
	$out.= '    });';
	$out.= '})';
	$out.= '</script>' . "\n";
	if (count($object->_attachments)) {
		foreach ($object->_attachments as $key => $aRow) {
			$out.= '<div id="attachfile_' . $key . '">';
			$out.= img_mime($key) . ' ' . $key;
			$out.= ' <input type="image" style="border: 0px;" src="' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/delete.png" value="' . $key . '" class="removedfile" id="removedfile_' . $key . '" name="removedfile_' . $key . '" />';
			$out.= '<br></div>';
		}
	} else {
		$out.= $langs->trans("NoAttachedFiles") . '<br>';
	}

	// Add link to add file
	$out.= '<input type="file" class="flat" id="addedfile" name="addedfile" value="' . $langs->trans("Upload") . '" />';
	$out.= ' ';
	$out.= '<input type="submit" class="button tiny nice" id="' . $addfileaction . '" name="' . $addfileaction . '" value="' . $langs->trans("MailingAddFile") . '" />';
	print $out;
	print '</td></tr>';

	// Message
	print '<tr><td width="25%" valign="top">' . $langs->trans("MailMessage") . '<br>';
	print '<br><i>' . $langs->trans("CommonSubstitutions") . ':<br>';
	print '__ID__ = ' . $langs->trans("IdRecord") . '<br>';
	print '__EMAIL__ = ' . $langs->trans("EMail") . '<br>';
	print '__CHECK_READ__ = ' . $langs->trans("CheckRead") . '<br>';
	print '__UNSUSCRIBE__ = ' . $langs->trans("MailUnsubcribe") . '<br>';
	print '__LASTNAME__ = ' . $langs->trans("Lastname") . '<br>';
	print '__FIRSTNAME__ = ' . $langs->trans("Firstname") . '<br>';
	print '__OTHER1__ = ' . $langs->trans("Other") . '1<br>';
	print '__OTHER2__ = ' . $langs->trans("Other") . '2<br>';
	print '__OTHER3__ = ' . $langs->trans("Other") . '3<br>';
	print '__OTHER4__ = ' . $langs->trans("Other") . '4<br>';
	print '__OTHER5__ = ' . $langs->trans("Other") . '5<br>';
	print '</i></td>';
	print '<td colspan="3">';
	// Editeur wysiwyg
	require_once(DOL_DOCUMENT_ROOT . "/core/class/doleditor.class.php");
	$doleditor = new DolEditor('body', $object->body, '', 320, 'dolibarr_mailings', '', true, true, true, 20, 70);
	$doleditor->Create();
	print '</td></tr>';

	print '<tr><td colspan="4" align="center">';
	print '<input type="submit" class="button small nice" value="' . $langs->trans("Save") . '" name="save">';
	print ' &nbsp; ';
	print '<input type="submit" class="button small nice black" value="' . $langs->trans("Cancel") . '" name="cancel">';
	print '</td></tr>';

	print '</table>';

	print '</form>';

	print end_box();
	print '</div>';
}

llxFooter();
?>
