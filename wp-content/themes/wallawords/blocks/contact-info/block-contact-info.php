<?php
/**
 * Block Name: Contact Info
 *
 * The template for displaying the custom gutenberg block named Contact Info.
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
$ww_blkcntntinfo_spcr_tp  = ( isset( $block_fields['ww_blkcntntinfo_spcr']['top_spacer'] ) && '' !== $block_fields['ww_blkcntntinfo_spcr']['top_spacer'] ) ? $block_fields['ww_blkcntntinfo_spcr']['top_spacer'] : null;
$ww_blkcntntinfo_spcr_btm = ( isset( $block_fields['ww_blkcntntinfo_spcr']['bottom_spacer'] ) && '' !== $block_fields['ww_blkcntntinfo_spcr']['bottom_spacer'] ) ? $block_fields['ww_blkcntntinfo_spcr']['bottom_spacer'] : null;
$ww_blkcntntinfo_col_one  = ( isset( $block_fields['ww_blkcntntinfo_col_one'] ) && '' !== $block_fields['ww_blkcntntinfo_col_one'] ) ? $block_fields['ww_blkcntntinfo_col_one'] : null;
$ww_blkcntntinfo_col_tw   = ( isset( $block_fields['ww_blkcntntinfo_col_tw'] ) && '' !== $block_fields['ww_blkcntntinfo_col_tw'] ) ? $block_fields['ww_blkcntntinfo_col_tw'] : null;


?>
<div id="<?php echo esc_html( $ww_id ); ?>" class="<?php echo esc_html( $ww_align_class . ' ' . $ww_class_name . ' ' . $ww_name ); ?> block-<?php echo esc_html( $block_name ); ?>">

<div class="glide-spacer <?php echo esc_html( $ww_blkcntntinfo_spcr_tp ); ?>"> </div>
	<?php if ( $ww_blkcntntinfo_col_one ) { ?>
		<div class="text-tiles">
			<?php
			foreach ( $ww_blkcntntinfo_col_one as  $ww_cntnt ) {
				$ww_heading = ( isset( $ww_cntnt['heading'] ) ) ? $ww_cntnt['heading'] : null;
				$ww_text    = ( isset( $ww_cntnt['text'] ) ) ? $ww_cntnt['text'] : null;
				?>
				<div class="text-tile">
					<?php if ( $ww_heading ) { ?>
						<div class="text-tile__title">
							<h3 class="heading-3">Contact Us</h3>
							<h2 class="heading-2"><?php echo html_entity_decode( $ww_heading ); ?></h2>
						</div>
					<?php } ?>
					<?php if ( $ww_text ) { ?>
						<div class="text-tile__text">
							<?php echo html_entity_decode( $ww_text ); ?>
						</div>
					<?php } ?>
				</div>
				<?php if ( $ww_blkcntntinfo_col_tw ) { ?>
					<div class="text-tile two-text-tiles">
					<?php
					foreach ( $ww_blkcntntinfo_col_tw as  $ww_cntnts ) {
						$ww_heading_two = ( isset( $ww_cntnts['heading_two'] ) ) ? $ww_cntnts['heading_two'] : null;
						$ww_form_two    = ( isset( $ww_cntnts['form'] ) ) ? $ww_cntnts['form'] : null;
						?>
						<div class="text-tile">
								<?php if ( $ww_heading_two ) { ?>
									<div class="text-tile__title">
										<h3 class="heading-3"><?php echo html_entity_decode( $ww_heading_two ); ?></h3>
									</div>
								<?php } ?>
								<?php if ( $ww_form_two ) { ?>
									<div class="text-tile__text text-22">
										<?php echo do_shortcode('[gravityform id="'.$ww_form_two.'" title="false" description="false"]'); ?>
									</div>
								<?php } ?>
							</div>
					<?php } ?>
				</div>
				<?php } ?>
			<?php } ?>
		</div>
	<?php } ?>
	<div class="s-72"></div>
		
<div class="glide-spacer <?php echo esc_html( $ww_blkcntntinfo_spcr_btm ); ?>"> </div>
</div>
