<?php

namespace App\Utilities;

class Collection implements \IteratorAggregate
{
    public function __construct($entities = [])
    {
        $this->entities = $entities;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->entities);
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

    public function pluck($field)
    {
        return array_map(function($entity) use ($field) {
            return $entity->getAttribute($field);
        }, $this->entities);
    }
}
