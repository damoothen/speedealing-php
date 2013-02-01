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
		'DomainPassword' => 'Password del dominio',
		'YouMustChangePassNextLogon' => 'La password per l\'utente <b>%s</ b> sul dominio <b>%s</ b> deve essere cambiata.',
		'UserMustChangePassNextLogon' => 'L\'utente deve cambiare password per il dominio %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Account utente',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Nessuna scadenza password',
		'LdapUacf_ACCOUNTDISABLE' => 'Account disabilitato nel dominio %s',
		'LDAPInformationsForThisContact' => 'Informazioni nel database LDAP per questo contatto',
		'LDAPInformationsForThisUser' => 'Informazioni nel database LDAP per questo utente',
		'LDAPInformationsForThisGroup' => 'Informazioni nel database LDAP per questo gruppo',
		'LDAPInformationsForThisMember' => 'Informazioni nel database LDAP per questo membro',
		'LDAPAttribute' => 'Attributo LDAP',
		'LDAPAttributes' => 'Attributi LDAP',
		'LDAPCard' => 'Scheda LDAP',
		'LDAPRecordNotFound' => 'Il record non è stato trovato in LDAP',
		'LDAPUsers' => 'Utenti nel database LDAP',
		'LDAPGroups' => 'Gruppi in LDAP',
		'LDAPFieldStatus' => 'Stato',
		'LDAPFieldFirstSubscriptionDate' => 'Prima data di sottoscrizione',
		'LDAPFieldFirstSubscriptionAmount' => 'Importo della prima sottoscrizione',
		'LDAPFieldLastSubscriptionDate' => 'Data ultima sottoscrizione',
		'LDAPFieldLastSubscriptionAmount' => 'Importo ultima sottoscrizione',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Utente sincronizzato',
		'GroupSynchronized' => 'Gruppo sincronizzato',
		'MemberSynchronized' => 'Membro sincronizzato',
		'ContactSynchronized' => 'Contatto sincronizzato',
		'ForceSynchronize' => 'Forza la sincronizzazione Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Impossibile leggere database LDAP. Controlla la configurazione del modulo di installazione LDAP.'
);
?>