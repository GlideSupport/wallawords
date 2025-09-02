<?php
/**
 * Block Name: Jump Location
 *
 * The template for displaying the custom gutenberg block named Jump Location.
 *
 * @link https://www.advancedcustomfields.com/resources/blocks/
 *
 * @package Walla
 * @since 1.0.0
 */

// Get all the fields from ACF for this block ID.
$block_fields = get_fields_escaped( $block['id'] );
// $block_fields = get_fields_escaped( $block['id'] ,'sanitize_text_field' ); // if want to remove all html.


// Set the block name for it's ID & class from it's file name.
$block_name = $block['name'];
$block_name = str_replace( 'acf/', '', $block_name );



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
$ww_blkjmplctn_hashid = ( isset( $block_fields['ww_blkjmplctn_hashid'] ) && '' !== $block_fields['ww_blkjmplctn_hashid'] ) ? $block_fields['ww_blkjmplctn_hashid'] : null;

?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name ); ?> block-<?php echo esc_html( $block_name ); ?>">

	<div class="jump-link <?php echo html_entity_decode( sanitize_title( $ww_blkjmplctn_hashid ) ); ?>" id="<?php echo html_entity_decode( sanitize_title( $ww_blkjmplctn_hashid ) ); ?>"> </div>

</div>
