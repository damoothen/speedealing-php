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
		'PHPVersion' => 'PHP الإصدار',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'PHP هذا الدعم البيانية ش ج المهام.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'الحد الأقصى الخاص بك PHP دورة الذاكرة ومن المقرر <b>٪ ق.</b> وينبغي أن يكون هذا كافيا.',
		'PHPMemoryTooLow' => 'الحد الأقصى الخاص بك PHP دورة الذاكرة ومن المقرر <b>٪ ق</b> بايت. لهذا ينبغي أن يكون منخفضا جدا. تغيير <b>php.ini</b> وضع <b>memory_limit</b> المعلم إلى ما لا يقل عن <b>٪ ق</b> بايت.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'PHP تركيب الخاص بك لا يدعم وظيفة بيانية ش ج. لا الرسم البياني سيكون متاحا.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'فشل إنشاء قاعدة بيانات \'٪ ق.',
		'ErrorFailedToConnectToDatabase' => 'فشل في الاتصال بقاعدة البيانات \'٪ ق.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP نسخة قديمة جدا. النسخة ٪ ق هو مطلوب.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP version too old. Version %s or more is expected. This version should allow install but is not supported.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'ملفات موجودة <b>٪ ق.</b>',
		'ConfFileDoesNotExists' => 'ملفات <b>ل ٪</b> لا وجود له!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'ملفات <b>ل ٪</b> لا وجود له وأنه لا يمكن خلق!',
		'ConfFileIsNotWritable' => 'ملفات <b>٪ ق</b> ليست للكتابة. التحقق من الأذونات. أولا لتركيب وخدمة الويب الخاص بك يجب أن تمنح ليكون قادرا على الكتابة في هذا الملف خلال عملية التهيئة ( "chmod 666" على سبيل المثال ، مثل نظام التشغيل يونكس).',
		'ConfFileIsWritable' => 'ملفات للكتابة هو <b>٪ ق.</b>',
		'YouMustCreateWithPermission' => 'يجب إنشاء ملف ق ٪ ومجموعة الكتابة على أذونات لملقم الويب أثناء عملية التثبيت.',
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
		'URLRoot' => 'عنوان روت',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'الملكية الفكرية في اسم أو عنوان خادم قاعدة البيانات ، وعادة \'localhost\' عندما يستضيف خادم قاعدة البيانات على نفس الخادم من خدمة الويب',
		'ServerPortDescription' => 'قاعدة بيانات الميناء. تبقي فارغة إذا كانت غير معروفة.',
		'DatabaseServer' => 'خادم قاعدة البيانات',
		'DatabaseName' => 'اسم قاعدة البيانات',
		'Login' => 'تسجيل الدخول',
		'AdminLogin' => 'ادخل لSpeedealing مدير قاعدة البيانات. تبقي فارغة إذا لم يذكر اسمه في اتصال',
		'Password' => 'كلمة السر',
		'AdminPassword' => 'Speedealing كلمة السر لمدير قاعدة البيانات. تبقي فارغة إذا لم يذكر اسمه في اتصال',
		'SystemIsInstalled' => 'هذا التثبيت الكامل.',
		'WithNoSlashAtTheEnd' => 'بدون خفض "/" في نهاية',
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