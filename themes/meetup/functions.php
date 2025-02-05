<?php

/**
 * Register custom post types.
 *
 * @return void
 */
function register_post_types() {

	/**
	 * Post Type: Movies.
	 */

	$labels = array(
		'name'          => esc_html__( 'Movies', 'meetup' ),
		'singular_name' => esc_html__( 'Movie', 'meetup' ),
	);

	$args = array(
		'label'                 => esc_html__( 'Movies', 'meetup' ),
		'labels'                => $labels,
		'description'           => '',
		'public'                => true,
		'publicly_queryable'    => true,
		'show_ui'               => true,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'rest_namespace'        => 'wp/v2',
		'has_archive'           => 'movies',
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'delete_with_user'      => false,
		'exclude_from_search'   => false,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => false,
		'can_export'            => true,
		'rewrite'               => array(
			'slug'       => 'movies',
			'with_front' => true,
		),
		'query_var'             => true,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'taxonomies'            => array( 'category' ),
		'show_in_graphql'       => false,
	);

	register_post_type( 'movies', $args );

	/**
	 * Post Type: Actors.
	 */

	$labels = array(
		'name'          => esc_html__( 'Actors', 'meetup' ),
		'singular_name' => esc_html__( 'Actor', 'meetup' ),
	);

	$args = array(
		'label'                 => esc_html__( 'Actors', 'meetup' ),
		'labels'                => $labels,
		'description'           => '',
		'public'                => true,
		'publicly_queryable'    => true,
		'show_ui'               => true,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'rest_namespace'        => 'wp/v2',
		'has_archive'           => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'delete_with_user'      => false,
		'exclude_from_search'   => false,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => false,
		'can_export'            => true,
		'rewrite'               => array(
			'slug'       => 'actors',
			'with_front' => true,
		),
		'query_var'             => true,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'taxonomies'            => array( 'movies' ),
		'show_in_graphql'       => false,
	);

	register_post_type( 'actors', $args );
}

add_action( 'init', 'register_post_types' );

/**
 * Register custom taxonomies.
 *
 * @return void
 */
function register_taxonomies() {

	/**
	 * Taxonomy: Actors.
	 */

	$labels = array(
		'name'          => esc_html__( 'Actors', 'meetup' ),
		'singular_name' => esc_html__( 'Actor', 'meetup' ),
	);

	$args = array(
		'label'                 => esc_html__( 'Actors', 'meetup' ),
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'query_var'             => true,
		'rewrite'               => array(
			'slug'       => 'actors_tax',
			'with_front' => true,
		),
		'show_admin_column'     => false,
		'show_in_rest'          => true,
		'show_tagcloud'         => false,
		'rest_base'             => 'actors_tax',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace'        => 'wp/v2',
		'show_in_quick_edit'    => false,
		'sort'                  => false,
		'show_in_graphql'       => false,
	);

	register_taxonomy( 'actors_tax', array( 'movies' ), $args );

	/**
	 * Taxonomy: Movies.
	 */

	$labels = array(
		'name'          => esc_html__( 'Movies', 'meetup' ),
		'singular_name' => esc_html__( 'Movie', 'meetup' ),
	);

	$args = array(
		'label'                 => esc_html__( 'Movies', 'meetup' ),
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'query_var'             => true,
		'rewrite'               => array(
			'slug'       => 'movies_tax',
			'with_front' => true,
		),
		'show_admin_column'     => false,
		'show_in_rest'          => true,
		'show_tagcloud'         => false,
		'rest_base'             => 'movies_tax',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace'        => 'wp/v2',
		'show_in_quick_edit'    => false,
		'sort'                  => false,
		'show_in_graphql'       => false,
	);

	register_taxonomy( 'movies_tax', array( 'actors' ), $args );
}

add_action( 'init', 'register_taxonomies', 0 );
