/**
 * product-teaser-slider
 */
jQuery( document ).ready( function() {
	const AcfBlock = function( $block ) {
		const blockId = jQuery( $block ).children();
	};
	// Initialize each block on page load (front end).
	if ( jQuery( '.block-acf-acfblock' ).length > 0 ) {
		jQuery( '.block-acf-acfblock' ).each( function() {
			AcfBlock( jQuery( this ) );
		} );
	}

	// Initialize dynamic block preview (editor).
	if ( window.acf ) {
		window.acf.addAction(
			'render_block_preview/type=acfblock',
			AcfBlock
		);
	}
} );
