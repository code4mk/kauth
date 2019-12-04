<?php

namespace Kauth;

/**
* @author    @code4mk <hiremostafa@gmail.com>
* @author    @0devco <with@0dev.co>
* @since     2019
* @copyright 0dev.co (https://0dev.co)
*/

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Kauth\Auth\Auth;

class KauthServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
   public function boot()
   {
     // publish database
      $this->publishes([
        __DIR__ . '/../migrations/' => base_path('/database/migrations'),
       ], 'migrations');
      // publish config
      $this->publishes([
        __DIR__ . '/../config/kauth.php' => config_path('kauth.php'),
      ], 'config');
      //load alias
      AliasLoader::getInstance()->alias('Kauth', 'Kauth\Facades\Auth');
   }

  /**
   * Register any application services.
   *
   * @return void
   */
   public function register()
   {
     $this->app->bind('kauth', function () {
      return new Auth;
     });
   }
}
