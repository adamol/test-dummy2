<?php

require __DIR__ . '/vendor/autoload.php';

use App\Utilities\Collection;
use App\Database\PdoConnection;
use PHPUnit\Framework\TestCase;
use App\Factories\CommentsFactory;
use App\Factories\PostsFactory;
use App\Models\Comment;
use App\Models\Post;


class FactoriesTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        putenv("env=test");

        $this->dbh = PdoConnection::getInstance();

        $this->dbh->exec("
            CREATE TABLE posts (
                id INTEGER AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(50),
                body TEXT NOT NULL,
                published TINYINT
            )
        ");

        $this->dbh->exec("
            CREATE TABLE comments (
                id INTEGER AUTO_INCREMENT PRIMARY KEY,
                post_id INTEGER NOT NULL,
                body TEXT NOT NULL
            )
        ");

        $this->postsFactory = new PostsFactory();
        $this->commentsFactory = new CommentsFactory();
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->dbh->exec("drop table posts");
        $this->dbh->exec("drop table comments");
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

    /** @test */
    function a_factory_can_automatically_instantiate_parent_objects()
    {
        $stmt = $this->dbh->prepare('SELECT id FROM posts');
        $stmt->execute();
        $row = $stmt->fetch();

        $this->assertFalse($row);

        $comment = $this->commentsFactory->create();

        $stmt = $this->dbh->prepare('SELECT id FROM posts');
        $stmt->execute();
        $row = $stmt->fetch();

        $this->assertNotFalse($row);
    }

    /** @test */
    function it_can_override_parameters_for_a_parent_object()
    {
        $comment = $this->commentsFactory->create([
            'body' => 'changed comments body',
            'POST' => [
                'body' => 'changed posts body'
            ]
        ]);

        $this->assertEquals('changed comments body', $comment->getAttribute('body'));

        $stmt = $this->dbh->prepare('SELECT body FROM posts');
        $stmt->execute();
        $row = $stmt->fetch();

        $this->assertEquals('changed posts body', $row['body']);
    }

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

        $ids = $posts->pluck('id');

        foreach ($this->dbh->query('SELECT post_id FROM comments') as $row) {
            $this->assertTrue(in_array($row['post_id'], $ids));

            $index = array_search($row['post_id'], $ids);
            unset($ids[$index]);
        }
        $this->assertEmpty($ids);
    }

    /** @test */
    function states_can_override_factory_default_values()
    {
        $post = $this->postsFactory->state('published')->create();

        $this->assertEquals(1, $post->getAttribute('published'));

        $stmt = $this->dbh->prepare('SELECT published FROM posts');
        $stmt->execute();
        $row = $stmt->fetch();

        $this->assertEquals(1, $row['published']);
    }
}


