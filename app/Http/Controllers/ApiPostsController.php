<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\PostCategories;

class ApiPostsController extends Controller
{
    /**
     * @return string
     */
    public function getAll(): string
    {
        return Post::all()->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Post $post
     * @return string
     */
    public function getSingle(Post $post): string
    {
        return $post->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Category $category
     * @return string
     */
    public function getCategoryPosts(Category $category): string
    {
        $GLOBALS['cat'] = $category;

        $res = PostCategories::join(
                    'posts',
                    'post_categories.post_id',
                    '=',
                    'posts.id'
                )->join(
                    'categories',
                    function ($join) {
                        $join->on(
                            'post_categories.category_id',
                            '=',
                            'categories.id'
                        )->where(
                            'categories.id',
                            '=',
                            $GLOBALS['cat']->id
                        );
                    }
                )->select(
                    'posts.id as post_id',
                    'categories.id as category_id',
                    'posts.title as post_title',
                    'posts.content as post_content',
                    'categories.name as category_name'
                )
                ->get();

        return $res->toJson(
            JSON_UNESCAPED_UNICODE
        );
    }
}
