jQuery( document ).ready( function() {
	const Testimonals = function( $block ) {

		if ( $block.find( '.testimonial__swiper' ).length > 0 ) {

		 var swiper = new Swiper('.testimonial__swiper', {
			
				slidesPerView: 1,
				autoHeight: true,
				freeMode: true,
				loop: true,
				
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev',
				  },
				
				
			} );
			 
		}
			
	};
	// Initialize each block on page load (front end).
	if ( jQuery( '.block-acf-testimonals' ).length > 0 ) {
		jQuery( '.block-acf-testimonals' ).each( function() {
			Testimonals( jQuery( this ) );
		} );
	}

	// Initialize dynamic block preview (editor).
	if ( window.acf ) {
		window.acf.addAction(
			'render_block_preview/type=testimonals',
			Testimonals
		);
	}
} );