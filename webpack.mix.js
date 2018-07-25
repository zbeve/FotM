let mix = require('laravel-mix');

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

mix.js('resources/assets/js/scripts.js', 'public/js/app.min.js')
    .js('resources/assets/js/firebase-messaging-sw.js', 'public/firebase-messaging-sw.js')
    .sass('resources/assets/sass/app.scss', 'public/css/app.min.css');
