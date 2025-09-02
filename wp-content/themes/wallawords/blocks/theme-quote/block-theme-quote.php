<?php
/**
 * Block Name: Block Theme Quote
 *
 * The template for displaying the custom gutenberg block named Block Theme Quote.
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
$ww_blkthquot_quote       = ( isset( $block_fields['ww_blkthquot_quote'] ) && '' !== $block_fields['ww_blkthquot_quote'] ) ? $block_fields['ww_blkthquot_quote'] : null;
$ww_blkthquot_name        = ( isset( $block_fields['ww_blkthquot_name'] ) && '' !== $block_fields['ww_blkthquot_name'] ) ? $block_fields['ww_blkthquot_name'] : null;
$ww_blkthquot_designation = ( isset( $block_fields['ww_blkthquot_designation'] ) && '' !== $block_fields['ww_blkthquot_designation'] ) ? $block_fields['ww_blkthquot_designation'] : null;
$ww_blkthquot_spcr_tp     = ( isset( $block_fields['ww_blkthquot_spcr']['top_spacer'] ) && '' !== $block_fields['ww_blkthquot_spcr']['top_spacer'] ) ? $block_fields['ww_blkthquot_spcr']['top_spacer'] : null;
$ww_blkthquot_spcr_btm    = ( isset( $block_fields['ww_blkthquot_spcr']['bottom_spacer'] ) && '' !== $block_fields['ww_blkthquot_spcr']['bottom_spacer'] ) ? $block_fields['ww_blkthquot_spcr']['bottom_spacer'] : null;

?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_blkthquot_spcr_tp ); ?>"> </div>
			<blockquote>
				<?php
				if ( $ww_blkthquot_quote ) {
					?>
					<p><?php echo esc_html( $ww_blkthquot_quote ); ?></p><?php } ?>
				<?php if ( $ww_blkthquot_name || $ww_blkthquot_designation ) { ?>
					<div class="client-detail">
						<div class="client-name"><?php echo esc_html( $ww_blkthquot_name ); ?>
							<?php
							if ( $ww_blkthquot_designation ) {
								?>
							<span><?php echo esc_html( $ww_blkthquot_designation ); ?></span><?php } ?>
						</div>
						<div class="clear"></div>
					</div>
				<?php } ?>
			</blockquote>
	<div class="glide-spacer <?php echo esc_html( $ww_blkthquot_spcr_btm ); ?>"> </div>
</div>
