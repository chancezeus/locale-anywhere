let mix = require('laravel-mix')

mix.setPublicPath('dist')
    .js('resources/js/locale-anywhere.js', 'js');
