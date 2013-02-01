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
		'WorkflowSetup' => 'Nastavitev modula poteka dela',
		'WorkflowDesc' => 'Ta modul je namenjen spreminjanju načina delovanja avtomatskih aktivnosti v aplikaciji. Privzeto je potek dela odprt (postavke lahko izvajate v poljubnem vrstnem redu). Za aktivnosti, ki vas zanimajo, lahko vklopite avtomatske aktivnosti.',
		'ThereIsNoWorkflowToModify' => 'Za aktiviran modul ni na voljo poteka dela, ki bi ga lahko spreminjali',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Po podpisu komercialne ponudbe avtomatsko ustvari naročilo kupca',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Po podpisu komercialne ponudbe avtomatsko ustvari račun za kupca',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Po potrditvi pogodbe avtomatsko ustvari račun za kupca',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Po zaključku naročila kupca avtomatsko ustvari račun za kupca',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid'
);
?>