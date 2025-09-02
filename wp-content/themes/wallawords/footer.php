<?php
/**
 * The template for displaying website footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

list( $ww_post_id, $ww_fields, $ww_option_fields ) = BaseTheme::defaults();
// Default Footer Options.
$ww_footer_scripts = $ww_option_fields['footer_scripts'] ?? '';

// Schema Markup - ACF variables.
$ww_schema_check = $ww_option_fields['ww_schema_check'] ?? null;
if ( $ww_schema_check ) {
	$ww_schema_business_name       = $ww_option_fields['ww_schema_business_name'] ?? null;
	$ww_schema_business_legal_name = $ww_option_fields['ww_schema_business_legal_name'] ?? null;
	$ww_schema_street_address      = $ww_option_fields['ww_schema_street_address'] ?? null;
	$ww_schema_locality            = $ww_option_fields['ww_schema_locality'] ?? null;
	$ww_schema_region              = $ww_option_fields['ww_schema_region'] ?? null;
	$ww_schema_postal_code         = $ww_option_fields['ww_schema_postal_code'] ?? null;
	$ww_schema_map_short_link      = $ww_option_fields['ww_schema_map_short_link'] ?? null;
	$ww_schema_latitude            = $ww_option_fields['ww_schema_latitude'] ?? null;
	$ww_schema_longitude           = $ww_option_fields['ww_schema_longitude'] ?? null;
	$ww_schema_opening_hours       = $ww_option_fields['ww_schema_opening_hours'] ?? null;
	$ww_schema_telephone           = $ww_option_fields['ww_schema_telephone'] ?? null;
	$ww_schema_business_email      = $ww_option_fields['ww_schema_business_email'] ?? null;
	$ww_schema_business_logo       = $ww_option_fields['ww_schema_business_logo'] ?? null;
	$ww_schema_price_range         = $ww_option_fields['ww_schema_price_range'] ?? null;
	$ww_schema_type                = $ww_option_fields['ww_schema_type'] ?? null;
}
// Custom - ACF variables.

$ww_ftrop_title     = $ww_option_fields['ww_ftrop_title'] ?? null;
$ww_ftrop_text      = $ww_option_fields['ww_ftrop_text'] ?? null;
$ww_ftrop_copyright = $ww_option_fields['ww_ftrop_copyright'] ?? null;
$ww_social_profiles = $ww_option_fields['ww_social_profiles'] ?? null;

?>

<?php //get_template_part( 'partials/cta' ); ?>

<footer id="footer-section" class="footer-section">
	<!-- Footer Start -->
	<div class="footer-ctn">
			<div class="footer-nav" role="menu">
				<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer-nav-one',
							'fallback_cb'    => 'BaseTheme::nav_fallback',
						)
					);
					?>
					
					<?php if ( $ww_ftrop_copyright ) { ?>
						<div class="copy-right"><?php echo esc_html( $ww_ftrop_copyright ); ?></div>
					<?php } ?>
			</div>
			
		</div>
	
	</div>
	<!-- Footer End -->
	<?php
	if ( $ww_schema_check ) {
		?>
	<script type="application/ld+json">
	{
		"@context": "http://schema.org",
		"@type": "<?php echo esc_html( $ww_schema_type ); ?>",
		"address": {
			"@type": "PostalAddress",
			"addressLocality": "<?php echo esc_html( $ww_schema_locality ); ?>",
			"addressRegion": "<?php echo esc_html( $ww_schema_region ); ?>",
			"postalCode": "<?php echo esc_html( $ww_schema_postal_code ); ?>",
			"streetAddress": "<?php echo esc_html( $ww_schema_street_address ); ?>"
		},
		"hasMap": "<?php echo esc_html( $ww_schema_map_short_link ); ?>",
		"geo": {
			"@type": "GeoCoordinates",
			"latitude": "<?php echo esc_html( $ww_schema_latitude ); ?>",
			"longitude": "<?php echo esc_html( $ww_schema_longitude ); ?>"
		},
		"name": "<?php echo esc_html( $ww_schema_business_name ); ?>",
		"openingHours": "<?php echo esc_html( $ww_schema_opening_hours ); ?>",
		"telephone": "<?php echo esc_html( $ww_schema_telephone ); ?>",
		"email": "<?php echo esc_html( $ww_schema_business_email ); ?>",
		"url": "<?php echo esc_url( home_url() ); ?>",
		"image": "<?php echo esc_html( $ww_schema_business_logo ); ?>",
		"legalName": "<?php echo esc_html( $ww_schema_business_legal_name ); ?>",
		"priceRange": "<?php echo esc_html( $ww_schema_price_range ); ?>"
	}
	</script> <?php } ?>
</footer>
</main>

<?php wp_footer(); ?>
<?php
if ( '' !== $ww_footer_scripts ) {
	?>
<div style="display: none;">
	<?php echo html_entity_decode( $ww_footer_scripts, ENT_QUOTES ); ?>
</div>
<?php } ?>
</body>

</html>
