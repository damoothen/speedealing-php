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
		'PHPVersion' => 'Versione PHP',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'PHP con supporto grafico GD.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'La memoria massima per la sessione è fissata dal PHP a <b>%s</b>. Dovrebbe essere sufficiente.',
		'PHPMemoryTooLow' => 'La memoria massima per la sessione è fissata dal PHP a <b>%s</b> byte. Cambia il file <b>php.ini</b> per impostare il parametro <b>memory_limit</b> ad almeno <b>%s</b> byte.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'La tua installazione di PHP non supporta la funzione grafica GD. Non sarà disponibile alcun grafico.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Impossibile creare il database <b>%s</b>.',
		'ErrorFailedToConnectToDatabase' => 'Impossibile collegarsi al database <b>%s</b>.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'Versione PHP troppo vecchia. E\' obbligatoria la versione %s o superiori.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'Versione del PHP troppo vecchia. È necessaria la versione %s o successive. Questa versione potrebbe consentire l\'installazione, ma non è supportata.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Il file di configurazione <b>%s</b> esiste.',
		'ConfFileDoesNotExists' => 'Il file di configurazione <b>%s</b> non esiste!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Il file di configurazione <b>%s</b> non esiste e non può essere creato!',
		'ConfFileIsNotWritable' => 'Il file di configurazione <b>%s</b> non è scrivibile. Controllare le autorizzazioni. Durante la prima installazione, il server web deve essere in grado di scrivere in questo file durante il processo di configurazione (usa, per esempio il comando "chmod 666" per renderlo scrivibile da tutti gli utenti su sistemi operativi Unix e simili).',
		'ConfFileIsWritable' => 'Il file di configurazione <b>%s</b> è scrivibile.',
		'YouMustCreateWithPermission' => 'È necessario creare il file %s e dare al server web i permessi di scrittura durante il processo di installazione.',
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
		'ServerAddressDescription' => 'Nome o indirizzo IP del database server. Quando il database è ospitato sullo stesso server del web, utilizzare <b>localhost</b>.',
		'ServerPortDescription' => 'Porta. Lasciare vuoto se sconosciuta.',
		'DatabaseServer' => 'Database server',
		'DatabaseName' => 'Nome del database',
		'Login' => 'Login',
		'AdminLogin' => 'Login per amministratore del database. Da lasciare vuoto se ci si collega in forma anonima',
		'Password' => 'Password',
		'AdminPassword' => 'Password per amministratore del database. Da lasciare vuoto se ci si collega in forma anonima',
		'SystemIsInstalled' => 'Installazione completata.',
		'WithNoSlashAtTheEnd' => 'Senza la barra "/" alla fine',
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