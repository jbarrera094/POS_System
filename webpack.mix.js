const mix = require('laravel-mix');


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

const MomentLocalesPlugin = require('moment-locales-webpack-plugin');


mix.js('resources/src/main.js', 'public').js('resources/src/login.js', 'public')
    // .sass('resources/src/assets/styles/sass/globals/globals.scss', 'public/css')
    .vue();

    mix.webpackConfig({
        output: {
          
            filename:'js/[name].min.js',
            chunkFilename: 'js/bundle/[name].js',
          },
        plugins: [
            new MomentLocalesPlugin(),
        ],
         //     stats: {
    //         children: true
    //    }
    });
