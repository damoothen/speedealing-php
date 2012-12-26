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

$compta = array(
		'CHARSET' => 'UTF-8',
		'VATToPay' => 'IVU ventas',
		'VATReceived' => 'IVU repercutido',
		'VATToCollect' => 'IVU compras',
		'VATSummary' => 'Balance de IVU',
		'VATPaid' => 'IVU Pagado',
		'VATCollected' => 'IVU recuperado',
		'PaymentVat' => 'Pago IVU',
		'NewVATPayment' => 'Nuevo pago de IVU',
		'VATPayment' => 'Pago IVU',
		'VATPayments' => 'Pagos IVU',
		'SocialContributionsPayments' => 'Pagos cargas sociales',
		'ShowVatPayment' => 'Ver pagos IVU',
		'TotalVATReceived' => 'Total IVU percibido',
		'RulesResultDue' => '- Los importes mostrados son importes totales<br>- Incluye las facturas, cargas e IVU debidos, que estén pagadas o no.<br>- Se basa en la fecha de validación para las facturas y el IVU y en la fecha de vencimiento para las cargas.<br>',
		'RulesResultInOut' => '- Los importes mostrados son importes totales<br>- Incluye los pagos realizados para las facturas, cargas e IVU.<br>- Se basa en la fecha de pago de las mismas.<br>',
		'VATReportByCustomersInInputOutputMode' => 'Informe por cliente del IVU repercutido y pagado (IVU pagado)',
		'VATReportByCustomersInDueDebtMode' => 'Informe por cliente del IVU repercutido y pagado (IVU debido)',
		'VATReportByQuartersInInputOutputMode' => 'Informe por tasa del IVU repercutido y pagado (IVU pagado)',
		'VATReportByQuartersInDueDebtMode' => 'Informe por tasa del IVU repercutido y pagado (IVU debido)',
		'SeeVATReportInInputOutputMode' => 'Ver el informe <b>%sIVU pagado%s</b> para un modo de cálculo estandard',
		'SeeVATReportInDueDebtMode' => 'Ver el informe <b>%sIVU debido%s</b> para un modo de cálculo con la opción sobre lo debido',
		'RulesVATIn' => '- Para los servicios, el informe incluye el IVU de los pagos efectivamente recibidos o emitidos basándose en la fecha del pago.<br>- Para los bienes materiales, incluye el IVU de las facturas basándose en la fecha de la factura.',
		'RulesVATDue' => '- Para los servicios, el informe incluye el IVU de las facturas debidas, pagadas o no basándose en la fecha de estas facturas.<br>- Para los bienes materiales, incluye el IVU de las facturas basándose en la fecha de la factura.',
		'COMPTA_VAT_ACCOUNT' => 'Código contable por defecto para el IVU (si no está definido en el diccionario "Tasas de IVU")',
);
?>