<?php

namespace App\Factories;

class CommentsFactory extends Factory
{
    public function defaultValues()
    {
        return [
            'id' => 1,
            'post_id' => function() {
                $postsFactory = new PostsFactory;

                return $postsFactory->create(['id' => 2])->getAttribute('id');
            },
            'body'  => 'Lorem ipsum, dolar sit amet...'
        ];
    }
}

