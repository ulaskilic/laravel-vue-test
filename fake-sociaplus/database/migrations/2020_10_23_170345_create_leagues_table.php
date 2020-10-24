<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('leagues')) {
            Schema::create('leagues', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('total_week')->default(0);
                $table->integer('current_week')->default(0);
                $table->timestamps();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('leagues')) {
            Schema::dropIfExists('leagues');
        }
    }
}
