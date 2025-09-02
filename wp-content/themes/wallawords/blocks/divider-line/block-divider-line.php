<?php
/**
 * Block Name: Divider Line
 *
 * The template for displaying the custom gutenberg block named Divider Line.
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
$ww_blkdvdrlin_spcr_tp  = ( isset( $block_fields['ww_blkdvdrlin_spcr']['top_spacer'] ) && '' !== $block_fields['ww_blkdvdrlin_spcr']['top_spacer'] ) ? $block_fields['ww_blkdvdrlin_spcr']['top_spacer'] : null;
$ww_blkdvdrlin_spcr_btm = ( isset( $block_fields['ww_blkdvdrlin_spcr']['bottom_spacer'] ) && '' !== $block_fields['ww_blkdvdrlin_spcr']['bottom_spacer'] ) ? $block_fields['ww_blkdvdrlin_spcr']['bottom_spacer'] : null;
?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_blkdvdrlin_spcr_tp ); ?>"> </div>
		<div class="divider-line"></div>
	<div class="glide-spacer <?php echo esc_html( $ww_blkdvdrlin_spcr_btm ); ?>"> </div>
</div>
