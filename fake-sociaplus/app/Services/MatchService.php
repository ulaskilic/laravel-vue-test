<?php


namespace App\Services;


use App\Models\League;
use App\Models\MatchHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class MatchService
{

    public function prepareFixture(League $league): Collection
    {
        $teams = $league->teams()->get();

        $fixture = [];

        $totalWeeks = (count($teams) - 1) * 2;
        $maxGameInOneWeek = count($teams) / 2;

        $games = [];

        // generate possible game pairs
        foreach ($teams as $home) {
            foreach ($teams as $away) {
                if(isset($fixture[$home->id][$away->id])) continue;
                if($home->id === $away->id) continue;
                $games[] = [$home->id, $away->id];
            }
        }

        MatchHistory::where('league_id', $league->id)->delete();

        // distribute games to weeks and save
        foreach (range(1, $totalWeeks) as $currWeek) {
            $blackList = [];
            foreach ($games as $index => $game) {
                if(isset($fixture[$currWeek]) && count($fixture[$currWeek]) >= $maxGameInOneWeek) break;
                if(in_array($game[0], $blackList) || in_array($game[1], $blackList)) continue;
                $fixture[$currWeek][] = $game;
                $blackList = array_merge($blackList, $game);
                unset($games[$index]);
                // Save match
                $match = new MatchHistory();
                $match->league_id = $league->id;
                $match->home_team = $game[0];
                $match->away_team = $game[1];
                $match->week = $currWeek;
                $match->due = Carbon::now();
                $match->save();
            }
            $blackList = [];
        }

        return MatchHistory::with('homeTeam')->with('awayTeam')->get();
    }

    public function simulateOneWeek(League $league): void
    {

    }

    public function simulateAll(League $league): void
    {

    }

    private function generateFakeEvent(): array
    {

    }

    private function generateFakeStats(): array
    {

    }

}
