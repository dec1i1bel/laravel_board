<?php

namespace App\Http\Controllers;

use Cassandra\Collection;
use Illuminate\Support\Collection as IlluminateCol;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use App\Models\Post;
use App\Models\Category;
use App\Models\Photo;
use App\Models\PostCategories;
use Illuminate\Support\Facades\Cache;

class PostsController extends Controller
{
    /**
     * @return Renderable
     */
    public function index(): Renderable
    {
        $posts = Cache::remember('posts100', 3600, function() {
            return Post::latest()->limit(100)->get();
        });
        
        $photos = Cache::remember('photos', 3600, function() {
            return Photo::orderBy('post_id', 'asc')->get();
        });
        
        $cat_top = Category::orderByDesc('count_posts')->get();
        $cat_posts = Category::getPostsCategories();

        $context = [
            'posts' => $posts,
            'categories_top' => $cat_top,
            'categories_posts' => $cat_posts,
            'photos' => $photos
        ];
        return view('index', $context);
    }

    /**
     * @param Post $post
     * @return Renderable
     */
    public function detail(Post $post): Renderable {
        $postsCats = Category::getPostsCategories()->toArray();

        $thisPost['post'] = $post;
        $thisPost['photos'] = $this->getThisPostPhotos($post);
        $thisPost['categories'] = $this->getThisPostCategories($post, $postsCats);

        $otherPosts['posts'] =  $this->getOtherPosts($thisPost['categories'], $thisPost['post']->id);

        $otherPostsIds = [];

        foreach ($otherPosts['posts'] as $post) {
            $otherPostsIds[] = $post->id;
        }

        $otherPosts['photos'] =  $this->getOtherPostsPhotos($otherPostsIds);

        $otherPosts['categories'] = $this->getOtherPostsCategories($otherPostsIds);

        return view('detail', [
                'thisPost' => $thisPost,
                'otherPosts' => $otherPosts
            ]
        );
    }

    /**
     * @param Post $post
     * @return IlluminateCol
     */
    private function getThisPostPhotos(Post $post): IlluminateCol {
        return Photo::where('post_id', '=', $post->id)->get();
    }

    /**
     * Другие объявления этих же категорий
     *
     * @param array $thisPostCategories
     * @param integer $thisPostId
     * @return IlluminateCol
     */
    private function getOtherPosts(array $thisPostCategories, int $thisPostId):IlluminateCol {
        return PostCategories::wherein(
                    'post_categories.category_id',
                    $thisPostCategories,
                )
                ->where(
                    'post_categories.post_id',
                    '!=',
                    $thisPostId
                )
                ->rightJoin(
                    'posts',
                    'post_categories.post_id',
                    '=',
                    'posts.id'
                )
                ->rightJoin(
                    'categories',
                    'post_categories.category_id',
                    '=',
                    'categories.id'
                )
                ->select(
                    'posts.id',
                    'posts.title',
                    'posts.content',
                    'posts.price',
                    'posts.img_preview'
                )
                ->distinct()
                ->get();
    }

    /**
     * @param array $otherPostsIds
     * @return IlluminateCol
     */
    private function getOtherPostsCategories(array $otherPostsIds): IlluminateCol {
        return PostCategories::whereIn(
            'post_categories.post_id',
            $otherPostsIds
        )
        ->join(
            'categories',
            'post_categories.category_id',
            '=',
            'categories.id'
        )
        ->select(
            'post_categories.post_id',
            'post_categories.category_id',
            'categories.name'
        )
        ->get();
    }

    /**
     * @param array $posts_ids
     * @return IlluminateCol
     */
    private function getOtherPostsPhotos(array $posts_ids): IlluminateCol {
        return Photo::whereIn(
            'post_id', $posts_ids
        )->get();
    }

    /**
     * @param Post $post
     * @param array $postsCats
     * @return array
     */
    private function getThisPostCategories(Post $post, array $postsCats): array
    {
        $result = [];

        foreach ($postsCats as $cat) {
            if ($cat->post_id == $post->id) {
                $result[$cat->name] = $cat->id;
            }
        }

        return $result;
    }
}
