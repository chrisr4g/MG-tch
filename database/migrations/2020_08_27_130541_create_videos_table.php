<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id');
            $table->string('title', 150)->nullable(false);
            $table->string('type', 15)->nullable(false);
            $table->string('url', 150)->nullable(true)->default(null);
            $table->string('thumbnailUrl', 150)->nullable(true);
            $table->boolean('thumbnailUrl_is_working')->nullable(false)->default(false);
            $table->boolean('resource_is_working')->nullable(false)->default(false);
            $table->string('movie_id', 50);
            $table->timestamps();

        });

        Schema::table('videos', function($table) {
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function($table) {
            $table->dropForeign(['movie_id']);
        });
        Schema::dropIfExists('videos');
    }
}
