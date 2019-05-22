jQuery(document).ready(function($) {

    "use strict";

	$('.gbt_portfolio_wrapper.mixitup').each(function() {

		$(this).mixItUp({
	     	selectors: {
	       		filter: $(this).find('.controls'),
	     	}
    	});
	});

});