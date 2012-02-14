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
print'<script>';
print "$(document).ready(function() {
     /* Get the lang */
      var lang = \"fr_FR\";
      var cate='';
      var exportright='';
      exportright ='".$user->rights->societe->contact->export."';      
      cate = '".$conf->categorie->enabled."';    
      /* Get the type */
      var type='".$_GET['type']."';
    /* Insert a 'details' column to the table */
    var nCloneTh = document.createElement( 'th' );
    var nCloneTd = document.createElement( 'td' );
     
    $('#liste thead tr').each( function () {
        this.insertBefore( nCloneTh, this.childNodes[0] );
    } );
     
    $('#liste tbody tr').each( function () {
        this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
    } );
    if(cate==1){
    
        if(exportright==1){
            /* init dataTable */
            oTable = $('#liste').dataTable( {
                \"aoColumnDefs\": [ 
                        {\"bVisible\": false, \"aTargets\": [ 9 ]},{\"bVisible\": false, \"aTargets\": [ 10 ]}
                        ], 
                \"iDisplayLength\": 10,
                \"aLengthMenu\": [[10,25, 50, 100,1000, -1], [10,25, 50, 100,1000,\"All\"]],
                \"bProcessing\": true,
                \"bServerSide\": true,
                \"sAjaxSource\": \"serverprocess.php?type=\"+type,
                \"bPaginate\": true,
                \"oLanguage\": {\"sUrl\": \"../lib/datatables/langs/\"+lang+\".txt\"

                            },

                \"sDom\": 'T<\"clear\">lfrtip',      
                \"oTableTools\": {
                    \"sSwfPath\": \"../lib/datatables/swf/copy_cvs_xls_pdf.swf\",
                    \"aButtons\": [
                    \"xls\"	
                    ]
                }
            });
            $(\"td#\"+9).css(\"display\", \"none\");
            $(\"td#\"+10).css(\"display\", \"none\");    
        }
        else{
            /* init dataTable */
        oTable = $('#liste').dataTable( {
            \"aoColumnDefs\": [ 
                        {\"bVisible\": false, \"aTargets\": [ 9 ]},{\"bVisible\": false, \"aTargets\": [ 10 ]}
                        ], 
            \"iDisplayLength\": 10,
            \"aLengthMenu\": [[10,25, 50, 100,1000, -1], [10,25, 50, 100,1000,\"All\"]],
            \"bProcessing\": true,
            \"bServerSide\": true,
            \"sAjaxSource\": \"serverprocess.php?type=\"+type,
            \"bPaginate\": true,
            \"oLanguage\": {\"sUrl\": \"../lib/datatables/langs/\"+lang+\".txt\"}
        });     
        $(\"td#\"+9).css(\"display\", \"none\");
        $(\"td#\"+10).css(\"display\", \"none\");    
        }
     }
     if(cate==''){
         if(exportright==1){
             /* init dataTable */
            oTable = $('#liste').dataTable( {
                \"aoColumnDefs\": [ 
                        {\"bVisible\": false, \"aTargets\": [ 8 ]},{\"bVisible\": false, \"aTargets\": [ 9 ]}
                        ], 
                \"iDisplayLength\": 10,
                \"aLengthMenu\": [[10,25, 50, 100,1000, -1], [10,25, 50, 100,1000,\"All\"]],
                \"bProcessing\": true,
                \"bServerSide\": true,
                \"sAjaxSource\": \"serverprocess.php?type=\"+type,
                \"bPaginate\": true,
                \"oLanguage\": {\"sUrl\": \"../lib/datatables/langs/\"+lang+\".txt\"

                            },

                \"sDom\": 'T<\"clear\">lfrtip',      
                \"oTableTools\": {
                    \"sSwfPath\": \"../lib/datatables/swf/copy_cvs_xls_pdf.swf\",
                    \"aButtons\": [
                    \"xls\"	
                    ]
                }
            });
            $(\"td#\"+8).css(\"display\", \"none\");
            $(\"td#\"+9).css(\"display\", \"none\");  
         }
         else{
            /* init dataTable */
            oTable = $('#liste').dataTable( {
                \"aoColumnDefs\": [ 
                        {\"bVisible\": false, \"aTargets\": [ 8 ]},{\"bVisible\": false, \"aTargets\": [ 9 ]}
                        ], 
                \"iDisplayLength\": 10,
                \"aLengthMenu\": [[10,25, 50, 100,1000, -1], [10,25, 50, 100,1000,\"All\"]],
                \"bProcessing\": true,
                \"bServerSide\": true,
                \"sAjaxSource\": \"serverprocess.php?type=\"+type,
                \"bPaginate\": true,
                \"oLanguage\": {\"sUrl\": \"../lib/datatables/langs/\"+lang+\".txt\"

                            }
            });
            $(\"td#\"+8).css(\"display\", \"none\");
            $(\"td#\"+9).css(\"display\", \"none\");  
         }
     }
   

   
    
         /* Add event listener for opening and closing details
     * Note that the indicator for showing which row is open is not controlled by DataTables,
     * rather it is done here
     */
     $('#liste tbody td img.plus').live('click', function () {
                                    var nTr = this.parentNode.parentNode;
                                    var id =  $(this).attr(\"id\");
                                    nTr.setAttribute(\"id\",id);
                                    if ( this.src.match('details_close') )
                                    {
                                        
                                        /* This row is already open - close it */
                                        this.src = \"../theme/cameleo/img/details_open.png\";
                                        oTable.fnClose( nTr );
                                    }
                                    else
                                    {
                                     /* Open this row */
                                        request(nTr);
                                        this.src = \"../theme/cameleo/img/details_close.png\";
                                        
                                    }
                } );
    
});
function fnShowHide( iCol )
{
 $(document).ready(function() {   
    var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
    oTable.fnSetColumnVis( iCol, bVis ? false : true );
    if(bVis==true){
      $(\"td#\"+iCol).css(\"display\", \"none\");
    }
    else {
      $(\"td#\"+iCol).css(\"display\", \"\");   
    }
});    
   
}
$(document).ready(function() {     
        $(\"table.hideshow a\").click(function (){
             if($(this).css(\"color\")==\"rgb(128, 128, 128)\"){ // grey
                $(this).css(\"color\",\"#000\"); 
             }
             else{
                $(this).css(\"color\",\"rgb(128, 128, 128)\");
             }
        })
});
";
print'</script>';
