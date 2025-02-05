<?php
/**
 * Plugin Name:       Movie Watchlist
 * Description:       An interactive block with the Interactivity API.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       movie-watchlist
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
function create_block_movie_watch_list_block_init() {
	register_block_type_from_metadata( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_movie_watch_list_block_init' );

function register_movie_watchlist_routes() {
	register_rest_route(
		'movie-watchlist/v1',
		'/add/',
		array(
			'methods'             => 'POST',
			'callback'            => function ( WP_REST_Request $request ) {
				$user_id = get_current_user_id();
				$post_id = (int) $request->get_param( 'postId' );

				$user_watchlist = get_user_meta( $user_id, 'movie_watchlist', true );
				$user_watchlist = is_array( $user_watchlist ) ? $user_watchlist : array();

				if ( ! in_array( $post_id, $user_watchlist, true ) ) {
					$user_watchlist[] = $post_id;
					update_user_meta( $user_id, 'movie_watchlist', $user_watchlist );
				}

				$watchlist = array();
				foreach ( $user_watchlist as $movie_id ) {
					$watchlist[] = array(
						'id'    => $movie_id,
						'title' => get_the_title( $movie_id ),
						'url'   => get_the_permalink( $movie_id ),
					);
				}

				return rest_ensure_response(
					array(
						'success'   => true,
						'watchlist' => $watchlist,
					)
				);
			},
			'permission_callback' => '__return_true',
			'args'                => array(
				'postId' => array(
					'required'          => true,
					'validate_callback' => function ( $param ) {
						return is_numeric( $param ) && get_post( $param );
					},
				),
			),
		)
	);

	register_rest_route(
		'movie-watchlist/v1',
		'/remove/',
		array(
			'methods'             => 'POST',
			'callback'            => function ( WP_REST_Request $request ) {
				$user_id = get_current_user_id();
				$post_id = (int) $request->get_param( 'postId' );

				$user_watchlist = get_user_meta( $user_id, 'movie_watchlist', true );
				$user_watchlist = is_array( $user_watchlist ) ? $user_watchlist : array();

				$user_watchlist = array_filter(
					$user_watchlist,
					function ( $id ) use ( $post_id ) {
						return $id !== $post_id;
					}
				);

				update_user_meta( $user_id, 'movie_watchlist', array_values( $user_watchlist ) );

				$watchlist = array();
				foreach ( $user_watchlist as $movie_id ) {
					$watchlist[] = array(
						'id'    => $movie_id,
						'title' => get_the_title( $movie_id ),
						'url'   => get_the_permalink( $movie_id ),
					);
				}

				return rest_ensure_response(
					array(
						'success'   => true,
						'watchlist' => $watchlist,
					)
				);
			},
			'permission_callback' => '__return_true',
			'args'                => array(
				'postId' => array(
					'required'          => true,
					'validate_callback' => function ( $param ) {
						return is_numeric( $param ) && get_post( $param );
					},
				),
			),
		)
	);
}

add_action( 'rest_api_init', 'register_movie_watchlist_routes' );
