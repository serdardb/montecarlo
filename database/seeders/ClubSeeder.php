<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\League;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clubs = [
            Club::create([
                'name' => 'Arsenal',
                'url' => 'https://www.premierleague.com/clubs/1/Arsenal/stats'
            ]),
            Club::create([
                'name' => 'Aston Villa',
                'url' => 'https://www.premierleague.com/clubs/2/Aston-Villa/stats'
            ]),
            Club::create([
                'name' => 'Brighton and Hove Albion',
                'url' => 'https://www.premierleague.com/clubs/131/Brighton-and-Hove-Albion/stats'
            ]),
            Club::create([
                'name' => 'Burnley',
                'url' => 'https://www.premierleague.com/clubs/43/Burnley/stats'
            ]),
            Club::create([
                'name' => 'Chelsea',
                'url' => 'https://www.premierleague.com/clubs/4/Chelsea/stats'
            ]),
            Club::create([
                'name' => 'Crystal Palace',
                'url' => 'https://www.premierleague.com/clubs/6/Crystal-Palace/stats'
            ]),
            Club::create([
                'name' => 'Everton',
                'url' => 'https://www.premierleague.com/clubs/7/Everton/stats'
            ]),
            Club::create([
                'name' => 'Fulham',
                'url' => 'https://www.premierleague.com/clubs/34/Fulham/stats'
            ]),
            Club::create([
                'name' => 'Leeds United',
                'url' => 'https://www.premierleague.com/clubs/9/Leeds-United/stats'
            ]),
            Club::create([
                'name' => 'Leicester City',
                'url' => 'https://www.premierleague.com/clubs/26/Leicester-City/stats'
            ]),
            Club::create([
                'name' => 'Liverpool',
                'url' => 'https://www.premierleague.com/clubs/10/Liverpool/stats'
            ]),
            Club::create([
                'name' => 'Manchester City',
                'url' => 'https://www.premierleague.com/clubs/11/Manchester-City/stats'
            ]),
            Club::create([
                'name' => 'Manchester United',
                'url' => 'https://www.premierleague.com/clubs/12/Manchester-United/stats'
            ]),
            Club::create([
                'name' => 'Newcastle United',
                'url' => 'https://www.premierleague.com/clubs/23/Newcastle-United/stats'
            ]),
            Club::create([
                'name' => 'Sheffield United',
                'url' => 'https://www.premierleague.com/clubs/18/Sheffield-United/stats'
            ]),
            Club::create([
                'name' => 'Southampton',
                'url' => 'https://www.premierleague.com/clubs/20/Southampton/stats'
            ]),
            Club::create([
                'name' => 'Tottenham Hotspur',
                'url' => 'https://www.premierleague.com/clubs/21/Tottenham-Hotspur/stats'
            ]),
            Club::create([
                'name' => 'West Bromwich Albion',
                'url' => 'https://www.premierleague.com/clubs/36/West-Bromwich-Albion/stats'
            ]),
            Club::create([
                'name' => 'West Ham United',
                'url' => 'https://www.premierleague.com/clubs/25/West-Ham-United/stats'
            ]),
            Club::create([
                'name' => 'Wolverhampton Wanderers',
                'url' => 'https://www.premierleague.com/clubs/38/Wolverhampton-Wanderers/stats'
            ]),
        ];
        $leagues = League::all();
        foreach ($leagues as $league){
            $shuffle = Arr::shuffle($clubs);
            $randomClubs = Arr::random($shuffle, 4);
            foreach ($randomClubs as $club){
                $league->clubs()->attach($club);
            }
        }
    }
}
