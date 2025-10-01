<?php
// MyDB.php
declare(strict_types=1);

namespace App\Model;

class MyDB
{
    private $connection;

    public function __construct()
    {
        // Use __DIR__ to get the current directory of this file
        // /../../ to move two directories up; ./ is used for the current one.
        $config = require __DIR__ . '/../../../config/dbconfig.php';
        $dbConfig = $config['db'];

        $this->connection = new \mysqli(
            $dbConfig['host'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['dbname']
        );

        if ($this->connection->connect_error) {
            die('Connection failed: ' . $this->connection->connect_error);
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
