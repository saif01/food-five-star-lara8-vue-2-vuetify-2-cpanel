const mix = require('laravel-mix');


// Main CSS
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();


//auth JS 
mix.js('resources/js/auth/js/app.js', 'public/js/auth/app.js').vue().version();
//auth CSS 
mix.styles([
    'resources/css/common/preloader-5star.css',
    'resources/css/common/color.css',
    'resources/css/auth/style.css',
], 'public/css/auth/app.css').version();




// *********Start food *********Start

//food admin JS 
mix.js('resources/js/food/admin/js/app.js', 'public/js/food/admin/app.js')
    .vue().version();
//food admin CSS 
mix.styles([
    'resources/css/common/preloader-5star.css',
    'resources/css/common/color.css',
], 'public/css/food/admin/app.css').version();


//food operator JS 
mix.js('resources/js/food/operator/js/app.js', 'public/js/food/operator/app.js')
    .vue().version();
//food operator CSS 
mix.styles([
    'resources/css/common/preloader-5star.css',
    'resources/css/common/color.css',
], 'public/css/food/operator/app.css').version();

// *********End food *********End


//food owner JS 
mix.js('resources/js/food/owner/js/app.js', 'public/js/food/owner/app.js')
    .vue().version();
//food owner CSS 
mix.styles([
    'resources/css/common/preloader-5star.css',
    'resources/css/common/color.css',
], 'public/css/food/owner/app.css').version();


// *********End food *********End
