<?php
/* Copyright (C) 2012	Regis Houssin	<regis@dolibarr.fr>
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

$sendings = array(
		'CHARSET' => 'UTF-8',
		'RefSending' => 'Ref. αποστολή',
		'Sending' => 'Αποστολή',
		'Sendings' => 'Αποστολές',
		'Shipments' => 'Αποστολές',
		'Receivings' => 'Receivings',
		'SendingsArea' => 'Αποστολές περιοχή',
		'ListOfSendings' => 'Κατάλογος των αποστολών',
		'SendingMethod' => 'Μέθοδο αποστολής',
		'SendingReceipt' => 'Παραλαβή Ναυτιλίας',
		'LastSendings' => 'Τελευταία %s αποστολές',
		'SearchASending' => 'Αναζήτηση για την αποστολή',
		'StatisticsOfSendings' => 'Στατιστικά στοιχεία για τις αποστολές',
		'NbOfSendings' => 'Αριθμός των αποστολών',
		'SendingCard' => 'Αποστολές καρτών',
		'NewSending' => 'Νέα αποστολή',
		'CreateASending' => 'Δημιουργία μιας αποστολής',
		'CreateSending' => 'Δημιουργία αποστολή',
		'QtyOrdered' => 'Ποσότητα διέταξε',
		'QtyShipped' => 'Ποσότητα που αποστέλλονται',
		'QtyToShip' => 'Ποσότητα σε πλοίο',
		'QtyReceived' => 'Ποσότητα λάβει',
		'KeepToShip' => 'Κρατήστε στο πλοίο',
		'OtherSendingsForSameOrder' => 'Άλλες αποστολές για αυτό το σκοπό',
		'DateSending' => 'Ημερομηνία αποστολή της παραγγελίας',
		'DateSendingShort' => 'Ημερομηνία αποστολή της παραγγελίας',
		'SendingsForSameOrder' => 'Οι αποστολές για αυτό το σκοπό',
		'SendingsAndReceivingForSameOrder' => 'Αποστολές και receivings για αυτό το σκοπό',
		'SendingsToValidate' => 'Αποστολές για την επικύρωση',
		'StatusSendingCanceled' => 'Ακυρώθηκε',
		'StatusSendingDraft' => 'Σχέδιο',
		'StatusSendingValidated' => 'Επικυρωμένη (προϊόντα με πλοίο ή που έχουν ήδη αποσταλεί)',
		'StatusSendingProcessed' => 'Processed',
		'StatusSendingCanceledShort' => 'Ακυρώθηκε',
		'StatusSendingDraftShort' => 'Σχέδιο',
		'StatusSendingValidatedShort' => 'Επικυρωμένη',
		'StatusSendingProcessedShort' => 'Processed',
		'SendingSheet' => 'Αποστολή φύλλο',
		'Carriers' => 'Carriers',
		'Carrier' => 'Μεταφορέας',
		'CarriersArea' => 'Carriers περιοχή',
		'NewCarrier' => 'Νέα μεταφορέα',
		'ConfirmDeleteSending' => 'Είστε σίγουροι ότι θέλετε να διαγράψετε αυτήν την αποστολή;',
		'ConfirmValidateSending' => 'Είστε σίγουροι ότι θέλετε να επικυρώσει αυτήν την αποστολή με <b>%s</b> αναφοράς;',
		'ConfirmCancelSending' => 'Είστε σίγουροι ότι θέλετε να ακυρώσετε αυτήν την αποστολή;',
		'GenericTransport' => 'Οι γενικές μεταφορές',
		'Enlevement' => 'Πάρει από τον πελάτη',
		'DocumentModelSimple' => 'Απλό μοντέλο έγγραφο',
		'DocumentModelMerou' => 'Mérou A5 μοντέλο',
		'WarningNoQtyLeftToSend' => 'Προσοχή, μην τα προϊόντα που περιμένουν να αποσταλεί.',
		'StatsOnShipmentsOnlyValidated' => 'Στατιστικά στοιχεία για τις μεταφορές που πραγματοποιούνται μόνο επικυρωμένες',
		'DateDeliveryPlanned' => 'Πλανισμένη ημερομηνία παράδοσης',
		'DateReceived' => 'Παράδοσης Ημερομηνία παραλαβής',
		'SendShippingByEMail' => 'Αποστολή ναυτιλία με Email',
		'SendShippingRef' => 'Αποστολή %s ναυτιλία',
		'ActionsOnShipping' => 'Acions στη ναυτιλία',
		'LinkToTrackYourPackage' => 'Link για να παρακολουθείτε το πακέτο σας',
		'ShipmentCreationIsDoneFromOrder' => 'Προς το παρόν, η δημιουργία ενός νέου αποστολή γίνεται από την κάρτα τάξη.',
		'RelatedShippings' => 'Related shippings',
		// Sending methods
		'SendingMethodCATCH' => 'Πιάσε από τον πελάτη',
		'SendingMethodTRANS' => 'Μεταφορέας',
		'SendingMethodCOLSUI' => 'Colissimo',
		// ModelDocument
		'DocumentModelSirocco' => 'Απλό μοντέλο έγγραφο για αποδεικτικά παράδοσης',
		'DocumentModelTyphon' => 'Πληρέστερη πρότυπο έγγραφο για αποδεικτικά παράδοσης (logo. ..)',
		'Error_EXPEDITION_ADDON_NUMBER_NotDefined' => 'Σταθερή EXPEDITION_ADDON_NUMBER δεν ορίζεται'
);
?>