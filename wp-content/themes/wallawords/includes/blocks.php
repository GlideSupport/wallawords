<?php
/**
 * Functions for custom Gutenberg blocks
 *
 * @link https://www.advancedcustomfields.com/resources/blocks/
 *
 * @package Walla
 * @since 1.0.0
 */

/**
 * Register custom Gutenberg blocks
 */

/**
 * A function in which all acf blocks are registered
 *
 *  @return void
 */
function register_acf_blocks() {

	register_block_type( BLOCK_DIR . '/section-container' );
	// Register a block - Theme Spacer.
	register_acf_block( 'theme-spacer' );
	// Register a block - Theme Buttons.
	register_acf_block( 'theme-buttons' );
	// Register a block - Image alongside Text.
	register_acf_block( 'image-alongside-text' );
	// Register a block - Hero.
	register_acf_block( 'hero' );
	// Register a block - FAQs.
	register_acf_block(
		'faqs',
		array( 'jquery' ),
		null,
		true
	);
	// Register a block - Theme Video.
	register_acf_block( 'theme-video' );
	// Register a block - Testimonals.
		register_acf_block(
			'testimonals',
			array( 'jquery', 'swiper' ),
			function() {
				wp_enqueue_script( 'swiper', assetDir . '/build/vendors/swiper-bundle.min.js', array( 'jquery' ), '1.0.0', true );
			},
			true
		);
	// Register a block - Icon Grid.
	register_acf_block( 'icon-grid' );
	// Register a block - Jump Location.
	register_acf_block( 'jump-location' );
	// Register a block - Numbered List.
	register_acf_block(
		'numbered-list',
		array( 'jquery', 'swiper' ),
		function() {
			wp_enqueue_script( 'swiper', assetDir . '/build/vendors/swiper-bundle.min.js', array( 'jquery' ), '1.0.0', true );
		},
		true
	);
	// Register a block - CTA Grid.
	register_acf_block( 'cta-grid' );
	// Register a block - Team.
	register_acf_block( 'team' );
	// Register a block - Midpage CTA.
	register_acf_block( 'midpage-cta' );
	// Register a block - Section Header.
	register_acf_block( 'section-header' );
	// Register a block - Divider Line.
	register_acf_block( 'divider-line' );
	// Register a block - Featured Text.
	register_acf_block( 'featured-text' );

	// Register a block - Image Gallery.
		register_acf_block(
			'image-gallery',
			array( 'jquery', 'swiper' ),
			function() {
				wp_enqueue_script( 'swiper', assetDir . '/build/vendors/swiper-bundle.min.js', array( 'jquery' ), '1.0.0', true );
			},
			true
		);
	// Register a block - Checklist.
	register_acf_block( 'checklist' );
	// Register a block - Block Theme Quote.
	register_acf_block( 'theme-quote' );
	// Register a block - Contact Info.
	register_acf_block( 'contact-info' );

}

add_action( 'init', 'register_acf_blocks' );

/**
 * A function which is used to register a block
 *
 * @param string   $block_name is the name of the block.
 * @param array    $block_script_order is a array of registered scripts in the correct order.
 * @param function $block_function is function to use when need external file in the block.
 * @param boolean  $has_script is boolean value that determines if block need to include script or not.
 *
 *  @return void
 */
function register_acf_block( $block_name = null, $block_script_order = array( 'jquery' ), $block_function = null, $has_script = false ) {
	if ( $has_script ) {
		if ( $block_function ) {
			$block_function();
		}
		wp_register_script( 'block-' . $block_name, blockDirAssets . '/' . $block_name . '/' . $block_name . '.js', $block_script_order, filemtime(BLOCK_DIR .'/' . $block_name . '/' . $block_name . '.js'), true );
	}
	register_block_type( BLOCK_DIR . '/' . $block_name );
}
