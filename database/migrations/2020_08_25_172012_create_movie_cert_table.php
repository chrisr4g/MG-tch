<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovieCertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('movie_cert', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->string('movie_id',50);
            $table->unsignedBigInteger('cert_id');
        });

        Schema::table('movie_cert', function($table) {
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->foreign('cert_id')->references('id')->on('cert')->onDelete('cascade');
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
        Schema::table('movie_cert', function($table) {
            $table->dropForeign(['movie_id']);
            $table->dropForeign(['cert_id']);
        });
        Schema::dropIfExists('movie_cert');
        Schema::enableForeignKeyConstraints();
    }
}
