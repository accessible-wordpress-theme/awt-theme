const wordpress = require( '@wordpress/eslint-plugin' );

module.exports = [
	...wordpress.configs.recommended,
	{
		files: [ 'assets/js/**/*.js' ],
		languageOptions: {
			globals: {
				window: 'readonly',
				document: 'readonly',
				wp: 'readonly',
			},
		},
	},
	{
		files: [ 'scripts/**/*.js' ],
		languageOptions: {
			globals: {
				require: 'readonly',
				process: 'readonly',
				console: 'readonly',
				__dirname: 'readonly',
			},
		},
		rules: {
			'no-console': 'off',
		},
	},
	{
		ignores: [ 'node_modules/**', 'vendor/**' ],
	},
];
