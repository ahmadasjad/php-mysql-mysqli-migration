<?php

namespace Example;

class DbConnection extends \mysqli
{
    public static $_db_instance = null;

    public function __construct(
        $hostname = null,
        $username = null,
        $password = null,
        $database = null,
        $port = null,
        $socket = null
    )
    {
        if ($port === null) {
            $hostParts = explode(':', $hostname);
            $hostname = $hostParts[0];
            if (isset($hostParts[1])) {
                $port = $hostParts[1];
            }
        }
        parent::__construct($hostname, $username, $password, $database, $port, $socket);
        self::$_db_instance = $this;
    }

    public static function getInstance()
    {
        if (static::$_db_instance === null) {
            throw new \RuntimeException('Database connection not initialized');
        }

        return static::$_db_instance;
    }

    /**
     * alias for getInstance()
     */
    public static function getConnection()
    {
        return self::getInstance();
    }

    public static function closeConnection($connection = null)
    {
        if ($connection === null) {
            return mysqli_close(self::getInstance());
        }
        return mysqli_close($connection);
    }

    public static function mysqli_result($result, $row, $field = 0)
    {
        if ($result instanceof \mysqli_result) {
            mysqli_data_seek($result, $row);
            $row = mysqli_fetch_array($result);
            return isset($row[$field]) ? $row[$field] : null;
        }
        return null;
    }
}
