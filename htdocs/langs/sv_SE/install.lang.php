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
		'PHPSupportGD' => 'Denna PHP stöd GD grafiska funktioner.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Din PHP max session minne är inställt på <b>%s.</b> Detta bör vara nog.',
		'PHPMemoryTooLow' => 'Din PHP max session minne är inställt på <b>%s</b> byte. Detta bör vara för låg. Ändra din <b>php.ini</b> för att ställa <b>memory_limit</b> parameter till minst <b>%s</b> byte.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Din PHP installation saknar stöd för grafisk funktion GD. Inga diagram kommer att finnas tillgängliga.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Misslyckades med att skapa databasen %s.',
		'ErrorFailedToConnectToDatabase' => 'Det gick inte att ansluta till databasen &quot;%s&quot;.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP version gamla också. Version %s krävs.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP version för gammal. Version %s eller flera förväntas. Denna version bör göra det möjligt installera, men stöds inte.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Konfigurationsfilen <b>%s</b> finns.',
		'ConfFileDoesNotExists' => 'Konfigurationsfilen <b>%s</b> finns inte!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Konfigurationsfilen <b>%s</b> finns inte och kunde inte skapas!',
		'ConfFileIsNotWritable' => 'Konfigurationsfilen <b>%s</b> är inte skrivbar. Kontrollera behörigheter. För den första installationen, måste din webbserver beviljas för att kunna skriva i denna fil under konfigurationen (&quot;chmod 666&quot; till exempel på en UNIX-liknande OS).',
		'ConfFileIsWritable' => 'Konfigurationsfilen <b>%s</b> är skrivbar.',
		'YouMustCreateWithPermission' => 'Du måste skapa filen %s och sätta skrivrättigheter på den för den webbserver under installationsprocessen.',
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
		'ServerAddressDescription' => 'Namn eller IP-adress för databasserver, vanligtvis &quot;localhost&quot; när databasservern är värd för på samma server än webbserver',
		'ServerPortDescription' => 'Databasservern hamn. Håll tom om okänd.',
		'DatabaseServer' => 'Databasservern',
		'DatabaseName' => 'Databas namn',
		'Login' => 'Inloggning',
		'AdminLogin' => 'Logga in för Speedealing databas ägaren.',
		'Password' => 'Lösenord',
		'AdminPassword' => 'Lösenord för Speedealing databas ägaren.',
		'SystemIsInstalled' => 'Denna installation är klar.',
		'WithNoSlashAtTheEnd' => 'Utan ett snedstreck &quot;/&quot; i slutet',
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