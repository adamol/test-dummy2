<?php

require __DIR__ . '/vendor/autoload.php';

echo "-- A POST CAN BE CREATED \n";

$postsFactory = new App\Factories\PostsFactory;
$postsFactory->create();

echo "\n-- IT CAN TAKE OVERRIDES \n";

$postsFactory->create(['title' => 'changed']);


echo "\n-- COMMENTSFACTORY AUTOMATICALLY CREATES PARAENT POST \n";
$commentsFactory = new App\Factories\CommentsFactory;
$commentsFactory->create();

echo "\n-- IT CAN CREATE A COMMENT FOR A SPECIFIC POST \n";
$post = $postsFactory->create(['id' => 3]);
$commentsFactory->create(['post_id' => $post->getAttribute('id')]);

echo "\n-- COMMENT CAN OVERRIDE ATTRIBUTES OF PARENT POST \n";
$commentsFactory->create([
    'body' => 'changed comments body',
    'POST' => [
        'body' => 'changed posts body'
    ]
]);

echo "\n-- CREATING MULTIPLE CREATES COLLECION AND HAS EACH FUNCTION \n";
$postsFactory->create(['id' => 4], 3)->each(function($post) use ($commentsFactory) {
    $commentsFactory->create(['post_id' => $post->getAttribute('id')]);
});

// NOT REALLY NECESSARY SINCE IT WOULD BE SOLVED
// BY THE DATABASE IN THE REAL APPLICATION

//$post1 = $postsFactory->create();
//$post2 = $postsFactory->create();

//$this->assertTrue($post1->getAttribute('id') !== $post2->getAttribute('id'));
