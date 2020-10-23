<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Services\MatchService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(MatchService $service)
    {
        $league = League::first();
        return $service->prepareFixture($league);
    }
}
