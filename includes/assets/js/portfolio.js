jQuery(document).ready(function($) {

    "use strict";

	$('.gbt_portfolio_wrapper.mixitup').each(function() {

		var containerEl = $(this).find('.container');
		var uniqueId = $(this).attr('id');

		var mixer = mixitup( containerEl, {
	     	selectors: {
	     		target: '.portfolio_item.' + uniqueId,
	       		control: '.portfolio_categories.'+ uniqueId+' .control',
	     	}
    	});
	});

});