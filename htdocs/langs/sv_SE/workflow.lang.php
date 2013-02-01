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

$workflow = array(
		'CHARSET' => 'UTF-8',
		'WorkflowSetup' => 'Arbetsflöde modul konfiguration',
		'WorkflowDesc' => 'Denna modul är designade för att ändra beteendet hos automatiska åtgärder i programmet. Som standard är arbetsflödet öppnas (du gör något för att du vill). Du kan aktiveras automatiska åtgärder som du är intressanta i.',
		'ThereIsNoWorkflowToModify' => 'Det finns ingen arbetsflöde du kan ändra för modul du har aktiverat.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Skapa en kundorder automatiskt efter en kommersiell förslag undertecknas',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Skapa en kundfaktura automatiskt efter en kommersiell förslag undertecknas',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Skapa en kundfaktura automatiskt efter ett avtal validerad',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Skapa en kundfaktura automatiskt efter en kundorder är stängd',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid'
);
?>