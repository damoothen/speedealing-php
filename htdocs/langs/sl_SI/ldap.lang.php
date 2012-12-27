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
		'DomainPassword' => 'Geslo za domeno',
		'YouMustChangePassNextLogon' => 'Geslo za uporabnika <b>%s</b> na domeni <b>%s</b> je potrebno spremeniti.',
		'UserMustChangePassNextLogon' => 'Uporabnik mora spremeniti geslo na domeni %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Uporabniški račun',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Geslo nikoli ne preteče',
		'LdapUacf_ACCOUNTDISABLE' => 'Račun na domeni %s je ukinjen',
		'LDAPInformationsForThisContact' => 'Informacija v LDAP bazi podatkov za ta kontakt',
		'LDAPInformationsForThisUser' => 'Informacija v LDAP bazi podatkov za tega uporabnika',
		'LDAPInformationsForThisGroup' => 'Informacija v LDAP bazi podatkov za to skupino',
		'LDAPInformationsForThisMember' => 'Informacija v LDAP bazi podatkov za tega člana',
		'LDAPAttribute' => 'LDAP atribut',
		'LDAPAttributes' => 'LDAP atributi',
		'LDAPCard' => 'LDAP kartica',
		'LDAPRecordNotFound' => 'Zapisa ni mogoče najti v LDAP bazi podatkov',
		'LDAPUsers' => 'Uporabniki v LDAP bazi podatkov',
		'LDAPGroups' => 'Skupine v LDAP bazi podatkov',
		'LDAPFieldStatus' => 'Status',
		'LDAPFieldFirstSubscriptionDate' => 'Datum prve naročnine',
		'LDAPFieldFirstSubscriptionAmount' => 'Znesek prve naročnine',
		'LDAPFieldLastSubscriptionDate' => 'Datum zadnje naročnine',
		'LDAPFieldLastSubscriptionAmount' => 'Znesek zadnje naročnine',
		'SynchronizeDolibarr2Ldap' => 'Sinhroniziraj uporabnika (Dolibarr -> LDAP)',
		'UserSynchronized' => 'Uporabnik sinhroniziran',
		'GroupSynchronized' => 'Skupina sinhronizirana',
		'MemberSynchronized' => 'Član sinhroniziran',
		'ContactSynchronized' => 'Kontakt sinhroniziran',
		'ForceSynchronize' => 'Vsili sinhronizacijo Dolibarr -> LDAP',
		'ErrorFailedToReadLDAP' => 'Branje LDAP baze podatkov ni uspelo. Preverite nastavitev LDAP modula in dostopnost baze podatkov.'
);
?>