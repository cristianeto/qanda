<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\User;
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
    protected $description = 'Execute Questions and Answers Program in an interactive mode';

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
        $choice = $this->choice(
            'Choose an option between [0-4], or write "exit" to stop this program:',
            [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ]
        );

        switch ($choice){
            case "Create a question": $this->createQuestion();
            break;
            case "List all questions": $this->listAllQuestions();
            break;
            default: break;

        }

//        $this->call('qanda:interactive');
        return 0;
    }

    protected function createQuestion(){
        $this->info('Creating a question...');
        $question = $this->ask('Write a question');
        $answer = $this->ask('Write a answer to the previous question');
        Question::create([
            'description'=> $question,
            'answer' => $answer,
            'status' => 'NOT ANSWERED',
            'user_id' => 1
        ]);

    }

    protected function listAllQuestions(){
        $this->info( "Fetching all my questions...");
//        dd(Question::all(['id','description', 'answer'])->toArray());
        $this->table(
            ['Question', 'Answer'],
            Question::all(['description', 'answer'])
        );

    }
}
