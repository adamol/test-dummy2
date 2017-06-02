<?php

namespace App\Factories;

class PostsFactory extends Factory
{
    public function defaultValues()
    {
        return [
            'id'    => 1,
            'title' => 'Some title',
            'body'  => 'Lorem ipsum, dolar sit amet...'
        ];
    }
}
