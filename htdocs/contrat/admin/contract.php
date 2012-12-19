<?php
/* Copyright (C) 2011-2012      Juanjo Menent	    <jmenent@2byte.es>
 * Copyright (C) 2011           Herve Prot          <herve.prot@symeos.com>
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
 *	\file       htdocs/admin/contract.php
 *	\ingroup    contract
 *	\brief      Setup page of module Contracts
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once(DOL_DOCUMENT_ROOT."/core/lib/contract.lib.php");
require_once DOL_DOCUMENT_ROOT.'/contrat/class/contrat.class.php';

$langs->load("admin");
$langs->load("errors");

if (!$user->admin) accessforbidden();

$action = GETPOST('action','alpha');
$value = GETPOST('value','alpha');

if (empty($conf->global->CONTRACT_ADDON))
{
    $conf->global->CONTRACT_ADDON='mod_contract_serpis';
}


/*
 * Actions
 */

if ($action == 'updateMask')
{
    $maskconst = GETPOST('maskconstcontract','alpha');
    $maskvalue =  GETPOST('maskcontract','alpha');
    if ($maskconst) $res = dolibarr_set_const($db,$maskconst,$maskvalue,'chaine',0,'',$conf->entity);

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

if ($action == 'setmod')
{
    dolibarr_set_const($db, "CONTRACT_ADDON",$value,'chaine',0,'',$conf->entity);
}

/*
 // constants of magre model
 if ($action == 'updateMatrice') dolibarr_set_const($db, "CONTRACT_NUM_MATRICE",$_POST["matrice"],'chaine',0,'',$conf->entity);
 if ($action == 'updatePrefix') dolibarr_set_const($db, "CONTRACT_NUM_PREFIX",$_POST["prefix"],'chaine',0,'',$conf->entity);
 if ($action == 'setOffset') dolibarr_set_const($db, "CONTRACT_NUM_DELTA",$_POST["offset"],'chaine',0,'',$conf->entity);
 if ($action == 'setNumRestart') dolibarr_set_const($db, "CONTRACT_NUM_RESTART_BEGIN_YEAR",$_POST["numrestart"],'chaine',0,'',$conf->entity);
 */

/*
 * View
 */

llxHeader();

$dir=DOL_DOCUMENT_ROOT."/core/modules/contract/";
$form=new Form($db);

$help_url='EN:Module Third Parties setup|FR:Paramétrage_du_module_Tiers|ES:Configuración_del_módulo_terceros';
llxHeader('',$langs->trans("ContractSetup"),$help_url);

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("ContractsSetup"),$linkback,'setup');

$head = contract_admin_prepare_head(null);

dol_fiche_head($head, 'general', $langs->trans("Contract"), 0, 'contract');


print "<br>";

print_titre($langs->trans("ContractsNumberingModules"));

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td width="100">'.$langs->trans("Name").'</td>';
print '<td>'.$langs->trans("Description").'</td>';
print '<td>'.$langs->trans("Example").'</td>';
print '<td align="center" width="60">'.$langs->trans("Status").'</td>';
print '<td align="center" width="16">'.$langs->trans("Infos").'</td>';
print "</tr>\n";

clearstatcache();

$dir = "../core/modules/contract/";
$handle = opendir($dir);
if (is_resource($handle))
{
    $var=true;

    while (($file = readdir($handle))!==false)
    {
        if (substr($file, 0, 13) == 'mod_contract_' && substr($file, dol_strlen($file)-3, 3) == 'php')
        {
            $file = substr($file, 0, dol_strlen($file)-4);

            require_once DOL_DOCUMENT_ROOT ."/core/modules/contract/".$file.'.php';

            $module = new $file;

            // Show modules according to features level
            if ($module->version == 'development'  && $conf->global->MAIN_FEATURES_LEVEL < 2) continue;
            if ($module->version == 'experimental' && $conf->global->MAIN_FEATURES_LEVEL < 1) continue;

            if ($module->isEnabled())
            {
                $var=!$var;
                print '<tr '.$bc[$var].'><td>'.$module->nom."</td>\n";
                print '<td>';
                print $module->info();
                print '</td>';

                // Show example of numbering module
                print '<td nowrap="nowrap">';
                $tmp=$module->getExample();
                if (preg_match('/^Error/',$tmp)) { $langs->load("errors"); print '<div class="error">'.$langs->trans($tmp).'</div>'; }
                elseif ($tmp=='NotConfigured') print $langs->trans($tmp);
                else print $tmp;
                print '</td>'."\n";

                print '<td align="center">';
                if ($conf->global->CONTRACT_ADDON == "$file")
                {
                    print img_picto($langs->trans("Activated"),'switch_on');
                }
                else
                {
                    print '<a href="'.$_SERVER["PHP_SELF"].'?action=setmod&amp;value='.$file.'&amp;scandir='.$module->scandir.'&amp;label='.urlencode($module->name).'">';
                    print img_picto($langs->trans("Disabled"),'switch_off');
                    print '</a>';
                }
                print '</td>';

                $contract=new Contrat($db);
                $contract->initAsSpecimen();

                // Info
                $htmltooltip='';
                $htmltooltip.=''.$langs->trans("Version").': <b>'.$module->getVersion().'</b><br>';
                $nextval=$module->getNextValue($mysoc,$contract);
                if ("$nextval" != $langs->trans("NotAvailable"))	// Keep " on nextval
                {
                    $htmltooltip.=''.$langs->trans("NextValue").': ';
                    if ($nextval)
                    {
                        $htmltooltip.=$nextval.'<br>';
                    }
                    else
                    {
                        $htmltooltip.=$langs->trans($module->error).'<br>';
                    }
                }

                print '<td align="center">';
                print $form->textwithpicto('',$htmltooltip,1,0);
                print '</td>';

                print '</tr>';
            }
        }
    }
    closedir($handle);
}

print '</table><br>';

dol_htmloutput_mesg($mesg);

$db->close();

llxFooter();
?>
