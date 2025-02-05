/**
 * WordPress dependencies
 */
import { store, getContext } from '@wordpress/interactivity';

const { state, actions } = store( 'meetup/movie-favorite', {
	state: {
		get buttonText() {
			const context = getContext();
			return context.isFavorite ? context.removeText : context.addText;
		},
	},
	actions: {
		async addFavorite () {
			const context = getContext();
			const response = await fetch('/wp-json/movie-watchlist/v1/add', {
				method: 'POST',
				body: JSON.stringify({ postId: context.movieId }),
				headers: {
					'X-WP-Nonce': context.nonce,
					'Content-Type': 'application/json',
				},
				credentials: 'same-origin',
			});
			return await response.json();
		},
		async removeFavorite () {
			const context = getContext();
			const response = await fetch('/wp-json/movie-watchlist/v1/remove', {
				method: 'POST',
				body: JSON.stringify({ postId: context.movieId }),
				headers: {
					'X-WP-Nonce': context.nonce,
					'Content-Type': 'application/json',
				},
				credentials: 'same-origin',
			});
			return await response.json();
		},
		*toggleFavorite() {
			const context = getContext();
			let response = null;
			if (context.isFavorite) {
				context.isFavorite = false;
				response = yield actions.removeFavorite();
			} else {
				context.isFavorite = true;
				response = yield actions.addFavorite();
			}
			state.watchlist = response?.watchlist;
		},
	},
} );
