<?php
/**
 * Custom functions added to current project
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */


//WallaWords game functions
include_once('game.php');


/**
 * Excerpt Function
 *
 * @param number $count is a number of words needed in the excerpt
 *
 * Function used to create custom excerpt.
 */
function build_excerpt( $count ) {
	global $post;
	$permalink = get_permalink( $post->ID );
	$excerpt   = get_the_excerpt();
	$excerpt   = wp_strip_all_tags( $excerpt );
	$excerpt   = substr( $excerpt, 0, $count );
	$excerpt   = substr( $excerpt, 0, strripos( $excerpt, ' ' ) );
	$excerpt   = $excerpt . ' ...';
	$excerpt   = $excerpt;
	return $excerpt;
}


/**
 * Excerpt with no read more option
 *
 * Function used to create custom excerpt.
 *
 * @param number $count is a number of words needed in the excerpt.
 *
 * @return string
 */
function build_excerpt_nomore( $count ) {
	global $post;
	$permalink = get_permalink( $post->ID );
	$excerpt   = get_the_excerpt();
	$excerpt   = wp_strip_all_tags( $excerpt );
	$excerpt   = substr( $excerpt, 0, $count );
	$excerpt   = substr( $excerpt, 0, strripos( $excerpt, ' ' ) );
	$excerpt   = $excerpt;
	return $excerpt;
}


/**
 * Pagination Function
 *
 * The pagination function to display pagination on any archive page
 *
 * @param number $pages are total number of pages.
 * @param number $range is a range of pagination.
 *
 * @return void
 */
function build_pagination( $pages = '', $range = 4 ) {
	$showitems = ( $range * 2 ) + 1;

	$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

	if ( '' === $pages ) {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if ( ! $pages ) {
			$pages = 1;
		}
	}

	if ( 1 !== $pages ) {
		echo '<div class="pagination"><span>Page ' . esc_html( $paged ) . ' of ' . esc_html( $pages ) . '</span>';
		if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
			echo "<a href='" . esc_url( get_pagenum_link( 1 ) ) . "'>&laquo; First</a>";
		}
		if ( $paged > 1 && $showitems < $pages ) {
			echo "<a href='" . esc_url( get_pagenum_link( $paged - 1 ) ) . "'>&lsaquo; Previous</a>";
		}

		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( 1 !== $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
				// @codingStandardsIgnoreStart
				echo ( $paged === $i ) ? '<span class="current">' . $i . '</span>' : "<a href='" . esc_url( get_pagenum_link( $i ) ) . "' class=\"inactive\">" . $i . '</a>';
			}
			// @codingStandardsIgnoreEnd
		}

		if ( $paged < $pages && $showitems < $pages ) {
			echo '<a href="' . esc_url( get_pagenum_link( $paged + 1 ) ) . '">Next &rsaquo;</a>';
		}
		if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
			echo "<a href='" . esc_url( get_pagenum_link( $pages ) ) . "'>Last &raquo;</a>";
		}
		echo "<div class='clear'></div></div>\n";
	}
}


/**
 * Helper function that builds button from ACF link object
 *
 * @param object $object is a acf button object.
 * @param string $classes are the string of classes of acf button.
 *
 * @return string
 */
function build_acf_button( $object, $classes = '' ) {
	if ( $object['url'] ) {
		$link  = '';
		$link  = "<a href='" . esc_url( $object['url'] ) . "'";
		$link .= " title='" . esc_html( $object['title'] ) . "'";
		if ( '' !== $object['target'] ) {
			$link .= " target='" . $object['target'] . "'";
		}
		if ( '' !== $classes ) {
			$link .= " class='" . $classes . "'";
		}
		$link .= '>' . esc_html( $object['title'] ) . '</a>';
		return $link;
	}
	return null;
}


function get_fields_escaped( $field_key, $escape_method = 'esc_html' ) {
	if ( function_exists( 'get_fields' ) ) {
		$field = get_fields( $field_key );
	}
	/* Check for null and falsy values and always return space */
	if ( false === $field || null === $field ) {
		$field = '';
	}

	/* Handle arrays */
	if ( is_array( $field ) || is_object( $field ) ) {
		$field_escaped = array();
		foreach ( $field as $key => $value ) {
			if ( is_array( $value ) || is_object( $value ) ) {
				$field_escaped[ $key ] = get_sub_field_escaped( $value, $escape_method );
			} else {
				$field_escaped[ $key ] = if_exist( ( null === $escape_method ) ? $value : $escape_method( $value ) );
				// $field_escaped[$key] =   esc_html($value);
			}
		}
		return $field_escaped;
	} else {
		return if_exist( ( null === $escape_method ) ? $field : $escape_method( $field ) );
	}
}

/**
 * Helper function to get escaped field for a sub-field from ACF inside a parent
 * and also normalize values.
 *
 * @param string $parent is the acf key name.
 * @param string $escape_method is the method of escaping html.
 *
 * @return mixed
 */
function get_sub_field_escaped( $parent = null, $escape_method = 'esc_html' ) {
	$field = $parent;
	/* Check for null and falsy values and always return space */
	if ( false === $field || null === $field ) {
		$field = '';
	}

	/* Handle arrays */
	if ( is_array( $field ) || is_object( $value ) ) {
		$field_escaped = array();
		foreach ( $field as $key => $value ) {
			if ( is_array( $value ) || is_object( $value ) ) {
				if ( is_object( $value ) ) {
					$obj = new \stdClass();

					foreach ( $value as $obj_k => $obj_v ) {

						$obj->$obj_k = if_exist( ( null === $escape_method ) ? $obj_v : $escape_method( $obj_v ) );
					}
					$field_escaped[ $key ] = $obj;
				} else {
					$field_escaped[ $key ] = get_sub_field_escaped( $value, $escape_method );
				}
			} else {

				$field_escaped[ $key ] = if_exist( ( null === $escape_method ) ? $value : $escape_method( $value ) );
			}
		}
		return $field_escaped;
	} else {
		return if_exist( ( null === $escape_method ) ? $field : $escape_method( $field ) );
	}

}

/**
 * Check if value exist
 *
 * @param mixed $value value to be checked.
 *
 * @return string
 */
function if_exist( $value ) {
	return ( isset( $value ) && '' !== $value ) ? $value : null;
}

/**
 * Return escaped string
 *
 * @param string $string string to decode.
 *
 * @return string
 */
function html_entity_remove( $string ) {
	return sanitize_text_field( html_entity_decode( $string ) );
}

/**
 * Fallback function for menus
 *
 * @return void
 */
function nav_fallback() {
	if ( is_user_logged_in() ) {
		?>
		<ul>
			<li> <?php esc_html__( 'Go to admin area to create navigation menu', 'amplify_td' ); ?></li>
		</ul>
		<?php
	}
}

/**
 * A Function that check if post exist then print class;
 *
 * @param string $class post class.
 *
 * @return void
 */
function have_post_class( $class ) {
	if ( have_posts() ) {
		echo esc_html( $class );
	}
}
/**
 * A Function that check if site is live of not
 *
 * @return boolean
 */
function if_live() {

	if ( 'local' === wp_get_environment_type() ) {
		return false;
	} elseif ( 'development' === wp_get_environment_type() ) {
		return false;
	} elseif ( 'staging' === wp_get_environment_type() ) {
		return false;
	} elseif ( 'production' === wp_get_environment_type() ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Modify the author schema output using team member object data
 *
 */

//Yoast author field

function amp_custom_yoast_author_callback( $author ) {

	global $post;
	$postID = $post->ID;
	
	if($post->post_type == 'post'){

		if(get_field('amp_snglpost_author',$postID)){
			$amp_author_team_id = get_field( 'amp_snglpost_author', $postID );	
			$author = get_field( 'amp_cpt_team_name', $amp_author_team_id );
		}
		
	}
		
	return $author;
}

add_filter( 'wpseo_meta_author', 'amp_custom_yoast_author_callback' );


//Schema author field(s)

function amp_custom_author_schema_callback( $output ){

	global $post;
	$postID = $post->ID;
	
	if($post->post_type == 'post'){

		if(get_field('amp_snglpost_author',$postID)){
			$amp_author_team_id = get_field( 'amp_snglpost_author', $postID );	

			$amp_author_name = get_field( 'amp_cpt_team_name', $amp_author_team_id );
			$amp_author_description = get_field( 'amp_cpt_team_details', $amp_author_team_id );
			$amp_author_url = get_permalink($amp_author_team_id );

			$output['author']['name'] = esc_html($amp_author_name);
			$output['author']['url'] = esc_html($amp_author_url);
			$output['author']['description'] = trim(esc_html(strip_tags($amp_author_description)));

			$amp_author_img_id = wp_get_attachment_image_src( get_post_thumbnail_id($amp_author_team_id), 'thumbnail'); 	

			if($amp_author_img_id){
				$amp_author_avatar = $amp_author_img_id[0];
			}
			
			if ( !$amp_author_avatar ) {
				$amp_author_avatar = get_avatar_url( $amp_post_author_id );
			}

			if ( $amp_author_avatar ) {
				$output['author']['image']['url'] = $amp_author_avatar;
			}
		}
		
	}

	return $output;

}

add_filter( 'saswp_modify_article_schema_output', 'amp_custom_author_schema_callback' );



/**
 * Add custom column to display author / team member data object
 *
 */

 /*

add_filter( 'manage_post_posts_columns', 'set_custom_post_columns' );

function set_custom_post_columns($columns) {
   	unset( $columns['author'] );
	//unset( $columns['categories'] );
	unset( $columns['date'] );
	unset( $columns['tags'] );
	unset( $columns['comments'] );
	//
    $columns['author-team'] = 'Author';
	//$columns['categories'] = 'Categories';
	$columns['tags'] = 'Tags';
	$columns['date'] = 'Date';
    return $columns;
}

add_action( 'manage_post_posts_custom_column' , 'post_custom_column', 10, 2 );
function post_custom_column( $column, $post_id ) {
    switch ( $column ) {
        case 'author-team':
			$amp_author_team_id = get_field( 'amp_snglpost_author', $post_id );	
			$amp_author_name = get_field( 'amp_cpt_team_name', $amp_author_team_id );
			$amp_author_link = '/wp-admin/post.php?post='.$amp_author_team_id.'&action=edit';

			echo '<a href="'.$amp_author_link.'" target="_blank">'.$amp_author_name.'</a>';
        break;
		
    }
}

//exclude specific page templates from search results
function exclude_page_templates_from_search($query) {
	
	if ($query->is_main_query() && $query->is_search()) {		
		
		$meta_query = 
            array(
				//needs an OR to be sure to include posts which do not have the page template meta var
                'relation' => 'OR',
				
                array(
                    'key' => '_wp_page_template',
                    'value' => array('templates/template-landing.php', 'templates/template-forms.php'),
                    'compare' => 'NOT IN'
                ),
				array(
                    'key' => '_wp_page_template',
                    //'value' => 'template-landing.php',
                    'compare' => 'NOT EXISTS'
                )
				
            );
		
		// Get existing meta query clauses, if any
        $existing_meta_query = $query->get('meta_query');

        // If there are existing meta query clauses, merge them with our new meta query
        if (!empty($existing_meta_query)) {
            $meta_query = array_merge($meta_query, $existing_meta_query);
        }

        // Set the modified meta query
        $query->set('meta_query', $meta_query);
    }

}

add_filter('pre_get_posts','exclude_page_templates_from_search');
*/