<?php

namespace App\Models;

class Comment extends Model
{
    protected $foreign_keys = [
        'post_id'
    ];

    protected $id;

    protected $post_id;

    protected $body;
}
