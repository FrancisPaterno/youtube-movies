<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_votes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shared_movie_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('vote');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('shared_movie_id')
                ->references('id')
                ->on('shared_movies')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_votes');
    }
}
