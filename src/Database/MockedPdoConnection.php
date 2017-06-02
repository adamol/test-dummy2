<?php

namespace App\Database;

class MockedPdoConnection
{
    public function prepare($sql)
    {
        $this->sql = $sql;
        return $this;
    }

    public function exec($values)
    {
        $startOfValues = strrpos($this->sql, "(");
        $endOfValues   = strrpos($this->sql, ")");
        $sql = substr($this->sql, 0, $startOfValues + 1)
            . implode(', ', $values)
            .  substr($this->sql, $endOfValues);
        var_dump($sql);

        return true;
    }
}
