/**
 * External Dependencies
 */
const path = require( 'path' );

/**
 * WordPress Dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = {
	...defaultConfig,
	...{
		output: {
			path: path.resolve( process.cwd(), 'blocks/section-container' ),
		},
		stats: { warnings: false },

	},
};
