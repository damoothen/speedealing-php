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
		'WorkflowSetup' => '工作流模块的设置',
		'WorkflowDesc' => '此模块desinged修改应用程序的自动操作的行为。默认情况下，工作流被打开（你做的事情在你想要的顺序）。您可以启用自动操作，你是有趣英寸',
		'ThereIsNoWorkflowToModify' => '没有工作流程，您可以修改你已激活的模块。',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => '商业提案签署后自动创建一个客户订单',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => '商业提案签署后自动创建一个客户发票',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => '创建一个客户发票，合同验证后自动',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => '创建一个客户发票，客户订单后自动关闭',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid'
);
?>