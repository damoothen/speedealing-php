
/* Copyright (C) 2012 Patrick Mary           <laube@hotmail.fr>
 
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
 *	    \file       htdocs/lib/datatables/js/searchColumns.js
 *      
 *		\brief      Page to init filter on column
 *		\version    $Id: footerSearch.js,v 1 2012/01/19 23:54:12 synry63 Exp $
 */	 
/* send input value to server */
$(document).ready(function() {
$("tbody input").keyup( function () {
	/* Filter on the column */
        var id = $(this).parent().attr("id");
        oTable.fnFilter( this.value, id);
        } );
/*send selected level value to server */        
$("tbody #level").change( function () {
	/* Filter on the column */
        var id = $(this).parent().attr("id");
        var value = $(this).val();
        oTable.fnFilter( value, id);
        } );
/*send selected stcomm value to server */   
$("tbody .flat").change( function () {
	/* Filter on the column */
        var id = $(this).parent().attr("id");
        var value = $(this).val();
        oTable.fnFilter( value, id);
        } );

});
