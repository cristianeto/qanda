<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class questionsAndAnswersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:interactive {user=guest@test.com}';

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
        $emailUser = $this->argument('user');
        $this->createUser($emailUser);
        $user = $this->getUser($emailUser);

        $this->info("*** Welcome ".$user->name." ***");
        $choice = $this->choice(
            'Choose an option between [0-4], or write "exit" to stop this program',
            [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ]
        );

        switch ($choice){
            case "Create a question": $this->createQuestion($user);
            break;
            case "List all questions": $this->listAllQuestions($user);
            break;
            case "Practice": $this->practice($user);
            break;
            default: break;

        }

//        $this->call('qanda:interactive');
        return 0;
    }

    protected function createQuestion($user){
        $this->info('Creating a question...');
        $question = $this->ask('Write a question');
        $answer = $this->ask('Write a answer to the previous question');
        Question::create([
            'description'=> $question,
            'answer' => $answer,
            'status' => 'NOT ANSWERED',
            'user_id' => $user->id
        ]);

    }

    protected function listAllQuestions($user){
        $this->info( "Fetching all my questions...");
        $this->table(
            ['Question', 'Answer'],
            Question::select('description', 'answer')->where('user_id', $user->id)->get()
        );

    }

    /**
     * @param $user
     */
    protected function practice($user){
        $this->info( "Practicing...");
        $questions = Question::where('status','!=','NOT ANSWERED')->get();

        $this->table(
            ['Question', 'Answer', 'Status'],
            Question::select('description', 'answer', 'status')->where('user_id', $user->id)->get()
        );

        $bar = $this->output->createProgressBar(count($questions));

        $bar->start();

        foreach ($questions as $question) {
            $this->performTask($question);

            $bar->advance();
        }

        $bar->finish();
    }

    /**
     * @param $emailUser
     */
    protected function createUser($emailUser){
        $user = User::where('email', $emailUser)->first();
        if(!isset($user)) {
            $parts = explode("@", $emailUser);
            User::create([
                'name' => ucfirst($parts[0]),
                'email' => $emailUser,
                'password' => Hash::make('password'),
            ]);
        }
    }

    protected function getUser($emailUser){
        return User::where('email', $emailUser)->firstOrFail();
    }
}
