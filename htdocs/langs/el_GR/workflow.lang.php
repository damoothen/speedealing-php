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

$workflow = array(
		'CHARSET' => 'UTF-8',
		'WorkflowSetup' => 'Workflow εγκατάσταση μονάδας',
		'WorkflowDesc' => 'Αυτή η ενότητα Σχεδιασμός για την τροποποίηση της συμπεριφοράς των αυτόματων ενεργειών σε εφαρμογή. Από προεπιλογή, η ροή εργασίας έχει ανοίξει (πράγμα που κάνετε στη σειρά που θέλετε). Μπορείτε να ενεργοποιήσει την αυτόματη ενέργειες που σας ενδιαφέρει in.',
		'ThereIsNoWorkflowToModify' => 'Δεν υπάρχει καμία ροή εργασίας, μπορείτε να τροποποιήσετε για την ενότητα που έχετε ενεργοποιήσει.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Δημιουργήστε μια παραγγελία του πελάτη αυτόματα μετά από μια εμπορική πρόταση υπογράφεται',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Δημιουργήστε ένα τιμολόγιο πελάτη αυτόματα μετά από μια εμπορική πρόταση υπογράφεται',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Δημιουργήστε ένα τιμολόγιο πελάτη αυτόματα μετά από μια σύμβαση έχει επικυρωθεί',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Δημιουργήστε ένα τιμολόγιο πελάτη αυτόματα μετά από παραγγελία του πελάτη είναι κλειστό',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid'
);
?>