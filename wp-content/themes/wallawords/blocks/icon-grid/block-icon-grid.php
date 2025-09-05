<?php
/**
 * Block Name: Icon Grid
 *
 * The template for displaying the custom gutenberg block named Icon Grid.
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

$ww_blkicngrd_title     = ( isset( $block_fields['ww_blkicngrd_title']['title'] ) && '' !== $block_fields['ww_blkicngrd_title']['title'] ) ? $block_fields['ww_blkicngrd_title']['title'] : null;
$ww_blkicngrd_title_tag = ( isset( $block_fields['ww_blkicngrd_title']['title_tag'] ) && '' !== $block_fields['ww_blkicngrd_title']['title_tag'] ) ? $block_fields['ww_blkicngrd_title']['title_tag'] : null;
$ww_blkicngrd_spcr_tp   = ( isset( $block_fields['ww_blkicngrd_spcr']['top_spacer'] ) && '' !== $block_fields['ww_blkicngrd_spcr']['top_spacer'] ) ? $block_fields['ww_blkicngrd_spcr']['top_spacer'] : null;
$ww_blkicngrd_spcr_btm  = ( isset( $block_fields['ww_blkicngrd_spcr']['bottom_spacer'] ) && '' !== $block_fields['ww_blkicngrd_spcr']['bottom_spacer'] ) ? $block_fields['ww_blkicngrd_spcr']['bottom_spacer'] : null;
$ww_blkicngrd_text      = ( isset( $block_fields['ww_blkicngrd_text'] ) && '' !== $block_fields['ww_blkicngrd_text'] ) ? $block_fields['ww_blkicngrd_text'] : null;
$ww_blkicngrd_icons     = ( isset( $block_fields['ww_blkicngrd_icons'] ) && '' !== $block_fields['ww_blkicngrd_icons'] ) ? $block_fields['ww_blkicngrd_icons'] : null;
$ww_blkicngrd_button    = ( isset( $block_fields['ww_blkicngrd_button'] ) && '' !== $block_fields['ww_blkicngrd_button'] ) ? $block_fields['ww_blkicngrd_button'] : null;
$ww_blkicngrd_cntnt_var = ( isset( $block_fields['ww_blkicngrd_cntnt_var'] ) && '' !== $block_fields['ww_blkicngrd_cntnt_var'] ) ? $block_fields['ww_blkicngrd_cntnt_var'] : null;


?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_blkicngrd_spcr_tp ); ?>"> </div>
		<?php if ( 'center' === $ww_blkicngrd_cntnt_var ) { ?>
			<div class="icon-grid">
				<?php if ( $ww_blkicngrd_title || $ww_blkicngrd_text ) { ?>
					<div class="section-head sh ">
						<?php if ( $ww_blkicngrd_title ) { ?>
							<div class="section-head__heading ">
								<<?php echo esc_html( $ww_blkicngrd_title_tag ); ?> class="heading-2"><?php echo html_entity_decode( $ww_blkicngrd_title ); ?><?php echo '</' . esc_html( $ww_blkicngrd_title_tag ) . '>'; ?>
							</div>
						<?php } ?>
						<?php if ( $ww_blkicngrd_text ) { ?>
							<div class="section-head__subheading text-22 dblue-text">
								<?php echo html_entity_decode( $ww_blkicngrd_text ); ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<?php
				if ( $ww_blkicngrd_icons ) {
					$amp_num_icons          = count( $ww_blkicngrd_icons );
					$amp_grid_columns_class = ($amp_num_icons === 1) ? 'one-columns'
                        : (($amp_num_icons === 2) ? 'two-columns' : 'three-columns');
					?>
					<div class="icon-grid__cols <?php echo esc_html( $amp_grid_columns_class ); ?>">
						<?php
						foreach ( $ww_blkicngrd_icons as $amp_icns ) {
							$amp_icon    = ( isset( $amp_icns['icon'] ) ) ? $amp_icns['icon'] : null;
							$amp_heading = ( isset( $amp_icns['heading'] ) ) ? $amp_icns['heading'] : null;
							$amp_text    = ( isset( $amp_icns['text'] ) ) ? $amp_icns['text'] : null;
							?>
							<div class="icon-grid__col column">
								<?php if ( $amp_icon ) { ?>
									<div class="icon-grid__img">
											<?php
											echo wp_get_attachment_image(
												$amp_icon,
												'thumb_700',
												array(
													'class' => '',
													'alt' => get_post_meta( $amp_icon, '_wp_attachment_image_alt', true ),
													'title' => get_the_title( $amp_icon ),
												)
											);
											?>
									</div>
								<?php } ?>
								<?php if ( $amp_heading ) { ?>
									<div class="icon-grid__title">
										<h3 class="heading-3"><?php echo html_entity_decode( $amp_heading ); ?></h3>
									</div>
								<?php } ?>
								<?php if ( $amp_text ) { ?>
									<div class="icon-grid__text">
										<?php echo html_entity_decode( $amp_text ); ?>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if ( $ww_blkicngrd_button ) { ?>
					<div class="icon-grid__btn">
						<?php echo build_acf_button( $ww_blkicngrd_button, 'button has-arrow' ); ?>
					</div>
				<?php } ?>
			</div>
			<?php
		} else {
			?>
			<div class="icon-grid icon-grid-variation center-align">
				<?php if ( $ww_blkicngrd_title || $ww_blkicngrd_text ) { ?>
					<div class="section-head sh">
						<?php if ( $ww_blkicngrd_title ) { ?>
							<div class="section-head__heading">
								<<?php echo esc_html( $ww_blkicngrd_title_tag ); ?> class="heading-2"><?php echo html_entity_decode( $ww_blkicngrd_title ); ?><?php echo '</' . esc_html( $ww_blkicngrd_title_tag ) . '>'; ?>
							</div>
						<?php } ?>
						<?php if ( $ww_blkicngrd_text ) { ?>
							<div class="section-head__subheading text-22">
								<?php echo html_entity_decode( $ww_blkicngrd_text ); ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if ( $ww_blkicngrd_icons ) { ?>
					<div class="icon-grid__cols three-columns gap20">
						<?php
						foreach ( $ww_blkicngrd_icons as $amp_icns ) {
							$amp_icon    = ( isset( $amp_icns['icon'] ) ) ? $amp_icns['icon'] : null;
							$amp_heading = ( isset( $amp_icns['heading'] ) ) ? $amp_icns['heading'] : null;
							$amp_text    = ( isset( $amp_icns['text'] ) ) ? $amp_icns['text'] : null;
							?>
							<div class="icon-grid__col column">
								<?php if ( $amp_icon ) { ?>
									<div class="icon-grid__area d-flex">
										<div class="icon-grid__img d-flex">
											<?php
												echo wp_get_attachment_image(
													$amp_icon,
													'thumb_700',
													array(
														'class' => '',
														'alt'   => get_post_meta( $amp_icon, '_wp_attachment_image_alt', true ),
														'title' => get_the_title( $amp_icon ),
													)
												);
											?>
										</div>
									</div>
									<?php } ?>
									<?php if ( $amp_heading ) { ?>
										<div class="icon-grid__title">
											<h3 class="heading-3"><?php echo html_entity_decode( $amp_heading ); ?></h3>
										</div>
									<?php } ?>
									<?php if ( $amp_text ) { ?>
										<div class="icon-grid__text">
											<?php echo html_entity_decode( $amp_text ); ?>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
					</div>
				<?php } ?>
				<?php if ( $ww_blkicngrd_button ) { ?>
					<div class="icon-grid__btn">
						<?php echo build_acf_button( $ww_blkicngrd_button, 'button has-arrow' ); ?>
					</div>
				<?php } ?>
			</div>
			<?php } ?>
	<div class="glide-spacer <?php echo esc_html( $ww_blkicngrd_spcr_btm ); ?>"> </div>
</div>
