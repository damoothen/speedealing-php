<?php
/* Copyright (C) 2010-2011 Patrick Mary           <laube@hotmail.fr>
 
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
 *	    \file       htdocs/lib/datatables/js/initDatatables.js
 *      
 *		\brief      Page to int lib datatable
 *		\version    $Id: initDatatables.js,v 1.5 2011/12/14 20:54:12 synry63 Exp $
 */
$path = "../core/datatables/langs/".$langs->defaultlang.".txt";
$result = file($path);
print'<script type="text/javascript" charset="utf-8">';
print '$(document).ready(function() {
    /* Get the lang */
    var lang="en_US"; 
    ';
if($result!=false)
    print'lang = "'.$langs->defaultlang.'"';
 
    /* Insert a \'details\' column to the table */
    print'
                  oTable = $(\'#liste\').dataTable( {
                
                "iDisplayLength": '.$conf->global->MAIN_SIZE_LISTE_LIMIT.',
                "aLengthMenu": [[10,25, 50, 100,1000, -1], [10,25, 50, 100,1000,"All"]],
                "bProcessing": true,
                "sAjaxSource": "serverprocess.php?type='.$type.'&pstcomm='.$pstcomm.'&search_sale='.$search_sale.'",
                "aoColumns": [
                    { "mDataProp": "nom", "bUseRendered": false, "bSearchable": true,
                        "fnRender": function(obj) {
                        var ar = [];
                        ar[ar.length] = "<a href=\"'.DOL_URL_ROOT.'\/comm\/prospect\/fiche.php?socid=";
                        ar[ar.length] = obj.aData.rowid;
                        ar[ar.length] = "\"><img src=\"'.DOL_URL_ROOT.'\/theme\/'.$conf->theme.'\/img\/object_company.png\" border=\"0\" alt=\"Afficher soci&eacute;t&eacute;:";
                        ar[ar.length] = obj.aData.nom.toString();
                        ar[ar.length] = "\" title=\"Afficher soci&eacute;t&eacute;:";
                        ar[ar.length] = obj.aData.nom.toString();
                        ar[ar.length] = "\"><\/a> <a href=\"'.DOL_URL_ROOT.'\/comm\/prospect\/fiche.php?socid=";
                        ar[ar.length] = obj.aData.rowid;
                        ar[ar.length] = "\">";
                        ar[ar.length] = obj.aData.nom.toString();
                        ar[ar.length] = "<\/a>";
                        var str = ar.join("");
                        return str;
                        }
                    },
                    { "mDataProp": "ville" },
                    '.(empty($conf->global->SOCIETE_DISABLE_STATE)?'{ "mDataProp": "departement" },':'').'
                    { "mDataProp": "cp" },
                    '.($conf->categorie->enabled?'{ "mDataProp": "category" },':'').'
                    {"mDataProp": "commerciaux", "bUseRendered": false, "bSearchable": false,
                        "fnRender": function(obj) {
                                var str = obj.aData.commerciaux;
                            return str;
                            }
                    },
                    { "mDataProp": "siren" },
                    { "mDataProp": "ape" },
                    { "mDataProp": "fk_prospectlevel" },
                    { "mDataProp": "fk_stcomm", "bUseRendered": true, "bSearchable": false,
                        "fnRender": function(obj) {
                                var str = obj.aData.fk_stcomm;
                                str = "Jamais contacté";
                            return str;
                            }
                    },
                ],
                "bDeferRender": true,
                "oLanguage": {
                    "sUrl": "../core/datatables/langs/"+lang+".txt"
                        
                },
                "sDom": \'<"top"Tflpi<"clear">>rt<"bottom"pi<"clear">>\',
                '.($user->rights->societe->contact->export?'
                "oTableTools": {
                    "sSwfPath": "../core/datatables/swf/copy_cvs_xls_pdf.swf",
                    "aButtons": [
                    "xls"
                    ]
                }
                ':"").'
            });
});
';
print'</script>';            

?>
