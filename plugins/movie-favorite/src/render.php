<?php

$user_id = get_current_user_id();

$user_watchlist = get_user_meta( $user_id, 'movie_watchlist', true );
if ( empty( $user_watchlist ) ) {
	$user_watchlist = array();
}

$movie_id    = get_the_ID();
$is_favorite = in_array( $movie_id, $user_watchlist, true );

$watchlist = array();
foreach ( $user_watchlist as $watchlist_movie_id ) {
	$watchlist[] = array(
		'id'    => $watchlist_movie_id,
		'title' => get_the_title( $watchlist_movie_id ),
		'url'   => get_the_permalink( $watchlist_movie_id ),
	);
}

wp_interactivity_state(
	'meetup/movie-favorite',
	array(
		'watchlist' => $watchlist,
	)
);

$context = array(
	'movieId'    => $movie_id,
	'isFavorite' => $is_favorite,
	'addText'    => esc_html__( 'Add', 'movie-favorite' ),
	'removeText' => esc_html__( 'Remove', 'movie-favorite' ),
	'nonce'      => wp_create_nonce( 'wp_rest' ),
);

?>

<div
	<?php echo get_block_wrapper_attributes(); ?>
	data-wp-interactive="meetup/movie-favorite"
	<?php echo wp_interactivity_data_wp_context( $context ); ?>
	data-wp-class--is-favorite="context.isFavorite"
>
	<button
		data-wp-on--click="actions.toggleFavorite"
		data-wp-text="state.buttonText"
	></button>
</div>
