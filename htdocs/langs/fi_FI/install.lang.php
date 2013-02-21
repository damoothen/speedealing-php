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
		'PHPSupportGD' => 'Tämä PHP tukea GD graafisia toimintoja.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Sinun PHP max istuntojakson muisti on <b>asetettu %s.</b> Tämän pitäisi olla tarpeeksi.',
		'PHPMemoryTooLow' => 'Sinun PHP max istuntojakson muisti on <b>asetettu %s</b> tavua. Tämä olisi liian alhainen. Change your <b>php.ini</b> asettaa <b>memory_limit</b> parametri <b>vähintään %s</b> tavua.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Sinun PHP asennus ei tue graafisia toiminto GD. N: o kaavio on saatavilla.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Luominen epäonnistui tietokanta \' %s\'.',
		'ErrorFailedToConnectToDatabase' => 'Epäonnistui muodostaa tietokanta \' %s\'.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP versio liian vanha. Versio %s on tarpeen.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP version liian vanha. Versio %s tai enemmän odotetaan. Tämä versio pitäisi mahdollistaa asentaa mutta ei tueta.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Configuration <b>file %s</b> on olemassa.',
		'ConfFileDoesNotExists' => 'Configuration <b>file %s</b> ei ole olemassa!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Configuration <b>file %s</b> ei ole olemassa, ja ei voi luoda!',
		'ConfFileIsNotWritable' => 'Kokoonpano <b>tiedostoa %s</b> ei ole kirjoitettavissa. Tarkista käyttöoikeudet. Ensimmäistä kertaa asentaa, verkkopalvelimesi on myönnettävä voi kirjoittaa tämä tiedosto aikana asennusprosessi ( "chmod 666" esimerkiksi Unix kuten OS).',
		'ConfFileIsWritable' => 'Configuration <b>file %s</b> on kirjoitettavissa.',
		'YouMustCreateWithPermission' => 'Sinun on luotava tiedosto %s ja asettaa kirjoittaa oikeudet sen Web-palvelimen aikana asentaa prosessiin.',
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
		'URLRoot' => 'URL juuri',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Nimi tai IP-osoite tietokanta palvelimelle, yleensä "localhost", kun tietokanta-palvelimen isännöidään samalla palvelimella kuin web-palvelin',
		'ServerPortDescription' => 'Tietokanta-palvelimen portti. Pidä tyhjä, jos tuntematon.',
		'DatabaseServer' => 'Database Server',
		'DatabaseName' => 'Tietokannan nimi',
		'Login' => 'Kirjautuminen',
		'AdminLogin' => 'Kirjautumistunnuksen Speedealing tietokannan ylläpitäjä. Pidä tyhjä jos kytket nimettömässä',
		'Password' => 'Salasana',
		'AdminPassword' => 'Salasana Speedealing tietokannan ylläpitäjä. Pidä tyhjä jos kytket nimettömässä',
		'SystemIsInstalled' => 'Tämä asennus on valmis.',
		'WithNoSlashAtTheEnd' => 'Ilman kauttaviivalla "/" lopussa',
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