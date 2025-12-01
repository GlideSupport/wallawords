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
 * ===============================
 *  Admin Enhancements for Puzzle CPT
 * ===============================
 * Adds custom columns, sorting, and taxonomy filters
 * for the 'puzzle' custom post type in the WordPress admin.
 */

/**
 * Add custom columns to the Puzzle CPT list table.
 */
add_filter('manage_puzzle_posts_columns', function ($columns) {
	// Remove unused default columns.
	unset($columns['date']);

	// Add custom columns.
	$columns['health'] = __('Health', 'wallawords_td');
	$columns['difficulty'] = __('Difficulty', 'wallawords_td');
	$columns['date']       = __('Published Date', 'wallawords_td');
	$columns['last_updated'] = __('Last Updated', 'wallawords_td');

	return $columns;
});

/**
 * Populate custom column content.
 */
add_action('manage_puzzle_posts_custom_column', function ($column, $post_id) {
	switch ($column) {
		case 'last_updated':
			echo '<strong>' . esc_html__('Last Updated:', 'wallawords_td') . '</strong><br>' . esc_html(get_the_modified_date('m/d/Y h:i:s a', $post_id));
			break;

		case 'health':
			$health = get_field('wwp_health', $post_id);
			echo esc_html($health);
			break;

		case 'difficulty':
			$difficulty = get_field('wwp_difficulty_settings', $post_id);
			echo esc_html(strtoupper($difficulty ?: '-'));
			break;
	}
}, 10, 2);

/**
 * Make custom columns sortable.
 */
add_filter('manage_edit-puzzle_sortable_columns', function ($columns) {
	$columns['last_updated'] = 'modified';
	return $columns;
});

/**
 * Add taxonomy filter dropdowns in the admin list table for CPTs.
 */
add_action('restrict_manage_posts', function () {
	global $typenow;

	// Only apply to custom post types.
	if (!post_type_exists($typenow)) return;
	$post_type = get_post_type_object($typenow);
	if (empty($post_type) || $post_type->_builtin) return;

	// Add taxonomy filters.
	foreach (get_object_taxonomies($typenow) as $tax_slug) {
		$tax_obj = get_taxonomy($tax_slug);
		$terms = get_terms(['taxonomy' => $tax_slug, 'hide_empty' => true]);

		if (empty($terms) || is_wp_error($terms)) continue;

		$selected = $_GET[$tax_obj->query_var] ?? '';
		wp_dropdown_categories([
			'show_option_all' => sprintf(__('Show All %s', 'wallawords_td'), $tax_obj->label),
			'taxonomy'        => $tax_slug,
			'name'            => $tax_obj->name,
			'orderby'         => 'slug',
			'selected'        => $selected,
			'hierarchical'    => $tax_obj->hierarchical,
			'show_count'      => false,
			'hide_empty'      => true,
		]);
	}
});

/**
 * Convert taxonomy IDs to slugs for sorting/filtering.
 */
add_filter('parse_query', function ($query) {
	global $pagenow, $typenow;

	if ($pagenow !== 'edit.php' || !$typenow) return $query;

	foreach (get_object_taxonomies($typenow) as $tax_slug) {
		if (!empty($query->query_vars[$tax_slug])) {
			$term = get_term_by('id', $query->query_vars[$tax_slug], $tax_slug);
			if ($term) {
				$query->query_vars[$tax_slug] = $term->slug;
			}
		}
	}

	return $query;
});

/**
 * Set default sorting for the Puzzle CPT (by published date DESC).
 */
add_action('pre_get_posts', function ($query) {
	if (
		is_admin() &&
		$query->is_main_query() &&
		$query->get('post_type') === 'puzzle' &&
		!isset($_GET['orderby'])
	) {
		$query->set('orderby', 'date');
		$query->set('order', 'DESC');
	}
});

/**
 * Add a Difficulty filter dropdown to the Puzzle admin list.
 */
add_action('restrict_manage_posts', function () {
	global $typenow;

	if ($typenow !== 'puzzle') {
		return;
	}

	// Define your available difficulty levels (should match ACF field values).
	$difficulty_levels = [
		''       => __('All Difficulties', 'textdomain'),
		'easy'   => __('Easy', 'textdomain'),
		'medium' => __('Medium', 'textdomain'),
		'hard'   => __('Hard', 'textdomain'),
	];

	$current = isset($_GET['filter_difficulty']) ? sanitize_text_field($_GET['filter_difficulty']) : '';

	echo '<select name="filter_difficulty">';
	foreach ($difficulty_levels as $value => $label) {
		printf(
			'<option value="%s"%s>%s</option>',
			esc_attr($value),
			selected($current, $value, false),
			esc_html($label)
		);
	}
	echo '</select>';
});

/**
 * Filter the Puzzle CPT list based on selected Difficulty.
 */
add_action('pre_get_posts', function ($query) {
	global $pagenow, $typenow;

	if (
		$pagenow !== 'edit.php' ||
		!$query->is_main_query() ||
		$typenow !== 'puzzle' ||
		empty($_GET['filter_difficulty'])
	) {
		return;
	}

	$difficulty = sanitize_text_field($_GET['filter_difficulty']);

	if (!empty($difficulty)) {
		$meta_query = [
			[
				'key'     => 'wwp_difficulty_settings',
				'value'   => $difficulty,
				'compare' => '=',
			],
		];

		$query->set('meta_query', $meta_query);
	}
});
