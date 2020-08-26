(function( $ ) {
	'use strict';

	 $( document ).ready(function(){
		// Attach popups
		$(".tooltipy-kw").each(function(index, elem){

			const tooltip_id = $(elem).attr("data-tooltip")
			const animation = wpTooltipy.tooltip_animation

			const animation_speed = wpTooltipy.tooltip_animation_speed

			const $container_elem = $( "#tooltipy-popups-wrapper" ).find("[data-tooltipy-id=" + tooltip_id + "]" )
			const HTMLcontent = $container_elem.find(".tooltipy-inner" ).clone().get(0)

			const options = {
					content: HTMLcontent,

					animation: false, // To use custom animation below
					arrow: true,
					interactive: true,
					trigger: wpTooltipy.tooltip_trigger,
					placement: wpTooltipy.tooltip_position + '-start',
					zIndex: 9999,

					theme: wpTooltipy.tooltip_theme, // Check https://atomiks.github.io/tippyjs/v6/themes/

					maxWidth: parseInt(wpTooltipy.tooltip_max_width),

					onMount: function(instance) {
						const box = instance.popper.firstElementChild;
						
						box.classList.add( 'tooltipy-pop' )
						box.classList.add( 'tooltipy-pop-' + tooltip_id )

						// Add metadata classes
						if( $container_elem.attr('class') && $container_elem.attr('class').trim() != '' ){
							$container_elem.attr('class').trim().split(' ').forEach( (cls) => {
								box.classList.add( cls )
							})
						}

						requestAnimationFrame(() => {

							box.classList.add('animated');
							if( animation != '' ){
								box.classList.add( animation );
							}
							if( animation_speed != '' ){
								box.classList.add( animation_speed );
							}
						});

						// Add custom classes to tooltip popup
						const customClasses = wpTooltipy.tooltip_css_classes.trim()

						if( customClasses ){
							customClasses.split( ' ' ).forEach( cls => {
								if( cls.trim() != '' ){
									box.classList.add( cls.trim() )
								}
							});
						}
					},
					onHidden: function(instance) {
						const box = instance.popper.firstElementChild;
						box.classList.remove('animated');
						if( animation != '' ){
							box.classList.remove( animation );
						}
						if( animation_speed != '' ){
							box.classList.remove( animation_speed );
						}
					},
				}
			tippy( elem, options );
		});
	 });

})( jQuery );
