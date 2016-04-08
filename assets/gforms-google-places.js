jQuery(function($) {

	// set up geo-complete fields
	if ($.fn.geocomplete) {
		$('input.geo-complete').each(function(){
			$el = $(this);
			$el.geocomplete({
				details: '#' + $el.closest('form').attr('id'),
				detailsAttribute: 'data-geo-' + $el.data('field-id'),
			});
		});
	}

});
