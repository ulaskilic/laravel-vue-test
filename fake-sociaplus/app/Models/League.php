<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;
    protected $table = 'leagues';

    public function teams()
    {
        return $this->hasMany('App\Models\Team', 'league_id');
    }

    public function scoreboard()
    {
        return $this->hasMany('App\Models\LeagueScoreboard', 'league_id');
    }

    public function fixture()
    {
        return $this->hasMany('App\Models\MatchHistory', 'league_id');
    }
}
