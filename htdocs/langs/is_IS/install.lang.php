<?php
/* Copyright (C) 2012	Regis Houssin	<regis.houssin@capnetworks.com>
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

$install = array(
		'CHARSET' => 'UTF-8',
		// Welcome step
		'SpeedealingSetup' => 'Speedealing Setup',
		'Welcome' => 'Welcome',
		'WelcomeTitle' => 'Welcome to Speedealing',
		'WelcomeDescription' => 'Speedealing install',
		'LanguageDescription' => 'Language used on United States',
		'InstallTypeTitle' => 'Install type',
		'InstallType' => 'Install type',
		'InstallTypeDescription' => 'Choose your install type',
		'InstallTypeServer' => 'Install type server',
		'InstallTypeServerDescription' => '',
		'InstallTypeClient' => 'Install type client',
		'InstallTypeClientDescription' => '',
		// Prerequisite step
		'Prerequisites' => 'Prerequisites',
		'PrerequisitesTitle' => 'Checking prerequisites',
		'PrerequisitesDescription' => 'The application requires a few prerequisites on your system to function properly.',
		'MoreInformation' => 'More information',
		'PHPVersion' => 'PHP Version',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Þetta PHP stuðning GD grafísku aðgerðir.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Your PHP max fundur minnið er stillt <b>á %s .</b> Þetta ætti að vera nóg.',
		'PHPMemoryTooLow' => 'Your PHP max fundur minnið er stillt <b>á %s </b> bæti. Þetta ætti að vera of lág. Breyta <b>php.ini</b> þinn til að stilla <b>memory_limit</b> breytu til að minnsta <b>kosti %s </b> bæti.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'PHP uppsetningu þinn styður ekki myndrænt fall GD. Nei línurit verður í boði.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Ekki tókst að búa til gagnagrunn \' %s \'.',
		'ErrorFailedToConnectToDatabase' => 'Tókst ekki að tengjast við gagnagrunn \' %s \'.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP útgáfa of gamall. Útgáfa %s  er krafist.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP útgáfa of gamall. Útgáfa %s eða meira er gert ráð fyrir. Þessi útgáfa ætti að leyfa setja en er ekki studd.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Stillingar <b>skrá %s </b> til.',
		'ConfFileDoesNotExists' => '<b>Stillingar %s  er</b> ekki til!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => '<b>Stillingar %s  er</b> ekki til og gat ekki búið til!',
		'ConfFileIsNotWritable' => '<b>Stillingarskráin %s </b> er ekki rétta aðgangsheimild. Athugaðu heimildir. Fyrir fyrsta sett verður upp á netþjóninn þinn verði veitt til að geta skrifað inn í þessa mynd á ferli stillingar ("chmod 666" til dæmis á Unix eins og OS).',
		'ConfFileIsWritable' => '<b>Stillingarskráin %s </b> er writable.',
		'YouMustCreateWithPermission' => 'Þú verður að búa til skrána %s  og setja skrifa leyfi á það fyrir vefþjón á meðan setja ferli.',
		// User sync
		'UserSyncCreated' => 'The replication user was created.',
		// Database
		'DatabaseCreated' => 'The database was created.',
		'WarningDatabaseAlreadyExists' => 'The database \'%s\' already exists.',
		// SuperAdmin
		'AdminCreated' => 'The superadmin was created.',
		// User
		'UserCreated' => 'The user was created.',
		// Lock file
		'LockFileCreated' => 'The lock file was created.',
		'LockFileCouldNotBeCreated' => 'The lock file could not be created.',
		'URLRoot' => 'URL Root',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Nafn eða ip tölu fyrir framreiðslumaður gagnasafn, venjulega \'localhost\' þegar gagnasafn framreiðslumaður er hýst á sama miðlara en vefþjóninum',
		'ServerPortDescription' => 'Gagnasafn framreiðslumaður höfn. Halda tómur ef ekki þekkt.',
		'DatabaseServer' => 'Gagnasafn miðlara',
		'DatabaseName' => 'Gagnasafn nafn',
		'Login' => 'Innskráning',
		'AdminLogin' => 'Innskráning fyrir Speedealing gagnasafn eigandi.',
		'Password' => 'Lykilorð',
		'AdminPassword' => 'Lykilorð fyrir Speedealing gagnasafn eigandi.',
		'SystemIsInstalled' => 'Þessi uppsetning er lokið.',
		'WithNoSlashAtTheEnd' => 'Án rista "/" í lok',
		'ServerPortCouchdbDescription' => 'Port du serveur. Défaut 5984.',
		'ServerAddressCouchdbDescription' => 'Nom FQDN du serveur de base de données, \'localhost.localdomain\' quand le serveur est installé sur la même machine que le serveur web',
		'DatabaseCouchdbUserDescription' => 'Login du super administrateur ayant tous les droits sur le serveur CouchDB ou l\'administrateur propriétaire de la base si la base et son compte d\'accès existent déjà (comme lorsque vous êtes chez un hébergeur).<br><br><div class="alert-box info">Cet utilisateur/mot de passe sera l\'administrateur pour se connecter à Speedealing.</div>',
		'ServerAddressMemcachedDesc' => 'Nom ou adresse ip du serveur memcached, généralement \'localhost\' quand le serveur est installé sur la même machine que le serveur web',
		'ServerPortMemcachedDesc' => 'Port du serveur memcached. Défaut : 11211',
		'FailedToCreateAdminLogin' => 'Echec de la création du compte administrateur Speedealing.',
		'Install' => 'Install',
		'InstallTitle' => 'Speedealing install',
		'InstallDescription' => 'Install desc',
		'SystemSetup' => 'System setup',
		'Database' => 'Database',
		'Security' => 'Security',
		'Start' => 'Start',
		// Upgrade
		'UpgradeOk' => 'Upgrade is ok !',
		'NewInstalledVersion' => 'Your new version is %s',
		'NeedUpgrade' => 'New Speedealing version !',
		'WarningUpgrade' => 'Installed version is %s, you must upgrade to %s. <br>Please contact your administrator.'
);
?>