<?php

namespace App\Models;

use App\Database\MockedPdoConnection;

class Model
{
    protected $attributes = [];

    protected $foreign_keys = [];

    public function getAttribute($attribute)
    {
        if (property_exists(get_called_class(), $attribute)) {
            return $this->attributes[$attribute];
        }
    }

    public function setAttribute($attribute, $value)
    {
        if (property_exists(get_called_class(), $attribute)) {
            $this->attributes[$attribute] = $value;
        }
    }

    public function save()
    {
        $sql = $this->buildInsertQuery();

        return $this->getPdoInstance()
             ->prepare($sql)
             ->exec($this->values());
    }

    private function buildInsertQuery()
    {
        $table   = $this->table();
        $columns = implode(', ', $this->columns());
        $values  = implode(', ', $this->preparedValues());

        return "INSERT INTO $table ($columns) VALUES ($values)";
    }

    private function table()
    {
        return $this->camelToSnakeCase($this->calledClass());
    }

    private function columns()
    {
        return array_keys($this->attributes);
    }

    private function values()
    {
        return array_values($this->attributes);
    }

    private function preparedValues()
    {
        return array_map(function($value) {
            return '?';
        }, $this->values());
    }

    private function camelToSnakeCase($camelCased)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $camelCased));
    }

    private function calledClass()
    {
        return array_values(array_slice(explode('\\', get_called_class()), -1))[0];
    }

    private function getPdoInstance()
    {
        return new MockedPdoConnection;
    }
}
