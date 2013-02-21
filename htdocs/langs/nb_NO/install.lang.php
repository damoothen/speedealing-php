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
		'PHPVersion' => 'PHP versjon',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Denne PHP-støtte GD grafiske funksjoner.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Din PHP max økten minnet er satt til <b>%s.</b> Dette bør være nok.',
		'PHPMemoryTooLow' => 'Din PHP max økten minnet er satt til <b>%s</b> bytes. Dette bør være for lav. Endre <b>php.ini</b> å sette <b>memory_limit</b> parameter til minst <b>%s</b> byte.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Din PHP installasjon har ikke støtte for grafiske funksjonen GD. Ingen grafen vil være tilgjengelig.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Kunne ikke opprette database \'%s\'.',
		'ErrorFailedToConnectToDatabase' => 'Kunne ikke koble til database \'%s\'.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP-versjonen for gammel. Versjon %s er nødvendig.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP-versjonen for gammel. Versjon %s eller mer er ventet. Denne versjonen bør tillate installere, men er ikke støttet.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Konfigurasjonsfil <b>%s</b> eksisterer.',
		'ConfFileDoesNotExists' => 'Konfigurasjonsfil <b>%s</b> finnes ikke!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Konfigurasjonsfil <b>%s</b> eksisterer ikke og kunne ikke opprettes!',
		'ConfFileIsNotWritable' => 'Konfigurasjonsfil <b>%s</b> er ikke skrivbar. Sjekk tillatelser. For første installere, må webserveren få innvilget å kunne skrive inn i denne filen under konfigureringen ("chmod 666" for eksempel på en Unix som OS).',
		'ConfFileIsWritable' => 'Konfigurasjonsfil <b>%s</b> er skrivbar.',
		'YouMustCreateWithPermission' => 'Du må lage filen %s og sette skriverettigheter på den for web-serveren under installasjonsprosessen.',
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
		'ServerAddressDescription' => 'Navn eller IP-adressen til database-serveren, som regel "localhost" når database server ligger på samme server enn webserveren',
		'ServerPortDescription' => 'Database server port. Hold tomt hvis ukjent.',
		'DatabaseServer' => 'Databaseserveren',
		'DatabaseName' => 'Databasenavn',
		'Login' => 'Innlogging',
		'AdminLogin' => 'Logg inn for Speedealing database eier.',
		'Password' => 'Passord',
		'AdminPassword' => 'Passord for Speedealing database eier.',
		'SystemIsInstalled' => 'Denne installasjonen er fullført.',
		'WithNoSlashAtTheEnd' => 'Uten skråstrek "/" på slutten',
		'ServerPortCouchdbDescription' => 'Port du serveur. Défaut 5984.',
		'ServerAddressCouchdbDescription' => 'Nom FQDN du serveur de base de données, \'localhost.localdomain\' quand le serveur est installé sur la même machine que le serveur web',
		'DatabaseCouchdbUserDescription' => 'Login du super administrateur ayant tous les droits sur le serveur CouchDB ou l\'administrateur propriétaire de la base si la base et son compte d\'accès existent déjà (comme lorsque vous êtes chez un hébergeur).<br><br><div class="alert-box info">Cet utilisateur/mot de passe sera l\'administrateur pour se connecter à Speedealing.</div>',
		'ServerAddressMemcachedDesc' => 'Nom ou adresse ip du serveur memcached, généralement \'localhost\' quand le serveur est installé sur la même machine que le serveur web',
		'ServerPortMemcachedDesc' => 'Port du serveur memcached. Défaut : 11211',
		'FailedToCreateAdminLogin' => 'Echec de la création du compte administrateur Speedealing.',
		// Upgrade
		'UpgradeOk' => 'Upgrade is ok !',
		'NewInstalledVersion' => 'Your new version is %s',
		'NeedUpgrade' => 'New Speedealing version !',
		'WarningUpgrade' => 'Installed version is %s, you must upgrade to %s. <br>Please contact your administrator.'
);
?>