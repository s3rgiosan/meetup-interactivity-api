/**
 * WordPress dependencies
 */
import { store, getContext } from '@wordpress/interactivity';

const { state, actions } = store( 'meetup/movie-finder', {
	state: {
		get currentUrl() {
			const context = getContext();
			const url = new URL( state.baseUrl );
			if ( context.selectedCategory ) {
				url.searchParams.set( 'category', context.selectedCategory );
			}
			if ( context.selectedActor ) {
				url.searchParams.set( 'actors_tax', context.selectedActor );
			}
			return url.toString();
		}
	},
	actions: {
		async getFilteredActors () {
			const context = getContext();
			const response = await fetch(`/wp-json/movie-finder/v1/actors?category=${context.selectedCategory}`);
			return await response.json();
		},
		// `*` Generator function: It signifies that the function will return an iterator and can be paused and resumed at specific points with yield.
		*updateFilters(ev) {
			const { target: { name, value } } = ev;
			const context = getContext();
			switch (name) {
				case 'category':
					context.selectedCategory = value;
					context.selectedActor = '';
					// `yield`: Pause the execution of the function until the actors are fetched.
					context.actors = yield actions.getFilteredActors();
					break;
				case 'actors_tax':
					context.selectedActor = value;
					break;
			}
		},
		*navigate(ev) {
			ev.preventDefault();

			// We import the package dynamically to reduce the initial JS bundle size.
			const { actions } = yield import(
				'@wordpress/interactivity-router'
			);

			yield actions.navigate(state.currentUrl);
		}
	},
} );
