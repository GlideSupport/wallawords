<?php
/**
 * Block Name: Checklist
 *
 * The template for displaying the custom gutenberg block named Checklist.
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

$ww_blkchklst_title       = ( isset( $block_fields['ww_blkchklst_title']['title'] ) && '' !== $block_fields['ww_blkchklst_title']['title'] ) ? $block_fields['ww_blkchklst_title']['title'] : null;
$ww_blkchklst_title_tag   = ( isset( $block_fields['ww_blkchklst_title']['title_tag'] ) && '' !== $block_fields['ww_blkchklst_title']['title_tag'] ) ? $block_fields['ww_blkchklst_title']['title_tag'] : null;
$ww_blkchklst_spacers_tp  = ( isset( $block_fields['ww_blkchklst_spacers']['top_spacer'] ) && '' !== $block_fields['ww_blkchklst_spacers']['top_spacer'] ) ? $block_fields['ww_blkchklst_spacers']['top_spacer'] : null;
$ww_blkchklst_spacers_btm = ( isset( $block_fields['ww_blkchklst_spacers']['bottom_spacer'] ) && '' !== $block_fields['ww_blkchklst_spacers']['bottom_spacer'] ) ? $block_fields['ww_blkchklst_spacers']['bottom_spacer'] : null;
$ww_blkchklst_text        = ( isset( $block_fields['ww_blkchklst_text'] ) && '' !== $block_fields['ww_blkchklst_text'] ) ? $block_fields['ww_blkchklst_text'] : null;
$ww_blkchklst_chcklst     = ( isset( $block_fields['ww_blkchklst_chcklst'] ) && '' !== $block_fields['ww_blkchklst_chcklst'] ) ? $block_fields['ww_blkchklst_chcklst'] : null;

?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_blkchklst_spacers_tp ); ?>"> </div>
		<div class="checklist-section">
			<?php if ( $ww_blkchklst_title || $ww_blkchklst_text ) { ?>
				<div class="section-head">
					<?php if ( $ww_blkchklst_title ) { ?>
						<div class="section-head__heading">
							<<?php echo esc_html( $ww_blkchklst_title_tag ); ?> class="heading-2"><?php echo html_entity_decode( $ww_blkchklst_title ); ?><?php echo '</' . esc_html( $ww_blkchklst_title_tag ) . '>'; ?>
						</div>
					<?php } ?>
					<?php if ( $ww_blkchklst_text ) { ?>
						<div class="section-head__subheading text-22 mt-42">
							<?php echo html_entity_decode( $ww_blkchklst_text ); ?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
				<?php if ( $ww_blkchklst_chcklst ) { ?>
					<div class="checklist-items">
						<ul>
							<?php
							foreach ( $ww_blkchklst_chcklst as $amp_value ) {
								$amp_text = ( isset( $amp_value['text'] ) ) ? $amp_value['text'] : null;
								?>
								<?php
								if ( $amp_text ) {
									?>
									<li><?php echo html_entity_decode( $amp_text ); ?></li><?php } ?>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
		</div>
	<div class="glide-spacer <?php echo esc_html( $ww_blkchklst_spacers_btm ); ?>"> </div>
</div>
