(function( $ ) {
	'use strict';

	// Glossary Ajax if option checked
	if( wpTooltipy.glossary_ajax == "yes" ){
		tooltipy_add_letter_listener()
	}

	function tooltipy_add_letter_listener(){
		$('.tltpy-glossary-header-letter').on('click', function(e){
			e.preventDefault()
			let letter = $(this).attr('data-letter')

			$('.tooltipy-glossary-wrap').prepend( $('<div class="tooltipy-loading">Loading</div>') )

			$.ajax({
				url: wpTooltipy.ajaxurl,
				type: "POST",
				data: {
					'action': 'tltpy_load_glossary',
					'letter': letter
				}
			}).done(function(response) {
				let data = JSON.parse( response )

				$('.tooltipy-glossary-wrap').empty()
				$('.tooltipy-glossary-wrap').replaceWith( $(data.html) )

				// Add active letter button class
				$('.tltpy-glossary-header-letter').removeClass('tltpy-glossary-header-letter--active')
				let $letterButton = $('.tooltipy-glossary-wrap').find('.tltpy-glossary-header-letter[data-letter='+letter+']')
				$letterButton.addClass('tltpy-glossary-header-letter--active')

				tooltipy_add_letter_listener()
			});
		})
	}

})( jQuery );
