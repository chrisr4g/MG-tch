<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovieCastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::disableForeignKeyConstraints();
        Schema::create('movie_cast', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->string('movie_id',50);
            $table->unsignedBigInteger('cast_id');
        });

        Schema::table('movie_cast', function($table) {
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->foreign('cast_id')->references('id')->on('cast')->onDelete('cascade');
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
        Schema::table('movie_cast', function($table) {
            $table->dropForeign(['movie_id']);
            $table->dropForeign(['cast_id']);
        });

        Schema::dropIfExists('movie_cast');
        Schema::enableForeignKeyConstraints();
    }
}
