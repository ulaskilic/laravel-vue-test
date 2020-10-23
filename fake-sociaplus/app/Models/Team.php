<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    public function league()
    {
        $this->belongsTo('App\Models\League', 'id', 'league_id');
    }
}
