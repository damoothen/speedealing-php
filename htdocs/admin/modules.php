<?php

/* Copyright (C) 2003-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2011 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011      Herve Prot           <herve.prot@symeos.com>
 * Copyright (C) 2011	   Juanjo Menent        <jmenent@2byte.es>
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
 *  \file       htdocs/admin/modules.php
 *  \brief      Page to activate/disable all modules
 */
require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php");

$langs->load("errors");
$langs->load("admin");

$mesg = GETPOST("mesg");
$action = GETPOST('action');

if (!$user->admin)
	accessforbidden();

$object = new DolibarrModules($db);

// Search modules dirs
$modulesdir = array();
foreach ($conf->file->dol_document_root as $type => $dirroot) {
	$modulesdir[$dirroot . '/core/modules/'] = $dirroot . '/core/modules/';

	$handle = @opendir($dirroot);
	if (is_resource($handle)) {
		while (($file = readdir($handle)) !== false) {
			if (is_dir($dirroot . '/' . $file) && substr($file, 0, 1) <> '.' && substr($file, 0, 3) <> 'CVS' && $file != 'includes') {
				if (is_dir($dirroot . '/' . $file . '/core/modules/')) {
					$modulesdir[$dirroot . '/' . $file . '/core/modules/'] = $dirroot . '/' . $file . '/core/modules/';
				}
			}
		}
		closedir($handle);
	}
}
//var_dump($modulesdir);


$filename = array();
$modules = array();
$orders = array();
$categ = array();
$dirmod = array();
$i = 0; // is a sequencer of modules found
$j = 0; // j is module number. Automatically affected if module number not defined.
$modNameLoaded = array();

foreach ($modulesdir as $dir) {

	// Load modules attributes in arrays (name, numero, orders) from dir directory
	//print $dir."\n<br>";
	$handle = @opendir($dir);
	if (is_resource($handle)) {
		while (($file = readdir($handle)) !== false) {
			//print "$i ".$file."\n<br>";
			if (is_readable($dir . $file) && substr($file, 0, 3) == 'mod' && substr($file, dol_strlen($file) - 10) == '.class.php') {
				$modName = substr($file, 0, dol_strlen($file) - 10);

				if ($modName) {
					if (!empty($modNameLoaded[$modName])) {
						$mesg = "Error: Module " . $modName . " was found twice: Into " . $modNameLoaded[$modName] . " and " . $dir . ". You probably have an old file on your disk.<br>";
						dol_syslog($mesg, LOG_ERR);
						continue;
					}

					try {
						$res = include_once($dir . $file);
						$objMod = new $modName($db);
						$modNameLoaded[$modName] = $dir;

						if ($objMod->numero > 0) {
							$j = $objMod->numero;
						} else {
							$j = 1000 + $i;
						}

						$modulequalified = 1;

						// We discard modules according to features level (PS: if module is activated we always show it)
						$const_name = 'MAIN_MODULE_' . strtoupper(preg_replace('/^mod/i', '', get_class($objMod)));
						if ($objMod->version == 'development' && $conf->global->MAIN_FEATURES_LEVEL < 2 && !$conf->global->$const_name)
							$modulequalified = 0;
						if ($objMod->version == 'experimental' && $conf->global->MAIN_FEATURES_LEVEL < 1 && !$conf->global->$const_name)
							$modulequalified = 0;

						if ($modulequalified) {
							$modules[$i] = $objMod;
							$filename[$i] = $modName;
							$orders[$i] = $objMod->family . "_" . $j;   // Tri par famille puis numero module
							//print "x".$modName." ".$orders[$i]."\n<br>";
							if (isset($categ[$objMod->special]))
								$categ[$objMod->special]++;  // Array of all different modules categories
							else
								$categ[$objMod->special] = 1;
							$dirmod[$i] = $dir;
							$j++;
							$i++;
						}
						else
							dol_syslog("Module " . get_class($objMod) . " not qualified");
					} catch (Exception $e) {
						dol_syslog("Failed to load " . $dir . $file . " " . $e->getMessage(), LOG_ERR);
					}
				}
			}
		}
		closedir($handle);
	} else {
		$object->dol_syslog("htdocs/admin/modules.php: Failed to open directory " . $dir . ". See permission and open_basedir option.", LOG_WARNING);
	}
}

asort($orders);
//var_dump($orders);
//var_dump($categ);
//var_dump($modules);
// Affichage debut page

dol_htmloutput_errors($mesg);

/**
 * Actions
 */
if ($action == 'set' && $user->admin) {
	try {
		$object->load($_GET['id']); // update if module exist
		$rev = $object->values->_rev;
	} catch (Exception $e) {
		
	}

	try {
		$key = $_GET['value'];
		$objMod = $modules[$key];

		$object->values = $objMod->values;
		$object->values->_id = "module:" . $objMod->values->name;
		$object->values->_rev = $rev;
		$object->values->enabled = true;
		dol_delcache("MenuTop:list"); //refresh menu
		dol_delcache("MenuTop:submenu"); //refresh menu
		dol_delcache("extrafields:" . $object->values->class); //refresh extrafields
		dol_delcache("const"); //delete $conf
		dol_delcache("DolibarrModules:list"); //refresh menu
		
		$object->record();
		$object->_load_documents();
	} catch (Exception $e) {
		$mesg = $e->getMessage();
	}
	Header("Location: " . $_SERVER['PHP_SELF'] . "?&mesg=" . urlencode($mesg));
	exit;
}

if ($action == 'reset' && $user->admin) {
	try {
		$object->load($_GET['id']);
		unset($object->values->enabled);
		
		dol_delcache("MenuTop:list"); //refresh menu
		dol_delcache("MenuTop:submenu"); //refresh menu
		dol_delcache("const"); //delete $conf
		dol_delcache("DolibarrModules:list"); //refresh menu

		$object->record();
	} catch (Exception $e) {
		$mesg = $e->getMessage();
	}
	Header("Location: " . $_SERVER['PHP_SELF'] . "?&mesg=" . urlencode($mesg));
	exit;
}

/*
 * View
 */

$help_url = 'EN:First_setup|FR:Premiers_paramétrages|ES:Primeras_configuraciones';
llxHeader('', $langs->trans("Setup"), $help_url);

print '<div class="row">';
print start_box($langs->trans("ModulesSetup"), 'twelve', '16-Cog-4.png', false);




$obj = new stdClass();
$i = 0;

print '<table class="display dt_act" id="list_modules">';

print'<thead>';
print'<tr>';

print'<th>';
print'</th>';
$obj->aoColumns[$i]->mDataProp = "id";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->bVisible = false;
$i++;

print'<th class="essential">';
print $langs->trans("Family");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "family";
$obj->aoColumns[$i]->sDefaultContent = "other";
$obj->aoColumns[$i]->bVisible = false;
$i++;

print'<th class="essential">';
print $langs->trans("Module");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "name";
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;

print'<th>';
print $langs->trans("Description");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "desc";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->bVisible = true;
$i++;
print'<th class="essential">';
print $langs->trans("Version");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "version";
$obj->aoColumns[$i]->sDefaultContent = "false";
$obj->aoColumns[$i]->sClass = "center";
$i++;
print'<th class="essential">';
print $langs->trans("Status");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Status";
$obj->aoColumns[$i]->sDefaultContent = "false";
$obj->aoColumns[$i]->sClass = "center";
$i++;
print'<th class="essential">';
print $langs->trans("SetupShort");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "setup";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->bSortable = false;
$obj->aoColumns[$i]->sClass = "center";
print'</tr>';

print'</thead>';
$obj->fnDrawCallback = "function(oSettings){
                if ( oSettings.aiDisplay.length == 0 )
                {
                    return;
                }
                var nTrs = jQuery('#list_modules tbody tr');
                var iColspan = nTrs[0].getElementsByTagName('td').length;
                var sLastGroup = '';
                for ( var i=0 ; i<nTrs.length ; i++ )
                {
                    var iDisplayIndex = oSettings._iDisplayStart + i;
                     var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData['family'];
                         if (sGroup!=null && sGroup!='' && sGroup != sLastGroup)
                            {
                                var nGroup = document.createElement('tr');
                                var nCell = document.createElement('td');
                                nCell.colSpan = iColspan;
                                nCell.className = 'group';
                                nCell.innerHTML = sGroup;
                                nGroup.appendChild( nCell );
                                nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
                                sLastGroup = sGroup;
                            }
                    
                    
                }
	}";

$i = 0;
print'<tfoot>';
print'</tfoot>';
print'<tbody>';

// Affichage liste modules

$var = true;
$oldfamily = '';

$familylib = array(
	'base' => $langs->trans("ModuleFamilyBase"),
	'crm' => $langs->trans("ModuleFamilyCrm"),
	'products' => $langs->trans("ModuleFamilyProducts"),
	'hr' => $langs->trans("ModuleFamilyHr"),
	'projects' => $langs->trans("ModuleFamilyProjects"),
	'financial' => $langs->trans("ModuleFamilyFinancial"),
	'ecm' => $langs->trans("ModuleFamilyECM"),
	'technic' => $langs->trans("ModuleFamilyTechnic"),
	'other' => $langs->trans("ModuleFamilyOther")
);

foreach ($orders as $key => $value) {
	$tab = explode('_', $value);
	$family = $tab[0];
	$numero = $tab[1];

	$modName = $filename[$key];
	$objMod = $modules[$key];
	//var_dump($objMod);

	if (!$objMod->getName()) {
		dol_syslog("Error for module " . $key . " - Property name of module looks empty", LOG_WARNING);
		continue;
	}

	$const_name = 'MAIN_MODULE_' . strtoupper(preg_replace('/^mod/i', '', get_class($objMod)));

	// Load all lang files of module
	if (isset($objMod->langfiles) && is_array($objMod->langfiles)) {
		foreach ($objMod->langfiles as $domain) {
			$langs->load($domain);
		}
	}

	//print "\n<!-- Module ".$objMod->numero." ".$objMod->getName()." found into ".$dirmod[$key]." -->\n";
	print '<tr>';

	// Id
	print '<td>';
	print $objMod->values->numero;
	print '</td>';

	// Family
	print '<td>';
	$family = $objMod->values->family;
	print $familytext = empty($familylib[$family]) ? $family : $familylib[$family];
	print "</td>\n";

	// Picto
	print '  <td>';
	$alttext = '';
	//if (is_array($objMod->need_dolibarr_version)) $alttext.=($alttext?' - ':'').'Dolibarr >= '.join('.',$objMod->need_dolibarr_version);
	//if (is_array($objMod->phpmin)) $alttext.=($alttext?' - ':'').'PHP >= '.join('.',$objMod->phpmin);
	if (!empty($objMod->values->picto)) {
		if (preg_match('/^\//i', $objMod->values->picto))
			print img_picto($alttext, $objMod->values->picto, ' width="14px"', 1);
		else
			print img_object($alttext, $objMod->values->picto, ' width="14px"');
	}
	else {
		print img_object($alttext, 'generic');
	}


	// Name
	print ' ' . $objMod->getName();
	print "</td>\n";

	// Desc
	print "<td>";
	print nl2br($objMod->getDesc());
	print "</td>\n";

	// Version
	print "<td>";
	print $objMod->getVersion();
	print "</td>\n";

	// Activate/Disable and Setup (2 columns)
	$name = $objMod->values->name;

	if (isset($conf->$name) && !empty($conf->$name->enabled)) {

		$disableSetup = 0;

		print "<td>";

		// Module actif
		if (!empty($conf->$name->always_enabled)) {

			print '<span class="lbl ok_bg sl_status ">' . $langs->trans("Required") . '</span>';
			print '</td>' . "\n";
		} else {
			print '<a href="' . $_SERVER['PHP_SELF'] . '?id=module:' . $objMod->values->name . '&amp;action=reset&amp;value=' . $key . '">';
			print img_picto($langs->trans("Activated"), 'switch_on');
			print '</a></td>' . "\n";
		}

		if (!empty($objMod->values->config_page_url) && !$disableSetup) {
			if (is_array($objMod->values->config_page_url)) {
				print '  <td>';
				$i = 0;
				foreach ($objMod->values->config_page_url as $page) {
					$urlpage = $page;
					if ($i++) {
						print '<a href="' . $_SERVER['PHP_SELF'] . '/' . $urlpage . '" title="' . $langs->trans($page) . '">' . img_picto(ucfirst($page), "setup") . '</a>&nbsp;';
						//    print '<a href="'.$page.'">'.ucfirst($page).'</a>&nbsp;';
					} else {
						if (preg_match('/^([^@]+)@([^@]+)$/i', $urlpage, $regs)) {
							print '<a href="' . dol_buildpath('/' . $regs[2] . '/admin/' . $regs[1], 1) . '" title="' . $langs->trans("Setup") . '">' . img_picto($langs->trans("Setup"), "setup") . '</a>&nbsp;';
						} else {
							print '<a href="' . DOL_URL_ROOT . '/admin/' . $urlpage . '" title="' . $langs->trans("Setup") . '">' . img_picto($langs->trans("Setup"), "setup") . '</a>&nbsp;';
						}
					}
				}
				print "</td>\n";
			} else if (preg_match('/^([^@]+)@([^@]+)$/i', $objMod->values->config_page_url, $regs)) {
				print '<td><a href="' . dol_buildpath('/' . $regs[2] . '/admin/' . $regs[1], 1) . '" title="' . $langs->trans("Setup") . '">' . img_picto($langs->trans("Setup"), "setup") . '</a></td>';
			} else {
				print '<td><a href="' . $objMod->values->config_page_url . '" title="' . $langs->trans("Setup") . '">' . img_picto($langs->trans("Setup"), "setup") . '</a></td>';
			}
		} else {
			print "<td>&nbsp;</td>";
		}
	} else {
		print "<td>";

		if ($objMod->values->version == 'dolibarr') {
			print "</td>\n  <td>&nbsp;</td>\n";
		} else {
			// Module non actif
			print '<a href="' . $_SERVER['PHP_SELF'] . '?id=module:' . $objMod->values->name . '&amp;action=set&amp;value=' . $key . '">';
			print img_picto($langs->trans("Disabled"), 'switch_off');
			print "</a></td>\n  <td>&nbsp;</td>\n";
		}
	}

	print "</tr>\n";
}
print'</tbody>';
print'</table>';

$obj->aaSorting = array(array(1, 'asc'));
$obj->sDom = 'l<fr>t<\"clear\"rtip>';
$obj->iDisplayLength = -1;

print $object->datatablesCreate($obj, "list_modules");

print end_box();
print '</div>';

llxFooter();

$db->close();
?>
