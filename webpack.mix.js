const mix = require('laravel-mix');

mix.react('resources/js/app.js', 'public/js') // Compiles React files from resources/js
   .sass('resources/sass/app.scss', 'public/css'); // Optional: Compiles SASS if you're using it
