const mix = require("laravel-mix");
const { exec } = require('child_process');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

 mix.autoload({
    jquery: [
        "$",
        "window.jQuery",
        "jQuery",
        "window.$",
        "jquery",
        "window.jquery",
    ],
    "popper.js/dist/umd/popper.js": ["Popper"],
});

mix.js("resources/js/app.js", "public/js")
    .vue({ version: 3 })
    .sass("resources/sass/app.scss", "public/css")
    .postCss("resources/css/app.css", "public/css")
    .version()
    .sourceMaps()
    .after(() => {
        exec('php artisan lang:js --quiet');
        exec('php artisan lang:js resources/js/vue-translations.js --no-lib --quiet');
    });

const WebpackShellPlugin = require("webpack-shell-plugin-next");

// Add shell command plugin configured to create JavaScript language file
mix.webpackConfig({
    stats: {
        children: true,
    },
    plugins: [
        new WebpackShellPlugin({
            onBuildStart: [
                "php artisan lang:js --quiet",
                "php artisan lang:js resources/js/vue-translations.js --no-lib --quiet",
            ],
            onBuildEnd: [],
        }),
    ],
});

mix.extract(); // Extract all node_modules vendor libraries into a vendor.js file.
