<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param League $league
     *
     * @return \Illuminate\Http\Response
     */
    public function index(League $league)
    {
        return response($league->teams()->with('homeHistory')->with('awayHistory')->with('league')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param League                   $league
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request, League $league)
    {

        $team = new Team();
        $team->league_id = $league->id;
        $team->name = $request->get('name');
        $team->save();
        return response([], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return response($team, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param League                   $league
     * @param \App\Models\Team         $team
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, League $league, Team $team)
    {
        $team->name = $request->get('name');
        $team->save();
        return response($team, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Team $team
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(League $league, Team $team)
    {
        $team->delete();
        return response($team, 204);
    }
}
