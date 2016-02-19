<?php
namespace Clusterpoint;

use Illuminate\Support\Str;
use Clusterpoint\Connection;
use Clusterpoint\Instance\Service;
use Clusterpoint\Exceptions\ClusterpointException;
use Clusterpoint\Query\Scope as QueryScope;
use Clusterpoint\Query\Builder as QueryBuilder;
use Illuminate\Contracts\Routing\UrlRoutable;

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
abstract class Model implements UrlRoutable {

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = "default";
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = "_id";
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $queryBuilder;
    /**
     * Set connection access points.
     *
     * @param  \stdClass  $connection
     * @return void
     */
    public function __construct()
    {
        $this->scope = new QueryScope;
        $this->connection = new Connection($this->connection);
        $this->connection->db = $this->getDatabase();
        $this->queryBuilder = new Service($this->connection);
    }
    /**
     * Wraps all method use in try - catch.
     *
     * @param  string  $method
     * @param  array  $arguments
     * @return $this
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->queryBuilder, $method], $arguments);
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $instance = new static;
        return call_user_func_array([$instance, $method], $parameters);
    }

    /**
     * Get or create Database name.
     *
     * @return string
     */
    protected function getDatabase()
    {
        if (isset($this->db)) {
            return $this->db;
        }
        return str_replace('\\', '', Str::snake(Str::plural(class_basename($this))));
    }
    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->getAttribute($this->getRouteKeyName());
    }
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return $this->primaryKey;
    }
    /**
     * Get scope attribute.
     *
     * @return string
     */
    public function getAttribute($key) 
    {
        return $this->scope->{$key};
    }
}