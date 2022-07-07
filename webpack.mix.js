const mix = require('laravel-mix');

mix.copyDirectory('./node_modules/@fortawesome/fontawesome-free/webfonts', './theme/assets/fonts');

mix.options({
    processCssUrls: false
});

mix.js('javascript/app.js', 'theme/assets/js').sass('style/style.scss', 'theme', [], [
    require('postcss-import'),
    require('autoprefixer'),
]).sass('style/rtl.scss', 'theme', [], [
    require('postcss-import'),
    require('rtlcss'),
    require('autoprefixer'),
]).sass('style/editor-style.scss', 'theme', [], [
    require('postcss-import'),
    require('autoprefixer'),
]);
