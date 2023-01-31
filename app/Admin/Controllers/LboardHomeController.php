<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Grid;
use App\Models\Post;
use App\Models\Photo;
use App\Models\Category;

class LboardHomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('LBoard')
            ->description('Стартовая страница')
            ->row(function (Row $row) {
                $row->column(10, function (Column $column) {
                    $grid = new Grid(new Post());

                    $grid->column('title', 'Товар');
                    $grid->column('content', 'Описание');
                    $grid->column('price', 'Цена');
                    $grid->column('img_preview', 'Превью')->image();

                    $grid->model()->take(5);
                    $grid->model()->orderBy('id', 'desc');

                    $this->disableActions($grid);

                    $column->append($grid);
                });

                $row->column(2, function (Column $column) {
                    $grid = new Grid(new Category);
                    $grid->column('name', 'Категория');
                    $grid->column('count_posts', 'Количество товаров');

                    $grid->model()->take(20);
                    $grid->model()->orderBy('id', 'desc');

                    $this->disableActions($grid);

                    $column->append($grid);
                });
            })
            ->row(function(Row $row) {
                $row->column(12, function(Column $column) {
                    $imgPreviews = Post::select('img_preview')
                        ->orderBy('id', 'desc')
                        ->get(10);

                    $photos = Photo::select('file')
                        ->orderBy('id', 'desc')
                        ->get(10);

                    $merged = $imgPreviews->merge($photos);
                    $merged->all();
                });
            });
    }

    private function disableActions($grid)
    {
        $grid->disableCreateButton();
        $grid->disablePagination();
        $grid->disableFilter();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableColumnSelector();

        return $grid;
    }
}
