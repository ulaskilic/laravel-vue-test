<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchEvent extends Model
{
    use HasFactory;

    protected $fillable = ['event', 'type', 'match_id', 'team_id', 'minute'];

    public function match()
    {
        return $this->belongsTo('App\Models\MatchHistory', 'id', 'match_id');
    }
}
