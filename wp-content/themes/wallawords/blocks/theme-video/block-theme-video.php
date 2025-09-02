<?php
/**
 * Block Name: Theme Video
 *
 * The template for displaying the custom gutenberg block named Theme Video.
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
$ww_blkthvdo_title       = ( isset( $block_fields['ww_blkthvdo_title']['title'] ) && '' !== $block_fields['ww_blkthvdo_title']['title'] ) ? $block_fields['ww_blkthvdo_title']['title'] : null;
$ww_blkthvdo_title_tag   = ( isset( $block_fields['ww_blkthvdo_title']['title_tag'] ) && '' !== $block_fields['ww_blkthvdo_title']['title_tag'] ) ? $block_fields['ww_blkthvdo_title']['title_tag'] : null;
$ww_blkthvdo_spcr_tp     = ( isset( $block_fields['ww_blkthvdo_spcr']['top_spacer'] ) && '' !== $block_fields['ww_blkthvdo_spcr']['top_spacer'] ) ? $block_fields['ww_blkthvdo_spcr']['top_spacer'] : null;
$ww_blkthvdo_spcr_btm    = ( isset( $block_fields['ww_blkthvdo_spcr']['bottom_spacer'] ) && '' !== $block_fields['ww_blkthvdo_spcr']['bottom_spacer'] ) ? $block_fields['ww_blkthvdo_spcr']['bottom_spacer'] : null;
$ww_blkthvdo_vid         = ( isset( $block_fields['ww_blkthvdo_vid'] ) && '' !== $block_fields['ww_blkthvdo_vid'] ) ? $block_fields['ww_blkthvdo_vid'] : null;
$ww_blkthvdo_vupld       = ( isset( $block_fields['ww_blkthvdo_vupld'] ) && '' !== $block_fields['ww_blkthvdo_vupld'] ) ? $block_fields['ww_blkthvdo_vupld'] : null;
$ww_blkthvdo_video_thumb = ( isset( $block_fields['ww_blkthvdo_video_thumb'] ) && '' !== $block_fields['ww_blkthvdo_video_thumb'] ) ? $block_fields['ww_blkthvdo_video_thumb'] : null;
$amp_src                  = wp_get_attachment_image_url( $ww_blkthvdo_video_thumb, 'thumb_1600' );

?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_blkthvdo_spcr_tp ); ?>"> </div>
		<div class="video-block-ctn ctn-gray border-20">
			<?php if ( $ww_blkthvdo_title ) { ?>
				<div class="section-head mb-0">
					<<?php echo esc_html( $ww_blkthvdo_title_tag ); ?> class="heading-2"><?php echo html_entity_decode( $ww_blkthvdo_title ); ?><?php echo '</' . esc_html( $ww_blkthvdo_title_tag ) . '>'; ?>
				</div>
			<?php } ?>
			<div class="video-block video-pop border-20"
				style="background-image: url(<?php echo esc_html( $amp_src ); ?>);">
				<?php if ( $ww_blkthvdo_vupld ) { ?>
				<video id="video" class="hero__video" loop="" muted="" preload="metadata" autoplay=""
					style="transition: opacity 2s ease 0s; opacity: 1;">
						<source class="video__source" type="video/mp4" src="<?php echo esc_url( $ww_blkthvdo_vupld ); ?>">
						<p>Your browser doesn't support HTML5 video. Here is a <a href="<?php echo esc_url( $ww_blkthvdo_vupld ); ?>">link to the video</a> instead.</p>
				</video>
				<?php } ?>
				<?php if ( $ww_blkthvdo_vid ) { ?>
					<iframe width="1334" height="545" src="https://www.youtube.com/embed/<?php echo esc_html( $ww_blkthvdo_vid ); ?>"
						title="YouTube video player" frameborder="0"
						allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
						allowfullscreen>
					</iframe>
				<?php } ?>
				<span class="popup-btn">
					<span class="play-btn" id="play-btn">
						<svg xmlns="http://www.w3.org/2000/svg" width="124" height="124" viewBox="0 0 124 124" fill="none">
  <circle cx="62" cy="62" r="62" fill="white" fill-opacity="0.1"/>
  <path d="M78.1465 59.3982C80.1465 60.5529 80.1465 63.4396 78.1465 64.5943L56.1465 77.296C54.1465 78.4507 51.6465 77.0074 51.6465 74.698L51.6465 49.2946C51.6465 46.9852 54.1465 45.5418 56.1465 46.6965L78.1465 59.3982Z" fill="white"/>
</svg>
</span> </span>
			</div>
		</div>
	<div class="glide-spacer <?php echo esc_html( $ww_blkthvdo_spcr_btm ); ?>"> </div>
</div>
