<?php

namespace App\Factories;

use App\Utilities\Collection;

class Factory
{
    protected $dependencies = [];

    protected $stateValues = [];

    protected $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function state($state)
    {
        $method = 'state' . ucfirst($state);

        $this->stateValues = $this->$method();

        return $this;
    }

    public function create($overrides = [], $times = 1)
    {
        $this->params = array_merge($this->defaultValues(), $overrides);

        $this->overrideStateValues();

        $this->triggerCallbacks();

        if ($times === 1) {
            return $this->createModel($this->params);
        }

        $collection = new Collection([]);

        for ($i = 0; $i < $times; $i++) {
            $collection->append($this->createModel($this->params));
        }

        return $collection;
    }

    private function triggerCallbacks()
    {
        foreach ($this->params as $key => &$param) {
            if (is_callable($param)) {
                $overrideKey = strtoupper(substr($key, 0, -3));

                if (isset($this->params[$overrideKey])) {
                    $param = $param($this->params[$overrideKey]);
                    unset($this->params[$overrideKey]);
                } else {
                    $param = $param();
                }
            }
        }
    }

    private function overrideStateValues()
    {
        if (! empty($this->stateValues)) {
            $this->params = array_merge($this->params, $this->stateValues);
        }
    }

    private function createModel($params)
    {
        $model = $this->getModelInstance($this->buildModelName());

        foreach ($params as $attribute => $value) {
            $model->setAttribute($attribute, $value);
        }

        if (! $model->save()) {
            throw new \Exception("Something went wrong");
        }

        return $model;
    }

    private function getModelInstance($model)
    {
        $model = 'App\\Models\\'.$model;
        return new $model($this->database);
    }

    private function buildModelName()
    {
        return substr($this->calledClass(), 0, -8);
    }

    private function calledClass()
    {
        return array_values(array_slice(explode('\\', get_called_class()), -1))[0];
    }
}
