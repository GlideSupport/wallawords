<?php
/**
 * Block Name: Theme Buttons
 *
 * The template for displaying the custom gutenberg block named Theme Buttons.
 *
 * @link https://www.advancedcustomfields.com/resources/blocks/
 *
 * @package Walla
 *
 * @global $button_style
 *
 * @since 1.0.0
 */

// Get all the fields from ACF for this block ID.
$block_fields = get_fields_escaped( $block['id'] );
// $block_fields = get_fields_escaped( $block['id'] ,'sanitize_text_field' ); // if want to remove all html.


// Set the block name for it's ID & class from it's file name.
$block_name = $block['name'];
$block_name = str_replace( 'acf/', '', $block_name );

// Set the preview thumbnail for this block for gutenberg editor view.
if ( isset( $block['data']['preview'] ) ) {
	echo '<img src="' . esc_url( get_template_directory_uri() . '/blocks/' . $block_name . '/' . $block['data']['preview'] ) . '" style="width:100%; height:auto;">';
}

// create align class ("alignwide") from block setting ("wide").
$amp_align_class = $block['align'] ? 'align' . $block['align'] : '';

// Get the class name for the block to be used for it.
$amp_class_name = ( isset( $block['className'] ) ) ? $block['className'] : null;

// Making the unique ID for the block.
$amp_id = 'block-' . $block_name . '-' . $block['id'];

// Making the unique ID for the block.
if ( $block['name'] ) {
	$block_name = $block['name'];
	$block_name = str_replace( '/', '-', $block_name );
	$amp_name   = 'block-' . $block_name;
}

// Block variables.

$ww_blk_btn_variation     = ( '' !== $block_fields['ww_blk_btn_variation'] && isset( $block_fields['ww_blk_btn_variation'] ) ) ? $block_fields['ww_blk_btn_variation'] : null;
$ww_blk_btn_button_style     = ( '' !== $block_fields['ww_blk_btn_button_style'] && isset( $block_fields['ww_blk_btn_button_style'] ) ) ? $block_fields['ww_blk_btn_button_style'] : 'primary-btn';
$ww_blk_btnpstn           = ( '' !== $block_fields['ww_blk_btnpstn'] && isset( $block_fields['ww_blk_btnpstn'] ) ) ? $block_fields['ww_blk_btnpstn'] : null;
$walla_blk_button_sp_tp  = ( isset( $block_fields['walla_blk_button_sp']['top_spacer'] ) && '' !== $block_fields['walla_blk_button_sp']['top_spacer'] ) ? $block_fields['walla_blk_button_sp']['top_spacer'] : null;
$walla_blk_button_sp_btm = ( isset( $block_fields['walla_blk_button_sp']['bottom_spacer'] ) && '' !== $block_fields['walla_blk_button_sp']['bottom_spacer'] ) ? $block_fields['walla_blk_button_sp']['bottom_spacer'] : null;

if ( 'left' === $ww_blk_btnpstn ) {
		$ww_blk_btnpstn = 'left-align ';
} elseif ( 'center' === $ww_blk_btnpstn ) {
	$ww_blk_btnpstn = 'center-align';
} else {
	$ww_blk_btnpstn = 'right-align';
}

if ( 'single' === $ww_blk_btn_variation ) {
	$ww_blk_button    = ( isset( $block_fields['ww_blk_button'] ) ) ? $block_fields['ww_blk_button'] : null;
	$ww_blk_btn_style = ( isset( $block_fields['ww_blk_btn_style'] ) ) ? $block_fields['ww_blk_btn_style'] : null;
} else {
	$ww_blk_buttons = ( isset( $block_fields['ww_blk_buttons'] ) ) ? $block_fields['ww_blk_buttons'] : null;
}

?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name . ' ' . $ww_blk_btnpstn ); ?> block-<?php echo esc_html( $block_name ); ?>">
<div class="glide-spacer <?php echo esc_html( $walla_blk_button_sp_tp ); ?>"> </div>
<?php
if ( 'single' === $ww_blk_btn_variation ) {
	if ( 'default' === $ww_blk_btn_style ) {
		echo build_acf_button( $ww_blk_button, 'site-btn' . $ww_blk_btn_button_style );
	} elseif ( 'has-arrow' === $ww_blk_btn_style ) {
		echo build_acf_button( $ww_blk_button, 'site-btn  site-arrow-btn-style ' . $ww_blk_btn_button_style );
	} elseif ( 'just-arrow small' === $ww_blk_btn_style ) {
		echo build_acf_button( $ww_blk_button, 'just-arrow small ' . $ww_blk_btn_button_style );
	} elseif ( 'has-icon' === $ww_blk_btn_style ) {
		echo build_acf_button( $ww_blk_button, 'site-btn  has-icon ' . $ww_blk_btn_button_style );
	} elseif ( 'text-arrow' === $ww_blk_btn_style ) {
		echo build_acf_button( $ww_blk_button, 'text-arrow ' . $ww_blk_btn_button_style );
	}
} else {
	if ( $ww_blk_buttons ) {
		foreach ( $ww_blk_buttons as $amp_button ) {
			$amp_button_link  = $amp_button['site-btn'];
			$amp_button_style = $amp_button['style'];

			if ( 'default' === $amp_button_style ) {
				echo build_acf_button( $amp_button_link, 'site-btn ' . $ww_blk_btn_button_style );
			} elseif ( 'has-arrow' === $amp_button_style ) {
				echo build_acf_button( $amp_button_link, 'site-btn  site-arrow-btn-style ' . $ww_blk_btn_button_style );
			} elseif ( 'just-arrow small' === $amp_button_style ) {
				echo build_acf_button( $amp_button_link, 'site-btn small ' . $ww_blk_btn_button_style );
			} elseif ( 'has-icon' === $amp_button_style ) {
				echo build_acf_button( $amp_button_link, 'site-btn  site-arrow-btn-style ' . $ww_blk_btn_button_style );
			} elseif ( 'text-arrow' === $amp_button_style ) {
				echo build_acf_button( $amp_button_link, 'text-arrow ' . $ww_blk_btn_button_style );
			}
		}
	}
}
?>

<div class="glide-spacer <?php echo esc_html( $walla_blk_button_sp_btm ); ?>"> </div>
</div>
