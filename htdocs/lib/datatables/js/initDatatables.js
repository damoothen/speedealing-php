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
 *	    \file       htdocs/contact/js/initDatatables.js
 *      \ingroup    societe
 *		\brief      Page to list all contacts
 *		\version    $Id: index.php,v 1.106 2011/12/14 23:54:12 synry63 Exp $
 */	 

$(document).ready(function() {
                        
    $('#liste').dataTable( {
        "sDom": 'T<"clear">lfrtip',
        "bPaginate": false,
        "oLanguage": { "sUrl": "../lib/datatables/langs/datatable_fr.txt" },
        "oTableTools": {
            "sSwfPath": "../lib/datatables/swf/copy_cvs_xls_pdf.swf",
            "aButtons": [
            "xls"	
            ]
        }
       
    });
    
});    
                              
// color for hide/display
$("a.visibility").toggle(
    function()
    {
        $(this).css("color", "gray");
    },
    function()
    {
        $(this).css("color", "blue");
    }
    );
//show/hide by column num        
function fnShowHide( iCol )
{
    // Get the DataTables object again - this is not a recreation, just a get of the object 
    var oTable = $('#liste').dataTable();
    var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
    oTable.fnSetColumnVis( iCol, bVis ? false : true );
}




