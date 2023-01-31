<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use App\Models\Category;
use App\Models\Photo;
use App\Models\PostCategories;
use Illuminate\Pagination\Paginator;

class CategoriesController extends Controller
{
    /**
     * @param Category $category
     * @return Renderable
     */
    public function renderCategoryItemsList(Category $category): Renderable
    {
        $req = \Request::query() ?? [];

        $sort = $req['sort'] ?? 'created_at';
        $order = $req['order'] ?? 'desc';

        $category_posts = $this->getCategoryPostsOrderedPaginated($category->id, $sort, $order);

        $posts_categories = Category::getPostsCategories();

        $arIds = [];

        foreach ($category_posts as $post) {
            $arIds[] = $post->id;
        }

        $photos = Photo::whereIn('post_id', $arIds)->get();

        return view('category_items_list', [
            'posts' => $category_posts,
            'photos' => $photos,
            'category' => $category,
            'categories' => $posts_categories,
            'sort' => $sort,
            'order' => $order
        ]);
    }

    /**
     * @param integer $categoryIdd
     * @param string $sort
     * @param string $order
     * @return Paginator
     */
    private function getCategoryPostsOrderedPaginated(int $categoryIdd, string $sort, string $order): Paginator
    {
        return PostCategories::where(
                'category_id',
                $categoryIdd
            )
            ->rightJoin(
                'categories',
                    'post_categories.category_id',
                    '=',
                    'categories.id'
            )
            ->rightJoin(
                'posts',
                    'post_categories.post_id',
                    '=',
                    'posts.id'
            )
            ->select(
                'post_categories.category_id',
                'post_categories.post_id',
                'categories.name',
                'posts.id',
                'posts.title',
                'posts.content',
                'posts.price',
                'posts.img_preview'
            )
            ->orderBy(
                $sort,
                $order
            )
            ->simplePaginate(4);
    }
}
