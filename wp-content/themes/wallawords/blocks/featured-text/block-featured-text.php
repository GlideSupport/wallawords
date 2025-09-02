<?php
/**
 * Block Name: Featured Text
 *
 * The template for displaying the custom gutenberg block named Featured Text.
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
$ww_blkfetrdtxt_txt      = ( isset( $block_fields['ww_blkfetrdtxt_txt'] ) && '' !== $block_fields['ww_blkfetrdtxt_txt'] ) ? $block_fields['ww_blkfetrdtxt_txt'] : null;
$ww_blkfetrdtxt_spcr_tp  = ( isset( $block_fields['ww_blkfetrdtxt_spcr']['top_spacer'] ) && '' !== $block_fields['ww_blkfetrdtxt_spcr']['top_spacer'] ) ? $block_fields['ww_blkfetrdtxt_spcr']['top_spacer'] : null;
$ww_blkfetrdtxt_spcr_btm = ( isset( $block_fields['ww_blkfetrdtxt_spcr']['bottom_spacer'] ) && '' !== $block_fields['ww_blkfetrdtxt_spcr']['bottom_spacer'] ) ? $block_fields['ww_blkfetrdtxt_spcr']['bottom_spacer'] : null;
$ww_blkfetrdtxt_text     = ( isset( $block_fields['ww_blkfetrdtxt_text'] ) && '' !== $block_fields['ww_blkfetrdtxt_text'] ) ? $block_fields['ww_blkfetrdtxt_text'] : null;

?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_blkfetrdtxt_spcr_tp ); ?>"> </div>
		<?php if ( $ww_blkfetrdtxt_txt || $ww_blkfetrdtxt_text ) { ?>
			<div class="featured-text">
				<div class="featured-text__inner">
					<?php
					if ( $ww_blkfetrdtxt_txt ) {
						?>
						<b><?php echo esc_html( $ww_blkfetrdtxt_txt ); ?></b><?php } ?>
					<?php
					if ( $ww_blkfetrdtxt_text ) {
						echo html_entity_decode( $ww_blkfetrdtxt_text );
					}
					?>
				</div>
			</div>
		<?php } ?>
	<div class="glide-spacer <?php echo esc_html( $ww_blkfetrdtxt_spcr_btm ); ?>"> </div>
</div>
