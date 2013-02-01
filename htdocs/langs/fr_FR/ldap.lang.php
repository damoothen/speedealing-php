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
		'DomainPassword' => 'Mot de passe du domaine',
		'YouMustChangePassNextLogon' => 'Le mot de passe de <b>%s</b> sur le domaine <b>%s</b> doit être modifié.',
		'UserMustChangePassNextLogon' => 'L\'utilisateur doit modifier son mot de passe sur le domaine %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Compte utilisateur',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Le mot de passe n\'expire jamais',
		'LdapUacf_ACCOUNTDISABLE' => 'Le compte est désactivé sur le domaine %s',
		'LDAPInformationsForThisContact' => 'Informations en base LDAP pour ce contact',
		'LDAPInformationsForThisUser' => 'Informations en base LDAP pour cet utilisateur',
		'LDAPInformationsForThisGroup' => 'Informations en base LDAP pour ce groupe',
		'LDAPInformationsForThisMember' => 'Informations en base LDAP pour ce membre',
		'LDAPAttribute' => 'Attribut LDAP',
		'LDAPAttributes' => 'Attributs LDAP',
		'LDAPCard' => 'Fiche LDAP',
		'LDAPRecordNotFound' => 'Enregistrement non trouvé dans la base LDAP',
		'LDAPUsers' => 'Utilisateurs en base LDAP',
		'LDAPGroups' => 'Groupes en base LDAP',
		'LDAPFieldStatus' => 'Statut',
		'LDAPFieldFirstSubscriptionDate' => 'Date première adhésion',
		'LDAPFieldFirstSubscriptionAmount' => 'Montant première adhésion',
		'LDAPFieldLastSubscriptionDate' => 'Date dernière adhésion',
		'LDAPFieldLastSubscriptionAmount' => 'Montant dernière adhésion',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Utilisateur synchronisé',
		'GroupSynchronized' => 'Groupe synchronisé',
		'MemberSynchronized' => 'Adhérent synchronisé',
		'ContactSynchronized' => 'Contact synchronisé',
		'ForceSynchronize' => 'Forcer synchro Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Échec de la lecture de l\'annuaire LDAP. Vérifier la configuration du module LDAP et l\'accessibilité de l\'annuaire.'
);
?>