<?php

namespace App\Console\Commands;

use App\Models\Question;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupInvalidQuestions extends Command
{
    protected $signature = 'questions:cleanup-invalid';
    protected $description = 'Delete questions with null option_A';

    public function handle()
    {
        $count = Question::where('option_A', null)->count();
        Question::where('option_A', null)->delete();

        $this->info('Deleted ' . $count . ' invalid questions.');
        Log::info('Deleted ' . $count . ' invalid questions.');
    }

        //  $schedule->call(function () {

        //     $remove = Question::where('option_A', null)->delete();
        // })->hourly();
}
