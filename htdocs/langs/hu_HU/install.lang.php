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
		'PHPVersion' => 'PHP Verzió',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Ez a PHP verzió támogatja a GD grafikai funkciókat.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'A munkamenetek maximális memóriája <b>%s</b>. Ennek elégnek kéne lennie.',
		'PHPMemoryTooLow' => 'A munkamenetek maximális memóriája <b>%s</b> byte. Ez kevés lesz. A <b>php.ini</b>-ben a <b>memory_limit</b> paramétert legalább <b>%s</b> byte-ra kell állítani.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Ez a PHP verzió NEM támogatja a GD grafikai funkciókat. A grafikonok nem lesznek elérhetõek.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Nem sikerült létrehozni a(z) \'%s\' adatbázist.',
		'ErrorFailedToConnectToDatabase' => 'Nem sikerült csatlakozni a(z) \'%s\' adatbázishoz.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'Túl régi a PHP verzió. Legalább %s kell.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP verzió túl régi. %s vagy több verzió várható. Ez a változat lehetővé teszi telepíteni, de nem támogatott.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => '<b>%s</b> konfigurációs fájl már létezik.',
		'ConfFileDoesNotExists' => '<b>%s</b> konfigurációs fájl NEM létezik!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => '<b>%s</b> konfigurációs fájl NEM létezik és NEM lehet létrehozni!',
		'ConfFileIsNotWritable' => '<b>%s</b> konfigurációs fájl NEM írható. Ellenõrizze a jogosúltságokat. Elsõ telepítés esetén, a web szervernek tudnia kell írni ebbe a fájlba a konfigurációs folyamat során (Unix alapu rendszer esetén "chmod 666").',
		'ConfFileIsWritable' => '<b>%s</b> konfigurációs fájl írható.',
		'YouMustCreateWithPermission' => 'Létre kell hoznia %s fájlt és írásai jogokat adnia a webszerveren a telepítés idejére.',
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
		'URLRoot' => 'Gyökér URL',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Adatbûzis szerver neve vagy IP címe, általában \'localhost\' vagy \'127.0.0.1\' ha ugyan az a webszerver és az adatbázis szerver',
		'ServerPortDescription' => 'Adatbázis szerver port. Hagyja üresen ha nem tudja.',
		'DatabaseServer' => 'Adatbázis szerver',
		'DatabaseName' => 'Adatbázis név',
		'Login' => 'Bejelentkezés',
		'AdminLogin' => 'Adatbázis tulajdonos bejelentkezési neve.',
		'Password' => 'Jelszó',
		'AdminPassword' => 'Adatbázis tulajdonos jelszava.',
		'SystemIsInstalled' => 'A telepítés készen van.',
		'WithNoSlashAtTheEnd' => '"/" nélkül a végén',
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