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

			const current_tooltip = wpTooltipy.keywords.filter( kw => kw.id == tooltip_id )[0]
			const tooltip_keyword = current_tooltip.title
			const is_wiki = current_tooltip.is_wiki
			const wiki_term = current_tooltip.wiki_term
			
			if( is_wiki.trim().length ){
				options.content = 'Loading'
				options.onShow = function(instance) {
					if (instance._isFetching || instance._error || instance._hasContent) {
					  return;
					}
					instance._isFetching = true;
				
					fetch('https://'+wpTooltipy.wikipedia_lang+'.wikipedia.org/api/rest_v1/page/summary/'+wiki_term)
						.then((response) => response.json() )
						.then((json) => {
							var elem = document.createElement('div');
							elem.innerHTML = ''

							if( json.thumbnail !== undefined ){
								elem.innerHTML += '<img src="'+json.thumbnail.source+'" >';
							}

							elem.innerHTML += json.extract_html;
					
							instance.setContent( elem )
						})
						.catch((error) => {
							instance._error = error;
							instance.setContent(`Request failed. ${error}`);
							instance._hasContent = false
						})
						.finally(() => {
							instance._isFetching = false;
							instance._hasContent = true
						});
				}
			}

			tippy( elem, options );
		});
	 });

})( jQuery );
