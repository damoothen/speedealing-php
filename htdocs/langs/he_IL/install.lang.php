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
		'PHPSupportGD' => 'תמיכה זו PHP פונקציות אלוקים גרפיים.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'זיכרון PHP ​​שלך מקס הפגישה מוגדרת <b>%s.</b> זה אמור להספיק.',
		'PHPMemoryTooLow' => 'זיכרון PHP ​​שלך מקס הפגישה מוגדרת בתים <b>%s.</b> זה צריך להיות נמוך מדי. שנה <b>php.ini</b> שלך להגדיר פרמטר <b>memory_limit</b> לפחות בתים <b>%s.</b>',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'התקנת PHP שלך לא תומך בפונקציה GD גרפי. הגרף לא תהיה זמינה.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'נכשל נסיון ליצור &quot;%s&quot; מסד הנתונים.',
		'ErrorFailedToConnectToDatabase' => 'נכשל נסיון להתחבר &quot;%s&quot; מסד הנתונים.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP גרסה ישנה מדי. %s גרסה נדרשת.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP גרסה ישנה מדי. %s גרסה או יותר צפוי. גרסה זו אמורה לאפשר להתקין אך לא נתמך.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'קובץ <b>%s</b> תצורת קיים.',
		'ConfFileDoesNotExists' => 'קובץ <b>%s</b> תצורת לא קיים!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'קבצים <b>%s</b> תצורה לא קיים ולא יכול להיווצר!',
		'ConfFileIsNotWritable' => 'קבצים <b>%s</b> תצורה אינו ניתן לכתיבה. בדוק את ההרשאות. עבור ההתקנה הראשונה, שרת האינטרנט צריך לקבל כדי להיות מסוגל לכתוב לתוך קובץ זה במהלך תהליך קביעת התצורה (&quot;chmod 666&quot;, למשל על יוניקס כמו מערכת הפעלה).',
		'ConfFileIsWritable' => 'קובץ <b>%s</b> תצורת הוא ניתן לכתיבה.',
		'YouMustCreateWithPermission' => 'עליך ליצור %s קבצים ולהגדיר הרשאות כתיבה על אותה עבור שרת האינטרנט במהלך תהליך ההתקנה.',
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
		'URLRoot' => 'כתובת האתר רוט',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'שם או כתובת IP של שרת מסד הנתונים, בדרך כלל \'localhost\', כאשר שרת מסד הנתונים מאוחסן על אותו שרת מ שרת האינטרנט',
		'ServerPortDescription' => 'יציאת שרת בסיס הנתונים. שמור על ריק אם לא ידוע.',
		'DatabaseServer' => 'שרת מסד הנתונים',
		'DatabaseName' => 'שם מסד הנתונים',
		'Login' => 'כניסה',
		'AdminLogin' => 'כניסה לבעל מסד הנתונים Speedealing.',
		'Password' => 'סיסמה',
		'AdminPassword' => 'הסיסמה של מסד הנתונים בעל Speedealing.',
		'SystemIsInstalled' => 'התקנה זו היא מוחלטת.',
		'WithNoSlashAtTheEnd' => 'ללא קו נטוי &quot;/&quot; על הקצה',
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