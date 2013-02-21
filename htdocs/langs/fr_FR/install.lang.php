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
		'SpeedealingSetup' => 'Installation de Speedealing',
		'Welcome' => 'Bienvenue',
		'WelcomeTitle' => 'Bienvenue dans Speedealing',
		'WelcomeDescription' => 'Installation de Speedealing',
		'LanguageDescription' => 'Langage utilisé en France métropolitaine',
		'InstallTypeTitle' => 'Type d\'installation',
		'InstallType' => 'Type d\'installation',
		'InstallTypeDescription' => 'Choisir son type d\'installation',
		'InstallTypeServer' => 'Installation type serveur',
		'InstallTypeServerDescription' => 'L\'installation type serveur contient la base de données. Elle est nécessaire pour connecter un ou plusieurs "clients" par la suite.',
		'InstallTypeClient' => 'Installation type client',
		'InstallTypeClientDescription' => 'L\'installation type client permet de se connecter à une installation type serveur contenant la base de données.',
		// Prerequisite step
		'Prerequisites' => 'Prérequis',
		'PrerequisitesTitle' => 'Test des prérequis',
		'PrerequisitesDescription' => 'Speedealing nécessite plusieurs prérequis afin de fonctionner convenablement sur votre système.',
		'MoreInformation' => 'Plus d\'informations',
		'PHPVersion' => 'Version PHP',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Ce PHP supporte les fonctions graphiques GD.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'Ce PHP supporte les fonctions CURL.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'Ce PHP supporte les fonctions Memcached.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Votre mémoire maximum de session PHP est définie à <b>%s</b>. Ceci devrait être suffisant.',
		'PHPMemoryTooLow' => 'Votre mémoire maximum de session PHP est définie à <b>%s</b> octets. Ceci est trop faible. Il est recommandé de modifier le paramètre <b>memory_limit</b> de votre fichier <b>php.ini</b> à au moins <b>%s</b> octets.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Ce PHP ne supporte pas les fonctions graphiques GD. Aucun graphique ne sera disponible.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Echec de création de la base \'%s\'.',
		'ErrorFailedToConnectToDatabase' => 'Echec de connexion à la base \'%s\'.',
		'ErrorDatabaseVersionTooLow' => 'Version de base de donnée (%s) trop ancienne. La version %s ou supérieure est requise.',
		'ErrorPHPVersionTooLow' => 'Version de PHP trop ancienne. La version %s est requise.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'Version de PHP trop ancienne. La version %s ou plus est recommandée. Cette version reste utilisable mais n\'est pas supportée.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Rechargement',
		'ReloadIsRequired' => 'Un rechargement est nécessaire',
		// Config file
		'ConfFileStatus' => 'Fichier de configuration',
		'ConfFileCreated' => 'Fichier de configuration créé',
		'ConfFileExists' => 'Le fichier de configuration <b>%s</b> existe.',
		'ConfFileDoesNotExists' => 'Le fichier de configuration <b>%s</b> n\'existe pas !',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Le fichier de configuration <b>%s</b> n\'existe pas et n\'a pu être créé !',
		'ConfFileIsNotWritable' => 'Le fichier <b>%s</b> n\'est pas modifiable. Pour une première installation, modifiez ses permissions. Le serveur Web doit avoir le droit d\'écrire dans ce fichier le temps de la configuration ("chmod 666" par exemple sur un OS compatible Unix).',
		'ConfFileIsWritable' => 'Le fichier <b>%s</b> est modifiable.',
		'YouMustCreateWithPermission' => 'Vous devez créer un fichier %s et donner les droits d\'écriture dans celui-ci au serveur web durant le processus d\'installation.',
		// User sync
		'UserSyncCreated' => 'La réplication de l\'utilisateur a été créé.',
		// Database
		'DatabaseCreated' => 'La base de données a été créé.',
		'WarningDatabaseAlreadyExists' => 'La base de données \'%s\' existe déjà.',
		// SuperAdmin
		'AdminCreated' => 'Le superadmin a été créé.',
		// User
		'UserCreated' => 'L\'utilisateur a été créé.',
		// Lock file
		'LockFileCreated' => 'Le fichier de verrouillage a été créé.',
		'LockFileCouldNotBeCreated' => 'Le fichier de verrouillage ne peut pas être créé.',
		'URLRoot' => 'URL Racine',
		'SpeedealingDatabase' => 'Base de données Speedealing',
		'ServerAddressDescription' => 'Nom ou adresse ip du serveur de base de données, généralement \'localhost\' quand le serveur est installé sur la même machine que le serveur web',
		'ServerPortDescription' => 'Port du serveur. Ne rien mettre si inconnu.',
		'DatabaseServer' => 'Serveur de la base de données',
		'DatabaseName' => 'Nom de la base',
		'Login' => 'Identifiant',
		'AdminLogin' => 'Identifiant du propriétaire de la base de données Speedealing.',
		'Password' => 'Mot de passe',
		'AdminPassword' => 'Mot de passe du propriétaire de la base de données Speedealing.',
		'SystemIsInstalled' => 'Votre système est maintenant installé.',
		'WithNoSlashAtTheEnd' => 'Sans le slash "/" à la fin',
		'ServerPortCouchdbDescription' => 'Port du serveur. Défaut 5984.',
		'ServerAddressCouchdbDescription' => 'Nom FQDN du serveur de base de données, \'localhost.localdomain\' quand le serveur est installé sur la même machine que le serveur web',
		'DatabaseCouchdbUserDescription' => 'Identifiant du super administrateur ayant tous les droits sur le serveur CouchDB ou l\'administrateur propriétaire de la base si la base et son compte d\'accès existent déjà (comme lorsque vous êtes chez un hébergeur).<br><br><div class="alert-box info">Cet utilisateur/mot de passe sera l\'administrateur pour se connecter à Speedealing.</div>',
		'ServerAddressMemcachedDesc' => 'Nom ou adresse ip du serveur memcached, généralement \'localhost\' quand le serveur est installé sur la même machine que le serveur web',
		'ServerPortMemcachedDesc' => 'Port du serveur memcached. Défaut : 11211',
		'FailedToCreateAdminLogin' => 'Echec de la création du compte administrateur Speedealing.',
		// Upgrade
		'UpgradeOk' => 'Mise à jour effectuée !',
		'NewInstalledVersion' => 'La nouvelle version Speedealing est %s',
		'NeedUpgrade' => 'Nouvelle version Speedealing !',
		'WarningUpgrade' => 'La version installée est %s, vous devez mettre à jour vers la version %s. <br>Merci de contacter votre administrateur.'
);
?>