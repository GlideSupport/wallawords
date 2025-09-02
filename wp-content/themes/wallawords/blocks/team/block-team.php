<?php
/**
 * Block Name: Team
 *
 * The template for displaying the custom gutenberg block named Team.
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
$ww_blkteam_title      = ( isset( $block_fields['ww_blkteam_title']['title'] ) && '' !== $block_fields['ww_blkteam_title']['title'] ) ? $block_fields['ww_blkteam_title']['title'] : null;
$ww_blkteam_kicker      = ( isset( $block_fields['ww_blkteam_kicker'] ) && '' !== $block_fields['ww_blkteam_kicker'] ) ? $block_fields['ww_blkteam_kicker'] : null;
$ww_blkteam_title_tag  = ( isset( $block_fields['ww_blkteam_title']['title_tag'] ) && '' !== $block_fields['ww_blkteam_title']['title_tag'] ) ? $block_fields['ww_blkteam_title']['title_tag'] : null;
$ww_blkteam_spcr_tp    = ( isset( $block_fields['ww_blkteam_spcr']['top_spacer'] ) && '' !== $block_fields['ww_blkteam_spcr']['top_spacer'] ) ? $block_fields['ww_blkteam_spcr']['top_spacer'] : null;
$ww_blkteam_spcr_btm   = ( isset( $block_fields['ww_blkteam_spcr']['bottom_spacer'] ) && '' !== $block_fields['ww_blkteam_spcr']['bottom_spacer'] ) ? $block_fields['ww_blkteam_spcr']['bottom_spacer'] : null;
$ww_blkteam_dsgn_vari  = ( isset( $block_fields['ww_blkteam_dsgn_vari'] ) && '' !== $block_fields['ww_blkteam_dsgn_vari'] ) ? $block_fields['ww_blkteam_dsgn_vari'] : null;
$ww_blkteam_text       = ( isset( $block_fields['ww_blkteam_text'] ) && '' !== $block_fields['ww_blkteam_text'] ) ? $block_fields['ww_blkteam_text'] : null;
$ww_blkteam_tem_membrs = ( isset( $block_fields['ww_blkteam_tem_membrs'] ) && '' !== $block_fields['ww_blkteam_tem_membrs'] ) ? $block_fields['ww_blkteam_tem_membrs'] : null;

?>
<div id="<?php echo esc_html( $amp_id ); ?>" class="<?php echo esc_html( $amp_align_class . ' ' . $amp_class_name . ' ' . $amp_name ); ?> block-<?php echo esc_html( $block_name ); ?>">
	<div class="glide-spacer <?php echo esc_html( $ww_blkteam_spcr_tp ); ?>"> </div>
	
			<div class="team">
			<?php if ( $ww_blkteam_title || $ww_blkteam_text ) { ?>
					<div class="section-head">
						<?php if ( $ww_blkteam_title ) { ?>
							<div class="section-head__heading">
								<?php if ( $ww_blkteam_kicker ) { ?>
									<div class="pre-header"><?php echo $ww_blkteam_kicker; ?></div>
								<?php } ?>
								<<?php echo esc_html( $ww_blkteam_title_tag ); ?> class="heading-2"><?php echo html_entity_decode( $ww_blkteam_title ); ?><?php echo '</' . esc_html( $ww_blkteam_title_tag ) . '>'; ?>
							</div>
						<?php } ?>
						<?php if ( $ww_blkteam_text ) { ?>
							<div class="section-head__subheading text-22">
								<?php echo html_entity_decode( $ww_blkteam_text ); ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if ( $ww_blkteam_tem_membrs ) { ?>
				
				<div class="s-72"></div>

					<div class="team-members">
						<?php
						global $post;
						$count = 0;
						$amp_lp_select_team = array();
						$amp_lp_select_team = $ww_blkteam_tem_membrs;
						foreach ( $amp_lp_select_team as $amp_lp_team ) {
							// @codingStandardsIgnoreStart
							$post = $amp_lp_team;
							// @codingStandardsIgnoreEnd
							setup_postdata( $post );
							$ww_post_id                = $post->ID;
							$post_fields                = get_fields( $ww_post_id );
							$ww_cpt_team_designation   = ( isset( $post_fields['ww_cpt_team_designation'] ) && '' !== $post_fields['ww_cpt_team_designation'] ) ? $post_fields['ww_cpt_team_designation'] : null;
							$ww_cpt_team_details       = ( isset( $post_fields['ww_cpt_team_details'] ) && '' !== $post_fields['ww_cpt_team_details'] ) ? $post_fields['ww_cpt_team_details'] : null;
							$ww_cpt_team_facebook_link = ( isset( $post_fields['ww_cpt_team_facebook_link'] ) && '' !== $post_fields['ww_cpt_team_facebook_link'] ) ? $post_fields['ww_cpt_team_facebook_link'] : null;
							$ww_cpt_team_x_link = ( isset( $post_fields['ww_cpt_team_x_link'] ) && '' !== $post_fields['ww_cpt_team_x_link'] ) ? $post_fields['ww_cpt_team_x_link'] : null;
							$ww_cpt_team_linkedin_link = ( isset( $post_fields['ww_cpt_team_linkedin_link'] ) && '' !== $post_fields['ww_cpt_team_linkedin_link'] ) ? $post_fields['ww_cpt_team_linkedin_link'] : null;
							$ww_cpt_team_email_address = ( isset( $post_fields['ww_cpt_team_email_address'] ) && '' !== $post_fields['ww_cpt_team_email_address'] ) ? $post_fields['ww_cpt_team_email_address'] : null;
							$ww_cpt_team_phone         = ( isset( $post_fields['ww_cpt_team_phone'] ) && '' !== $post_fields['ww_cpt_team_phone'] ) ? $post_fields['ww_cpt_team_phone'] : null;
							$amp_permalink              = get_the_permalink();
							if ( strpos( $amp_permalink, '/team/' ) !== false && strpos( $amp_permalink, '/mortgage/' ) === false && strpos( $amp_permalink, '/commercial-loans/' ) === false ) {
								$amp_url   = '#' . sanitize_title( get_the_title() );
								$amp_class = 'addc';
							} else {
								$amp_url   = esc_url( get_the_permalink() );
								$amp_class = '';
							}
							?>
							<div class="single-team-member column <?php if($count < 3){ echo 'leadership';}?>">
								<div class="single-team-member__details">
									<div class="member-img">
									<?php
									if ( ! has_post_thumbnail() ) {
										echo '<img class="" src="' . esc_url( get_template_directory_uri() ) . '/assets/build/images/admin/defaults/default-image.webp" alt="' . esc_html( get_the_title() ) . '" >';
									} else {
										the_post_thumbnail(
											'thumb_1000',
											array(
												'class' => '',
												'alt'   => get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ),
												'title' => get_the_title( get_post_thumbnail_id() ),
											)
										);
									}
									?>
									</div>
									<div class="member-bio">
										<div class="member-name">
											<h4><?php the_title(); ?></h4>
											<?php if ( $ww_cpt_team_designation ) { ?>
												<div class="member-designation">
													<span>
														<?php echo html_entity_decode( $ww_cpt_team_designation ); ?>
													</span>
												</div>
											<?php } ?>
											<?php if ( $ww_cpt_team_facebook_link ) { ?>
												<div class="social">
													<a href="<?php echo $ww_cpt_team_facebook_link;?>" target="_blank">													
													<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
														viewBox="0 0 155.139 155.139" style="enable-background:new 0 0 155.139 155.139;" xml:space="preserve">
													<g>
														<path id="f_1_" d="M89.584,155.139V84.378h23.742l3.562-27.585H89.584V39.184
															c0-7.984,2.208-13.425,13.67-13.425l14.595-0.006V1.08C115.325,0.752,106.661,0,96.577,0C75.52,0,61.104,12.853,61.104,36.452
															v20.341H37.29v27.585h23.814v70.761H89.584z"/>
													</g>
													</svg>
													</a>
												</div>
											<?php } ?>
											<?php if ( $ww_cpt_team_x_link ) { ?>
												<div class="social">
													<a href="<?php echo $ww_cpt_team_x_link;?>" target="_blank">													
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>
													</a>
												</div>
											<?php } ?>
											<?php if ( $ww_cpt_team_linkedin_link ) { ?>
												<div class="social">
													<a href="<?php echo $ww_cpt_team_linkedin_link;?>" target="_blank">
													<svg enable-background="new 0 0 100 100" height="512" viewBox="0 0 100 100" width="512" xmlns="http://www.w3.org/2000/svg"><g id="_x31_0.Linkedin"><path d="m90 90v-29.3c0-14.4-3.1-25.4-19.9-25.4-8.1 0-13.5 4.4-15.7 8.6h-.2v-7.3h-15.9v53.4h16.6v-26.5c0-7 1.3-13.7 9.9-13.7 8.5 0 8.6 7.9 8.6 14.1v26h16.6z"/><path d="m11.3 36.6h16.6v53.4h-16.6z"/><path d="m19.6 10c-5.3 0-9.6 4.3-9.6 9.6s4.3 9.7 9.6 9.7 9.6-4.4 9.6-9.7-4.3-9.6-9.6-9.6z"/></g></svg>
													</a>
												</div>
											<?php } ?>

										</div>
									</div>
								</div>
						</div>
						<?php
						$count++;
						}
						wp_reset_postdata();
						?>
					</div>
				<?php } ?>
	
	<div class="glide-spacer <?php echo esc_html( $ww_blkteam_spcr_btm ); ?>"> </div>
</div>
