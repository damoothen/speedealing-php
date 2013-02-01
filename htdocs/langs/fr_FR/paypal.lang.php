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

$paypal = array(
		'CHARSET' => 'UTF-8',
		'PaypalSetup' => 'Configuration module PayPal',
		'PaypalDesc' => 'Ce module permet d\'offrir une page de paiement via le prestataire <a href="http://www.paypal.com" target="_blank">Paypal</a> pour réaliser un paiement quelconque ou un paiement par rapport à un objet Speedealing (factures, commande...)',
		'PaypalOrCBDoPayment' => 'Poursuivre le paiement par carte ou par Paypal',
		'PaypalDoPayment' => 'Poursuivre le paiement par Paypal',
		'PaypalCBDoPayment' => 'Poursuivre le paiement par carte',
		'PAYPAL_API_SANDBOX' => 'Mode test/bac à sable (sandbox)',
		'PAYPAL_API_USER' => 'Nom utilisateur API',
		'PAYPAL_API_PASSWORD' => 'Mot de passe utilisateur API',
		'PAYPAL_API_SIGNATURE' => 'Signature API',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Proposer le paiement intégral (Carte+Paypal) ou Paypal seul',
		'PAYPAL_CSS_URL' => 'Url optionnelle de la feuille de style CSS de la page de paiement',
		'ThisIsTransactionId' => 'Voici l\'identifiant de la transaction: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Ajouter l\'url de paiement Paypal lors de l\'envoi d\'un document par mail',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'Adresse e-mail pour les notifications instantanées de paiement (IPN)',
		'PredefinedMailContentLink' => 'Vous pouvez cliquer sur le lien sécurisé ci-dessous pour effectuer votre paiement via Paypal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => 'Vous êtes actuellement dans le mode "sandbox"'
);
?>