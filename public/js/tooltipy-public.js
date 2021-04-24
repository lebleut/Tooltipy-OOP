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
				tooltipy_add_letter_listener()
			});
		})
	}

})( jQuery );
