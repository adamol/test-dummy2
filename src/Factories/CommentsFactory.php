<?php

namespace App\Factories;

class CommentsFactory extends Factory
{
    protected $dependencies = [
        'POST' => 'PostsController'
    ];

    public function defaultValues()
    {
        return [
            'post_id' => function($overrides = []) {
                $postsFactory = new PostsFactory();

                return $postsFactory->create($overrides)->getAttribute('id');
            },
            'body'  => 'Lorem ipsum, dolar sit amet...'
        ];
    }
}

