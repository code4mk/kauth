<?php

namespace Kauth;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Kauth\Auth\Auth;

/**
 * @author    @code4mk <hiremostafa@gmail.com>
 * @author    @0devco <with@0dev.co>
 * @since     2019
 * @copyright 0dev.co (https://0dev.co)
 */

class KauthServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
   public function boot()
   {
     // load database
      $this->loadMigrationsFrom(
        __DIR__.'/../migrations/'
      );
      // load config
      $this->mergeConfigFrom(
        __DIR__.'/../config/kauth.php','kauth'
      );
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
