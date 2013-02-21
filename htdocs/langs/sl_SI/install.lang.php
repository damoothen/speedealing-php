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
		'PHPVersion' => 'PHP Verzija',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Ta PHP podpira GD grafične funkcije.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Maksimalni spomin za sejo vašega PHP je nastavljen na <b>%s</b>. To bi moralo zadoščati.',
		'PHPMemoryTooLow' => 'Maksimalni spomin za sejo vašega PHP je nastavljen na <b>%s</b> bytov. To je morda premalo. Spremenite datoteko <b>php.ini</b> in nastavite parameter <b>memory_limit</b> najmanj na <b>%s</b> bytov.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Vaša PHP instalacija ne podpira grafične funkcije GD. Grafi ne bodo na voljo.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Neuspešno kreiranje baze podatkov \'%s\'.',
		'ErrorFailedToConnectToDatabase' => 'Neuspešna povezava z bazo podatkov \'%s\'.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP verzija je prestara. Zahtevana je verzija %s.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP verzija je prestara. Pričakovana je verzija %s ali novejša. Ta verzija bi morala dovoliti namestitev, vendar ni podprta.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Konfiguracijska datoteka <b>%s</b> že obstaja.',
		'ConfFileDoesNotExists' => 'Konfiguracijska datoteka <b>%s</b> ne obstaja !',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Konfiguracijska datoteka <b>%s</b> Ne obstaja in je ni mogoče kreirati !',
		'ConfFileIsNotWritable' => 'V konfiguracijsko datoteko <b>%s</b> ni možno zapisovanje. Preverite dovoljenja. Pri prvi instalaciji mora vaš strežnik dovoljevati možnost zapisovanja v to datoteko med postopkom konfiguracije(na primer "chmod 666" na Unix in podobnih OS).',
		'ConfFileIsWritable' => 'V konfiguracijsko datoteka <b>%s</b> je možno zapisovanje.',
		'YouMustCreateWithPermission' => 'Ustvariti morate datoteko %s in nastaviti dovoljenja za zapisovanje za spletni strežnik med postopkom namestitve.',
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
		'URLRoot' => 'URL koren',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Ime ali IP naslov strežnika za bazo podatkov, običajno \'localhost\', če strežni baze podatkov gostuje na istem strežniku, kot spletni strežnik',
		'ServerPortDescription' => 'Vrata strežnika baze podatkov. Če niso znana, pustite prazno.',
		'DatabaseServer' => 'Strežnik za bazo podatkov',
		'DatabaseName' => 'Ime baze podatkov',
		'Login' => 'Uporabniško ime',
		'AdminLogin' => 'Uporabniško ime za lastnika Speedealing baze podatkov.',
		'Password' => 'Geslo',
		'AdminPassword' => 'Geslo za lastnika Speedealing baze podatkov.',
		'SystemIsInstalled' => 'Ta instalacija je zaključena.',
		'WithNoSlashAtTheEnd' => 'Brez poševnice "/" na koncu',
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