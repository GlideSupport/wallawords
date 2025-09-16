<?php
/**
 * Block Name: Testimonals
 *
 * The template for displaying the custom gutenberg block named Testimonals.
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
$ww_blktstimnls_title        = ( isset( $block_fields['ww_blktstimnls_title']['title'] ) && '' !== $block_fields['ww_blktstimnls_title']['title'] ) ? $block_fields['ww_blktstimnls_title']['title'] : null;
$ww_blktstimnls_title_tag    = ( isset( $block_fields['ww_blktstimnls_title']['title_tag'] ) && '' !== $block_fields['ww_blktstimnls_title']['title_tag'] ) ? $block_fields['ww_blktstimnls_title']['title_tag'] : null;
$ww_blktstimnls_spcr_tp      = ( isset( $block_fields['ww_blktstimnls_spcr']['top_spacer'] ) && '' !== $block_fields['ww_blktstimnls_spcr']['top_spacer'] ) ? $block_fields['ww_blktstimnls_spcr']['top_spacer'] : null;
$ww_blktstimnls_spcr_btm     = ( isset( $block_fields['ww_blktstimnls_spcr']['bottom_spacer'] ) && '' !== $block_fields['ww_blktstimnls_spcr']['bottom_spacer'] ) ? $block_fields['ww_blktstimnls_spcr']['bottom_spacer'] : null;
$ww_blktstimnls_testimonials = ( isset( $block_fields['ww_blktstimnls_testimonials'] ) && '' !== $block_fields['ww_blktstimnls_testimonials'] ) ? $block_fields['ww_blktstimnls_testimonials'] : null;
$testi_count = is_array($ww_blktstimnls_testimonials) ? count($ww_blktstimnls_testimonials) : 0;

?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_blktstimnls_spcr_tp ); ?>"> </div>
		<div class="testimonial">
			<?php if ( $ww_blktstimnls_title ) { ?>
				<div class="testimonial__title">
					<<?php echo esc_html( $ww_blktstimnls_title_tag ); ?> class="heading-2"><?php echo html_entity_decode( $ww_blktstimnls_title ); ?><?php echo '</' . esc_html( $ww_blktstimnls_title_tag ) . '>'; ?>
				<?php if ( $testi_count > 1 ) : ?>
					<div class="testimonial__swiper-controls">
						<div class="swiper-button-prev">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<path d="M21 12H3M3 12L10 5M3 12L10 19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							</div>
						<div class="swiper-button-next">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<path d="M3 12H21M21 12L14 5M21 12L14 19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
					</div>
				<?php endif; ?>
				</div>

			<?php } ?>
			<?php
			if ( $ww_blktstimnls_testimonials ) {?>
				<div class="testimonial__swiper">
						<div class="testimonials-slider swiper-wrapper owl-carousel owl-theme">
							<?php
							global $post;
							$amp_lp_select_testimnls = array();
							$amp_lp_select_testimnls = $ww_blktstimnls_testimonials;
							foreach ( $amp_lp_select_testimnls as $amp_lp_testi ) {
								// @codingStandardsIgnoreStart
								$post = $amp_lp_testi;
								// @codingStandardsIgnoreEnd
								setup_postdata( $post );
								$ww_post_id               = $post->ID;
								$post_fields               = get_fields( $ww_post_id );
								$ww_cpt_testi_designation = ( isset( $post_fields['ww_cpt_testi_designation'] ) && '' !== $post_fields['ww_cpt_testi_designation'] ) ? $post_fields['ww_cpt_testi_designation'] : null;
								$ww_cpt_testi_quote       = ( isset( $post_fields['ww_cpt_testi_quote'] ) && '' !== $post_fields['ww_cpt_testi_quote'] ) ? $post_fields['ww_cpt_testi_quote'] : null;
								?>
								<div class="swiper-slide testimonial__slide item">
									<div class="testimonial__slide--quote testimonial__quote">
										<?php if ( $ww_cpt_testi_quote ) { ?>
											<div class="testimonial__quote--text">
												<p><?php echo esc_html( $ww_cpt_testi_quote ); ?></p>
											</div>
										<?php } ?>
										<div class="client client__detail">
											<?php if ( has_post_thumbnail() ) { ?>
												<div class="client__image">
													<div class="client__image--inner">
														<?php
																the_post_thumbnail(
																	'thumb_300',
																	array(
																		'class' => '',
																		'alt'   => get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ),
																		'title' => get_the_title( get_post_thumbnail_id() ),
																	)
																);
														?>
													</div>
												</div>
											<?php } ?>
											<div class="client__name">
													<?php
													if ( $ww_cpt_testi_designation ) {
														?>
													<span><?php echo esc_html( $ww_cpt_testi_designation ); ?></span><?php } ?>
											</div>
										</div>
									</div>
								</div>
							
							<?php
							}
							wp_reset_postdata();
							?>
						</div>
				</div>
			<?php } ?>
		</div>
	<div class="glide-spacer <?php echo esc_html( $ww_blktstimnls_spcr_btm ); ?>"> </div>
</div>
