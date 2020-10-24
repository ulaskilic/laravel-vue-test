<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_stats', function (Blueprint $table) {
            $table->id();
            $table->integer('match_id');
            $table->integer('home_red_card');
            $table->integer('away_red_card');
            $table->integer('home_yellow_card');
            $table->integer('away_yellow_card');
            $table->integer('home_foul');
            $table->integer('away_foul');
            $table->integer('home_average_play');
            $table->integer('away_average_play');
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
        Schema::dropIfExists('match_stats');
    }
}
