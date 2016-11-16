function set_fields() {
    jQuery('form.job-manager-form input[type="text"]').closest('fieldset').not('.fieldset-job_location').addClass('textfields');
    if (jQuery('form.job-manager-form input[type="text"]').hasClass('jmfe-date-picker')) {
        jQuery('form.job-manager-form .jmfe-date-picker').closest('fieldset').removeClass('textfields').addClass('calendarfields');
    }
    jQuery('form.job-manager-form select').closest('fieldset').addClass('selectfields');
    jQuery('form.job-manager-form textarea').closest('fieldset').addClass('textareafields');
    jQuery('form.job-manager-form input[type="file"]').closest('fieldset').addClass('filefields');
    jQuery('form.job-manager-form input[type="radio"]').closest('fieldset').addClass('radiofields');
    jQuery('form.job-manager-form input[type="radio"]').not(':first').before('<span class="rd-clearfix"/>');
	jQuery(".jmfe-date-picker").each(function(){
		jQuery(this).datepicker({
			dateFormat:jmfe_date_field.dateFormat,
			monthNames:jmfe_date_field.monthNames,
			monthNamesShort:jmfe_date_field.monthNamesShort,
			dayNames:jmfe_date_field.dayNames,
			dayNamesShort:jmfe_date_field.dayNamesShort,
			dayNamesMin:jmfe_date_field.dayNamesMin
		})
	})
}
var fieldInt = setInterval(function() {
    if (jQuery('form.job-manager-form fieldset').length > 0) {
        set_fields();
        clearInterval(fieldInt);
    }
}, 1000);
(function($) {
    function create_tabs() {
        var fields = [];
        var i = 0;
        setTimeout(function() {
            $('h2[id*="c_tab"]').remove();
            $('h2[id*="c_all"]').remove();
            $('div[id*="c_all"]').on('click', function() {
                $(this).toggleClass('active');
                $('div[id*="c_tab"]').each(function() {
                    $(this).toggle();
                });
            });
            $('div[id*="c_tab"]').last().addClass('last');
            $('div[id*="c_tab"]').each(function() {
                $(this).hide();
                var group = $(this).attr('id');
                $(this).css('cursor', 'pointer').nextUntil('div[id*="c_tab"]', 'fieldset[class^="fieldset-child"]').wrapAll('<div class="tab-wrapper" data-group="' + group + '"></div>');
                $(this).on('click', function() {
                    $(this).toggleClass('active');
                    $('div[id*="c_tab"]').not($(this)).removeClass('active');
                    $(this).next().toggleClass('active').animate({
                        'opacity': '1'
                    }, 200);
                    $('form').find('.tab-wrapper').not($(this).next()).removeClass('active').css('opacity', 0);
                });
            });
            $('.tab-wrapper').each(function() {
                $(this).append('<span class="clearAll"> Reset Fields</span>');
            });
        }, 10);
        $('.job-manager-form').on('click', '.clearAll', function() {
            cont = $(this).closest('.tab-wrapper');
            cont.children().find('input[type="text"]').val('');
            cont.children().find('input[type="radio"]').each(function() {
                if ($(this).prop('checked') != false) {
                    $(this).prop('checked', false);
                    $('.jmfe-clear-radio').hide();
                }
            });
            cont.children().find('select[multiple="multiple"]').val('');
            cont.children().find('select').not('select[multiple="multiple"]').each(function() {
                var firstVal = $(this).children().first().val();
                $(this).val(firstVal);
            });
        });
    }
    $(document).ready(function() {
        create_tabs();
    });
})(jQuery);
