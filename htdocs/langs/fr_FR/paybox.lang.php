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

$paybox = array(
		'CHARSET' => 'UTF-8',
		'PayBoxSetup' => 'Configuration module PayBox',
		'PayBoxDesc' => 'Ce module permet d\'offrir une page de paiement via le prestataire <a href="http://www.paybox.com" target="_blank">Paybox</a> pour réaliser un paiement quelconque ou un paiement par rapport à un objet Speedealing (factures, commande...)',
		'FollowingUrlAreAvailableToMakePayments' => 'Les URL suivantes sont disponibles pour permettre à un client de faire un paiement',
		'PaymentForm' => 'Formulaire de paiement',
		'WelcomeOnPaymentPage' => 'Bienvenue sur notre service de paiement en ligne',
		'ThisScreenAllowsYouToPay' => 'Cet écran vous permet de réaliser votre paiement en ligne à destination de %s.',
		'ThisIsInformationOnPayment' => 'Voici les informations sur le paiement à réaliser',
		'ToComplete' => 'A compléter',
		'YourEMail' => 'Email de confirmation du paiement',
		'Creditor' => 'Bénéficiaire',
		'PaymentCode' => 'Code de paiement',
		'PayBoxDoPayment' => 'Poursuivre le paiement par carte',
		'YouWillBeRedirectedOnPayBox' => 'Vous serez redirigé vers la page sécurisée Paybox de saisie de votre carte bancaire',
		'PleaseBePatient' => 'Merci de patientez quelques secondes',
		'Continue' => 'Continuer',
		'ToOfferALinkForOnlinePayment' => 'URL de paiement %s',
		'ToOfferALinkForOnlinePaymentOnOrder' => 'URL offrant une interface de paiement en ligne %s sur la base du montant d\'une commande client',
		'ToOfferALinkForOnlinePaymentOnInvoice' => 'URL offrant une interface de paiement en ligne %s sur la base du montant d\'une facture client',
		'ToOfferALinkForOnlinePaymentOnContractLine' => 'URL offrant une interface de paiement en ligne %s sur la base du montant d\'une ligne de contrat',
		'ToOfferALinkForOnlinePaymentOnFreeAmount' => 'URL offrant une interface de paiement en ligne %s pour un montant libre',
		'ToOfferALinkForOnlinePaymentOnMemberSubscription' => 'URL offrant une interface de paiement en ligne %s sur la base d\'une cotisation d\'adhérent',
		'YouCanAddTagOnUrl' => 'Vous pouvez de plus ajouter le paramètre url <b>&tag=<i>value</i></b> à n\'importe quelles de ces URL (obligatoire pour le paiement libre uniquement) pour ajouter votre propre "code commentaire" du paiement.',
		'SetupPayBoxToHavePaymentCreatedAutomatically' => 'Configurez votre url PayBox à <b>%s</b> pour avoir le paiement créé automatiquement si validé.',
		'YourPaymentHasBeenRecorded' => 'Cette page confirme que votre paiement a bien été enregistré. Merci.',
		'YourPaymentHasNotBeenRecorded' => 'Votre paiement n\'a pas été enregistré et la transaction a été annulée.',
		'AccountParameter' => 'Paramètres du compte',
		'UsageParameter' => 'Paramètres d\'utilisation',
		'InformationToFindParameters' => 'Informations pour trouver vos paramètres de compte %s',
		'PAYBOX_CGI_URL_V2' => 'Url du module CGI Paybox de paiement',
		'VendorName' => 'Nom du vendeur',
		'CSSUrlForPaymentForm' => 'Url feuille style css pour le formulaire de paiement',
		'MessageOK' => 'Message sur page de retour de paiement validé',
		'MessageKO' => 'Message sur page de retour de paiement annulé'
);
?>