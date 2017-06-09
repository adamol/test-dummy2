<?php

require __DIR__ . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Factories\CommentsFactory;
use App\Factories\PostsFactory;
use App\Models\Comment;
use App\Models\Post;
use App\Database\MockedPdoConnection;
use App\Utilities\Collection;


class FactoriesTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->postsFactory = new PostsFactory(new MockedPdoConnection);
        $this->commentsFactory = new CommentsFactory(new MockedPdoConnection);
    }
    /** @test */
    function it_works()
    {
        $this->assertTrue(true);
    }

    /** @test */
    function a_post_can_be_created()
    {
        $post = $this->postsFactory->create();

        $this->assertTrue($post instanceof Post);
    }

    /** @test */
    function a_factory_can_take_overrides()
    {
        $post = $this->postsFactory->create(['title' => 'changed']);

        $this->assertEquals('changed', $post->getAttribute('title'));
    }

    ///** @test */
    //function a_related_object_can_be_retrieved()
    //{
    //    $post = $this->postsFactory->create();
    //    $comment = $this->commentsFactory->create(['post_id' => $post->getAttribute('id')]);

    //    $this->assertTrue($comment->post() instanceof Post);
    //    $this->assertEquals($post->id, $comment->post()->id);
    //}

    ///** @test */
    //function a_factory_can_automatically_instantiate_parent_objects()
    //{
    //    $comment = $this->commentsFactory->create();

    //    $post = $comment->post();

    //    $this->assertTrue($post instanceof Post);
    //}

    ///** @test */
    //function it_can_override_parameters_for_a_parent_object()
    //{
    //    $comment = $commentsFactory->create([
    //        'body' => 'changed comments body',
    //        'POST' => [
    //            'body' => 'changed posts body'
    //        ]
    //    ]);

    //    $this->assertEquals('changed comments body' $comment->getAttribute('body'));
    //    $this->assertEquals('changed posts body' $comment->post()->getAttribute('body'));
    //}

    /** @test */
    function factories_can_create_multiple_objects_in_a_collection()
    {
        $collection = $this->postsFactory->create([], 3);

        $this->assertTrue($collection instanceof Collection);
    }

    /** @test */
    function collections_have_an_each_method_which_takes_a_callback()
    {
        $cf = $this->commentsFactory;
        $posts = $this->postsFactory->create([], 3)->each(function($post) use ($cf) {
            $cf->create(['post_id' => $post->getAttribute('id')]);
        });

        foreach ($posts as $post) {
            $this->assertTrue($post->comments() instanceof Collection);
        }
    }
}


//echo "\n-- CREATING MULTIPLE CREATES COLLECION AND HAS EACH FUNCTION \n";
//
//echo "\n-- STATES CAN BE ATTACHED TO FACTORIES\n";
//$postsFactory->state('published')->create();

