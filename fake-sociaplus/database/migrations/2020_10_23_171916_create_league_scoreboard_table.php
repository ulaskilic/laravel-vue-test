<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagueScoreboardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_scoreboard', function (Blueprint $table) {
            $table->id();
            $table->integer('league_id');
            $table->integer('team_id');
            $table->integer('won');
            $table->integer('drawn');
            $table->integer('lost');
            $table->integer('for');
            $table->integer('against');
            $table->integer('goal_diff');
            $table->integer('points');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league_scoreboard');
    }
}
