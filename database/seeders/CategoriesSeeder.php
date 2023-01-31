<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'мебель',
                'count_posts' => 0
            ],
            [
                'name' => 'транспорт',
                'count_posts' => 0
            ],
            [
                'name' => 'детские товары',
                'count_posts' => 0
            ],
            [
                'name' => 'одежда',
                'count_posts' => 0
            ]
        ]);
    }
}
