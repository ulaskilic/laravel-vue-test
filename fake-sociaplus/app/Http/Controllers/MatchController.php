<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Services\MatchService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    /**
     * Distribute Fixture
     *
     * @param League       $league
     * @param MatchService $service
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function distributeFixture(League $league, MatchService $service)
    {
        return $service->prepareFixture($league);
    }

    /**
     * Simulate one week
     *
     * @param League       $league
     * @param MatchService $service
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function simulateOneWeek(League $league, MatchService $service)
    {
        $service->simulateOneWeek($league);
        return response([], 200);
    }

    /**
     * Simulate all weeks
     *
     * @param League       $league
     * @param MatchService $service
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function simulateAll(League $league, MatchService $service)
    {
        $service->simulateAll($league);
        return response([], 200);
    }

    /**
     * This function will save the world!
     *
     * @param MatchService $service
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function debug(MatchService $service)
    {
        $league = League::first();
        $service->simulateOneWeek($league);
        return response([], 200);
    }

    /**
     * Lahmacun gonna save us
     *
     * @param League       $league
     * @param MatchService $service
     */
    public function predictDebug(League $league, MatchService $service)
    {
        return $service->predictFavoriteTeam($league);
    }
}
