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
		'PHPVersion' => 'PHP versie',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Dit PHP ondersteuning GD grafische functies.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Uw PHP max. session geheugen is ingesteld op <b>%s</b>. Dit moet genoeg zijn.',
		'PHPMemoryTooLow' => 'Uw PHP max. session geheugen is ingesteld op <b>%s</b> bytes. Dit zal te laag zijn. Verander uw <b>php.ini</b> om <b>memory_limit</b> parameter in te stellen naar ten minste <b>%s</b> bytes.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Uw PHP installatie biedt geen ondersteuning voor grafische functie GD. Geen grafiek zal beschikbaar zijn.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Mislukt om database \'%s\' te creëren.',
		'ErrorFailedToConnectToDatabase' => 'Kan geen verbinding maken met database \'%s\'.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP versie te oud. Versie %s is vereist.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP version too old. Version %s or more is expected. This version should allow install but is not supported.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Configuratiebestand <b>%s</b> bestaat.',
		'ConfFileDoesNotExists' => 'Configuratiebestand <b>%s</b> bestaat niet!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Configuratiebestand <b>%s</b> bestaat niet en kan niet worden gemaakt!',
		'ConfFileIsNotWritable' => 'Configuratiebestand <b>%s</b> is niet schrijfbaar. Controleer de machtigingen. Wanneer u voor het eerst installeert zal u aan uw web-server schrijfrechten moet verlenen aan het configuratiebestand tijdens het installatie proces ( "chmod 666", bijvoorbeeld op Unix zoals OS).',
		'ConfFileIsWritable' => 'Configuratiebestand <b>%s</b> is schrijfbaar.',
		'YouMustCreateWithPermission' => 'U moet bestand %s aanmaken en schrijfrechten geven voor de webserver tijdens het installatieproces.',
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
		'ServerAddressDescription' => 'Naam of IP-adres voor de database server, meestal "localhost" als database server wordt gehost op dezelfde server dan de web-server',
		'ServerPortDescription' => 'Database server poort. Leeg houden als onbekend.',
		'DatabaseServer' => 'Database server',
		'DatabaseName' => 'Database naam',
		'Login' => 'Inloggen',
		'AdminLogin' => 'Login voor administrator van de Speedealing database.',
		'Password' => 'Wachtwoord',
		'AdminPassword' => 'Wachtwoord voor administrator van de Speedealing database.',
		'SystemIsInstalled' => 'Deze installatie is voltooid.',
		'WithNoSlashAtTheEnd' => 'Zonder de schuine streep "/" aan het eind',
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