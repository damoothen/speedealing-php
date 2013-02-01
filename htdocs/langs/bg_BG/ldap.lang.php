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

$ldap = array(
		'CHARSET' => 'UTF-8',
		'DomainPassword' => 'Парола за домейн',
		'YouMustChangePassNextLogon' => 'Парола за потребителски <b>%s</b> за домейна <b>%s</b> трябва да бъдат променени.',
		'UserMustChangePassNextLogon' => 'Потребителят трябва да смени паролата на домейна %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Потребителски акаунт',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Парола не изтича',
		'LdapUacf_ACCOUNTDISABLE' => 'Акаунтът е забранен в областта %s',
		'LDAPInformationsForThisContact' => 'Информация в LDAP база данни за този контакт',
		'LDAPInformationsForThisUser' => 'Информация в LDAP база данни за този потребител',
		'LDAPInformationsForThisGroup' => 'Информация в LDAP база данни за тази група',
		'LDAPInformationsForThisMember' => 'Информация в LDAP база данни за този потребител',
		'LDAPAttribute' => 'LDAP атрибут',
		'LDAPAttributes' => 'LDAP атрибути',
		'LDAPCard' => 'LDAP карта',
		'LDAPRecordNotFound' => 'Запишете не се срещат в LDAP база данни',
		'LDAPUsers' => 'Потребителите в LDAP база данни',
		'LDAPGroups' => 'Групи в LDAP база данни',
		'LDAPFieldStatus' => 'Статус',
		'LDAPFieldFirstSubscriptionDate' => 'Първа абонамент дата',
		'LDAPFieldFirstSubscriptionAmount' => 'Първа размера',
		'LDAPFieldLastSubscriptionDate' => 'Последно абонамент дата',
		'LDAPFieldLastSubscriptionAmount' => 'Последно размера',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Потребителят синхронизирани',
		'GroupSynchronized' => 'Група синхронизирани',
		'MemberSynchronized' => 'Държавите-синхронизирани',
		'ContactSynchronized' => 'Свържи се синхронизират',
		'ForceSynchronize' => 'Force синхронизиране Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Неуспех при четенето на LDAP база данни. Проверете LDAP модул за настройка и достъпността на базата данни.'
);
?>