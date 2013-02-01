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
		'DomainPassword' => 'Wachtwoord voor het domein',
		'YouMustChangePassNextLogon' => 'Wachtwoord voor de gebruiker <b>%s</b> op het domein <b>%s</b> dient te worden gewijzigd.',
		'UserMustChangePassNextLogon' => 'Gebruiker dient het wachtwoord te wijzigen op het domein %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Gebruikersaccount',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Wachtwoord verloopt nooit',
		'LdapUacf_ACCOUNTDISABLE' => 'Account is uitgeschakeld in het domein %s',
		'LDAPInformationsForThisContact' => 'Informatie in LDAP database voor dit contact',
		'LDAPInformationsForThisUser' => 'Informatie in LDAP database voor deze gebruiker',
		'LDAPInformationsForThisGroup' => 'Informatie in LDAP database voor deze groep',
		'LDAPInformationsForThisMember' => 'Informatie in LDAP database voor dit lid',
		'LDAPAttribute' => 'LDAP-attribuut',
		'LDAPAttributes' => 'LDAP-attributen',
		'LDAPCard' => 'LDAP-kaart',
		'LDAPRecordNotFound' => 'Tabelregel niet gevonden in de LDAP database',
		'LDAPUsers' => 'Gebruikers in LDAP database',
		'LDAPGroups' => 'Groepen in de LDAP database',
		'LDAPFieldStatus' => 'Status',
		'LDAPFieldFirstSubscriptionDate' => 'Eerste inschrijvingsdatum',
		'LDAPFieldFirstSubscriptionAmount' => 'Eerste inschrijvingsbedrag',
		'LDAPFieldLastSubscriptionDate' => 'Laatste inschrijvingsdatum',
		'LDAPFieldLastSubscriptionAmount' => 'Laatste inschrijvingsbedrag',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Gebruiker gesynchroniseerd',
		'GroupSynchronized' => 'Groep gesynchroniseerd',
		'MemberSynchronized' => 'Lidmaatschap gesynchroniseerd',
		'ContactSynchronized' => 'Contact gesynchroniseerd',
		'ForceSynchronize' => 'Forceer synchronisatie Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Kon niet lezen uit de LDAP-database. Controleer de instellingen van de LDAP module en database toegankelijkheid.'
);
?>