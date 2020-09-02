<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewingWindowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('viewing_windows', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id');
            $table->string('title', 50)->nullable(true)->default(null);
            $table->date('startDate')->nullable(false);
            $table->string('wayToWatch', 25)->nullable(false);
            $table->date('endDate')->nullable(true);
            $table->string('movie_id', 50);
            $table->timestamps();
        });

        Schema::table('viewing_windows', function($table) {
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
        Schema::table('viewing_windows', function($table) {
            $table->dropForeign(['movie_id']);
        });
        Schema::dropIfExists('viewing_windows');
        Schema::enableForeignKeyConstraints();
    }
}
