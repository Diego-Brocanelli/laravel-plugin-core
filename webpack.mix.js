const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js/app.js')
    .js('resources/js/theme.js', 'public/js/theme.js')
    .sass('resources/sass/app.scss', 'public/css/app.css')
    .sass('resources/sass/theme.scss', 'public/css/theme.css')
    .version();
