<?php
/**
 * Block Name: Image Alongside Text
 *
 * The template for displaying the custom gutenberg block named Image Alongside Text.
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
$ww_blkiat_title      = ( isset( $block_fields['ww_blkiat_title']['title'] ) && '' !== $block_fields['ww_blkiat_title']['title'] ) ? $block_fields['ww_blkiat_title']['title'] : null;
$ww_blkiat_title_tag  = ( isset( $block_fields['ww_blkiat_title']['title_tag'] ) && '' !== $block_fields['ww_blkiat_title']['title_tag'] ) ? $block_fields['ww_blkiat_title']['title_tag'] : null;
$ww_blkiat_kicker     = ( isset( $block_fields['ww_blkiat_kicker'] ) && '' !== $block_fields['ww_blkiat_kicker'] ) ? $block_fields['ww_blkiat_kicker'] : null;
$ww_blkiat_spcr_tp    = ( isset( $block_fields['ww_blkiat_spcr']['top_spacer'] ) && '' !== $block_fields['ww_blkiat_spcr']['top_spacer'] ) ? $block_fields['ww_blkiat_spcr']['top_spacer'] : null;
$ww_blkiat_spcr_btm   = ( isset( $block_fields['ww_blkiat_spcr']['bottom_spacer'] ) && '' !== $block_fields['ww_blkiat_spcr']['bottom_spacer'] ) ? $block_fields['ww_blkiat_spcr']['bottom_spacer'] : null;
$ww_blkiat_imgpositon = ( isset( $block_fields['ww_blkiat_imgpositon'] ) && '' !== $block_fields['ww_blkiat_imgpositon'] ) ? $block_fields['ww_blkiat_imgpositon'] : null;
$ww_blkiat_text       = ( isset( $block_fields['ww_blkiat_text'] ) && '' !== $block_fields['ww_blkiat_text'] ) ? $block_fields['ww_blkiat_text'] : null;
$ww_blkiat_image      = ( isset( $block_fields['ww_blkiat_image'] ) && '' !== $block_fields['ww_blkiat_image'] ) ? $block_fields['ww_blkiat_image'] : null;
$ww_blkiat_btn        = ( isset( $block_fields['ww_blkiat_btn'] ) && '' !== $block_fields['ww_blkiat_btn'] ) ? $block_fields['ww_blkiat_btn'] : null;
$ww_blkiat_link       = ( isset( $block_fields['ww_blkiat_link'] ) && '' !== $block_fields['ww_blkiat_link'] ) ? $block_fields['ww_blkiat_link'] : null;

//$ww_blkiat_dsgnvar    = ( isset( $block_fields['ww_blkiat_dsgnvar'] ) && '' !== $block_fields['ww_blkiat_dsgnvar'] ) ? $block_fields['ww_blkiat_dsgnvar'] : null;

if ( 'left' === $ww_blkiat_imgpositon ) {
	$ww_blkiat_imgpositon = 'image-at-left';
} else {
	$ww_blkiat_imgpositon = 'image-at-right';
}

?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_blkiat_spcr_tp ); ?>"> </div>
	
		<div class="iat image-alongside-text two-columns <?php echo esc_html( $ww_blkiat_imgpositon ); ?>">
			<div class="iat__image--area column">
			<?php if ( $ww_blkiat_image ) { ?>
						<div class="iat__image img-cover">
							<?php
							if ( $ww_blkiat_image ) {
								echo wp_get_attachment_image(
									$ww_blkiat_image,
									'thumb_700',
									false,
									array(
										'class' => '',
										'alt'   => get_post_meta( $ww_blkiat_image, '_wp_attachment_image_alt', true ),
										'title' => get_the_title( $ww_blkiat_image ),
									)
								);
							}
							?>
						</div>
					<?php } ?>

					</div>
					<div class="iat__content column">
						<?php if ( $ww_blkiat_title || $ww_blkiat_text || $ww_blkiat_btn ) { ?>
							<div class="section-head sh mb-0">
								<?php if ( $ww_blkiat_title ) { ?>
									<div class="section-head__heading">
										<?php if ( $ww_blkiat_kicker ) { ?>
										<div class="pre-header"><?php echo $ww_blkiat_kicker; ?></div>
										<?php } ?>
										<<?php echo esc_html( $ww_blkiat_title_tag ); ?> class="heading-2"><?php echo html_entity_decode( $ww_blkiat_title ); ?><?php echo '</' . esc_html( $ww_blkiat_title_tag ) . '>'; ?>										
									</div>
								<?php } ?>
								<?php if ( $ww_blkiat_text ) { ?>
									<div class="section-head__subheading">
										<?php echo html_entity_decode( $ww_blkiat_text ); ?>
									</div>
								<?php } ?>
								<?php if ( $ww_blkiat_btn || $ww_blkiat_link ) { ?>
								<div class="iat__content--btn
									<?php
									if ( $ww_blkiat_link && ! $ww_blkiat_btn ) {
										echo 'just-arrow-link';}
									?>
								">
									<?php
									if ( $ww_blkiat_btn ) {
										?>
										<?php echo build_acf_button( $ww_blkiat_btn, 'button has-arrow' ); ?><?php } ?>
									<?php
									if ( $ww_blkiat_link ) {
										?>
										<?php echo build_acf_button( $ww_blkiat_link, 'text-arrow' ); ?><?php } ?>
								</div>
							<?php } ?>
							</div>
						<?php } ?>
					</div>
			</div>
				
		<div class="glide-spacer <?php echo esc_html( $ww_blkiat_spcr_btm ); ?>"> </div>
</div>
