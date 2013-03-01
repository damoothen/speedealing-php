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
		'DomainPassword' => 'Password for domain',
		'YouMustChangePassNextLogon' => 'Password for user <b>%s</b> on the domain <b>%s</b> must be changed.',
		'UserMustChangePassNextLogon' => 'User must change password on the domain %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'User account',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Password never expires',
		'LdapUacf_ACCOUNTDISABLE' => 'Account is disabled in the domain %s',
		'LDAPInformationsForThisContact' => 'Information in LDAP database for this contact',
		'LDAPInformationsForThisUser' => 'Information in LDAP database for this user',
		'LDAPInformationsForThisGroup' => 'Information in LDAP database for this group',
		'LDAPInformationsForThisMember' => 'Information in LDAP database for this member',
		'LDAPAttribute' => 'LDAP attribute',
		'LDAPAttributes' => 'LDAP attributes',
		'LDAPCard' => 'LDAP card',
		'LDAPRecordNotFound' => 'Record not found in LDAP database',
		'LDAPUsers' => 'Users in LDAP database',
		'LDAPGroups' => 'Groups in LDAP database',
		'LDAPFieldStatus' => 'Status',
		'LDAPFieldFirstSubscriptionDate' => 'First subscription date',
		'LDAPFieldFirstSubscriptionAmount' => 'First subscription amount',
		'LDAPFieldLastSubscriptionDate' => 'Last subscription date',
		'LDAPFieldLastSubscriptionAmount' => 'Last subscription amount',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'User synchronized',
		'GroupSynchronized' => 'Group synchronized',
		'MemberSynchronized' => 'Member synchronized',
		'ContactSynchronized' => 'Contact synchronized',
		'ForceSynchronize' => 'Force synchronizing Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Failed to read LDAP database. Check LDAP module setup and database accessibility.'
);
?>