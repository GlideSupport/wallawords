<?php
/**
 * Blocks related functions
 *
 * @link
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

namespace BaseTheme\Blocks;

use BaseTheme;

/**
 * Template Class For Blocks
 *
 * Template Class
 *
 * @category Setting_Class
 * @package  Base Theme Package
 */
class WP_Theme_Blocks {
	/**
	 * Define class Constructor
	 **/
	public function __construct() {
		add_action( 'init', array( $this, 'register_acf_blocks' ) );
	}

	/**
	 * A function in which all acf blocks are registered
	 *
	 *  @return void
	 */
	public function register_acf_blocks() {

		register_block_type( BASETHEME_BLOCK_DIR . '/section-container' );
		// Register a block - FAQ.
		self::register_acf_block( 'faqs' );
		// Register a block - Media Alongside Text.
		self::register_acf_block( 'media-alongside-text' );
		// Register a block - Jump Location.
		self::register_acf_block( 'jump-location' );
		// Register a block - AcfBlock.
		self::register_acf_block(
			'acfblock',
			true,
			array( 'assets/build/vendors/owl.carousel.min.js', 'assets/build/vendors/organic-tab.js' ), // name will be wp-theme-owl and wp-theme-organic-tab.
		);
		// [register_here].
	}


	/**
	 * A function which is used to register a block
	 *
	 * @param string  $block_name is the name of the block.
	 * @param boolean $has_script is boolean value that determines if block need to include script or not.
	 * @param array   $block_scripts is array to use when need external file in the block.
	 *
	 *  @return void
	 */
	protected static function register_acf_block( $block_name = null, $has_script = false, $block_scripts = null ) {
		if ( $has_script ) {
			$block_script_order = array( 'jquery' );
			$dependencies       = array();
			if ( $block_scripts ) {
				$dependencies = BaseTheme::register_scripts( $block_scripts );
			}
			$block_script_order = array_merge( $block_script_order, $dependencies );
			BaseTheme::register_script(
				'blocks/' . $block_name . '/' . $block_name . '.js',
				$block_script_order,
				args:array(
					'in_footer' => true,
					'strategy'  => 'defer',
				),
			);
		}
		register_block_type( BASETHEME_BLOCK_DIR . '/' . $block_name );
	}
}
new WP_Theme_Blocks();
