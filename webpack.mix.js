const mix = require("laravel-mix");
require("laravel-mix-clean");

// Set global options for Mix
mix.options({
	processCssUrls: false,
});

// Compile Sass files
mix.sass("_source/scss/styles.scss", "_build/css/styles.css");

// Compile JavaScript files
mix.js("_source/js/scripts.js", "_build/js/scripts.js");

// Clean build directory
mix.clean({
	cleanOnceBeforeBuildPatterns: ["./_build/*"],
});
