/* Copyright (C) 2010-2011 Patrick Mary <laube@hotmail.fr>

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
 *       \file       htdocs/lib/datatables/request.js
 *       
 *       \brief      ajax request/response
 *       \version    $Id: request.js,v 1.224 2011/08/10 22:47:34 synry63 Exp $
 */

function request(id,nTr) {
	var xhr = getXMLHttpRequest();
	
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			readdata(xhr.response,nTr);
                         
                }
                
      };
    
	xhr.open("GET", "fiche.php?requestfiche="+id, true);
        xhr.send(null);
        
}
/* Formating function for row details */ 
function readdata(data,nTr){
            var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
            var col = eval( "(" + data + ")"); 
            for(var key in col){  
               if(col[key])
               sOut+='<tr><td>'+key+' : <strong>'+col[key]+'</strong></td></tr>';
            }
            sOut += '</table>';
            oTable.fnOpen( nTr,sOut, 'details' );
       }



