<?php
/**
 * Block Name: Numbered List
 *
 * The template for displaying the custom gutenberg block named Numbered List.
 *
 * @link https://www.advancedcustomfields.com/resources/blocks/
 *
 * @package Walla
 * @since 1.0.0
 */

 $pageTemplate = basename(get_page_template(get_the_ID()));

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

$ww_blknmbrlst_title       = ( isset( $block_fields['ww_blknmbrlst_title']['title'] ) && '' !== $block_fields['ww_blknmbrlst_title']['title'] ) ? $block_fields['ww_blknmbrlst_title']['title'] : null;
$ww_blknmbrlst_title_tag   = ( isset( $block_fields['ww_blknmbrlst_title']['title_tag'] ) && '' !== $block_fields['ww_blknmbrlst_title']['title_tag'] ) ? $block_fields['ww_blknmbrlst_title']['title_tag'] : null;
$ww_blknmbrlst_spcr_tp     = ( isset( $block_fields['ww_blknmbrlst_spcr']['top_spacer'] ) && '' !== $block_fields['ww_blknmbrlst_spcr']['top_spacer'] ) ? $block_fields['ww_blknmbrlst_spcr']['top_spacer'] : null;
$ww_blknmbrlst_spcr_btm    = ( isset( $block_fields['ww_blknmbrlst_spcr']['bottom_spacer'] ) && '' !== $block_fields['ww_blknmbrlst_spcr']['bottom_spacer'] ) ? $block_fields['ww_blknmbrlst_spcr']['bottom_spacer'] : null;
$ww_blknmbrlst_text        = ( isset( $block_fields['ww_blknmbrlst_text'] ) && '' !== $block_fields['ww_blknmbrlst_text'] ) ? $block_fields['ww_blknmbrlst_text'] : null;
$ww_blknmbrlst_nmbr_cntnt  = ( isset( $block_fields['ww_blknmbrlst_nmbr_cntnt'] ) && '' !== $block_fields['ww_blknmbrlst_nmbr_cntnt'] ) ? $block_fields['ww_blknmbrlst_nmbr_cntnt'] : null;
$ww_blknmbrlst_nmbr_dsgvar = ( isset( $block_fields['ww_blknmbrlst_nmbr_dsgvar'] ) && '' !== $block_fields['ww_blknmbrlst_nmbr_dsgvar'] ) ? $block_fields['ww_blknmbrlst_nmbr_dsgvar'] : null;
?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_blknmbrlst_spcr_tp ); ?>"> </div>
		<?php if ( 'default' === $ww_blknmbrlst_nmbr_dsgvar ) { ?>
			<div class="number-list">
				<?php if ( $ww_blknmbrlst_title || $ww_blknmbrlst_text ) { ?>
					<div class="section-head section-head-variation">
						<?php if ( $ww_blknmbrlst_title ) { ?>
							<div class="section-head__heading">
								<<?php echo esc_html( $ww_blknmbrlst_title_tag ); ?> class="heading-2"><?php echo html_entity_decode( $ww_blknmbrlst_title ); ?><?php echo '</' . esc_html( $ww_blknmbrlst_title_tag ) . '>'; ?>
							</div>
						<?php } ?>
						<?php if ( $ww_blknmbrlst_text ) { ?>
							<div class="section-head__subheading text-22">
								<?php echo html_entity_decode( $ww_blknmbrlst_text ); ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			<?php if ( $ww_blknmbrlst_nmbr_cntnt ) { ?>
				<div class="number-list__items">

					<div class="center-align number-list-slider three-slides owl-carousel owl-theme
					<?php
					if ( count( $ww_blknmbrlst_nmbr_cntnt ) < 4 ) {
						echo 'not-slider'; }
					?>
							">
							<?php
							$amp_count = 0;
							foreach ( $ww_blknmbrlst_nmbr_cntnt as $amp_value ) {
								$amp_heading = ( isset( $amp_value['heading'] ) ) ? $amp_value['heading'] : null;
								$amp_text    = ( isset( $amp_value['text'] ) ) ? $amp_value['text'] : null;
								$amp_link    = ( isset( $amp_value['link'] ) ) ? $amp_value['link'] : null;
								$amp_count ++;
								?>
							<div class="item">
								<div class="number-list__icon d-flex"><?php echo esc_html( $amp_count ); ?></div>
								<?php if ( $amp_heading || $amp_text || $amp_link ) { ?>
									<div class="number-list__content">
										<?php if ( $amp_heading ) { ?>
											<div class="number-list__title">
												<h3 class="heading-3"><?php echo html_entity_decode( $amp_heading ); ?></h3>
											</div>
										<?php } ?>
										<?php if ( $amp_text ) { ?>
											<div class="number-list__text">
												<p><?php echo html_entity_decode( $amp_text ); ?></p>
											</div>
										<?php } ?>
										<?php if ( $amp_link ) { ?>
											<div class="number-list__link">
												<?php if($pageTemplate == 'template-landing.php'):
												 echo build_acf_button( $amp_link, 'button has-arrow' );
												else:
												 echo build_acf_button( $amp_link, 'learn-more' ); 
												endif; ?>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>

			</div>
		<?php } else { ?>
			<div class="number-list">
				<?php if ( $ww_blknmbrlst_title || $ww_blknmbrlst_text ) { ?>
					<div class="section-head section-head-variation">
						<?php if ( $ww_blknmbrlst_title ) { ?>
							<div class="section-head__heading">
								<<?php echo esc_html( $ww_blknmbrlst_title_tag ); ?> class="heading-2"><?php echo html_entity_decode( $ww_blknmbrlst_title ); ?><?php echo '</' . esc_html( $ww_blknmbrlst_title_tag ) . '>'; ?>
							</div>
						<?php } ?>
						<?php if ( $ww_blknmbrlst_text ) { ?>
							<div class="section-head__subheading text-22">
								<?php echo html_entity_decode( $ww_blknmbrlst_text ); ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if ( $ww_blknmbrlst_nmbr_cntnt ) { ?>
					<div class="number-list__items ">

							<div class="five-slides number-list-slider center-align owl-carousel owl-theme
							<?php
							if ( count( $ww_blknmbrlst_nmbr_cntnt ) < 6 ) {
								echo 'not-slider'; }
							?> <?php
							if ( count( $ww_blknmbrlst_nmbr_cntnt ) < 5 ) {
								echo 'center-slider'; }
							?>
							<?php
							if ( count( $ww_blknmbrlst_nmbr_cntnt ) == 4 ) {
								echo 'four-slider'; }
							?>
							">
								<?php
								$amp_count = 0;
								foreach ( $ww_blknmbrlst_nmbr_cntnt as $amp_value ) {
									$amp_heading = ( isset( $amp_value['heading'] ) ) ? $amp_value['heading'] : null;
									$amp_text    = ( isset( $amp_value['text'] ) ) ? $amp_value['text'] : null;
									$amp_link    = ( isset( $amp_value['link'] ) ) ? $amp_value['link'] : null;
									$amp_count ++;
									?>
									<div class="item">
										<div class="number-list__icon d-flex"><?php echo esc_html( $amp_count ); ?></div>
											<div class="number-list__content">
												<?php if ( $amp_heading ) { ?>
													<div class="number-list__title">
														<h3 class="heading-6"><?php echo html_entity_decode( $amp_heading ); ?></h3>
													</div>
												<?php } ?>
												<?php if ( $amp_text ) { ?>
													<div class="number-list__text">
														<p><?php echo html_entity_decode( $amp_text ); ?></p>
													</div>
												<?php } ?>
												<?php if ( $amp_link ) { ?>
													<div class="number-list__link">
														<?php if($pageTemplate == 'template-landing.php'):
														echo build_acf_button( $amp_link, 'button has-arrow' );
														else:
														echo build_acf_button( $amp_link, 'learn-more' ); 
														endif; ?>
													</div>
												<?php } ?>
											</div>
									</div>
								<?php } ?>
							</div>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	<div class="glide-spacer <?php echo esc_html( $ww_blknmbrlst_spcr_btm ); ?>"> </div>
</div>
