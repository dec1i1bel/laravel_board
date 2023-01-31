<?php

namespace App\Lib\Helpers\Generators;

use \Illuminate\Support\Facades\Log;

class LibLog
{
    /**
     * @param \App\Models\Post $post
     * 
     * @return void
     */
    public static function post(\App\Models\Post $post)
    {
        Log::info('post info', [
            'post_id' => $post->id,
            'post_title' => $post->title,
            'post_price' => $post->price,
        ]);

        Log::warning('test warning');

        Log::stack([
            'single',
            'emergency',
        ])->notice('test notice level from on-demand stack');
    }
}
