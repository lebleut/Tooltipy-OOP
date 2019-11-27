(function( $ ) {
	'use strict';

	 jQuery( document ).ready(function(){
		// Attach popups
		jQuery(".tooltipy-kw").each(function(index, elem){

			var tooltip_id = jQuery(elem).attr("data-tooltip")
			var animation = wpTooltipy.tooltip_animation
			var animation_speed = wpTooltipy.tooltip_animation_speed

			var HTMLcontent = jQuery( "#tooltipy-pop-" + tooltip_id ).clone().get(0)

			var options = {
					content: HTMLcontent,

					animation: false, // To use custom animation below
					arrow: true,
					interactive: true,
					trigger: wpTooltipy.tooltip_trigger,
					placement: wpTooltipy.tooltip_position + '-start',
					zIndex: 9999,
					//theme: 'custom', // Check https://atomiks.github.io/tippyjs/themes/#creating-a-theme
					maxWidth: parseInt(wpTooltipy.tooltip_max_width),

					onMount: function(instance) {
						const {tooltip} = instance.popperChildren;

						requestAnimationFrame(() => {
						  tooltip.classList.add('animated');
						  tooltip.classList.add( animation );
						  tooltip.classList.add( animation_speed );
						});

						// Add custom classes to tooltip popup
						var customClasses = wpTooltipy.tooltip_css_classes.trim()

						if( customClasses ){
							customClasses.split( ' ' ).forEach( cls => {
								if( cls.trim() != '' ){
									tooltip.classList.add( cls.trim() )
								}
							});
						}
					},
					onHidden: function(instance) {
						const {tooltip} = instance.popperChildren;
						tooltip.classList.remove('animated');
						tooltip.classList.remove( animation );
						tooltip.classList.remove( animation_speed );
					},
				}
			tippy( elem, options );
		});
	 });

})( jQuery );
