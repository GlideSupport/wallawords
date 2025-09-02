/**
 * product-teaser-slider
 */
jQuery( document ).ready( function() {
	const Faqs = function( $block ) {
		const blockId = jQuery( $block ).children();
		jQuery( blockId ).find( '.faq__single > span' ).on( 'click', function() {
			if ( jQuery( this ).hasClass( 'active' ) ) {
				jQuery( this ).removeClass( 'active' );
				jQuery( this ).siblings( '.faq__content' ).slideUp( 300 );
			} else {
				jQuery( '.faq__single > span' ).removeClass( 'active' );
				jQuery( this ).addClass( 'active' );
				jQuery( '.faq__content' ).slideUp();
				jQuery( this ).siblings( '.faq__content' ).slideDown( 300 );
			}
		} );
	};
	// Initialize each block on page load (front end).
	if ( jQuery( '.block-acf-faqs' ).length > 0 ) {
		jQuery( '.block-acf-faqs' ).each( function() {
			Faqs( jQuery( this ) );
		} );
	}

	// Initialize dynamic block preview (editor).
	if ( window.acf ) {
		window.acf.addAction(
			'render_block_preview/type=faqs',
			Faqs
		);
	}
} );
