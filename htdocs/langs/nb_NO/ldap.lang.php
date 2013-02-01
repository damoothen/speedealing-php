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
		'DomainPassword' => 'Passord til domene',
		'YouMustChangePassNextLogon' => 'Passordet for bruker <b>%s</b> i domenet <b>%s</b> må endres.',
		'UserMustChangePassNextLogon' => 'Brukeren må endre passord i domenet %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Brukerkonto',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Passordet utløper aldri',
		'LdapUacf_ACCOUNTDISABLE' => 'Kontoen er deaktivert i domenet %s',
		'LDAPInformationsForThisContact' => 'Informasjon i LDAP-databasen for denne kontakten',
		'LDAPInformationsForThisUser' => 'Informasjon i LDAP-databasen for denne brukeren',
		'LDAPInformationsForThisGroup' => 'Informasjon i LDAP-databasen for denne gruppen',
		'LDAPInformationsForThisMember' => 'Informasjon i LDAP-databasen for dette medlemmet',
		'LDAPAttribute' => 'LDAP-attributt',
		'LDAPAttributes' => 'LDAP-attributter',
		'LDAPCard' => 'LDAP-kort',
		'LDAPRecordNotFound' => 'Fant ikke posten i LDAP-databasen',
		'LDAPUsers' => 'Brukere i LDAP-databasen',
		'LDAPGroups' => 'Grupper i LDAP-databasen',
		'LDAPFieldStatus' => 'Status',
		'LDAPFieldFirstSubscriptionDate' => 'Første abonnementsdato',
		'LDAPFieldFirstSubscriptionAmount' => 'Første abonnementsbeløp',
		'LDAPFieldLastSubscriptionDate' => 'Siste abonnementsdato',
		'LDAPFieldLastSubscriptionAmount' => 'Siste abonnementsbeløp',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Brukeren er synkronisert',
		'GroupSynchronized' => 'Gruppe synkronisert',
		'MemberSynchronized' => 'Medlem synkronisert',
		'ContactSynchronized' => 'Kontakt synkronisert',
		'ForceSynchronize' => 'Tving synkronisering Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Kunne ikke lese LDAP-databasen. Sjekk LDAP-modulens innstillinger og databasetilgjengelighet.'
);
?>