<?php

/**
 * Functions for custom post types
 *
 * @link https://developer.wordpress.org/themes/basics/post-types/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

use BaseTheme\CPT\WP_Theme_CPT;

new WP_Theme_CPT(
	array(
		'labels'       => array(
			'singular_capital'   => 'Testimonial',
			'plural_capital'     => 'Testimonials',
			'singular_lowercase' => 'testimonial',
			'plural_lowercase'   => 'testimonials',
			// CPT Slug & Name.
			'register_key'       => 'testimonial',
			'slug'               => 'testimonial',
		),
		'supports'     => array('title', 'thumbnail', 'author', 'excerpt'),
		'menu_icon'    => 'dashicons-format-quote',
		'public'       => false,
		'show_in_menu' => true,
		'show_ui'      => true,
		/*
		'taxonomies'   => array(
			array(
				'slug'          => 'types',
				'name'          => 'Type',
				'singular_name' => 'Type',
				'plural_name'   => 'Types',
			),
		),*/
	)
);

new WP_Theme_CPT(
	array(
		'labels'    => array(
			'singular_capital'   => 'Team Member',
			'plural_capital'     => 'Team Members',
			'singular_lowercase' => 'team member',
			'plural_lowercase'   => 'team members',
			// CPT Slug & Name.
			'register_key'       => 'team',
			'slug'               => 'team',
		),
		'supports'  => array('title', 'editor', 'thumbnail', 'author', 'excerpt'),
		'menu_icon' => 'dashicons-businessperson',
		'public'    => false,
	)
);

new WP_Theme_CPT(
	array(
		'labels'    => array(
			'singular_capital'   => 'Puzzle',
			'plural_capital'     => 'Puzzles',
			'singular_lowercase' => 'puzzle',
			'plural_lowercase'   => 'puzzles',
			// CPT Slug & Name.
			'register_key'       => 'puzzle',
			'slug'               => 'puzzle',
		),
		'supports'  => array('title', 'author'),
		'menu_icon' => 'dashicons-schedule',
		'public'    => true,
		'taxonomies'   => array(
			array(
				'slug'          => 'topic',
				'name'          => 'Topic',
				'singular_name' => 'Topic',
				'plural_name'   => 'Topics',
			)
		)
	)
);


/**
 * Add custom column to display listing data
 */

add_filter('manage_puzzle_posts_columns', 'set_cpt_puzzle_post_columns');

function set_cpt_puzzle_post_columns($columns)
{
	//unset( $columns['author'] );
	//  unset( $columns['date'] );
	//unset( $columns['taxonomy-listing_location'] ); //swap default taxonomy behavior
	//
	$columns['difficulty'] = 'Difficulty';
	//$columns['photo'] = 'Photo';
	$columns['date-new'] = 'Last Updated Date';
	return $columns;
}

add_action('manage_puzzle_posts_custom_column', 'cpt_puzzle_custom_columns', 10, 2);

function cpt_puzzle_custom_columns($column, $post_id)
{
	switch ($column) {
		case 'date-new':
			echo '<b>Last Updated:</b> <br>' . get_the_modified_date('m/d/Y h:i:s a');
			break;

		case 'difficulty':
			$rank = get_field('wwp_difficulty_settings', $post_id) ?? '-';
			echo strtoupper($rank);
			break;
	}
}

add_filter('manage_edit-puzzle_sortable_columns', 'cpt_sortable_puzzle_column');

function cpt_sortable_puzzle_column($columns)
{
	//$columns['difficulty'] = 'difficulty';   
	$columns['date-new'] = 'date';
	return $columns;
}

function ww_add_sort_manage_posts()
{
	global $typenow;
	$args = array('public' => true, '_builtin' => false);
	$post_types = get_post_types($args);
	if (in_array($typenow, $post_types)) {
		$filters = get_object_taxonomies($typenow);
		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_data = get_terms($tax_slug);

			if (isset($_GET[$tax_obj->query_var])):
				$selected = $_GET[$tax_obj->query_var];
			else:
				$selected = '';
			endif;

			if (count($tax_data) > 0):
				wp_dropdown_categories(array(
					'show_option_all' => __('Show All ' . $tax_obj->label),
					'taxonomy' => $tax_slug,
					'name' => $tax_obj->name,
					'orderby' => 'slug',
					'selected' => $selected,
					'hierarchical' => $tax_obj->hierarchical,
					'show_count' => false,
					'hide_empty' => true
				));
			endif;
		}
	}
}

/**
 * Add additional sorting features for CPT
 */

function ww_convert_sort($query)
{
	global $pagenow;
	global $typenow;
	if ($pagenow == 'edit.php') {
		$filters = get_object_taxonomies($typenow);
		foreach ($filters as $tax_slug) {
			$var = &$query->query_vars[$tax_slug];
			if (isset($var)) {
				$term = get_term_by('id', $var, $tax_slug);
				if ($term):
					$var = $term->slug;
				endif;
			}
		}
	}
	return $query;
}

/**
 * Change default sorting for CPT
 */

function ww_set_sort_defaults($query)
{
	if (is_admin() && $query->is_main_query() && ($query->get('post_type') == 'puzzle')):

		if (!isset($_GET['orderby'])):

			if ($query->get('post_type') == 'puzzle'):
				$query->set('order', 'DESC');
				$query->set('orderby', 'date'); // Sort by published date instead of modified date
			endif;

		endif;

	endif;
}

add_action('restrict_manage_posts', 'ww_add_sort_manage_posts');
add_filter('parse_query', 'ww_convert_sort');
add_action('pre_get_posts', 'ww_set_sort_defaults');
