<?php

namespace App\Database;

use PDO;

class PdoConnection
{
    protected $dbh;

    protected static $instance;

    private function __construct()
    {
        $this->dbh = new PDO('sqlite::memory:');
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function exec($sql)
    {
        return $this->dbh->exec($sql);
    }

    public function query($sql)
    {
        return $this->dbh->query($sql);
    }

    public function execute($array)
    {
        return $this->dbh->execute($array);
    }

    public function prepare($sql)
    {
        return $this->dbh->prepare($sql);
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }
}
