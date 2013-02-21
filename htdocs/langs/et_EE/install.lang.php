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
		'PHPSupportGD' => 'See PHP tugi GD graafiline funktsioone.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Sinu PHP max sessiooni mälu on seatud <b>%s.</b> See peaks olema piisav.',
		'PHPMemoryTooLow' => 'Sinu PHP max sessiooni mälu on seatud <b>%s</b> bytes. See peaks olema liiga madal. Muuda oma <b>php.ini</b> määrata <b>memory_limit</b> parameeter vähemalt <b>%s</b> bytes.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Sinu PHP paigaldus ei toeta graafiline funktsiooni GD. Ei graafik on kättesaadav.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Suutnud luua andmebaasi \'%s &quot;.',
		'ErrorFailedToConnectToDatabase' => 'Ei suutnud ühendada andmebaas &quot;%s&quot;.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP versioon liiga vana. Version %s on vaja.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP versioon liiga vana. Version %s või rohkem oodata. See versioon peaks võimaldama paigaldada, kuid ei toetata.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Konfiguratsioonifaili <b>%s</b> olemas.',
		'ConfFileDoesNotExists' => 'Konfiguratsioonifaili <b>%s</b> ei eksisteeri!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Konfiguratsioonifaili <b>%s</b> ei ole ja ei saa luua!',
		'ConfFileIsNotWritable' => 'Konfiguratsioonifaili <b>%s</b> ei ole kirjutatav. Vaata õigusi. Sest 1. install, veebi server tuleb anda, et oleks võimalik kirjutada seda pilti jooksul konfiguratsiooni protsessi (&quot;chmod 666&quot; näiteks Unix nagu OS).',
		'ConfFileIsWritable' => 'Konfiguratsioonifaili <b>%s</b> on kirjutatav.',
		'YouMustCreateWithPermission' => 'Peate looma fail %s ning määrata kirjutada õigusi seda veebiserveri ajal install protsessi.',
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
		'ServerAddressDescription' => 'Nime või ip aadressi andmebaasi server, tavaliselt \'localhost\', kui andmebaasi server on majutatud sama server kui veebiserver',
		'ServerPortDescription' => 'Database server port. Pea tühi, kui teadmata.',
		'DatabaseServer' => 'Database server',
		'DatabaseName' => 'Andmebaasi nimi',
		'Login' => 'Logi',
		'AdminLogin' => 'Logi sisse jaoks Speedealing andmebaasi omanik.',
		'Password' => 'Parool',
		'AdminPassword' => 'Parooli Speedealing andmebaasi omanik.',
		'SystemIsInstalled' => 'See on installeerimine lõpetatud.',
		'WithNoSlashAtTheEnd' => 'Ilma kaldkriips &quot;/&quot; lõpus',
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