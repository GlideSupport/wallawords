/**
 * Image Alonside Text
 */
jQuery( document ).ready( function() {
	const ImageGallery = function( $block ) {
		//const blockId = jQuery( $block ).children();
		if ( jQuery($block).find( '.gallery__swiper' ).length > 0 ) {

			console.log('init gallery');
	 	
			new Swiper( '.gallery__swiper', {
				slidesPerView: 'auto',
				spaceBetween: 41,
				freeMode: true,
				loop: false,
				loopedSlides: 4,
				loopPreventsSlide: false,
			 	breakpoints: {
					375: {
						spaceBetween: 17,
					},
					1004: {
						spaceBetween: 25,
					},
					1300: {
						spaceBetween: 36,
					},
					1400: {
						spaceBetween: 41,
					},
				},
			} );
		}
	};
	// Initialize each block on page load (front end).
	if ( jQuery( '.block-acf-image-gallery' ).length > 0 ) {
		jQuery( '.block-acf-image-gallery' ).each( function() {
			ImageGallery( jQuery( this ) );
		} );
	}

	// Initialize dynamic block preview (editor).
	if ( window.acf ) {
		window.acf.addAction(
			'render_block_preview/type=image-gallery',
			ImageGallery
		);
	}
} );
