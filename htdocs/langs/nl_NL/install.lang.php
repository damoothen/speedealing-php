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
		'PHPSupportGD' => 'Deze PHP installatie ondersteund GD grafische functies.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Het maximale sessiegeheugen van deze PHP installatie is ingesteld op <b>%s</b>. Dit zou genoeg moeten zijn.',
		'PHPMemoryTooLow' => 'Het maximale sessiegeheugen van deze PHP installatie is ingesteld op <b>%s</b> bytes. Dit zou te weinig kunnen zijn. Verander uw <b>php.ini</b> om de <b>memory_limit</b> instelling op minimaal <b>%s</b> bytes te zetten.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Uw PHP installatie ondersteund geen grafische functies. Grafieken zullen niet beschikbaar zijn.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'De database \'%s\' kon niet worden gecreëerd.',
		'ErrorFailedToConnectToDatabase' => 'Er kon niet verbonden worden met de database \'%s\'.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'De geïnstalleerde PHP versie is te oud. Versie %s is nodig.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'De geïnstalleerde PHP versie is te oud. Versie %d of hoger wordt verwacht. Deze versie laat installatie toe, maar wordt niet ondersteund.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Configuratiebestand <b>%s</b> bestaat.',
		'ConfFileDoesNotExists' => 'Configuratiebestand <b>%s</b> bestaat niet!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Configuratiebestand <b>%s</b> bestaat niet en kon ook niet worden gecreëerd !',
		'ConfFileIsNotWritable' => 'Configuratie bestand <b>%s</b> is niet voor schrijven te openen. Voor de eerste installatie, moet de webserver toestemming krijgen om naar dit bestand te schrijven tijdens het configuratieproces (door bijvoorbeeld "chmod 666" op een Unix-achtig OS).',
		'ConfFileIsWritable' => 'Configuratiebestand <b>%s</b> kan voor schrijven geopend worden.',
		'YouMustCreateWithPermission' => 'U dient het bestand %s te creëren en het schrijfrechten te geven voor de webserver tijdens de installatie.',
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
		'ServerAddressDescription' => 'Naam of IP-adres van de database server, normaal gesproken \'localhost\' wanneer de database gehost wordt op dezelfde webserver',
		'ServerPortDescription' => 'Databaseserverpoort. Laat dit leeg wanneer u dit niet weet.',
		'DatabaseServer' => 'Databaseserver',
		'DatabaseName' => 'Databasenaam',
		'Login' => 'Login',
		'AdminLogin' => 'Gebruikersnaam voor Speedealing database eigenaar.',
		'Password' => 'Wachtwoord',
		'AdminPassword' => 'Wachtwoord voor de database eigenaar.',
		'SystemIsInstalled' => 'De installatie is voltooid',
		'WithNoSlashAtTheEnd' => 'Zonder toevoeging van de slash "/"',
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