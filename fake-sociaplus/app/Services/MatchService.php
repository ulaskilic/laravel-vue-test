<?php


namespace App\Services;


use App\Models\League;
use App\Models\LeagueScoreboard;
use App\Models\MatchHistory;
use App\Models\Team;
use App\Utils\Math;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class MatchService
 *
 * BIG TODO: Refactor code! its so dirty...
 *
 * @package App\Services
 */
class MatchService
{

    /**
     * Prepare fixture
     *
     * @param League $league
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
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

        /**
         * Clean all history for making sure
         */
        MatchHistory::where('league_id', $league->id)->delete();

        /**
         * Distribute games to weeks and save as match history
         */
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

        /**
         * Update league
         */
        $league->total_week = $totalWeeks;
        $league->current_week = 0;
        $league->save();

        /**
         * Rebuild Scoreboard
         */
        LeagueScoreboard::where('league_id', $league->id)->delete();
        foreach ($teams as $team) {
            $score = new LeagueScoreboard();
            $score->league_id = $league->id;
            $score->team_id = $team->id;
            $score->save();
        }

        /**
         * Provide all history
         */
        return MatchHistory::with('homeTeam')->with('awayTeam')->get();
    }

    /**
     * Simulate one week
     *
     * @param League $league
     */
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

    /**
     * Simulate single match
     *
     * @param MatchHistory $matchHistory
     */
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

    /**
     * Simulate all weeks
     *
     * @param League $league
     */
    public function simulateAll(League $league): void
    {
        $currentWeek = $league->current_week;
        $totalWeek = $league->total_week;

        foreach (range($currentWeek, $totalWeek + 1) as $week) {
            $this->simulateOneWeek($league);
        }
    }

    /**
     * Possible approachs
     * 1) Nontransitive dice
     * 2) Elo rating
     * 3) Rating percentage index RPI
     * 4) Or customized way, why not?
     *
     * @param League $league
     *
     * @return array
     */
    public function predictFavoriteTeam(League $league)
    {
        $scoreBoard = $league->scoreboard()->orderByDesc('points')->with('team')->get();
        $remainingWeeks = $league->total_week - $league->current_week;
        $playedWeeks = $league->current_week;

        $leaders = [];
        /**
         * Calculate possible 1st 2nd 3th 4th
         *
         * @var LeagueScoreboard $score
         */
        foreach ($scoreBoard as $index => $score) {
            $leaders[$index] = [
                'team' => $score->toArray(),
                'maxPossiblePoint' => $score->points + ($remainingWeeks * 3),
                'minPossiblePoint' => $score->points
            ];
        }

        $scoresCollection = collect($leaders);
        $minP = $scoresCollection->min('minPossiblePoint');
        $maxP = $scoresCollection->max('maxPossiblePoint');

        $scoresCollection = $scoresCollection->map(function ($item) use($minP, $maxP) {
            if($item['maxPossiblePoint'] < $minP) {
                $item['rate'] = 0;
            } else {
                /**
                 * Experimental things
                 */
                $goalDiffEffect = $item['team']['goal_diff'] > 0 ? $item['team']['goal_diff'] * 1.25 / $item['team']['goal_diff'] : 1;
                $goalEffect = $item['team']['for'] > 0 ? $item['team']['for'] * 1.10 / $item['team']['for'] : 1;
                $item['rate'] = ($item['maxPossiblePoint'] - $minP);
            }
            return $item;
        });

        $totalRate = $scoresCollection->sum('rate');
        $scoresCollection = $scoresCollection->map(function ($item) use($totalRate) {
            if($item['rate'] != 0) {
                $item['rate'] = number_format($item['rate'] / $totalRate * 100, 2);
            }
            return $item;
        });


        return $scoresCollection->sortByDesc('rate')->toArray();
    }

    /**
     * Generate fake event
     *
     * @param $t1
     * @param $t2
     *
     * @return array
     */
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

    /**
     * Get random event as msg type
     *
     * @param $t1
     * @param $t2
     *
     * @return array|int|string|string[]
     */
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

    /**
     * TODO there is no time to implement this
     */
    private function generateFakeMetric()
    {

    }

}
