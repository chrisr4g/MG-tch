<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('movies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->string('id', 50)->index();
            $table->mediumText('body')->comment('Movie description')->nullable(true);
            $table->integer('duration')->nullable(false);
            $table->string('headline', 100)->comment('Movie name')->nullable();
            $table->date('lastUpdated')->nullable(false);
            $table->text('quote')->nullable(true);
            $table->tinyInteger('rating')->nullable(true);
            $table->string('reviewAuthor', 50)->nullable(true);
            $table->string('skyGoId', 100)->nullable(true);
            $table->string('skyGoUrl', 250)->nullable(true);
            $table->string('sum', 50)->nullable(false);
            $table->text('synopsis')->nullable()->comment('Synopsis');
            $table->string('url', 100)->nullable(false);
            $table->string('class',10)->nullable(false)->comment('Movie or Series');
            $table->year('year')->nullable();
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
        Schema::dropIfExists('movies');
        Schema::enableForeignKeyConstraints();
    }
}
