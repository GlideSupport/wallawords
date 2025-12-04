<?php
/**
 * The template  displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Base Theme Package
 * @since   1.0.0
 */

// Include header.
get_header();

list( $bst_var_post_id, $bst_fields, $bst_option_fields ) = BaseTheme::defaults();

// 404 Page - Advanced custom fields variables.
$bst_var_error_headline         = $bst_option_fields['bst_var_error_headline'] ?? null;
$bst_var_error_sub_headline     = $bst_option_fields['bst_var_error_sub_headline'] ?? null;
$bst_var_error_text             = $bst_option_fields['bst_var_error_text'] ?? null;
$bst_var_error_menu             = $bst_option_fields['bst_var_error_menu'] ?? null;
$bst_var_error_menu_bottom_text = $bst_option_fields['bst_var_error_menu_bottom_text'] ?? null;
$bst_var_error_search           = $bst_option_fields['bst_var_error_search'] ?? false;

?>

<section id="page-section" class="page-section">
	<!-- Page Content Start -->
	<div class="m-section">
		<div class="wrapper">
			<div class="ts-80"></div>
			<div class="error-404 not-found">
				<h1><?php echo html_entity_decode( $bst_var_error_headline ); ?></h1>
				<div class="banner-text">
					<p><?php echo html_entity_decode( $bst_var_error_sub_headline ); ?></p>
				</div>
				<div class="page-content">
					<?php
					if ( $bst_var_error_text ) {
						echo html_entity_decode( $bst_var_error_text );
					}
					if ( $bst_var_error_menu ) {
						?>
					<div class="error">
						<?php echo html_entity_decode( $bst_var_error_menu ); ?> </div>
						<?php
					}
					?>
					<div class="back-btn">
						<a href="<?php echo esc_url(home_url('/')); ?>" title="Back To Home" role="button" aria-label="Back To Home" class="site-btn is-style-site-arrow-btn">
							Back To Home 
						</a>
					</div>
					
					<div class="form-404">

						<?php
						if ( $bst_var_error_menu_bottom_text ) {
							echo html_entity_decode( $bst_var_error_menu_bottom_text );
						}
						if ( ! $bst_var_error_search ) {
							echo '<div class="ts-80"></div>';
							get_search_form();
						}
						?>
					</div>
					<!--404-form-->
				</div><!-- .page-content -->
					</div><!-- .error-404 -->
			<div class="ts-80"></div>
		</div>
	</div>
</section>
<?php
get_footer();
