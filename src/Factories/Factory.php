<?php

namespace App\Factories;

// use App\Utilities\Collection;

class Factory
{
    public function create($overrides = [], $times = 1)
    {
        $params    = array_merge($this->defaultValues(), $overrides);

        foreach ($params as &$param) {
            if (is_callable($param)) {
                var_dump($param());
                $param = $param();
            }
        }

        if ($times === 1) {
            return $this->createModel($params);
        }

        $collection = new Collection;

        for ($i = 0; $i < $times; $i++) {
            $collection->append($this->createModel($params));
        }

        return $collection;
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
        return new $model;
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
