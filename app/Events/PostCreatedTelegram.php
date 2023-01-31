<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Foundation\Events\Dispatchable;

class PostCreatedTelegram
{
    use Dispatchable;

    /**
     * The Post instance
     *
     * @var \App\Models\Post
     */
    public $post;
    
    /**
     * @param Post $post
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}
