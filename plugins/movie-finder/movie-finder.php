<?php
/**
 * Plugin Name:       Movie Finder
 * Description:       An interactive block with the Interactivity API.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       movie-finder
 *
 * @package           create-block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_movie_finder_block_init() {
	register_block_type_from_metadata( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_movie_finder_block_init' );

function register_movie_finder_endpoints() {

	register_rest_route(
		'movie-finder/v1',
		'/actors',
		array(
			'methods'             => 'GET',
			'callback'            => function ( $request ) {
				$category = $request->get_param( 'category' );

				$query_args = array(
					'post_type' => 'movies',

				);

				if ( ! empty( $category ) ) {
					$query_args['tax_query'] = array(
						array(
							'taxonomy' => 'category',
							'field'    => 'slug',
							'terms'    => sanitize_text_field( $category ),
						),
					);
				}

				$query = new \WP_Query( $query_args );

				if ( empty( $query->posts ) ) {
					return array();
				}

				$actor_ids = array();
				foreach ( $query->posts as $movie ) {
					$terms = wp_get_post_terms( $movie->ID, 'actors_tax' );

					if ( is_wp_error( $terms ) ) {
						continue;
					}

					foreach ( $terms as $term ) {
						$actor_ids[] = $term->term_id;
					}
				}

				$terms = get_terms(
					array(
						'taxonomy' => 'actors_tax',
						'include'  => array_unique( $actor_ids ),
					)
				);

				if ( is_wp_error( $terms ) ) {
					return array();
				}

				$result = array_map(
					function ( $term ) {
						return array(
							'id'    => $term->term_id,
							'value' => $term->slug,
							'text'  => $term->name,
						);
					},
					$terms
				);

				return $result;
			},
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'register_movie_finder_endpoints' );
