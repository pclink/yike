<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id')->unsigned();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('last_edit_user_id');
            $table->boolean('is_spammed')->default(0);
            $table->boolean('is_draft')->default(0);

            $table->string('title');
            $table->string('slug')->unique();

            // 封面
            $table->unsignedInteger('image_id')->nullable();

            // 类型
            $table->enum('type', ['markdown','html'])->default('html')->index();

            // 状态缓存
            $table->unsignedInteger('comment_cache')->default(0)->index();
            $table->unsignedInteger('like_cache')->default(0)->index();
            $table->unsignedInteger('favorite_cache')->default(0)->index();
            $table->unsignedInteger('view_cache')->default(0)->index();
            $table->unsignedInteger('vote_cache')->default(0)->index();

            // 内容
            $table->text('content');
            $table->text('content_original')->nullable();

            // 是否 ban 掉
            $table->string('banned_reason')->nullable();
            $table->timestamp('banned_at')->nullable()->comment('禁止时间');

            // 发布时间
            $table->timestamp('published_at')->nullable()->comment('发布时间');
            $table->timestamp('recommended_at')->nullable()->comment('推荐时间');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
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
        Schema::table('posts', function ($table) {
            $table->dropForeign('posts_user_id_foreign');
        });
        Schema::dropIfExists('posts');
    }
}
