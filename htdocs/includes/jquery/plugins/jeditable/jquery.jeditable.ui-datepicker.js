/* Create an inline datepicker which leverages the
   jQuery UI datepicker 
*/
jQuery.editable.addInputType('datepicker', {
    element: function(settings, original) {
        var input = jQuery('<input size=8 />');
        // Catch the blur event on month change

        settings.onblur = function(e) {
        };
        
        input.datepicker({
            //dateFormat: 'dd/mm/yy',
            onSelect: function(dateText, inst) {
                // you may want to remove this or
                // change it to
                // jQuery(this).submit();
                // see GamB's comments below
                jQuery(this).parents("form").submit();
            }
        });
 
        input.datepicker('option', 'showAnim', 'slide');

        jQuery(this).append(input);
        return (input);
    }
});