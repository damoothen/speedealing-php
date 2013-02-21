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
		'SpeedealingSetup' => 'Configurare Speedealing',
		'Welcome' => 'Bine ati venit',
		'WelcomeTitle' => 'Bine ati venit  la Speedealing',
		'WelcomeDescription' => 'Instalare Speedealing',
		'LanguageDescription' => 'Limba engleză in Statele Unite',
		'InstallTypeTitle' => 'Tip instalare',
		'InstallType' => 'Tip instalare',
		'InstallTypeDescription' => 'Alegeţi tipul dvs. de instalare',
		'InstallTypeServer' => 'Tip instalare server ',
		'InstallTypeServerDescription' => '',
		'InstallTypeClient' => 'Tip instalare client',
		'InstallTypeClientDescription' => '',
		// Prerequisite step
		'Prerequisites' => 'Cerinţe preliminare',
		'PrerequisitesTitle' => 'Verificare cerinţe preliminare',
		'PrerequisitesDescription' => 'Aplicaţia necesită câteva condiţii preliminare pe sistemul dvs. pentru a funcţiona corect.',
		'MoreInformation' => 'Mai multe informaţii',
		'PHPVersion' => 'Versiunea PHP',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Acest sprijin PHP functii grafice HG nr.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'Acest PHP suportă funcţii CURL',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'Acest PHP suportă funcţii  Mecached',
		'PHPMemoryLimit' => 'Memorie PHP',
		'PHPMemoryOK' => 'PHP max memorie sesiune este setată la <b>%s.</b> Acest lucru ar trebui să fie suficient.',
		'PHPMemoryTooLow' => 'PHP max memorie sesiune este setată la octeţi <b>%s.</b> Acest lucru ar trebui să fie prea mic. Modificarea <b>php.ini</b> pentru a seta parametrul <b>memory_limit</b> de bytes <b>%s</b> cel puţin.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB versiunea  %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Instalarea dvs. PHP nu are suport pentru funcţia de grafică HG nr. Graficul nu va fi disponibil.',
		'ErrorPHPDoesNotSupportCurl' => 'Instalarea dvs. PHP nu suportă funcţiile Curl. Acestea sunt necesare pentru a interacţiona cu baza de date.',
		'ErrorFailedToCreateDatabase' => 'Nu a reuşit să creeze &quot;%s&quot; bază de date.',
		'ErrorFailedToConnectToDatabase' => 'Nu a reuşit să se conecteze la &quot;%s&quot; bază de date.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP versiune prea veche. %s versiune este necesar.',
		'ErrorCouchDBVersion' => 'Versiunea CouchDB ( %s ) este prea veche. Versiunea %s sau mai nou este cerută.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP versiune prea veche. %s versiune sau mai mult este de aşteptat. Această versiune ar trebui să permită instalarea, dar nu este acceptat.',
		'WarningPHPDoesNotSupportMemcached' => 'Instalarea dvs. PHP nu suportă funcţii Memcached.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reîncarcă',
		'ReloadIsRequired' => 'Reîncărcarea este necesară',
		// Config file
		'ConfFileStatus' => 'Fişier de configurare',
		'ConfFileCreated' => 'Fişier configurare creat',
		'ConfFileExists' => '<b>%s</b> fişierul de configurare există.',
		'ConfFileDoesNotExists' => '<b>%s</b> fişier de configurare nu exista!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => '<b>%s</b> de fişiere de configurare nu există şi nu a putut fi creat!',
		'ConfFileIsNotWritable' => '<b>%s</b> fişier de configurare nu poate fi scris. Verificaţi permisiunile. Pentru prima de instalare, serverul de web trebuie să fie acordate pentru a putea scrie în acest fişier în timpul procesului de configurare (&quot;chmod 666&quot;, de exemplu, pe un Unix ca OS).',
		'ConfFileIsWritable' => '<b>%s</b> fişier de configurare este de scriere.',
		'YouMustCreateWithPermission' => 'Trebuie să creaţi %s de fişiere şi setaţi permisiuni de scriere pe el pentru serverul de web în timpul procesului de instalare.',
		// User sync
		'UserSyncCreated' => 'Replicarea  utilizatorului a fost creată',
		// Database
		'DatabaseCreated' => 'Baza de date a fost creată.',
		'WarningDatabaseAlreadyExists' => 'Baza de date  \'%s\' există deja.',
		// SuperAdmin
		'AdminCreated' => 'Superadninul a fost creat',
		// User
		'UserCreated' => 'Utilizatorul a fost creat',
		// Lock file
		'LockFileCreated' => 'Fişierul blocat a fost creat.',
		'LockFileCouldNotBeCreated' => 'Fişierul blocat nu a putut fi creat',
		'URLRoot' => 'URL-ul Root',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Numele sau adresa IP pentru serverul de baze de date, de obicei &quot;localhost&quot;, atunci când serverul de baze de date este găzduit pe acelaşi server decât serverul de web',
		'ServerPortDescription' => 'Port-ul serverului de baze de date. Păstraţi gol, dacă necunoscut.',
		'DatabaseServer' => 'Date de pe server',
		'DatabaseName' => 'Nazwa bazy danych',
		'Login' => 'Login',
		'AdminLogin' => 'Autentifica-te pentru proprietarul bazei de date Speedealing.',
		'Password' => 'Parolă',
		'AdminPassword' => 'Parola pentru proprietarul bazei de date Speedealing.',
		'SystemIsInstalled' => 'Această instalaţie este completă.',
		'WithNoSlashAtTheEnd' => 'Fără a slash &quot;/&quot; de la sfârşitul',
		'ServerPortCouchdbDescription' => 'Port du serveur. Défaut 5984.',
		'ServerAddressCouchdbDescription' => 'Nom FQDN du serveur de base de données, \'localhost.localdomain\' quand le serveur est installé sur la même machine que le serveur web',
		'DatabaseCouchdbUserDescription' => 'Login du super administrateur ayant tous les droits sur le serveur CouchDB ou l\'administrateur propriétaire de la base si la base et son compte d\'accès existent déjà (comme lorsque vous êtes chez un hébergeur).<br><br><div class="alert-box info">Cet utilisateur/mot de passe sera l\'administrateur pour se connecter à Speedealing.</div>',
		'ServerAddressMemcachedDesc' => 'Nom ou adresse ip du serveur memcached, généralement \'localhost\' quand le serveur est installé sur la même machine que le serveur web',
		'ServerPortMemcachedDesc' => 'Port du serveur memcached. Défaut : 11211',
		'FailedToCreateAdminLogin' => 'Echec de la création du compte administrateur Speedealing.',
		// Upgrade
		'UpgradeOk' => 'Actualizare efectuată !',
		'NewInstalledVersion' => 'Noua versiune Speedealing este %s',
		'NeedUpgrade' => 'Noua versiune Speadealing',
		'WarningUpgrade' => 'Versiunea instalata este %s, trebuie sa actualiyati la versiunea %s.<br> Contactati administratorul dvs.'
);
?>