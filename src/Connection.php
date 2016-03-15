<?php 
namespace Clusterpoint;

use Clusterpoint\Contracts\ConnectionInterface;

/**
 *
 * Holds access points for Transport layer and Query Building.
 *
 * @category   Clusterpoint 4.0 PHP Client API
 * @package    clusterpoint/laravel-clusterpoint
 * @copyright  Copyright (c) 2016 Clusterpoint (http://www.clusterpoint.com)
 * @author     Marks Gerasimovs <marks.gerasimovs@clusterpoint.com>
 * @license    http://opensource.org/licenses/MIT    MIT
 */
class Connection implements ConnectionInterface
{
    /**
     * Holds database name.
     *
     * @var string
     */
    public $db;
    /**
     * Holds host.
     *
     * @var string
     */
    public $host;
     /**
     * Holds Account ID.
     *
     * @var string
     */
    public $accountId;
     /**
     * Holds Username.
     *
     * @var string
     */
    public $username;
     /**
     * Holds Password.
     *
     * @var string
     */
    public $password;
     /**
     * Holds query to execute.
     *
     * @var string
     */
    public $query;
     /**
     * Holds transaction ID.
     *
     * @var string
     */
    public $transactionId;
    /**
     * Creates Connection instance.
     *
     * @param  string|array  $connection
     * @return void
     */
    public function __construct($connection)
    {
        $connection = $this->checkSource($connection);
        $this->parseConfig($connection);
    }
    /**
     * Resets query data.
     *
     * @param  string|array  $connection
     * @return void
     */
    public function resetSelf()
    {
        unset($this->action);
        unset($this->method);
        $this->query = null;
    }
    /**
     * Parse access points from connection array.
     *
     * @param  array  $connection
     * @return void
     */
    private function parseConfig($connection)
    {
        $allowed_keys = array_keys(get_object_vars($this));
        foreach ($connection as $key => $value) {
            $this->setParam($this->camelCase($key), $value, $allowed_keys);
        }
        $this->debug = config('app.debug');
        $this->transactionId = null;
        $this->query = null;
    }
    /**
     * Set valid Access Point.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $allowed_keys
     * @return void
     */
    private function setParam($key, $value, $allowed_keys)
    {
        if (in_array($key, $allowed_keys)) {
            $this->{$key} = $value;
        }
    }
    /**
     * Set right source of access points.
     *
     * @param  mixed  $connection
     * @return array
     */
    private function checkSource($connection)
    {
        if (gettype($connection)=="string") {
            $connArr = include(config_path('clusterpoint.php'));
            if (isset($connArr[$connection]))
                $connection = $connArr[$connection];
            elseif (isset($connArr['account_id']))
                $connection = $connArr;
            else
                return false;
        }
        if (!isset($connection['host']) || !isset($connection['account_id']) || !isset($connection['username']) || !isset($connection['password']))
            return false;
        return $connection;
    }
    /**
     * Makes config key camel case.
     *
     * @return void
     */
    private function camelCase($val)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $val))));
    }
}
