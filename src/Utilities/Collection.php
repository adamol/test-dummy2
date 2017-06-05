<?php

namespace App\Utilities;

class Collection
{
    public function __construct($entities = [])
    {
        $this->entities = $entities;
    }

    public function each($callable)
    {
        array_walk($this->entities, $callable);

        return $this;
    }

    public function append($entity)
    {
        $this->entities[] = $entity;
    }
}
