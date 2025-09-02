<?php
/**
 * Template part for displaying the_content() function
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

the_content(
	sprintf(
		wp_kses(
		/* translators: %s: Name of current post. Only visible to screen readers */
			__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'basetheme_td' ),
			array(
				'span' => array(
					'class' => array(),
				),
			)
		),
		get_the_title()
	)
);

$meta = get_post_meta(get_the_ID());

wp_link_pages(
	array(
		'before' => '<div class="page-links">' . __( 'Pages:', 'basetheme_td' ),
		'after'  => '</div>',
	)
);
