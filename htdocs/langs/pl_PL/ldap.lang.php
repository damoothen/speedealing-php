<?php
/* Copyright (C) 2012	Regis Houssin	<regis@dolibarr.fr>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

$ldap = array(
		'CHARSET' => 'UTF-8',
		'DomainPassword' => 'Hasło do domeny',
		'YouMustChangePassNextLogon' => 'Hasło dla <b>użytkownika %s na %s</b> domeny muszą być zmienione.',
		'UserMustChangePassNextLogon' => 'Użytkownik musi zmienić hasło w domenie %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Konto użytkownika',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Hasło nigdy nie wygasa',
		'LdapUacf_ACCOUNTDISABLE' => 'Konto jest wyłączone w domenie %s',
		'LDAPInformationsForThisContact' => 'Informacje zawarte w bazie danych LDAP dla tego kontaktu',
		'LDAPInformationsForThisUser' => 'Informacje zawarte w bazie danych LDAP dla tego użytkownika',
		'LDAPInformationsForThisGroup' => 'Informacje zawarte w bazie danych LDAP dla tej grupy',
		'LDAPInformationsForThisMember' => 'Informacje zawarte w bazie danych LDAP dla tego członka',
		'LDAPAttribute' => 'LDAP atrybutu',
		'LDAPAttributes' => 'LDAP atrybuty',
		'LDAPCard' => 'LDAP karty',
		'LDAPRecordNotFound' => 'Zapis nie został odnaleziony w bazie danych LDAP',
		'LDAPUsers' => 'Użytkowników w bazie danych LDAP',
		'LDAPGroups' => 'Grupy w bazie danych LDAP',
		'LDAPFieldStatus' => 'Stan',
		'LDAPFieldFirstSubscriptionDate' => 'Pierwsze subskrypcji daty',
		'LDAPFieldFirstSubscriptionAmount' => 'Fist kwoty abonamentu',
		'LDAPFieldLastSubscriptionDate' => 'Ostatnia data subskrypcji',
		'LDAPFieldLastSubscriptionAmount' => 'Ostatnia kwota subskrypcji',
		'SynchronizeDolibarr2Ldap' => 'Synchronizacja użytkownika (Dolibarr -> LDAP)',
		'UserSynchronized' => 'Użytkownik zsynchronizowane',
		'GroupSynchronized' => 'Grupa zsynchronizowane',
		'MemberSynchronized' => 'Państwa zsynchronizowane',
		'ContactSynchronized' => 'Kontakt zsynchronizowane',
		'ForceSynchronize' => 'Siły synchronizujące Dolibarr -> LDAP',
		'ErrorFailedToReadLDAP' => 'Nie można odczytać bazy danych LDAP. Sprawdź LDAP moduł konfiguracji bazy danych i dostępności.'
);
?>