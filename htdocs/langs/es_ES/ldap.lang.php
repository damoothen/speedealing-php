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
		'DomainPassword' => 'Contraseña del dominio',
		'YouMustChangePassNextLogon' => 'La contraseña de <b>%s</b> en el dominio <b>%s</b> debe de ser modificada.',
		'UserMustChangePassNextLogon' => 'El usuario debe cambiar de contraseña en la próxima conexión',
		'LdapUacf_NORMAL_ACCOUNT' => 'Cuenta usuario',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'La contraseña no caduca',
		'LdapUacf_ACCOUNTDISABLE' => 'La cuenta está desactivada en el dominio',
		'LDAPInformationsForThisContact' => 'Información de la base de datos LDAP de este contacto',
		'LDAPInformationsForThisUser' => 'Información de la base de datos LDAP de este usuario',
		'LDAPInformationsForThisGroup' => 'Información de la base de datos LDAP de este grupo',
		'LDAPInformationsForThisMember' => 'Información de la base de datos LDAP de este miembro',
		'LDAPAttribute' => 'Atributo LDAP',
		'LDAPAttributes' => 'Atributos LDAP',
		'LDAPCard' => 'Ficha LDAP',
		'LDAPRecordNotFound' => 'Registro no encontrado en la base de datos LDAP',
		'LDAPUsers' => 'Usuarios en la base de datos LDAP',
		'LDAPGroups' => 'Grupos en la base de datos LDAP',
		'LDAPFieldStatus' => 'Estatuto',
		'LDAPFieldFirstSubscriptionDate' => 'Fecha primera adhesión',
		'LDAPFieldFirstSubscriptionAmount' => 'Importe primera adhesión',
		'LDAPFieldLastSubscriptionDate' => 'Fecha última adhesión',
		'LDAPFieldLastSubscriptionAmount' => 'Importe última adhesión',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Usuario sincronizado',
		'GroupSynchronized' => 'Grupo sincronizado',
		'MemberSynchronized' => 'Miembro sincronizado',
		'ContactSynchronized' => 'Contacto sincronizado',
		'ForceSynchronize' => 'Forzar sincronización Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Error de la lectura del directorio LDAP. Comprobar la configuración del módulo LDAP y la accesibilidad del anuario.'
);
?>