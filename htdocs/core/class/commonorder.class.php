<?php
/* Copyright (C) 2012 Regis Houssin  <regis@dolibarr.fr>
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

/**
 *       \file       htdocs/core/class/commonorder.class.php
 *       \ingroup    core
 *       \brief      File of the superclass of orders classes (customer and supplier)
 */

require_once DOL_DOCUMENT_ROOT .'/core/class/commonobject.class.php';

/**
 *		\class 		CommonOrder
 *       \brief 		Superclass for orders classes
 */
abstract class CommonOrder extends CommonObject
{

}

?>
