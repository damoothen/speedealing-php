<?php
/* Copyright (C) 2012	Regis Houssin	<regis.houssin@capnetworks.com>
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

$admin = array(
		'CHARSET' => 'UTF-8',
		'Permission91' => 'Consultar impuestos e ISV',
		'Permission92' => 'Crear/modificar impuestos e ISV',
		'Permission93' => 'Eliminar impuestos e ISV',
		'DictionnaryVAT' => 'Tasa de ISV (Impuesto sobre ventas en EEUU)',
		'VATManagement' => 'Gestión ISV',
		'VATIsUsedDesc' => 'El tipo de ISV propuesto por defecto en las creaciones de presupuestos, facturas, pedidos, etc. Responde a la siguiente regla:<br>Si el vendedor no está sujeto a ISV, ISV por defecto=0. Final de regla.<br>Si el país del vendedor= país del comprador entonces ISV por defecto=ISV del producto vendido. Final de regla.<br>Si vendedor y comprador residen en la Comunidad Europea y el bien vendido= nuevo medio de transportes (auto, barco, avión), ISV por defecto=0 (el ISV debe ser pagado por comprador a la hacienda pública de su país y no al vendedor). Final de regla<br>Si vendedor y comprador residen en la Comunidad Europea y comprador= particular o empresa sin NIF intracomunitario entonces ISV por defecto=ISV del producto vendido. Final de regla.<br>Si vendedor y comprador residen en la Comunidad Europea y comprador= empresa con NIF intracomunitario entonces ISV por defecto=0. Final de regla.<br>Si no, ISV propuesto por defecto=0. Final de regla.<br>',
		'VATIsNotUsedDesc' => 'El tipo de ISV propuesto por defecto es 0. Este es el caso de asociaciones, particulares o algunas pequeñas sociedades.',
		'UnitPriceOfProduct' => 'Precio unitario sin ISV de un producto',
		'OptionVatMode' => 'Opción de carga de ISV',
		'OptionVatDefaultDesc' => 'La carga del ISV es: <br>-en el envío de los bienes (en la práctica se usa la fecha de la factura)<br>-sobre el pago por los servicios',
		'OptionVatDebitOptionDesc' => 'La carga del ISV es: <br>-en el envío de los bienes (en la práctica se usa la fecha de la factura)<br>-sobre la facturación de los servicios',
);
?>