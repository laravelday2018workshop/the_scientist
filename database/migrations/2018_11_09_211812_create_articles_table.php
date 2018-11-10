<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->primary(['id']);
            $table->uuid('id');
            $table->char('title', 100);
            $table->text('body');
            $table->uuid('reviewer_id');
            $table->uuid('academic_id');
            $table->time('created_at');
            $table->time('updated_at')->nullable(true);
            $table->time('published_at')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
