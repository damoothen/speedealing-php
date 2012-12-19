<?php
/* Copyright (C) 2009-2012	Laurent Destailleur	<eldy@users.sourceforge.org>
 * Copyright (C) 2011	    Juanjo Menent		<jmenent@2byte.es>
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
 *	\file       htdocs/admin/geoipmaxmind.php
 *	\ingroup    geoipmaxmind
 *	\brief      Setup page for geoipmaxmind module
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/dolgeoip.class.php';

// Security check
if (!$user->admin)
accessforbidden();

$langs->load("admin");
$langs->load("errors");

$action = GETPOST("action");

/*
 * Actions
 */
if ($action == 'set')
{
	$error=0;

	$gimcdf= GETPOST("GEOIPMAXMIND_COUNTRY_DATAFILE");

	if (! $gimcdf && ! file_exists($gimcdf))
	{
		$mesg='<div class="error">'.$langs->trans("ErrorFileNotFound",$gimcdf).'</div>';
		$error++;
	}

	if (! $error)
	{
		$res = dolibarr_set_const($db,"GEOIPMAXMIND_COUNTRY_DATAFILE",$gimcdf,'chaine',0,'',$conf->entity);
		if (! $res > 0) $error++;

		if (! $error)
	    {
	        $mesg = "<font class=\"ok\">".$langs->trans("SetupSaved")."</font>";
	    }
	    else
	    {
	        $mesg = "<font class=\"error\">".$langs->trans("Error")."</font>";
	    }
	}
}



/*
 * View
 */

$form=new Form($db);

llxHeader();

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("GeoIPMaxmindSetup"),$linkback,'setup');
print '<br>';

$version='';
$geoip='';
if (! empty($conf->global->GEOIPMAXMIND_COUNTRY_DATAFILE))
{
	$geoip=new DolGeoIP('country',$conf->global->GEOIPMAXMIND_COUNTRY_DATAFILE);
}

// Mode
$var=true;
print '<form action="'.$_SERVER["PHP_SELF"].'" method="post">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<input type="hidden" name="action" value="set">';

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Parameter").'</td><td>'.$langs->trans("Value").'</td>';
print '<td align="right"><input type="submit" class="button" value="'.$langs->trans("Modify").'"></td>';
print "</tr>\n";

$var=!$var;
print '<tr '.$bc[$var].'><td width=\"50%\">'.$langs->trans("PathToGeoIPMaxmindCountryDataFile").'</td>';
print '<td colspan="2">';
print '<input size="50" type="text" name="GEOIPMAXMIND_COUNTRY_DATAFILE" value="'.$conf->global->GEOIPMAXMIND_COUNTRY_DATAFILE.'">';
if ($geoip) $version=$geoip->getVersion();
if ($version)
{
	print '<br>'.$langs->trans("Version").': '.$version;
}
print '</td></tr>';

print '</table>';

print "</form>\n";

print '<br>';

print $langs->trans("NoteOnPathLocation").'<br>';

$url1='http://www.maxmind.com/app/perl?rId=awstats';
print $langs->trans("YouCanDownloadFreeDatFileTo",'<a href="'.$url1.'" target="_blank">'.$url1.'</a>');

print '<br>';

$url2='http://www.maxmind.com/app/perl?rId=awstats';
print $langs->trans("YouCanDownloadAdvancedDatFileTo",'<a href="'.$url2.'" target="_blank">'.$url2.'</a>');

if ($geoip)
{
	print '<br><br>';
	print '<br>'.$langs->trans("TestGeoIPResult",$ip).':';

	$ip='24.24.24.24';
	print '<br>'.$ip.' -> ';
	$result=dol_print_ip($ip,1);
	if ($result) print $result;
	else print $langs->trans("Error");

	/* We disable this test because dol_print_ip need an ip as input
	$ip='www.google.com';
	print '<br>'.$ip.' -> ';
	$result=dol_print_ip($ip,1);
	if ($result) print $result;
	else print $langs->trans("Error");
	*/
	$geoip->close();
}

dol_htmloutput_mesg($mesg);

llxFooter();

$db->close();
?>
