const mix = require('laravel-mix');


mix.js('javascript/app.js', 'theme/assets/js').sass('style/style.scss', 'theme', [], [
    require('postcss-import'),
    require('autoprefixer'),
]).sass('style/rtl.scss', 'theme', [], [
    require('postcss-import'),
    require('rtlcss'),
    require('autoprefixer'),
]);
