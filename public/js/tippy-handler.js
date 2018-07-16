(function( $ ) {
	'use strict';

	 jQuery( document ).ready(function(){
		// Attach popups
		jQuery(".tooltipy-kw").each(function(index, elem){

			var tooltip_id = jQuery(elem).attr("data-tooltip")

			var options = {
					animation: 'fade',
					arrow: true,
					interactive: true,
  					arrowType: 'round',
					trigger: 'mouseenter',
					theme: 'dark',
					position: 'bottom',
					zIndex: 9999,
					html: "#tooltipy-pop-" + tooltip_id
				}

			tippy.one( elem, options );
		});
	 });

})( jQuery );
