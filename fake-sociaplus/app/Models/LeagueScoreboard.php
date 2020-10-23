<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueScoreboard extends Model
{
    use HasFactory;
    protected $table = 'league_scoreboard';

    public function team()
    {
        $this->hasOne('App\Models\Team', 'team_id', 'id');
    }

    public function league()
    {
        $this->belongsTo('App\Models\League', 'id', 'league_id');
    }
}
