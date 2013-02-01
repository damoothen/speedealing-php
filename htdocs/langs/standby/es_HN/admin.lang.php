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