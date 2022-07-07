const mix = require('laravel-mix');


mix.js('./javascript/app.js', './theme/assets/js').sass('./style/style.scss', 'theme', [], []);
