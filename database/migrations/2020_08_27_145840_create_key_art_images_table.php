<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeyArtImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('key_art_images', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('url_local', 250)->nullable(true)->default(null);
            $table->string('url_remote', 150)->nullable(false);
            $table->integer('width')->nullable(false);
            $table->integer('height')->nullable(false);
            $table->string('movie_id', 50);
        });

        Schema::table('key_art_images', function($table) {
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('key_art_images', function($table) {
            $table->dropForeign(['movie_id']);
        });
        Schema::dropIfExists('key_art_images');
        Schema::enableForeignKeyConstraints();
    }
}
