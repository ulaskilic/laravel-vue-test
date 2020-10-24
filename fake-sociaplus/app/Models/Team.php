<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    public function league()
    {
        return $this->belongsTo('App\Models\League', 'league_id', 'id');
    }

    public function homeHistory()
    {
        return $this->hasMany('App\Models\MatchHistory', 'home_team');
    }

    public function awayHistory()
    {
        return $this->hasMany('App\Models\MatchHistory', 'away_team');
    }

    public function allHistory()
    {
        return $this->homeHistory()->merge($this->awayHistory());
    }

    public function scoreBoard()
    {
        return $this->hasOne('App\Models\LeagueScoreboard', 'team_id');
    }


}
