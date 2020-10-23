<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_history', function (Blueprint $table) {
            $table->id();
            // TODO find better naming!
            $table->integer('league_id');
            $table->integer('home_team');
            $table->integer('away_team');
            $table->integer('week');
            $table->integer('home_team_score')->default(0);
            $table->integer('away_team_score')->default(0);
            $table->dateTime('due');
            $table->boolean('is_played')->default(false);
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
        Schema::dropIfExists('match_history');
    }
}
