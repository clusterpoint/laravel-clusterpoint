<?php
namespace Clusterpoint;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

/**
 *
 * Laravel 5 Clusterpoint 4.0 PHP Client API
 *
 * @category   Clusterpoint 4.0 PHP Client API - Laravel extension
 * @package    clusterpoint/php-client-api-v4-laravel
 * @copyright  Copyright (c) 2016 Clusterpoint (http://www.clusterpoint.com)
 * @author     Marks Gerasimovs <marks.gerasimovs@clusterpoint.com>
 * @license    http://opensource.org/licenses/MIT    MIT
 */
class ClusterpointServiceProvider extends ServiceProvider {
    /**
     * Register.
     *
     * @return void
     */
    public function register()
    {

    }
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    { 
        $this->publishes([
            __DIR__ . '/config.example' => config_path('clusterpoint.php'),
        ]);
    }
}