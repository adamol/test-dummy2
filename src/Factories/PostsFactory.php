<?php

namespace App\Factories;

class PostsFactory extends Factory
{
    public function defaultValues()
    {
        return [
            'title' => 'Some title',
            'body'  => 'Lorem ipsum, dolar sit amet...',
            'published' => 0
        ];
    }

    public function statePublished()
    {
        return [
            'published' => 1
        ];
    }
}
