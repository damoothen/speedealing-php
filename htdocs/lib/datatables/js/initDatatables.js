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
     /* Get the lang :desactived */
     // var tabs = location.search.substring(1).split("&");
     // lang = tabs[1].substr(5,5);
    
    /* Insert a 'details' column to the table */
    var nCloneTh = document.createElement( 'th' );
    var nCloneTd = document.createElement( 'td' );
     
    $('#liste thead tr').each( function () {
        this.insertBefore( nCloneTh, this.childNodes[0] );
    } );
     
    $('#liste tbody tr').each( function () {
        this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
    } );
    
    /* init dataTable */
    oTable = $('#liste').dataTable( {
        "sDom": 'T<"clear">lfrtip',
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "serverprocess.php",
        "bPaginate": true,
       //  "oLanguage": {"sUrl": "../lib/datatables/langs/"+lang+".txt"}, lang desactived
        "oTableTools": {
            "sSwfPath": "../lib/datatables/swf/copy_cvs_xls_pdf.swf",
            "aButtons": [
            "xls"	
            ]
        }
       
    });
    
     /* Add event listener for opening and closing details
     * Note that the indicator for showing which row is open is not controlled by DataTables,
     * rather it is done here
     */
     $('#liste tbody td img.plus').live('click', function () {
                                    var nTr = this.parentNode.parentNode;
                                    var id =  $(this).attr("id");
                                    nTr.setAttribute("id",id);
                                    if ( this.src.match('details_close') )
                                    {
                                        
                                        /* This row is already open - close it */
                                        this.src = "../theme/cameleo/img/details_open.png";
                                        oTable.fnClose( nTr );
                                    }
                                    else
                                    {
                                     /* Open this row */
                                        request(nTr);
                                        this.src = "../theme/cameleo/img/details_close.png";
                                        
                                    }
                } );
    
    
});    





