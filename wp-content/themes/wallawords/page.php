<?php
/**
 * The template for displaying all pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

// Include header.
get_header();


list( $bst_var_post_id, $bst_fields, $bst_option_fields ) = BaseTheme::defaults();

$bst_var_tmp_def_title  = $bst_fields['bst_var_tmp_def_title'] ?? null;
$bst_var_tmp_def_text   = $bst_fields['bst_var_tmp_def_text'] ?? null;
$bst_var_tmp_def_button = $bst_fields['bst_var_tmp_def_button'] ?? null;

?>

<section id="hero-section" class="hero-section hero-section-default hero-alongside-pattern">
	<!-- Hero Start -->
	<div class="hero-default center-align ctn-760">
		<div class="wrapper">
			<div class="hero-alongside-block">
				<div class="banner-text">
					<h1><?php echo html_entity_decode( $bst_var_tmp_def_title ); ?></h1>
					<?php if (!empty($bst_var_tmp_def_text)) : ?><?php echo html_entity_decode($bst_var_tmp_def_text); ?><?php endif; ?>
					<?php if (!empty($bst_var_tmp_def_button)) : ?><div class="block-btn"><?php echo BaseTheme::button($bst_var_tmp_def_button, 'button'); ?></div><?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<!-- Hero End -->
</section>


<section id="page-section" class="page-section">
	<!-- Content Start -->
	<?php
		global $wp_query;
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			// Include specific template for the content.
			get_template_part( 'partials/content', 'page' );
		}
		?>
		<?php
	} else {
		// If no content, include the "No posts found" template.
		get_template_part( 'partials/content', 'none' );
	}
	?>
	<div class="ts-80"></div>
	<!-- Content End -->
</section>
<?php get_footer(); ?>
