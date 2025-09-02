<?php
/**
 * Block Name: Image Gallery
 *
 * The template for displaying the custom gutenberg block named Image Gallery.
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

$ww_blkimgglry_title       = ( isset( $block_fields['ww_blkimgglry_title']['title'] ) && '' !== $block_fields['ww_blkimgglry_title']['title'] ) ? $block_fields['ww_blkimgglry_title']['title'] : null;
$ww_blkimgglry_title_tag   = ( isset( $block_fields['ww_blkimgglry_title']['title_tag'] ) && '' !== $block_fields['ww_blkimgglry_title']['title_tag'] ) ? $block_fields['ww_blkimgglry_title']['title_tag'] : null;
$ww_blkimgglry_spacers_tp  = ( isset( $block_fields['ww_blkimgglry_spacers']['top_spacer'] ) && '' !== $block_fields['ww_blkimgglry_spacers']['top_spacer'] ) ? $block_fields['ww_blkimgglry_spacers']['top_spacer'] : null;
$ww_blkimgglry_spacers_btm = ( isset( $block_fields['ww_blkimgglry_spacers']['bottom_spacer'] ) && '' !== $block_fields['ww_blkimgglry_spacers']['bottom_spacer'] ) ? $block_fields['ww_blkimgglry_spacers']['bottom_spacer'] : null;
$ww_blkimgglry_text        = ( isset( $block_fields['ww_blkimgglry_text'] ) && '' !== $block_fields['ww_blkimgglry_text'] ) ? $block_fields['ww_blkimgglry_text'] : null;
$ww_blkimgglry_imgglry     = ( isset( $block_fields['ww_blkimgglry_imgglry'] ) && '' !== $block_fields['ww_blkimgglry_imgglry'] ) ? $block_fields['ww_blkimgglry_imgglry'] : null;
?>
<div id="<?php echo esc_html( $ww_id ); ?>" class="<?php echo esc_html( $ww_align_class . ' ' . $ww_class_name . ' ' . $ww_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_blkimgglry_spacers_tp ); ?>"> </div>
		<div class="gallery gallery-block gallery__with-bg">
			<div class="gallery__head ctn-gradient">
				<div class="gallery__content">
					<div class="section-head center-align">
						<?php if ( $ww_blkimgglry_title ) { ?>
							<div class="section-head__heading">
								<<?php echo esc_html( $ww_blkimgglry_title_tag ); ?>><?php echo html_entity_decode( $ww_blkimgglry_title ); ?></<?php echo esc_html( $ww_blkimgglry_title_tag ); ?>>
							</div>
						<?php } ?>
						<?php if ( $ww_blkimgglry_text ) { ?>
							<div class="section-head__subheading text-22">
								<?php echo html_entity_decode( $ww_blkimgglry_text ); ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php if ( $ww_blkimgglry_imgglry ) { ?>
				<div class="gallery__swiper swiper mySwiper">
					<div class="swiper-wrapper">
						<?php
						foreach ( $ww_blkimgglry_imgglry as $ww_key => $ww_value ) {
							$ww_image = ( '' !== $ww_value['image'] && isset( $ww_value['image'] ) ) ? $ww_value['image'] : null;
							$ww_key++;
							?>
							<div class="swiper-slide gallery__slide<?php if ( count( $ww_image ) > 1 ) : echo ' two-images'; endif;?>">
							<?php
								foreach ( $ww_image as $ww_img ) {
									$ww_image = ( '' !== $ww_img ['image'] && isset( $ww_img['image'] ) ) ? $ww_img['image'] : null;
									?>
									<?php if ( $ww_image ) { 
										$imgVal = wp_get_attachment_image_src($ww_image,'thumb_500');?>

										<div class="gallery__slide--image"<?php if(is_array($imgVal)): echo ' style="background-image: url(\''.$imgVal[0].'\');"';	endif;?>>											
										
										<?php
										/*
										//prev script output <img>
										echo wp_get_attachment_image(
											$ww_image,
											'thumb_500',
											false,
											array(
												'class' => '',
												'alt' => get_post_meta( $ww_image, '_wp_attachment_image_alt', true ),
												'title' => get_the_title( $ww_image ),
											)
										); */
										?>
									</div>
									<?php }	?>
								<?php } ?>
						</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
	<div class="glide-spacer <?php echo esc_html( $ww_blkimgglry_spacers_btm ); ?>"> </div>
</div>
