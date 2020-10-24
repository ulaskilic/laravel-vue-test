<?php


namespace App\Services;


use App\Models\League;
use App\Models\LeagueScoreboard;
use App\Models\MatchHistory;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class MatchService
{

    public function prepareFixture(League $league)
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
        $processedGames = [];
        foreach (range(1, $totalWeeks) as $currWeek) {
            $blackList = [];
            foreach ($games as $index => $game) {
                if(in_array($index, $processedGames)) continue;

                if(isset($fixture[$currWeek]) && count($fixture[$currWeek]) >= $maxGameInOneWeek) continue;

                if(in_array($game[0], $blackList) || in_array($game[1], $blackList)) continue;
                $fixture[$currWeek][] = $game;
                $blackList = array_merge($blackList, $game);

                $processedGames[] = $index;
                // Save match
                $match = new MatchHistory();
                $match->league_id = $league->id;
                $match->home_team = $game[0];
                $match->away_team = $game[1];
                $match->week = $currWeek;
                $match->due = Carbon::now();
                $match->save();
            }
        }

        $league->total_week = $totalWeeks;
        $league->current_week = 0;
        $league->save();

        LeagueScoreboard::where('league_id', $league->id)->delete();
        foreach ($teams as $team) {
            $score = new LeagueScoreboard();
            $score->league_id = $league->id;
            $score->team_id = $team->id;
            $score->save();
        }

        return MatchHistory::with('homeTeam')->with('awayTeam')->get();
    }

    public function simulateOneWeek(League $league)
    {
        $currentWeek = $league->current_week;
        $totalWeek = $league->total_week;

        if($currentWeek == $totalWeek) {
            return;
        }

        $currentWeek++;

        $matches = $league->fixture()->where('is_played', 0)->where('week', $currentWeek)->get();
        foreach ($matches as $match) {
            $this->playMatch($match);
        }

        $league->current_week = $league->current_week + 1;
        $league->save();
    }

    private function playMatch(MatchHistory $matchHistory)
    {
//        $matchHistory->events()->create(['type' => 'msg', 'event' => 'Maç başlıyor', 'minute' => 0]);

        /**
         * @var Team $homeTeam
         */
        $homeTeam = $matchHistory->homeTeam()->first();
        /**
         * @var Team $awayTeam
         */
        $awayTeam = $matchHistory->awayTeam()->first();

        $stats = [
            'home' => [
                'red' => 0,
                'yellow' => 0,
                'foul' => 0,
                'goal' => 0
            ],
            'away' => [
                'red' => 0,
                'yellow' => 0,
                'foul' => 0,
                'goal' => 0
            ]
        ];

        foreach (range(1, 90) as $minute) {
            $getEvent = rand(0, 1);

            if($getEvent == 1) {
                $event = $this->generateFakeEvent($homeTeam->name, $awayTeam->name);
                switch ($event['type']) {
                    case 'msg':
//                        $matchHistory->events()->create([
//                            'type' => $event['type'],
//                            'event' => $event['type'],
//                            'minute' => $minute,
//                        ]);
                        break;
                    case 'goal':
//                        $matchHistory->events()->create([
//                            'type' => $event['type'],
//                            'event' => 'GOOOL!!!',
//                            'team_id' => $event['team'] == 1 ? $homeTeam->id : $awayTeam->id,
//                            'minute' => $minute,
//                        ]);
                        $stats[$event['team'] == 1 ? 'home' : 'away']['goal']++;
                        break;
                    case 'red':
//                        $matchHistory->events()->create([
//                            'type' => $event['type'],
//                            'event' => 'Maçta bir kırmızı kart çıkıyor...',
//                            'team_id' => $event['team'] == 1 ? $homeTeam->id : $awayTeam->id,
//                            'minute' => $minute,
//                        ]);
                        $stats[$event['team'] == 1 ? 'home' : 'away']['red']++;
                        break;
                    case 'yellow':
//                        $matchHistory->events()->create([
//                            'type' => $event['type'],
//                            'event' => 'Maçta bir sarı kart çıkıyor...',
//                            'team_id' => $event['team'] == 1 ? $homeTeam->id : $awayTeam->id,
//                            'minute' => $minute,
//                        ]);
                        $stats[$event['team'] == 1 ? 'home' : 'away']['yellow']++;
                        break;
                    case 'foul':
//                        $matchHistory->events()->create([
//                            'type' => $event['type'],
//                            'event' => 'Rakip takım bir faul yapıyor...',
//                            'team_id' => $event['team'] == 1 ? $homeTeam->id : $awayTeam->id,
//                            'minute' => $minute,
//                        ]);
                        $stats[$event['team'] == 1 ? 'home' : 'away']['foul']++;
                        break;
                    default:
                        break;
                }
            }
        }

        $matchHistory->is_played = true;
        $matchHistory->home_team_score = $stats['home']['goal'];
        $matchHistory->away_team_score = $stats['away']['goal'];
        $matchHistory->save();

        $homeScore = $homeTeam->scoreBoard()->first();
        $homeScore->won = $homeScore->won + ($matchHistory->home_team_score > $matchHistory->away_team_score ? 1 : 0);
        $homeScore->lost = $homeScore->lost + ($matchHistory->home_team_score < $matchHistory->away_team_score ? 1 : 0);
        $homeScore->drawn = $homeScore->drawn + ($matchHistory->home_team_score == $matchHistory->away_team_score ? 1 : 0);
        $homeScore->for = $homeScore->for + $matchHistory->home_team_score;
        $homeScore->against = $homeScore->against + $matchHistory->away_team_score;
        $homeScore->goal_diff = $homeScore->for - $homeScore->against;
        $homeScore->points = ($homeScore->won * 3) + $homeScore->drawn;
        $homeScore->save();

        $awayScore = $awayTeam->scoreBoard()->first();
        $awayScore->won = $awayScore->won + ($matchHistory->home_team_score < $matchHistory->away_team_score ? 1 : 0);
        $awayScore->lost = $awayScore->lost + ($matchHistory->home_team_score > $matchHistory->away_team_score ? 1 : 0);
        $awayScore->drawn = $awayScore->drawn + ($matchHistory->home_team_score == $matchHistory->away_team_score ? 1 : 0);
        $awayScore->for = $awayScore->for + $matchHistory->away_team_score;
        $awayScore->against = $awayScore->against + $matchHistory->home_team_score;
        $awayScore->goal_diff = $awayScore->for - $awayScore->against;
        $awayScore->points = ($awayScore->won * 3) + $awayScore->drawn;
        $awayScore->save();

    }

    public function simulateAll(League $league): void
    {
        $currentWeek = $league->current_week;
        $totalWeek = $league->total_week;

        foreach (range($currentWeek, $totalWeek + 1) as $week) {
            $this->simulateOneWeek($league);
        }
    }

    public function predictFavoriteTeam(League $league)
    {

    }

    private function generateFakeEvent($t1, $t2): array
    {
        $possibilities = [
            'goal' => [0, 10],
            'red' => [25,37],
            'yellow' => [45, 50],
            'foul' => [90, 95],
        ];

        $rand = rand(0, 100);
        foreach ($possibilities as $event => $values) {
            if($rand >= $values[0] and $rand <= $values[1]) {
                return ['type' => $event, 'team' => rand(1, 2)];
            }
        }
        return ['type' => 'msg', 'msg' => $this->getRandomEventMsg($t1, $t2)];
    }

    private function getRandomEventMsg($t1, $t2)
    {
        $msg = [
            '@t1 takımı topu taça gönderiyor',
            '@t2 takımı köşe vuruşunu kullanıyor',
            '@t1 @t2 takımına faul yapıyor ama hakemden devam kararı çıkıyor',
            '@t1 takımı uzaklardan bir şut denedi ama sonuç alamadı!',
            '@t2 takımı riskli geliyor!',
            '@t1 kale vuruşuyla başlıyor',
            '@t2 kalecisi topu atmosfere gönderiyor, büyük ihtimalle o top dünya dışı varlıklarla iletişime geçecek',
            'Maçın hakemi cüneyt çakır serbest vuruşu işaret ediyor',
            'Rakip takımın taraftarları sahaya çakmak fırlatıyor ve bir oyuncunun başına isabet ediyor'
        ];

        return str_replace(['@t1', '@t2'], [$t1, $t2], array_rand($msg, 1));
    }

    private function generateFakeMetric()
    {

    }

}
