<?php

namespace App\Http\Controllers;

use App\Classes\Fixture;
use App\Models\League;
use MathPHP\Probability\Distribution\Discrete;


class LeagueController extends Controller
{
    public function index()
    {
        $leagues = League::with('clubs')->get();
        return view('index', compact('leagues'));
    }

    public function show(League $league)
    {
        $league->load(['clubs', 'fixture']);
        if (!count($league->fixture)) {
            $fixture = new Fixture($league);
            $fixture->create();
            $league->refresh();
        }
        $weekends = [];
        foreach ($league->fixture->groupBy('week') as $key => $items) {
            $weekends[$key] = [];
            foreach ($items as $item) {
                $weekends[$key][] = [
                    'home' => [
                        'score' => $item->home_score,
                        'club' => $item->home_club,
                    ],
                    'away' => [
                        'score' => $item->away_score,
                        'club' => $item->away_club
                    ],
                ];
            }
        }
        return view('show', compact('league', 'weekends'));
    }

    public function run(League $league, $week)
    {
        $weekGroup = $league->fixture->where('week', $week);
        $monte = [];
        foreach ($weekGroup as $key => $item) {
            $homeStats = $item->home_club->getStats();
            $awayStats = $item->away_club->getStats();

            $homeAverageGoals = $homeStats['goals'] / $homeStats['matches_played'];
            $homeAverageConceded = $homeStats['goals'] / $homeStats['goals_conceded'];


            $awayAverageGoals = $awayStats['goals'] / $awayStats['matches_played'];
            $awayAverageConceded = $awayStats['goals'] / $awayStats['goals_conceded'];

            $homeTeam = ($homeAverageGoals + $awayAverageConceded) / 2;
            $awayTeam = ($awayAverageGoals + $homeAverageConceded) / 2;

            $homePoisson = [
                $this->poisson($homeTeam, 0),
                $this->poisson($homeTeam, 1),
                $this->poisson($homeTeam, 2),
                $this->poisson($homeTeam, 3),
                $this->poisson($homeTeam, 4),
                $this->poisson($homeTeam, 5),
            ];
            $awayPoisson = [
                $this->poisson($awayTeam, 0),
                $this->poisson($awayTeam, 1),
                $this->poisson($awayTeam, 2),
                $this->poisson($awayTeam, 3),
                $this->poisson($awayTeam, 4),
                $this->poisson($awayTeam, 5),
            ];

            $homeTotal = max($homePoisson) + min($homePoisson);
            $homeDif = max($homePoisson) - min($homePoisson);
            $homeSelect = $homeTotal - $homeDif;

            $awayTotal = max($awayPoisson) + min($awayPoisson);
            $awayDif = max($awayPoisson) - min($awayPoisson);
            $awaySelect = $awayTotal - $awayDif;
            $homeArray = [];
            for ($i = 0; $i < 5000; $i++) {
                $homeArray[$i] = ($this->randomFloat() * $homeSelect) + $homeDif;
                $awayArray[$i] = ($this->randomFloat() * $awaySelect) + $awayDif;
                $monte[$key]['data'][] = [
                    'home' => $homeArray[$i],
                    'away' => $awayArray[$i]
                ];
            }
            $monte[$key]['stand_deviation'] = [
                'home' => $this->stand_deviation($homeArray),
                'away' => $this->stand_deviation($awayArray),
            ];
            $monte[$key]['average'] = [
                'home' => $this->average($homeArray),
                'away' => $this->average($awayArray),
            ];
        }
        return $monte;
    }

    public function randomFloat($min = 0, $max = 1)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    public function stand_deviation($arr)
    {
        $num_of_elements = count($arr);
        $variance = 0.0;
        $average = array_sum($arr) / $num_of_elements;
        foreach ($arr as $i) {
            $variance += pow(($i - $average), 2);
        }

        return (float)sqrt($variance / $num_of_elements);
    }

    public function average($arr)
    {
        return array_sum($arr) / count($arr);
    }

    public function poisson($chance, $occurrence)
    {
        return exp(-$chance) * pow($chance, $occurrence) / $this->factorial($occurrence);
    }

    public function factorial($number)
    {
        $sum = 1;
        for ($i = 1; $i <= floor($number); $i++) {
            $sum *= $i;
        }

        return $sum;
    }
}
