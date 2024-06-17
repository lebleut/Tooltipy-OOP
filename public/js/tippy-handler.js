(function( $ ) {
	'use strict';

	$( document ).ready(function(){
		// Attach popups
		$(".tooltipy-kw").each(function(index, elem){
			if( !$(this).hasClass('tooltipy-kw-wiki') ){
				fill_tooltip( elem )
			}
		});

		// Tooltip post single page
		$(".tooltipy-post--wiki").each(function(index, elem){
			const $current_wiki = $(this)
			const $thumb_wrapper = $current_wiki.find('.tooltipy-pop__thumbnail')
			const $content_wrapper = $current_wiki.find('.tooltipy-pop__content')
			let $elements = []

			const tooltip_id = $current_wiki.attr('data-tltpy')
			const is_wiki = $current_wiki.attr('data-is_wiki')
			const wiki_term = $current_wiki.attr('data-wiki_term')

			if( is_wiki.trim().length ){
				fetch('https://'+wpTooltipy.wikipedia_lang+'.wikipedia.org/api/rest_v1/page/summary/'+wiki_term)
					.then((response) => response.json() )
					.then((json) => {
						$thumb_wrapper.html( $('<img src="'+json.thumbnail.source+'" >') )
						$content_wrapper.html( $(json.extract_html) )
					})
					.catch((error) => {
						$thumb_wrapper.html('')
						$content_wrapper.html( 'Not found' )
					})
					.finally(() => {
						$elements = $('.tooltipy-kw-' + tooltip_id).toArray()
						for (let index = 0; index < $elements.length; index++) {
							fill_tooltip( $elements[index] )
						}
					});
			}
		})
	});

	function fill_tooltip( elem ){
		//elems = typeof elems !== "object" ? [elems] : elems;

		//for( elem of elems){
				const tooltip_id = $(elem).attr("data-tltpy")
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

					onCreate(instance) {
						instance._isFetching = false
						instance._error = false
						instance._hasContent = false
					},

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

						// Unset these properties so new network requests can be initiated
						instance._error = null;
						instance._isFetching = false
					},
			}

			tippy( elem, options );
		//}
		
	}
})( jQuery );
