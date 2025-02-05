<?php

wp_interactivity_state(
	'meetup/movie-watchlist',
	array()
);

?>

<div
	<?php echo get_block_wrapper_attributes(); ?>
	data-wp-interactive="meetup/movie-watchlist"
>
	<h4>Watchlist</h4>
	<ul>
		<template
			data-wp-each--movie="meetup/movie-favorite::state.watchlist"
			data-wp-each-key="meetup/movie-favorite::context.movie.id"
		>
			<li>
				<a
					data-wp-bind--href="meetup/movie-favorite::context.movie.url"
					data-wp-text="meetup/movie-favorite::context.movie.title"
				></a>
			</li>
		</template>
	</ul>
</div>
