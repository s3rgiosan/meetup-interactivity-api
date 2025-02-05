<?php

$movies_archive_url = get_post_type_archive_link( 'movies' );

wp_interactivity_state(
	'meetup/movie-finder',
	array(
		'baseUrl'    => esc_url( $movies_archive_url ),
		'currentUrl' => esc_url( $movies_archive_url ),
	)
);


$context = array(
	'selectedCategory' => '',
	'selectedActor'    => '',
	'categories'       => array(),
	'actors'           => array(),
);

$categories = get_terms(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => true,
	)
);

if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
	$context['categories'] = array_merge(
		$context['categories'],
		array_map(
			function ( $category ) {
				return array(
					'id'    => $category->term_id,
					'value' => $category->slug,
					'text'  => $category->name,
				);
			},
			$categories
		)
	);
}

?>

<div
	<?php echo get_block_wrapper_attributes(); ?>
	data-wp-interactive="meetup/movie-finder"
	<?php echo wp_interactivity_data_wp_context( $context ); ?>
	data-wp-on--change="actions.updateFilters"
>

	<select
		name="category"
		data-wp-bind--value="context.selectedCategory"
	>
		<option value="">Select Category</option>
		<template
			data-wp-each--cat="context.categories"
			data-wp-each-key="context.cat.id"
		>
			<option
				data-wp-bind--value="context.cat.value"
				data-wp-text="context.cat.text"
			></option>
		</template>
	</select>

	<select
		name="actors_tax"
		data-wp-bind--value="context.selectedActor"
		data-wp-bind--disabled="!context.selectedCategory"
	>
		<option value="">Select Actor</option>
		<template
			data-wp-each--actor="context.actors"
			data-wp-each-key="context.actor.id"
		>
			<option
				data-wp-bind--value="context.actor.value"
				data-wp-text="context.actor.text"
			></option>
		</template>
	</select>

	<a
		data-wp-bind--href="state.currentUrl"
		data-wp-bind--hidden="!context.selectedActor"
		data-wp-on--click="actions.navigate"
	>
		View Movies
	</a>
	<div data-wp-router-region="query-5"></div>
</div>
