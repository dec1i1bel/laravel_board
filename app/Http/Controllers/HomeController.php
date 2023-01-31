<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Post;
use App\Models\Photo;
use App\Models\PostCategories;
use App\Events\PostCreatedTelegram;
use App\Events\PostCreatedDiscord;
use App\Events\PostCreatedMail;
use App\Lib\Helpers\Generators\LibLog;
use Illuminate\Support\Facades\Cache;

/**
 * Управление личным кабинетом пользователя
 *
 * @property array POST_VALIDATOR
 * @property array POST_ERROR_MESSAGES
 */
class HomeController extends Controller
{
    /**
     * @var array
     */
    private const POST_VALIDATOR = [
        'title' => 'required|max:255',
        'content' => 'required',
        'price' => 'required|numeric',
        'img_preview' => 'image|max:2500|mimes:jpg,bmp,png',

        'photo_1' => 'image|max:250|mimes:jpg,bmp,png',
        'photo_2' => 'image|max:250|mimes:jpg,bmp,png',
        'photo_3' => 'image|max:250|mimes:jpg,bmp,png',
        'photo_4' => 'image|max:250|mimes:jpg,bmp,png'
    ];

    /**
     * сообщения валидатора
     *
     * @var array
     */
    private const POST_ERROR_MESSAGES = [
        'title.max' => 'Длина строки - не более :max символов',
        'required' => 'Поле обязательно для заполнения',
        'numeric' => 'Допустимы только числа',
        'mimes' => 'mime-тип изображения должен быть jpg, bmp или png',
        'file.image' => 'Загружаемый файл должен быть изображением',
        'file.max' => 'Размер файла - не более :max кб'
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        $posts = Auth::user()
                    ->post()
                    ->latest()
                    ->get();

        $categories = Category::getPostsCategories();

        // demo logging
        if (null!== $posts->first()) {
            LibLog::post($posts->first());
        }

        return view(
            'home',
            [
                'posts' => $posts,
                'categories' => $categories
            ]
        );
    }

    /**
     * @return Renderable
     */
    public function renderAddPostForm(): Renderable
    {
        return view(
            'post_add',
            [
                'categories' => Category::all()
            ]
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function storePost(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            self::POST_VALIDATOR,
            self::POST_ERROR_MESSAGES
        );

        if (isset($validated['img_preview'])) {
            $imgPreviewStore = Storage::putfile(
                'public',
                $validated['img_preview']
            );
        } else {
            $imgPreviewStore = false;
        }

        Auth::user()->post()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'price' => $validated['price'],
            'img_preview' => $imgPreviewStore
        ]);

        $post = $this->getLastPost();
        $postId = $post->id;

        Cache::add($postId, $post, 3600);

        $this->createPostCategories($postId, $request);
        $this->increasePostsNumberInCategory($request);

        $this->setAdditionalPhotos($validated, $postId);

        PostCreatedTelegram::dispatch($post);
        PostCreatedDiscord::dispatch($post);
        PostCreatedMail::dispatch($post);

        return redirect()->route('home');
    }

    /**
     * @param Post $post
     * @return Renderable
     */
    public function renderEditPostForm(Post $post): Renderable
    {
        $categories = Category::getPostsCategories();
        $cats = $categories->where(
            'post_id',
            $post->id
        )
        ->all();

        $cats_all = Category::all();

        return view('post_edit', [
            'post' => $post,
            'categories_post' => $cats,
            'categories_all' => $cats_all
        ]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return RedirectResponse
     */
    public function updatePost(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate(
            self::POST_VALIDATOR,
            self::POST_ERROR_MESSAGES
        );

        if (isset($validated['img_preview'])) {
            $imgPreviewStore = Storage::putfile(
                'public',
                $validated['img_preview']
            );
        } else {
            $imgPreviewStore = false;
        }

        $fill = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'price' => $validated['price']
        ];

        if ($imgPreviewStore) {
            $fill['img_preview'] = $imgPreviewStore;
        }

        $post->fill($fill);

        $req = $request->toArray();

        if (isset($req['category'])) {
            foreach ($req['category'] as $i) {
                $updCatsIds[] = $i;
            }
        }

        if (isset($updCatsIds)) {
            $this->setPostCategories($updCatsIds, $post->id);
        }

        $post->save();

        for ($i=1; $i <= 4; $i++) {
            if (
                isset($validated['photo_'.$i]) &&
                null !== $validated['photo_'.$i]
            ) {
                Photo::updateOrInsert(
                    [
                        'post_id' => $post->id,
                        'photo_num' => 'photo_'.$i
                    ],
                    [
                        'file' => Storage::putfile(
                                        'public',
                                        $validated['photo_'.$i]
                                    )
                    ]
                );
            }
        }

        return redirect()->route('home');
    }

    /**
     * @param Post $post
     * @return Renderable
     */
    public function renderDeletePostForm(Post $post): Renderable
    {
        $cats = Category::getPostsCategories();

        $categories = $cats->where(
            'post_id',
            $post->id
        )->all();

        return view(
            'post_delete',
            [
                'post' => $post,
                'categories' => $categories
            ]
        );
    }

    /**
     * @param Post $post
     * @return RedirectResponse
     */
    public function destroyPost(Post $post): RedirectResponse
    {

        $postCatsIds = $this->getPostCategoriesIds($post->id);
        if (!empty($postCatsIds)) {
            $this->removePostFromCategories($postCatsIds);
        }

        $this->removePostFromPostCategories($post->id);

        $post->delete();

        return redirect()->route('home');
    }

    /**
     * @param array $updCatsIds - id категорий, которые были отмечены при сохранении поста
     * @param Integer $postId - id редактируемого поста
     *
     * @var Array $catsIdsBeforeUpdate - id категорий, которые были отмечены у поста перед его редактирванием
     */
    private function setPostCategories(array $updCatsIds, int $postId) {
        $dbCats = DB::table('categories');
        $dbPostCats = DB::table('post_categories');

        /**
         * при обновлении post_categories:
         * сначала вытянуть уже существующие категории, до изменения, и в categories сделать decrement для отменённых категорий
         */

        $q = $dbPostCats->where(
            'post_id',
            $postId
        )
        ->select(
            'category_id'
        )
        ->get();

        $catsIdsBeforeUpdate = [];

        foreach ($q as $r) {
            $catsIdsBeforeUpdate[] = $r->category_id;
        }

        /**
         * изменение:
         * - список категорий товара в карточках и личном кабинете
         * - количество товара в категориях на главной
         */
        if (!empty($catsIdsBeforeUpdate)) {

            $this->addNewPostCategoriest(
                $catsIdsBeforeUpdate,
                $updCatsIds,
                $dbCats,
                $dbPostCats,
                $postId
            );

            $this->removeUncheckedPostCategories(
                $catsIdsBeforeUpdate,
                $updCatsIds,
                $postId
            );

        }
    }

    /**
     * @param array $validated
     * @param Integer $postId
     * @return void
     */
    private function setAdditionalPhotos(array $validated, int $postId) {

        $photo = new Photo;

        for ($i=1; $i <= 4; $i++) {
            if (isset($validated['photo_'.$i])) {
                $photo->create([
                    'file' => Storage::putfile(
                        'public',
                        $validated['photo_'.$i]
                    ),
                    'post_id' => $postId,
                    'photo_num' => 'photo_'.$i
                ]);
            }
        }
    }

    /**
     * если сняли категорию в данном сеансе редактирования, то:
     * - таблица categories: уменьшаем count_posts данной категории на 1
     * - таблица post_categories: добавляем строку с данной category_id и данным post_id
     */
//     private function removeUncheckedPostCategories($catsIdsBeforeUpdate,$updCatsIds, $dbCats, $dbPostCats, $postId) {
    private function removeUncheckedPostCategories($catsIdsBeforeUpdate, $updCatsIds, $postId) {

        if (!empty($catsIdsBeforeUpdate)) {

            foreach ($catsIdsBeforeUpdate as $id) {

                if (!in_array($id, $updCatsIds)) {
                    $dbCats = DB::table('categories');
                    $dbPostCats = DB::table('post_categories');

                    $dbCats->where('id', $id)
                            ->decrement('count_posts', 1);

                    $dbPostCats->where('category_id', $id)
                                ->where('post_id', $postId)
                                ->delete();
                }

            }

        }

    }

    /**
     * если категорию отметили в данном сеансе редактирования, то:
     * - таблица categories: увеличиваем count_posts данный категории на 1
     * - таблица post_categories: добавляем строку с данной category_id и данным post_id
     *
     * @param array $catsIdsBeforeUpdate
     * @param array $updCatsIds
     * @param Builder $dbCats
     * @param Builder $dbPostCats
     * @param Integer $postId
     * @return void
     */
    private function addNewPostCategoriest(array $catsIdsBeforeUpdate, array $updCatsIds, Builder $dbCats, Builder $dbPostCats, int $postId) {

        if (!empty($updCatsIds)) {

            foreach ($updCatsIds as $id) {

                if (!in_array($id, $catsIdsBeforeUpdate)) {

                    $dbCats->where('id', '=', $id)
                            ->increment('count_posts');

                    $dbPostCats->insert([
                        'post_id' => $postId,
                        'category_id' => $id
                    ]);
                }

            }

        }

    }


    /**
     * @param Integer $postId
     * @return void
     */
    private function removePostFromPostCategories(int $postId) {
        PostCategories::where(
            'post_id',
            $postId
        )
        ->delete();
    }

    /**
     * @param Integer $postId
     * @return array
     */
    private function getPostCategoriesIds(int $postId): array
    {
        $q = PostCategories::where(
                'post_id',
                $postId
            )
            ->select('category_id')
            ->get();

        $catsIds = [];

        foreach ($q as $val) {
            $catsIds[] = $val['category_id'];
        }

        return $catsIds;
    }

    /**
     * @param array $postCatsIds
     * @return void
     */
    private function removePostFromCategories(array $postCatsIds) {
        foreach ($postCatsIds as $cat_id) {
            DB::table('categories')
                ->where(
                    'id',
                    $cat_id
                )
                ->decrement(
                    'count_posts'
                );
        }
    }

    /**
     * получает последний сохранённый пост как коллекцию
     *
     * @return Post
     */
    private function getLastPost(): Post
    {
        return Post::latest()->first();
    }

    /**
     * сохраняет выбранные категории в таблице post_categories
     *
     * @param integer $postId
     * @param Request $request
     *
     * @return void
     */
    private function createPostCategories(int $postId, Request $request)
    {
        if (isset($request['category'])) {
            $pct = new PostCategories;

            foreach ($request['category'] as $catId) {
                $pct->create([
                    'post_id' => $postId,
                    'category_id' => $catId
                ]);
            }
        }
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    private function increasePostsNumberInCategory(Request $request)
    {
        if (isset($request['category'])) {
            $cat = new Category;

            foreach ($request['category'] as $catId) {
                $cat->where('id', $catId)
                    ->increment('count_posts');
            }
        }
    }
}
