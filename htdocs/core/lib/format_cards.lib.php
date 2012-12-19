<?php
/* Copyright (C) 2006-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2006      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2007      Patrick Raguin <patrick.raguin@gmail.com>
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
 * or see http://www.gnu.org/
 */

/**
 *	\file       htdocs/core/lib/format_cards.lib.php
 *	\brief      Set of functions used for cards generation
 *	\ingroup    core
 */


global $_Avery_Labels;


$_Avery_Labels = array (
			      '5160'=>array('name'=>'5160 (Letter)',
					    'paper-size'=>'letter',
					    'metric'=>'mm',
					    'marginLeft'=>1.762,
					    'marginTop'=>10.7,
					    'NX'=>3,
					    'NY'=>10,
					    'SpaceX'=>3.175,
					    'SpaceY'=>0,
					    'width'=>66.675,
					    'height'=>25.4,
					    'font-size'=>8),
			      '5161'=>array('name'=>'5161 (Letter)',
					    'paper-size'=>'letter',
					    'metric'=>'mm',
					    'marginLeft'=>0.967,
					    'marginTop'=>10.7,
					    'NX'=>2,
					    'NY'=>10,
					    'SpaceX'=>3.967,
					    'SpaceY'=>0,
					    'width'=>101.6,
					    'height'=>25.4,
					    'font-size'=>8),
			      '5162'=>array('name'=>'5162 (Letter)',
					    'paper-size'=>'letter',
					    'metric'=>'mm',
					    'marginLeft'=>0.97,
					    'marginTop'=>20.224,
					    'NX'=>2,
					    'NY'=>7,
					    'SpaceX'=>4.762,
					    'SpaceY'=>0,
					    'width'=>100.807,
					    'height'=>35.72,
					    'font-size'=>8),
			      '5163'=>array('name'=>'5163 (Letter)',
					    'paper-size'=>'letter',
					    'metric'=>'mm',
					    'marginLeft'=>1.762,
					    'marginTop'=>10.7,
					    'NX'=>2,
					    'NY'=>5,
					    'SpaceX'=>3.175,
					    'SpaceY'=>0,
					    'width'=>101.6,
					    'height'=>50.8,
					    'font-size'=>8),
			      '5164'=>array('name'=>'5164 (Letter)',
					    'paper-size'=>'letter',
					    'metric'=>'in',
					    'marginLeft'=>0.148,
					    'marginTop'=>0.5,
					    'NX'=>2,
					    'NY'=>3,
					    'SpaceX'=>0.2031,
					    'SpaceY'=>0,
					    'width'=>4.0,
					    'height'=>3.33,
					    'font-size'=>12),
			      '8600'=>array('name'=>'8600 (Letter)',
					    'paper-size'=>'letter',
					    'metric'=>'mm',
					    'marginLeft'=>7.1,
					    'marginTop'=>19,
					    'NX'=>3,
					    'NY'=>10,
					    'SpaceX'=>9.5,
					    'SpaceY'=>3.1,
					    'width'=>66.6,
					    'height'=>25.4,
					    'font-size'=>8),
			      'L7163'=>array('name'=>'L7163 (A4)',
					     'paper-size'=>'A4',
					     'metric'=>'mm',
					     'marginLeft'=>5,
					     'marginTop'=>15,
					     'NX'=>2,
					     'NY'=>7,
					     'SpaceX'=>25,
					     'SpaceY'=>0,
					     'width'=>99.1,
					     'height'=>38.1,
					     'font-size'=>10),
			      'AVERYC32010'=>array('name'=>'AVERY-C32010 (A4)',
					     'paper-size'=>'A4',
					     'metric'=>'mm',
					     'marginLeft'=>15,
					     'marginTop'=>13,
					     'NX'=>2,
					     'NY'=>5,
					     'SpaceX'=>10,
					     'SpaceY'=>0,
					     'width'=>85,
					     'height'=>54,
					     'font-size'=>10),
					'CARD'=>array('name'=>'Dolibarr Business cards (A4)',
					    'paper-size'=>'A4',
					    'metric'=>'mm',
					    'marginLeft'=>15,
					    'marginTop'=>15,
					    'NX'=>2,
					    'NY'=>5,
					    'SpaceX'=>0,
					    'SpaceY'=>0,
					    'width'=>85,
					    'height'=>54,
					    'font-size'=>10)
		);


?>