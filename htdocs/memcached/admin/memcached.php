<?php
/* Copyright (C) 2010	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2012	Regis Houssin		<regis.houssin@capnetworks.com>
 * Copyright (C) 2013	Herve Prot		<herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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

include '../../main.inc.php';
require DOL_DOCUMENT_ROOT . '/memcached/lib/memcached.lib.php';
require DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';

// Security check
if (!$user->admin)
    accessforbidden();
if (!empty($dolibarr_memcached_view_disable)) // Hidden variable to add to conf file to disable browsing
    accessforbidden();

$langs->load("admin");
$langs->load("errors");
$langs->load("install");
$langs->load("memcached@memcached");

$action = GETPOST('action');

//exit;

/*
 * Actions
 */

/*
 * View
 */

$html = new Form($db);

$help_url = "EN:Module_MemCached_En|FR:Module_MemCached|ES:M&oacute;dulo_MemCached";
llxHeader("", $langs->trans("MemcachedSetup"), $help_url);

$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">' . $langs->trans("BackToModuleList") . '</a>';
print_fiche_titre($langs->trans('MemcachedSetup'));

print '<div class="with-padding">';
print '<div class="columns">';

print start_box($langs->trans('MemcachedSetup'), "twelve", '' , false);

$head = memcached_prepare_head();
dol_fiche_head($head, 'serversetup', $langs->trans("MemCached"));

print $langs->trans("MemcachedDesc") . "<br>\n";
print "<br>\n";

$error = 0;

// Check prerequisites
if (!class_exists("Memcache") && !class_exists("Memcached")) {
    print '<div class="error">';
    //var_dump($langs->tab_translate['ClientNotFound']);
    //var_dump($langs->trans('ClientNotFound'));
    print $langs->trans("ClientNotFound");
    print '</div>';
    $error++;
} else {
    print $langs->trans("MemcachedClient", "Memcached") . ': ';
    if (class_exists("Memcached"))
        print $langs->trans("Available");
    else
        print $langs->trans("NotAvailable");
    print '<br>';
    print $langs->trans("MemcachedClient", "Memcache") . ': ';
    if (class_exists("Memcache"))
        print $langs->trans("Available");
    else
        print $langs->trans("NotAvailable");
    print '<br>';
    if (class_exists("Memcached") && class_exists("Memcache"))
        print $langs->trans("MemcachedClientBothAvailable", 'Memcached') . '<br>';
    else if (class_exists("Memcached"))
        print $langs->trans("OnlyClientAvailable", 'Memcached') . '<br>';
    else if (class_exists("Memcache"))
        print $langs->trans("OnlyClientAvailable", 'Memcache') . '<br>';
}
print '<br>';

print '</div>';


if (!$error) {
    if (class_exists("Memcached"))
        $m = new Memcached();
    elseif (class_exists("Memcache"))
        $m = new Memcache();
    else
        dol_print_error('', 'Should not happen');

    // This action must be set here and not in actions to be sure all lang files are already loaded
    if ($_GET["action"] == 'clear') {
        $error = 0;
        if (!$error) {
            dol_flushcache();

            $mesg = '<div class="ok">' . $langs->trans("Flushed") . '</div>';
        }
    }

    if ($mesg)
        print '<br>' . $mesg;

    if (!empty($conf->memcached->host)) {
        $server = $conf->memcached->host;
        $port = $conf->memcached->port;

        //dol_syslog("Try to connect to server " . $server . " port " . $port . " with class " . get_class($m));
        $result = $m->addServer($server, $port);
        //$m->setOption(Memcached::OPT_COMPRESSION, false);
        //print "xxx".$result;
        // Read cache
        $arraycache = $m->getStats();
        //var_dump($arraycache);
    }

    // Action
    print '<div class="tabsAction">';
    print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?action=clear">' . $langs->trans("FlushCache") . '</a>';
    print '</div>';
    print '<br>';


    // Statistics of cache server
    print '<table class="noborder" width="60%">';
    print '<tr class="liste_titre"><td colspan="2">' . $langs->trans("Status") . '</td></tr>';

    if (empty($conf->memcached->host)) {
        print '<tr><td colspan="2">' . $langs->trans("ConfigureParametersFirst") . '</td></tr>';
    } else if (is_array($arraycache)) {
        $newarraycache = array();
        if (class_exists("Memcached"))
            $newarraycache = $arraycache;
        else if (class_exists("Memcache"))
            $newarraycache[$conf->memcached->host] = $arraycache;
        else
            dol_print_error('', 'Should not happen');

        foreach ($newarraycache as $key => $val) {
            print '<tr ' . $bc[0] . '><td>' . $langs->trans("MemcachedServer") . '</td>';
            print '<td>' . $key . '</td></tr>';

            print '<tr ' . $bc[1] . '><td>' . $langs->trans("Version") . '</td>';
            print '<td>' . $val['version'] . '</td></tr>';

            print '<tr ' . $bc[0] . '><td>' . $langs->trans("Status") . '</td>';
            print '<td>' . $langs->trans("On") . '</td></tr>';
        }
    } else {
        print '<tr><td colspan="2">' . $langs->trans("FailedToReadServer") . ' - Result code = ' . $resultcode . '</td></tr>';
    }

    print '</table>';
}
dol_fiche_end();

print end_box();
print '</div></div>';

llxfooter();
?>