var elixir = require('laravel-elixir');

require('laravel-elixir-postcss');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
  // mix.sass('app.scss');
  mix.postcss('app.css', {
    plugins:[
      require('postcss-easy-import')({glob: true}),
      require('postcss-cssnext'),
    ],
  });
});
