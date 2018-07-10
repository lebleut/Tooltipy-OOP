// Quick edit population
jQuery(function($){
    // it is a copy of the inline edit function
    var wp_inline_edit_function = inlineEditPost.edit;

    // we overwrite the it with our own
    inlineEditPost.edit = function( post_id ) {

        // let's merge arguments of the original function
        wp_inline_edit_function.apply( this, arguments );

        // get the post ID from the argument
        var id = 0;
        if ( typeof( post_id ) == 'object' ) { // if it is object, get the ID number
            id = parseInt( this.getId( post_id ) );
        }

        //if post id exists
        if ( id > 0 ) {

            // add rows to variables
            var specific_post_edit_row = $( '#edit-' + id ),
                specific_post_row = $( '#post-' + id ),
                tltpy_synonyms = $( '.column-tltpy_synonyms .data', specific_post_row ).text() //  remove $ sign


            // check if the Featured Product column says Yes
            var tltpy_is_prefix = $( '.column-tltpy_is_prefix .data', specific_post_row ).text() ? true : false;
            var tltpy_case_sensitive = $( '.column-tltpy_case_sensitive .data', specific_post_row ).text() ? true : false;

            // populate the inputs with column data
            $( ':input[name="tltpy_synonyms"]', specific_post_edit_row ).val( tltpy_synonyms );
            
            $( ':input[name="tltpy_is_prefix"]', specific_post_edit_row ).prop( 'checked', tltpy_is_prefix );
            $( ':input[name="tltpy_case_sensitive"]', specific_post_edit_row ).prop( 'checked', tltpy_case_sensitive );
        }
    }
});

// Bulk edit save
jQuery(function($){
	$( 'body' ).on( 'click', 'input[name="bulk_edit"]', function() {
 
		// let's add the WordPress default spinner just before the button
		$( this ).after('<span class="spinner is-active"></span>');
 
 
		// define: prices, featured products and the bulk edit table row
		var bulk_edit_row = $( 'tr#bulk-edit' ),
		    post_ids = new Array()
		    tltpy_synonyms = bulk_edit_row.find( 'input[name="tltpy_synonyms"]' ).val(),
		    tltpy_is_prefix = bulk_edit_row.find( 'input[name="tltpy_is_prefix"]' ).attr('checked') ? 1 : 0;
		    tltpy_case_sensitive = bulk_edit_row.find( 'input[name="tltpy_case_sensitive"]' ).attr('checked') ? 1 : 0;
 
		// now we have to obtain the post IDs selected for bulk edit
		bulk_edit_row.find( '#bulk-titles' ).children().each( function() {
			post_ids.push( $( this ).attr( 'id' ).replace( /^(ttle)/i, '' ) );
		});
 
		// save the data with AJAX
		$.ajax({
			url: ajaxurl, // WordPress has already defined the AJAX url for us (at least in admin area)
			type: 'POST',
			async: false,
			cache: false,
			data: {
				action: 'tltpy_bulk_save',
                post_ids: post_ids, 
                tltpy_synonyms: tltpy_synonyms,
                tltpy_is_prefix: tltpy_is_prefix,
                tltpy_case_sensitive: tltpy_case_sensitive,
				nonce: $('#tooltipy_nonce').val()
			}
		});
	});
});