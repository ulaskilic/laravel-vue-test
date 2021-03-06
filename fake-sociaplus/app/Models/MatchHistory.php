<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchHistory extends Model
{
    use HasFactory;
    protected $table = 'match_history';

    public function league()
    {
        return $this->belongsTo('App\Models\League', 'league_id', 'id');
    }

    public function homeTeam()
    {
        return $this->belongsTo('App\Models\Team', 'home_team', 'id');
    }

    public function awayTeam()
    {
        return $this->belongsTo('App\Models\Team', 'away_team', 'id');
    }

    public function events()
    {
        return $this->hasMany('App\Models\MatchEvent', 'match_id');
    }
}
