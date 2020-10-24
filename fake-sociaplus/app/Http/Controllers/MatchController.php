<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Services\MatchService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function distributeFixture(League $league, MatchService $service)
    {
        return $service->prepareFixture($league);
    }

    public function simulateOneWeek(League $league, MatchService $service)
    {
        $service->simulateOneWeek($league);
        return response([], 200);
    }

    public function simulateAll(League $league, MatchService $service)
    {
        $service->simulateAll($league);
        return response([], 200);
    }

    public function debug(MatchService $service)
    {
        $league = League::first();
        $service->simulateOneWeek($league);
        return response([], 200);
    }
}
