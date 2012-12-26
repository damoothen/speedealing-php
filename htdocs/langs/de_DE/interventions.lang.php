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

$interventions = array(
		'CHARSET' => 'UTF-8',
		'Intervention' => 'Eingriff',
		'Interventions' => 'Eingriffe',
		'InterventionCard' => 'Eingriffskarte',
		'NewIntervention' => 'Neuer Eingriff',
		'AddIntervention' => 'Eingriffsstelle hinzufügen',
		'ListOfInterventions' => 'Liste der Eingriffe',
		'EditIntervention' => 'Eingriff bearbeiten',
		'ActionsOnFicheInter' => 'Aktionen zum Eingriff',
		'LastInterventions' => 'Letzte %s Eingriffe',
		'AllInterventions' => 'Alle Eingriffe',
		'CreateDraftIntervention' => 'Eingriffsentwurf',
		'CustomerDoesNotHavePrefix' => 'Kunde hat kein Präfix',
		'InterventionContact' => 'Kontakt für Eingriffe',
		'DeleteIntervention' => 'Eingriff löschen',
		'ValidateIntervention' => 'Eingriff freigeben',
		'ModifyIntervention' => 'Geänderte Eingriff',
		'DeleteInterventionLine' => 'Eingriffszeile löschen',
		'ConfirmDeleteIntervention' => 'Möchten Sie diesen Eingriff wirklich löschen?',
		'ConfirmValidateIntervention' => 'Möchten Sie diesen Eingriff wirklich freigeben?',
		'ConfirmModifyIntervention' => 'Sind Sie sicher, dass Sie ändern möchten diese Intervention?',
		'ConfirmDeleteInterventionLine' => 'Möchten Sie diese Eingriffszeile wirklich löschen?',
		'NameAndSignatureOfInternalContact' => 'Name und Unterschrift des internen Kontakts:',
		'NameAndSignatureOfExternalContact' => 'Name und Unterschrift des Kunden:',
		'DocumentModelStandard' => 'Standard-Dokumentvorlage für Eingriffe',
		'ClassifyBilled' => 'Eingegordnet "Angekündigt"',
		'StatusInterInvoiced' => 'Angekündigt',
		'RelatedInterventions' => 'Verbundene Eingriffe',
		'ShowIntervention' => 'Zeige Eingriffe',
		////////// Types de contacts //////////
		'TypeContact_fichinter_internal_INTERREPFOLL' => 'Eingriffsnachverfolgung durch Vertreter',
		'TypeContact_fichinter_internal_INTERVENING' => 'Eingriff läuft',
		'TypeContact_fichinter_external_BILLING' => 'Rechnungskontakt Kunde',
		'TypeContact_fichinter_external_CUSTOMER' => 'Kundenkontakt-Nachverfolgung',
		// Modele numérotation
		'ArcticNumRefModelDesc1' => 'Generisches Nummernmodell',
		'ArcticNumRefModelError' => 'Fehler beim aktivieren',
		'PacificNumRefModelDesc1' => 'Liefere Nummer im Format %syymm-nnnn zurück, wobei yy das Jahr, mm das Monat und nnnn eine Zahlensequenz ohne Nullwert oder Leerzeichen ist',
		'PacificNumRefModelError' => 'Eine Interventionskarte beginnend mit $syymm existiert bereits und ist nicht mir dieser Numerierungssequenz kompatibel. Bitte löschen oder umbenennen.',
);
?>