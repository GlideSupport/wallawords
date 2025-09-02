<?php
/**
 * Block Name: FAQs
 *
 * The template for displaying the custom gutenberg block named FAQs.
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

$ww_faq_title     = ( isset( $block_fields['ww_faq_title']['title'] ) && '' !== $block_fields['ww_faq_title']['title'] ) ? $block_fields['ww_faq_title']['title'] : null;
$ww_faq_title_tag = ( isset( $block_fields['ww_faq_title']['title_tag'] ) && '' !== $block_fields['ww_faq_title']['title_tag'] ) ? $block_fields['ww_faq_title']['title_tag'] : null;
$ww_faq_faqs      = ( isset( $block_fields['ww_faq_faqs'] ) && '' !== $block_fields['ww_faq_faqs'] ) ? $block_fields['ww_faq_faqs'] : null;
$ww_faq_txt       = ( isset( $block_fields['ww_faq_txt'] ) && '' !== $block_fields['ww_faq_txt'] ) ? $block_fields['ww_faq_txt'] : null;
$ww_faq_btxt      = ( isset( $block_fields['ww_faq_btxt'] ) && '' !== $block_fields['ww_faq_btxt'] ) ? $block_fields['ww_faq_btxt'] : null;
$ww_faq_sp_tp     = ( isset( $block_fields['ww_faq_sp']['top_spacer'] ) && '' !== $block_fields['ww_faq_sp']['top_spacer'] ) ? $block_fields['ww_faq_sp']['top_spacer'] : null;
$ww_faq_sp_btm    = ( isset( $block_fields['ww_faq_sp']['bottom_spacer'] ) && '' !== $block_fields['ww_faq_sp']['bottom_spacer'] ) ? $block_fields['ww_faq_sp']['bottom_spacer'] : null;


?>
<div id="<?php echo esc_html( $ww_id ); ?>" class="<?php echo esc_html( $ww_align_class . ' ' . $ww_class_name . ' ' . $ww_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_faq_sp_tp ); ?>"> </div>
		<div class="faq-block">
			<?php if ( $ww_faq_title || $ww_faq_txt ) { ?>
					<div class="faq-heading">
						<div class="pre-header">FAQS</div>
						<<?php echo esc_html( $ww_faq_title_tag ); ?> class="heading-2"><?php echo html_entity_decode( $ww_faq_title ); ?><?php echo '</' . esc_html( $ww_faq_title_tag ) . '>'; ?>
					</div>
					<?php if ( $ww_faq_txt ) { ?>
						<div class="faq-text">
							<?php echo html_entity_decode( $ww_faq_txt ); ?>
						</div>
					<?php } ?>
				<?php
			}
			if ( $ww_faq_faqs ) {
				?>
						<?php
						foreach ( $ww_faq_faqs as $ww_faq ) {
							$ww_question = ( isset( $ww_faq['question'] ) ) ? $ww_faq['question'] : null;
							$ww_hash_id  = ( isset( $ww_faq['hash_id'] ) ) ? $ww_faq['hash_id'] : null;
							$ww_answer   = ( isset( $ww_faq['answer'] ) ) ? $ww_faq['answer'] : null;
							?>
								<div class="faq__single">
								<?php if ( $ww_question ) { ?>
										<span><h2><?php echo esc_html( $ww_question ); ?></h2></span>
									<?php } ?>
									<?php if ( $ww_hash_id ) { ?>
										<div class="jump-link" id="<?php echo html_entity_decode( $ww_hash_id ); ?>"> </div>
									<?php } ?>
									<?php if ( $ww_answer ) { ?>
										<div class="faq__content" style="display: none;">
											<?php echo html_entity_decode( $ww_answer ); ?>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
				<?php } ?>
		</div>
		<?php if ( $ww_faq_btxt ) { ?>
			<div class="section-bottom-text">
				<?php echo html_entity_decode( $ww_faq_btxt ); ?>
			</div>
		<?php } ?>
	<div class="glide-spacer <?php echo esc_html( $ww_faq_sp_tp ); ?>"> </div>
</div>
