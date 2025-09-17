<?php
/**
 * Block Name: Media Alongside Text
 *
 * The template for displaying the custom gutenberg block named Media Alongside Text.
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
$bst_var_blk_mat_title      = ( isset( $block_fields['bst_var_blk_mat_title']['title'] ) && '' !== $block_fields['bst_var_blk_mat_title']['title'] ) ? $block_fields['bst_var_blk_mat_title']['title'] : null;
$bst_var_blk_mat_title_tag  = ( isset( $block_fields['bst_var_blk_mat_title']['title_tag'] ) && '' !== $block_fields['bst_var_blk_mat_title']['title_tag'] ) ? $block_fields['bst_var_blk_mat_title']['title_tag'] : null;
$bst_var_blk_mat_kicker     = ( isset( $block_fields['bst_var_blk_mat_kicker'] ) && '' !== $block_fields['bst_var_blk_mat_kicker'] ) ? $block_fields['bst_var_blk_mat_kicker'] : null;
$bst_var_blk_mat_img_location = ( isset( $block_fields['bst_var_blk_mat_img_location'] ) && '' !== $block_fields['bst_var_blk_mat_img_location'] ) ? $block_fields['bst_var_blk_mat_img_location'] : null;
$bst_var_blk_mat_mediatype = ( isset( $block_fields['bst_var_blk_mat_mediatype'] ) && '' !== $block_fields['bst_var_blk_mat_mediatype'] ) ? $block_fields['bst_var_blk_mat_mediatype'] : 'image';
$bst_var_blk_mat_text       = ( isset( $block_fields['bst_var_blk_mat_text'] ) && '' !== $block_fields['bst_var_blk_mat_text'] ) ? $block_fields['bst_var_blk_mat_text'] : null;
$bst_var_blk_mat_image      = ( isset( $block_fields['bst_var_blk_mat_image'] ) && '' !== $block_fields['bst_var_blk_mat_image'] ) ? $block_fields['bst_var_blk_mat_image'] : null;
$bst_var_blk_mat_button        = ( isset( $block_fields['bst_var_blk_mat_button'] ) && '' !== $block_fields['bst_var_blk_mat_button'] ) ? $block_fields['bst_var_blk_mat_button'] : null;
$bst_var_blk_mat_video = ( isset( $block_fields['bst_var_blk_mat_video'] ) && ! empty( $block_fields['bst_var_blk_mat_video'] ) ) ? $block_fields['bst_var_blk_mat_video'] : null;

$video_type       = $bst_var_blk_mat_video['choose_video_type'] ?? '';
$youtube_id       = $bst_var_blk_mat_video['youtube_video_embed_id'] ?? '';
$vimeo_id         = $bst_var_blk_mat_video['vimeo_video_embed_id'] ?? '';
$video_poster     = $bst_var_blk_mat_video['video_poster'] ?? '';
$uploaded_video   = $bst_var_blk_mat_video['video_file'] ?? '';
$modal_video_embed = '';

switch ( strtolower( $video_type ) ) {
    case 'youtube':
        if ( $youtube_id ) {
            $src = 'https://www.youtube.com/embed/' . esc_attr( $youtube_id ) . '?autoplay=1&mute=1&rel=0&playsinline=1';
            $modal_video_embed = '<iframe src="' . esc_url( $src ) . '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
        }
        break;

    case 'vimeo':
        if ( $vimeo_id ) {
            $src = 'https://player.vimeo.com/video/' . esc_attr( $vimeo_id ) . '?autoplay=1&muted=1&title=0&byline=0&portrait=0';
            $modal_video_embed = '<iframe src="' . esc_url( $src ) . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
        }
        break;

    case 'upload':
        if ( $uploaded_video ) {
            $modal_video_embed = '
                <video class="videos" autoplay muted  playsinline controls poster="' . esc_url( $video_poster ) . '">
                    <source src="' . esc_url( $uploaded_video ) . '" type="video/mp4">
                    Your browser does not support the video tag.
                </video>';
        }
        break;
}
if ( 'left' === $bst_var_blk_mat_img_location ) {
	$bst_var_blk_mat_img_location = 'image-at-left';
} else {
	$bst_var_blk_mat_img_location = 'image-at-right';
}

?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	
		<div class="iat image-alongside-text two-columns <?php echo esc_html( $bst_var_blk_mat_img_location ); ?>">
			<div class="iat__image--area column">
				
				<?php if ( $bst_var_blk_mat_mediatype === 'image' ) { ?>
					<?php if ( $bst_var_blk_mat_image ) { ?>
						<div class="iat__image img-cover">
							<?php
							echo wp_get_attachment_image(
								$bst_var_blk_mat_image,
								'thumb_700',
								false,
								array(
									'class' => '',
									'alt'   => get_post_meta( $bst_var_blk_mat_image, '_wp_attachment_image_alt', true ),
									'title' => get_the_title( $bst_var_blk_mat_image )
								)
							);
							?>
						</div>
					<?php } ?>
				<?php } else { ?>
					<?php if ($modal_video_embed): ?>
						<div class="iat__image img-cover">
							<img src="<?php echo esc_url( $video_poster ); ?>"  alt="<?php echo esc_attr( get_the_title() ); ?> Video Poster"  title="<?php echo esc_attr( get_the_title() ); ?> Video" 
							loading="lazy"  width="1280" height="720"  />

                        <a href="#<?php echo esc_attr( $amp_id ); ?>-modal" class="play-btn" data-lity>
							<svg xmlns="http://www.w3.org/2000/svg" width="124" height="124" viewBox="0 0 124 124" fill="none">
								<circle cx="62" cy="62" r="62" fill="white" fill-opacity="0.1"/>
								<path d="M78.1465 59.3982C80.1465 60.5529 80.1465 63.4396 78.1465 64.5943L56.1465 77.296C54.1465 78.4507 51.6465 77.0074 51.6465 74.698V49.2946C51.6465 46.9852 54.1465 45.5418 56.1465 46.6965L78.1465 59.3982Z" fill="white"/>
							</svg>
						</a>
						</div>
                    <?php endif; ?>
				<?php } ?>


					</div>
					<div class="iat__content column">
						<?php if ( $bst_var_blk_mat_title || $bst_var_blk_mat_text || $bst_var_blk_mat_button ) { ?>
							<div class="section-head sh mb-0">
								<?php if ( $bst_var_blk_mat_title ) { ?>
									<div class="section-head__heading">
										<?php if ( $bst_var_blk_mat_kicker ) { ?>
										<div class="pre-header"><?php echo $bst_var_blk_mat_kicker; ?></div>
										<?php } ?>
										<<?php echo esc_html( $bst_var_blk_mat_title_tag ); ?> class="heading-2"><?php echo html_entity_decode( $bst_var_blk_mat_title ); ?><?php echo '</' . esc_html( $bst_var_blk_mat_title_tag ) . '>'; ?>										
									</div>
								<?php } ?>
								<?php if ( $bst_var_blk_mat_text ) { ?>
									<div class="section-head__subheading">
										<?php echo html_entity_decode( $bst_var_blk_mat_text ); ?>
									</div>
								<?php } ?>
								<?php if ( $bst_var_blk_mat_button) { ?>
								<div class="iat__content--btn">
									<?php if ( $bst_var_blk_mat_button ) {?>
										<?php echo build_acf_button( $bst_var_blk_mat_button, 'button has-arrow' ); ?>
									<?php } ?>
								</div>
							<?php } ?>
							</div>
						<?php } ?>
					</div>
			</div>
</div>

<?php if ( $modal_video_embed ) : ?>
	<div id="<?php echo esc_attr( $amp_id ); ?>-modal" class="lity-hide popup-block">
		<div class="popup-video popup-block-design">
			<div class="video-play">
				<?php echo $modal_video_embed; ?>
			</div>
		</div>
	</div>
<?php endif; ?>