<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCategoryIdColsFromPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'category_id_1',
                'category_id_2',
                'category_id_3',
                'category_id_4'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id_1');
            $table->unsignedBigInteger('category_id_2');
            $table->unsignedBigInteger('category_id_3');
            $table->unsignedBigInteger('category_id_4');
        });
    }
}
