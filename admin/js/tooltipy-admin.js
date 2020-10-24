(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	/**
	 * Handles the "Generate relationships" button
	 */
	window.tltpy_relationships_results = function(response, $button){
		// Show results
		if( response.result == "SUCCESS" ){
			$button.hide()
			$(response.message).insertAfter($button)
		}else{
			$('<span>Error while processing</span>').insertAfter($button)
		}

		console.log(response)
	}

	/**
	 * Handles the "Migrate old options" button
	 */
	window.tltpy_old_options_results = function( response, $button ){
		$button.hide()
		$(response.message).insertAfter($button)

		console.log(response)
	}

	// Loads the admin side wiki data
	window.tltpy_load_wiki_data = function(){
		if(
			$('[name="tltpy_is_wiki"]').prop('checked')
			&& $('[name="tltpy_wiki_term"]').val().trim() != ""
		){
			// ajx to fill '#wiki-term-wrapper'
			const $wiki_wrapper = $('#wiki-term-wrapper')
			const tooltip_keyword = $('[name="tltpy_wiki_term"]').val().trim()

			fetch('https://'+wpTooltipy.wikipedia_lang+'.wikipedia.org/api/rest_v1/page/summary/'+tooltip_keyword)
				.then((response) => response.json() )
				.then((json) => {
		
					let html = ''

					if( json.thumbnail !== undefined ){
						html += '<img src="'+json.thumbnail.source+'" >';
					}

					html += json.extract_html;
			
					$wiki_wrapper.html( html )
				})
				.catch((error) => {
					$wiki_wrapper.html( `Request failed. <span style="color:red;">${error}</span>`);
				})
		}
	}


	$(document).ready(function(){

		tltpy_load_wiki_data()

		const  $checkboxes = $('.tltpy-exclude-cats').find('input[type=checkbox]')

		// Fill tltpy_excluded_cats_nbr
		showExcludedCatsNbr()

		function showExcludedCatsNbr(){
			let excluded_cats = $checkboxes.filter( (index, elem) => $(elem).prop('checked') == true ).length
			$('.tltpy_excluded_cats_nbr').text( '(' + excluded_cats + '/' + $checkboxes.length + ')' )
		}

		$(document).on( 'change', '#tltpy-check-all-cats', function(){
			if( $(this).prop('checked') ){
				$checkboxes.prop('checked',true)
			}else{
				$checkboxes.prop('checked',false)
			}

			showExcludedCatsNbr()
		})

		$(document).on( 'change', '.tltpy-exclude-cats', function(){
			let allChecked = true
			$checkboxes.each( (index, elem) => {
				if( $(elem).prop('checked') == false ){
					allChecked = false
				}
			});

			$('#tltpy-check-all-cats').prop( 'checked', allChecked )

			showExcludedCatsNbr()
		})
	})
})( jQuery );