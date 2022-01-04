<?php

namespace Database\Seeders;

use App\Models\Games;
use Illuminate\Database\Seeder;

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = [
            ['id' => '1', 'name' => 'Trivia', 'type' => 'INTEL', 'number_of_winners' => '3', 'status'=>'1', 'time_allowed' => '2', 'number_of_questions' => '10']
        ];

        foreach($games as $game)
        {
            Games::updateOrCreate($game);
        }
    }
}
