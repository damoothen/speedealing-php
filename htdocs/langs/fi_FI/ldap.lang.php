<?php
/* Copyright (C) 2012	Regis Houssin	<regis.houssin@capnetworks.com>
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
		'DomainPassword' => 'Salasana verkkotunnuksen',
		'YouMustChangePassNextLogon' => 'Salasana <b>käyttäjän %s verkkotunnuksen %s</b> on muuttunut.',
		'UserMustChangePassNextLogon' => 'Käyttäjän on muutettava salasana verkkotunnuksen %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'User account',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Salasana ei vanhene',
		'LdapUacf_ACCOUNTDISABLE' => 'Tili on poistettu käytöstä verkkotunnuksen %s',
		'LDAPInformationsForThisContact' => 'Tiedot LDAP-tietokannan tämä yhteystieto',
		'LDAPInformationsForThisUser' => 'Tiedot LDAP-tietokannan tämän käyttäjän',
		'LDAPInformationsForThisGroup' => 'Tiedot LDAP-tietokannan tähän ryhmään',
		'LDAPInformationsForThisMember' => 'Tiedot LDAP-tietokannan tämä jäsen',
		'LDAPAttribute' => 'LDAP-attribuutti',
		'LDAPAttributes' => 'LDAP-attribuutteja',
		'LDAPCard' => 'LDAP-kortti',
		'LDAPRecordNotFound' => 'Kirjataan ei löydy LDAP-tietokanta',
		'LDAPUsers' => 'Käyttäjät LDAP-tietokanta',
		'LDAPGroups' => 'Ryhmät LDAP-tietokanta',
		'LDAPFieldStatus' => 'Tila',
		'LDAPFieldFirstSubscriptionDate' => 'Ensimmäisen tilauksen päivämäärä',
		'LDAPFieldFirstSubscriptionAmount' => 'Fist merkinnän määrästä',
		'LDAPFieldLastSubscriptionDate' => 'Viimeisin tilaus päivämäärän',
		'LDAPFieldLastSubscriptionAmount' => 'Viimeisin tilaus määrä',
		'SynchronizeDolibarr2Ldap' => 'Synkronoi käyttäjä (Dolibarr -> LDAP)',
		'UserSynchronized' => 'Käyttäjän synkronoidaan',
		'GroupSynchronized' => 'Ryhmän synkronoidaan',
		'MemberSynchronized' => 'Jäsen synkronoidaan',
		'ContactSynchronized' => 'Yhteystiedot synkronoidaan',
		'ForceSynchronize' => 'Force synkronointi Dolibarr -> LDAP',
		'ErrorFailedToReadLDAP' => 'Lukeminen epäonnistui LDAP-tietokantaan. Tarkista LDAP-moduulin asennus ja tietokannan saavutettavuus.'
);
?>