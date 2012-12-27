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
		'DomainPassword' => 'Salasõna domeeni',
		'YouMustChangePassNextLogon' => 'Salasõna kasutaja <b>%s</b> domeeni <b>%s</b> tuleb muuta.',
		'UserMustChangePassNextLogon' => 'Kasutaja peab muutuma parooli domeeni %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Kasutaja konto',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Salasõna kunagi lõpeb',
		'LdapUacf_ACCOUNTDISABLE' => 'Konto on blokeeritud domeeni %s',
		'LDAPInformationsForThisContact' => 'Teavet LDAP andmebaasi selle kontakti',
		'LDAPInformationsForThisUser' => 'Teavet LDAP andmebaasi selle kasutaja',
		'LDAPInformationsForThisGroup' => 'Teavet LDAP andmebaasi selle grupi',
		'LDAPInformationsForThisMember' => 'Teavet LDAP andmebaasi selle liikme',
		'LDAPAttribute' => 'LDAP atribuut',
		'LDAPAttributes' => 'LDAP atribuudid',
		'LDAPCard' => 'LDAP kaart',
		'LDAPRecordNotFound' => 'Salvestada ei leitud LDAP andmebaasi',
		'LDAPUsers' => 'Kasutajad LDAP andmebaasi',
		'LDAPGroups' => 'Grupid in LDAP andmebaas',
		'LDAPFieldStatus' => 'Staatus',
		'LDAPFieldFirstSubscriptionDate' => 'Esiteks tellimuse kuupäev',
		'LDAPFieldFirstSubscriptionAmount' => 'Esimene liitumisleping summa',
		'LDAPFieldLastSubscriptionDate' => 'Last tellimise kuupäev',
		'LDAPFieldLastSubscriptionAmount' => 'Last märkimissummast',
		'SynchronizeDolibarr2Ldap' => 'Sünkroonida kasutaja (Dolibarr -> LDAP)',
		'UserSynchronized' => 'Kasutaja sünkroniseeritud',
		'GroupSynchronized' => 'Group sünkroniseeritud',
		'MemberSynchronized' => 'Liikmesriigid sünkroniseeritud',
		'ContactSynchronized' => 'Võta sünkroniseeritud',
		'ForceSynchronize' => 'Force sünkroonimine Dolibarr -> LDAP',
		'ErrorFailedToReadLDAP' => 'Lugemine ebaõnnestus LDAP andmebaasi. Vaata LDAP moodul setup ja andmebaasi kättesaadavust.'
);
?>