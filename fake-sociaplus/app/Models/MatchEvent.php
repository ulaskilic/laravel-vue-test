<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchEvent extends Model
{
    use HasFactory;

    public function match()
    {
        return $this->belongsTo('App\Models\MatchHistory', 'id', 'match_id');
    }
}
