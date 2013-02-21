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
		'PHPVersion' => 'PHP-Version',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Ihre PHP-Konfiguration unterstützt grafische Funktionen mittels GD.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Die Sitzungsspeicherbegrenzung ihrer PHP-Konfiguration steht auf <b>%s</b>. Dies sollte ausreichend sein.',
		'PHPMemoryTooLow' => 'Die Sitzungsspeicherbegrenzung ihrer PHP-Konfiguration steht auf <b>%s</b> Bytes. Dies ist nicht ausreichend. Ändern Sie in Ihrer <b>php.ini</b> den Parameter <b>memory_limit</b> auf mindestens <b>%s</b> Bytes.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Ihre PHP-Installation unterstützt keine grafischen Funktion mittels GD. Ihnen stehen dadurch keine Diagramme zur Verfügung.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Fehler beim Erstellen der Datenbank \'%s\'.',
		'ErrorFailedToConnectToDatabase' => 'Es konnte keine Verbindung zur Datenbank \' %s\'.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'Ihre PHP-Version ist veraltet. Sie benötigen mindestens Version %s .',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'Die PHP-Version ist zu alt. Es wird Version %s oder höher erwartet. Sie können unter dieser PHP-Version installieren, aber sie wird nicht unterstützt.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Die Konfigurationsdatei <b>%s</b> ist vorhanden.',
		'ConfFileDoesNotExists' => 'Die Konfigurationsdatei <b>%s</b> existiert nicht!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Die Konfigurationsdatei <b>%s</b> ist nicht vorhanden und konnte auch nicht erstellt werden!',
		'ConfFileIsNotWritable' => 'Die Konfigurationsdatei <b>%s</b> ist nicht beschreibbar. Bitte überprüfen Sie die Dateizugriffsrechte. Für die Erstinstallation muss Ihr Webserver in die Konfigurationsdatei schreiben können, sezzten Sie die Dateiberechtigungen entsprechend (z.B. mittels "chmod 666" auf Unix-Betriebssystemen).',
		'ConfFileIsWritable' => 'Die Konfigurationsdatei <b>%s</b> ist beschreibbar.',
		'YouMustCreateWithPermission' => 'Für den Installationsvorgang erstellen Sie bitte die Datei %s und machen Sie diese für Ihren Webserver beschreibbar.',
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
		'URLRoot' => 'URL root',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Name oder IP-Adresse des Datenbankservers, in der Regel ist dies "localhost" (Datenbank und Webserver liegen auf demselben Server).',
		'ServerPortDescription' => 'Datenbankserver-Port. Lassen Sie dieses Feld im Zweifel leer.',
		'DatabaseServer' => 'Datenbankserver',
		'DatabaseName' => 'Name der Datenbank',
		'Login' => 'Anmeldung',
		'AdminLogin' => 'Login für Speedealing Datenbank-Administrator.',
		'Password' => 'Passwort',
		'AdminPassword' => 'Passwort des speedealing-Datenbankadministrators',
		'SystemIsInstalled' => 'Die Installation wurde erfolgreich abgeschlossen.',
		'WithNoSlashAtTheEnd' => 'Ohne Schrägstrich "/" am Ende',
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