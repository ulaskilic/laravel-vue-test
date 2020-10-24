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
            $table->integer('won')->default(0);
            $table->integer('drawn')->default(0);
            $table->integer('lost')->default(0);
            $table->integer('for')->default(0);
            $table->integer('against')->default(0);
            $table->integer('goal_diff')->default(0);
            $table->integer('points')->default(0);
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
