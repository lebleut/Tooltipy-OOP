(function( $ ) {
	'use strict';

	 jQuery( document ).ready(function(){
		// Attach popups
		jQuery(".tooltipy-kw").each(function(index, elem){

			var tooltip_id = jQuery(elem).attr("data-tooltip")

			var options = {
					content: jQuery( "#tooltipy-pop-" + tooltip_id ).get(0),
					
					animation: 'fade',
					arrow: true,
					interactive: true,
					trigger: 'mouseenter',
					placement: 'bottom-start',
					zIndex: 9999,
					//theme: 'custom', // Check https://atomiks.github.io/tippyjs/themes/#creating-a-theme
					//maxWidth: 350,
				}

			tippy( elem, options );
		});
	 });

})( jQuery );
