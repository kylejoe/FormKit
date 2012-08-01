FormKit.register(function(e,scopeEl) {
    $(scopeEl).find('.formkit-widget-date').each(function(){
        if( !$(this).attr('readonly') )
            $(this).datepicker({
                dateFormat: $(this).data('date-format')
            });
    });
});
