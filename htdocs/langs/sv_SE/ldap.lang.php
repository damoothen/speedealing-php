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
		'DomainPassword' => 'Lösenord för domän',
		'YouMustChangePassNextLogon' => 'Lösenord för användare <b>%s</b> på domänen <b>%s</b> måste ändras.',
		'UserMustChangePassNextLogon' => 'Användaren måste byta lösenord på domänen %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Användarkonto',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Lösenordet upphör aldrig att',
		'LdapUacf_ACCOUNTDISABLE' => 'Kontot är inaktivt i domänen %s',
		'LDAPInformationsForThisContact' => 'Information i LDAP-databas för denna kontakt',
		'LDAPInformationsForThisUser' => 'Information i LDAP-databas för denna användare',
		'LDAPInformationsForThisGroup' => 'Information i LDAP-databas för denna grupp',
		'LDAPInformationsForThisMember' => 'Information i LDAP-databas för denna medlem',
		'LDAPAttribute' => 'LDAP-attribut',
		'LDAPAttributes' => 'LDAP-attribut',
		'LDAPCard' => 'LDAP-kort',
		'LDAPRecordNotFound' => 'Spela som inte finns i LDAP-databas',
		'LDAPUsers' => 'Användare i LDAP-databas',
		'LDAPGroups' => 'Grupper i LDAP-databas',
		'LDAPFieldStatus' => 'Status',
		'LDAPFieldFirstSubscriptionDate' => 'Första teckningsdag',
		'LDAPFieldFirstSubscriptionAmount' => 'Fist teckningsbelopp',
		'LDAPFieldLastSubscriptionDate' => 'Sista teckningsdag',
		'LDAPFieldLastSubscriptionAmount' => 'Senaste teckningsbelopp',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Användare synkroniseras',
		'GroupSynchronized' => 'Grupp synkroniseras',
		'MemberSynchronized' => 'Medlem synkroniseras',
		'ContactSynchronized' => 'Kontakta synkroniseras',
		'ForceSynchronize' => 'Force synkronisera Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Misslyckades med att läsa LDAP-databas. Kontrollera LDAP-modul setup och databas tillgänglighet.'
);
?>