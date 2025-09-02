/**
 * Theme Video
 */
jQuery( document ).ready( function() {
	const Themevideo = function( $block ) {
		const blockId = jQuery( $block ).children();
		var isMobile = {
			Android() {
				return navigator.userAgent.match( /Android/i );
			},
			BlackBerry() {
				return navigator.userAgent.match( /BlackBerry/i );
			},
			iOS() {
				return navigator.userAgent.match( /iPhone|iPad|iPod/i );
			},
			Opera() {
				return navigator.userAgent.match( /Opera Mini/i );
			},
			Windows() {
				return navigator.userAgent.match( /IEMobile/i ) || navigator.userAgent.match( /WPDesktop/i );
			},
			any() {
				return (
					isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows()
				);
			},
		};
		if ( ! isMobile.any() ) {
			// get reference to video sources
			const sources = document.getElementsByClassName( 'video__source' );
			// loop through and replace data-src with src
			for ( let i = 0; i < sources.length; i++ ) {
				if ( sources[ i ].getAttribute( 'data-src' ) ) {
					sources[ i ].setAttribute( 'src', sources[ i ].getAttribute( 'data-src' ) );
					sources[ i ].removeAttribute( 'data-src' ); // use only if you need to remove data-src attribute after setting src
				}
			}
			// fade in video from css when it's ready to play
			const video = document.getElementById( 'video' );
			// listen for canplay event and fade video in
			video.addEventListener( 'canplay', function() {
				//console.log('video duration information available');
				video.style.transition = 'opacity 2s';
				video.style.opacity = 1;
			} );
			// reload video sources
			video.load();
		}
	};
	// Initialize each block on page load (front end).
	if ( jQuery( '.block-acf-theme-video' ).length > 0 ) {
		jQuery( '.block-acf-theme-video' ).each( function() {
			Themevideo( jQuery( this ) );
		} );
	}

	// Initialize dynamic block preview (editor).
	if ( window.acf ) {
		window.acf.addAction(
			'render_block_preview/type=theme-video',
			Themevideo
		);
	}
} );
