<?php
/**
 * Block Name: Hero
 *
 * The template for displaying the custom gutenberg block named Hero.
 *
 * @link https://www.advancedcustomfields.com/resources/blocks/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

list( $bst_block_id, $bst_block_fields ) = BaseTheme::defaults( $block['id'] );
list($bst_var_post_id, $bst_fields, $bst_option_fields) = BaseTheme::defaults(get_the_ID());


// Set the block name for it's ID & class from it's file name.
$bst_block_name   = $block['name'];
$bst_block_name   = str_replace( 'acf/', '', $bst_block_name );
$bst_block_styles = BaseTheme::convert_to_css( $block );
// Set the preview thumbnail for this block for gutenberg editor view.
if ( isset( $block['data']['preview'] ) ) {
	echo '<img src="' . esc_url( get_template_directory_uri() . '/blocks/' . $block_name . '/' . $block['data']['preview'] ) . '" style="width:100%; height:auto;">';
}
// create align class ("alignwide") from block setting ("wide").
$bst_var_align_class = $block['align'] ? 'align' . $block['align'] : '';

// Get the class name for the block to be used for it.
$bst_var_class_name = ( isset( $block['className'] ) ) ? $block['className'] : null;

// Making the unique ID for the block.
$bst_block_html_id = 'block-' . $bst_block_name . '-' . $block['id'];
if( !empty($block['anchor']) ) {
	$bst_block_html_id = $block['anchor'];
}

// Making the unique ID for the block.
if ( $block['name'] ) {
	$bst_block_name = $block['name'];
	$bst_block_name = str_replace( '/', '-', $bst_block_name );
	$bst_var_name   = 'block-' . $bst_block_name;
}

// Block variables.
$ww_hero_choose_variation = $bst_block_fields['ww_hero_choose_variation'] ?? 'home';
$ww_hero_headline     = ( isset( $bst_block_fields['ww_hero_headline']['title'] ) && '' !== $bst_block_fields['ww_hero_headline']['title'] ) ? $bst_block_fields['ww_hero_headline']['title'] : null;
$ww_hero_headline_tag = ( isset( $bst_block_fields['ww_hero_headline']['title_tag'] ) && '' !== $bst_block_fields['ww_hero_headline']['title_tag'] ) ? $bst_block_fields['ww_hero_headline']['title_tag'] : null;
$ww_hero_wysiwyg = $bst_block_fields['ww_hero_wysiwyg'] ?? null;
$ww_hero_button = $bst_block_fields['ww_hero_button'] ?? null;
$ww_hero_bg_image = $bst_block_fields['ww_hero_bg_image'] ?? null;



?>

<div id="<?php echo esc_html($bst_block_html_id); ?>" class="<?php echo esc_html($bst_var_align_class . ' ' . $bst_var_class_name . ' ' . $bst_var_name . ' '. $ww_hero_choose_variation); ?> block-<?php echo esc_html($bst_block_name); ?>" style="<?php echo esc_html($bst_block_styles); ?> ">
	<?php 
	if($ww_hero_choose_variation == 'home'): ?> 
	<section id="hero-section" class="hero-section hero-section-default hero-alongside-pattern">
		<div  class="hero-default center-align ctn-760"  <?php if (!empty($ww_hero_bg_image)) : ?> style="background-image: url('<?php echo esc_url( wp_get_attachment_image_url($ww_hero_bg_image, 'full') ); ?>');"
		<?php endif; ?>>
			<div class="wrapper">
				<div class="gl-s200"></div>
				<div class="hero-alongside-block">
					<div class="banner-text">
						<?php if ( $ww_hero_headline ) { ?>
							<div class="section-head__heading ">
								<<?php echo esc_html( $ww_hero_headline_tag ); ?> class="heading"><?php echo html_entity_decode( $ww_hero_headline ); ?><?php echo '</' . esc_html( $ww_hero_headline_tag ) . '>'; ?>
							</div>
						<?php } ?>

						<?php if (!empty($ww_hero_wysiwyg)) : ?>
							<?php echo html_entity_decode($ww_hero_wysiwyg); ?>
						<?php endif; ?>

						<?php if (!empty($ww_hero_button)) : ?>
							<div class="block-btn">
								<?php echo BaseTheme::button($ww_hero_button, 'button'); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="gl-s200"></div>
			</div>
		</div>
	</section>
	<?php
	endif;
	if($ww_hero_choose_variation == 'text-only'): ?>
		<section id="hero-section" class="hero-section hero-section-default hero-alongside-pattern">
			<div class="hero-default center-align ctn-760">
				<div class="wrapper">
					<div class="gl-s200"></div>
					<div class="hero-alongside-block">
						<div class="banner-text">
							<?php if ( $ww_hero_headline ) { ?>
								<div class="section-head__heading ">
									<<?php echo esc_html( $ww_hero_headline_tag ); ?> class="heading"><?php echo html_entity_decode( $ww_hero_headline ); ?><?php echo '</' . esc_html( $ww_hero_headline_tag ) . '>'; ?>
								</div>
							<?php } ?>

							<?php if (!empty($ww_hero_wysiwyg)) : ?>
								<?php echo html_entity_decode($ww_hero_wysiwyg); ?>
							<?php endif; ?>

							<?php if (!empty($ww_hero_button)) : ?>
								<div class="block-btn">
									<?php echo BaseTheme::button($ww_hero_button, 'button'); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="gl-s200"></div>
				</div>
			</div>
		</section>
	<?php endif; ?>
</div>
