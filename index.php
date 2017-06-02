<?php

require __DIR__ . '/vendor/autoload.php';

$postsFactory = new App\Factories\PostsFactory;
$postsFactory->create();

$postsFactory->create(['title' => 'changed']);

$commentsFactory = new App\Factories\CommentsFactory;
$commentsFactory->create();

$post = $postsFactory->create(['id' => 3]);
$commentsFactory->create(['post_id' => $post->getAttribute('id')]);

//$commentsFactory->create([
//    'body' => 'changed comments body',
//    'POST' => [
//        'body' => 'changed comments body'
//    ]
//]);
//
$postsFactory->create(['id' => 4], 3)->each(function($post) use ($commentsFactory) {
    $commentsFactory->create(['post_id' => $post->getAttribute('id')]);
});

//$post1 = $postsFactory->create();
//$post2 = $postsFactory->create();
//
//$this->assertTrue($post1->getAttribute('id') !== $post2->getAttribute('id'));
