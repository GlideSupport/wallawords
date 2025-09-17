<?php
/**
 * Block Name: Midpage CTA
 *
 * The template for displaying the custom gutenberg block named Midpage CTA.
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
$ww_align_class = $block['align'] ? 'align' . $block['align'] : '';

// Get the class name for the block to be used for it.
$ww_class_name = ( isset( $block['className'] ) ) ? $block['className'] : null;

// Making the unique ID for the block.
$ww_id = 'block-' . $block_name . '-' . $block['id'];

// Making the unique ID for the block.
if ( $block['name'] ) {
	$block_name = $block['name'];
	$block_name = str_replace( '/', '-', $block_name );
	$ww_name   = 'block-' . $block_name;
}

// Block variables.

$ww_blkmdpg_title        = ( isset( $block_fields['ww_blkmdpg_title']['title'] ) && '' !== $block_fields['ww_blkmdpg_title']['title'] ) ? $block_fields['ww_blkmdpg_title']['title'] : null;
$ww_blkmdpg_title_tag    = ( isset( $block_fields['ww_blkmdpg_title']['title_tag'] ) && '' !== $block_fields['ww_blkmdpg_title']['title_tag'] ) ? $block_fields['ww_blkmdpg_title']['title_tag'] : null;
$ww_blkmdpg_spcr_tp      = ( isset( $block_fields['ww_blkmdpg_spcr']['top_spacer'] ) && '' !== $block_fields['ww_blkmdpg_spcr']['top_spacer'] ) ? $block_fields['ww_blkmdpg_spcr']['top_spacer'] : null;
$ww_blkmdpg_spcr_btm     = ( isset( $block_fields['ww_blkmdpg_spcr']['bottom_spacer'] ) && '' !== $block_fields['ww_blkmdpg_spcr']['bottom_spacer'] ) ? $block_fields['ww_blkmdpg_spcr']['bottom_spacer'] : null;
$ww_blkmdpg_content        = ( isset( $block_fields['ww_blkmdpg_content'] ) && '' !== $block_fields['ww_blkmdpg_content'] ) ? $block_fields['ww_blkmdpg_content']: null;
$ww_blkmdpg_btn    = ( isset( $block_fields['ww_blkmdpg_btn'] ) && '' !== $block_fields['ww_blkmdpg_btn'] ) ? $block_fields['ww_blkmdpg_btn'] : null;
$ww_blkmdpg_secondary_btn    = ( isset( $block_fields['ww_blkmdpg_secondary_btn'] ) && '' !== $block_fields['ww_blkmdpg_secondary_btn'] ) ? $block_fields['ww_blkmdpg_secondary_btn'] : null;
$ww_blkmdpg_button_layout    = ( isset( $block_fields['ww_blkmdpg_button_layout'] ) && '' !== $block_fields['ww_blkmdpg_button_layout'] ) ? $block_fields['ww_blkmdpg_button_layout'] : 'variation-one';
			
?>
<div id="<?php echo esc_html( $ww_id ); ?>" class="<?php echo esc_html( $ww_align_class . ' ' . $ww_class_name . ' ' . $ww_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_blkmdpg_spcr_tp ); ?>"> </div>
		
			<div class="mid-page-cta mpc ctn-gradient <?php echo $ww_blkmdpg_button_layout; ?>">
				<div class="mpc__content d-flex">
				<div class="mpc__content--inner">
					<?php if ( $ww_blkmdpg_title ) { ?>
							<?php if ( $ww_blkmdpg_title ) { ?>
							<div class="mpc__heading">
								<<?php echo esc_html( $ww_blkmdpg_title_tag ); ?> class="heading-1"><?php echo html_entity_decode( $ww_blkmdpg_title ); ?><?php echo '</' . esc_html( $ww_blkmdpg_title_tag ) . '>'; ?>
							</div>
						<?php } ?>
						<div class="mpc__content--text">
							<?php if($ww_blkmdpg_content) { echo html_entity_decode($ww_blkmdpg_content); }?>
						</div>
					<?php } ?>
					
					<?php if ( $ww_blkmdpg_btn || $ww_blkmdpg_secondary_btn) { ?>
						<div class="mpc__btns">
							<?php if ( $ww_blkmdpg_btn ) { ?>
								<?php echo build_acf_button( $ww_blkmdpg_btn, 'site-btn' ); ?>
							<?php } ?>
							<?php if ( $ww_blkmdpg_secondary_btn ) { ?>
								<?php echo build_acf_button( $ww_blkmdpg_secondary_btn, 'site-btn site-secondary-btn' ); ?>
							<?php } ?>
						</div>
					<?php } ?>
					</div>
			</div>
		</div>
		<div class="glide-spacer <?php echo esc_html( $ww_blkmdpg_spcr_btm ); ?>"> </div>
</div>
