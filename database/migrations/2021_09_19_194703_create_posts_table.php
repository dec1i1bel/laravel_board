<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->unsignedBigInteger('category_id_1')->nullable();
            $table->unsignedBigInteger('category_id_2')->nullable();
            $table->unsignedBigInteger('category_id_3')->nullable();
            $table->unsignedBigInteger('category_id_4')->nullable();
            $table->text('content');
            $table->float('price');
            $table->string('img_preview')->nullable();
            $table->timestamps();
            $table->index('created_at');
            $table->foreignId('user_id')->constrained()
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
