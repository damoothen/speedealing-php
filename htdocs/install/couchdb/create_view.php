<?php

/* Copyright (C) 2012      Herve Prot           <herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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

/**
 * 	\file       htdocs/comm/serverprocess.php
 * 	\ingroup    commercial societe
 * 	\brief      load data to display
 * 	\version    $Id: serverprocess.php,v 1.6 2012/01/27 16:15:05 synry63 Exp $
 */
require_once("../../main.inc.php");
/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */

// If the user must only see his prospect, force searching by him
if (!$user->admin) {
    accessforbidden();
}

/*basic companies request query */

$couch = new couchClient("http://couch.symeos.com:5984/","dolibarr");

$i=0;

// egoMailing
$obj[$i]->_id="_design/egoMailing";
$obj[$i]->language="javascript";
$obj[$i]->views->list->map='function(doc) {
        if(doc.class && doc.class=="egoMailing")
            emit(doc._id,doc);
        }';
$i++;

// societe
$obj[$i]->_id="_design/societe";
$obj[$i]->language="javascript";
$obj[$i]->views->list->map='function(doc) {
    if(doc.class && doc.class=="societe")
      emit(doc.nom, doc);
    }';
$i++;

try {
    $couch->storeDocs($obj,false);
    } catch (Exception $e) {
        echo "Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
        exit(1);
    }
print "Success";
?>