/* Create an inline datepicker which leverages the
   jQuery UI autocomplete 
*/
/*$.editable.addInputType('autocomplete', {
	element	: $.editable.types.text.element,
	plugin	: function(settings, original) {
		$('input', this).autocomplete(settings.autocomplete);
	}
});*/

$.editable.addInputType('autocomplete', {
    element : $.editable.types.text.element,
    plugin : function(settings, original) {
        console.log(this);
        $('input', this).autocomplete(settings.autocomplete.url, {                                                 
            dataType:'json',
            //parse : function(data) {
            //console.log(data);
            //    return $.map(data, function(item){
            //        console.log(item);
            //        return {
            //            data : item,
            //            value : item.Key,
            //            result: item.value                                                                                     
            //        };
            //    })
            //},
            //formatItem: function(row, i, n) {                                                        
            //    return row.value;
            //},
            mustMatch: false
            //focus: function(event, ui) {                                                
            //    $('#example tbody td[title]').val(ui.item.label);
            //    return false;
            //}
        });                                        
    }
});