<?php


namespace App\Classes;


use App\Models\League;

class Fixture
{
    protected $league;
    private $clubs;

    public function __construct(League $league)
    {
        $this->league = $league;
    }

    public function create()
    {
        $this->clubs = $this->league->clubs;
        if (!count($this->clubs)){
            throw new \Exception('Before creating Fixture, you must choose clubs');
        }
        $this->clubs = $this->clubs->shuffle();
        //dd($this->clubs);
        $this->show_fixtures();
    }

    protected function numbuers($numbers)
    {
        $ns = [];
        for ($i = 1; $i <= $numbers; $i++) {
            $ns[] = $i;
        }
        return $ns;
    }

    private function show_fixtures() {
        $clubCounts = sizeof($this->clubs);
        $ghost = false;
        if ($clubCounts % 2 === 1) {
            $clubCounts++;
            $ghost = true;
        }
        $totalRounds = $clubCounts - 1;
        $matchesPerRound = $clubCounts / 2;
        $rounds = [];
        for ($i = 0; $i < $totalRounds; $i++) {
            $rounds[$i] = [];
        }
        $week = 0;
        for ($round = 0; $round < $totalRounds; $round++) {
            for ($match = 0; $match < $matchesPerRound; $match++) {
                $home = ($round + $match) % ($clubCounts - 1);
                $away = ($clubCounts - 1 - $match + $round) % ($clubCounts - 1);
                if ($match === 0) {
                    $away = $clubCounts - 1;
                }
                $rounds[$round][$match] = [
                    'home' => $home,
                    'away' => $away
                ];
                $this->league->fixture()->create([
                    'home_id' => $this->clubs[$home]->id,
                    'away_id' => $this->clubs[$away]->id,
                    'week' => ($week + 1)
                ]);
                //$rounds[$round][$match] = $home . " v " . $away;
            }
            $week++;
        }
        foreach ($rounds as $key => $round){
            foreach ($round as $match){
                $this->league->fixture()->create([
                    'home_id' => $this->clubs[$match['away']]->id,
                    'away_id' => $this->clubs[$match['home']]->id,
                    'week' => ($week + 1)
                ]);
            }
            $week++;
        }
        return;
        $interleaved = [];
        for ($i = 0; $i < $totalRounds; $i++) {
            $interleaved[$i] = [];
        }
        $evn = 0;
        $odd = ($clubCounts / 2);
        for ($i = 0; $i < sizeof($rounds); $i++) {
            if ($i % 2 == 0) {
                $interleaved[$i] = $rounds[$evn++];
            } else {
                $interleaved[$i] = $rounds[$odd++];
            }
        }
        $rounds = $interleaved;
        for ($round = 0; $round < sizeof($rounds); $round++) {
            if ($round % 2 == 1) {
                $rounds[$round][0] = $this->flip($rounds[$round][0]);
            }
        }
        for ($i = 0; $i < sizeof($rounds); $i++) {
            print "<p>Round " . ($i + 1) . "</p>\n";
            foreach ($rounds[$i] as $r) {
                print $r . "<br />";
            }
            print "<br />";
        }
        print "<p>Second half is mirror of first half</p>";
        $round_counter = sizeof($rounds) + 1;
        for ($i = sizeof($rounds) - 1; $i >= 0; $i--) {
            print "<p>Round " . $round_counter . "</p>\n";
            $round_counter += 1;
            foreach ($rounds[$i] as $r) {
                print flip($r) . "<br />";
            }
            print "<br />";
        }
        print "<br />";

        if ($ghost) {
            print "Matches against team " . $clubCounts . " are byes.";
        }
    }
    private function flip($match) {
        $components = explode(' v ', $match);
        return $components[1] . " v " . $components[0];
    }

    private function team_name($num, $names) {
        $i = $num - 1;
        if (sizeof($names) > $i && strlen(trim($names[$i])) > 0) {
            return trim($names[$i]);
        } else {
            return $num;
        }
    }


    /*$teams = array(
        'Liverpool',
        'Chelsea',
        'Manchester United',
        'Everton',
    );


    function nums($n) {
        $ns = array();
        for ($i = 1; $i <= $n; $i++) {
            $ns[] = $i;
        }
        return $ns;
    }
    function show_fixtures($names) {
        $teams = sizeof($names);

        print "<p>Fixtures for $teams teams.</p>";
        // If odd number of teams add a "ghost".
        $ghost = false;
        if ($teams % 2 == 1) {
            $teams++;
            $ghost = true;
        }
        // Generate the fixtures using the cyclic algorithm.
        $totalRounds = $teams - 1;
        $matchesPerRound = $teams / 2;
        $rounds = array();
        for ($i = 0; $i < $totalRounds; $i++) {
            $rounds[$i] = array();
        }

        for ($round = 0; $round < $totalRounds; $round++) {
            for ($match = 0; $match < $matchesPerRound; $match++) {
                $home = ($round + $match) % ($teams - 1);
                $away = ($teams - 1 - $match + $round) % ($teams - 1);
                if ($match == 0) {
                    $away = $teams - 1;
                }
                $rounds[$round][$match] = team_name($home + 1, $names)
                    . " v " . team_name($away + 1, $names);
            }
        }
        $interleaved = array();
        for ($i = 0; $i < $totalRounds; $i++) {
            $interleaved[$i] = array();
        }
        $evn = 0;
        $odd = ($teams / 2);
        for ($i = 0; $i < sizeof($rounds); $i++) {
            if ($i % 2 == 0) {
                $interleaved[$i] = $rounds[$evn++];
            } else {
                $interleaved[$i] = $rounds[$odd++];
            }
        }
        $rounds = $interleaved;
        for ($round = 0; $round < sizeof($rounds); $round++) {
            if ($round % 2 == 1) {
                $rounds[$round][0] = flip($rounds[$round][0]);
            }
        }

        for ($i = 0; $i < sizeof($rounds); $i++) {
            print "<p>Round " . ($i + 1) . "</p>\n";
            foreach ($rounds[$i] as $r) {
                print $r . "<br />";
            }
            print "<br />";
        }
        print "<p>Second half is mirror of first half</p>";
        $round_counter = sizeof($rounds) + 1;
        for ($i = sizeof($rounds) - 1; $i >= 0; $i--) {
            print "<p>Round " . $round_counter . "</p>\n";
            $round_counter += 1;
            foreach ($rounds[$i] as $r) {
                print flip($r) . "<br />";
            }
            print "<br />";
        }
        print "<br />";

        if ($ghost) {
            print "Matches against team " . $teams . " are byes.";
        }
    }
    function flip($match) {
        $components = explode(' v ', $match);
        return $components[1] . " v " . $components[0];
    }

    function team_name($num, $names) {
        $i = $num - 1;
        if (sizeof($names) > $i && strlen(trim($names[$i])) > 0) {
            return trim($names[$i]);
        } else {
            return $num;
        }
    }
    shuffle($teams);
    dd(show_fixtures($teams));*/
}
