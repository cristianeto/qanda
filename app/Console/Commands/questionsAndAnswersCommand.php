<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class questionsAndAnswersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:interactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute an interactive mode about Questions and Answers Program ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("*** Welcome to QANDA program***");
        $this->choice(
            'Choose an option between [0-5], or write "exit" to stop this program:',
            [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
                'Exit'
            ],
            null
        );

    }
}
