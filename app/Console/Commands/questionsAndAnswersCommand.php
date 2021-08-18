<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;

class questionsAndAnswersCommand extends Command
{

    protected $signature = 'qanda:interactive {user=guest@test.com}';

    protected $description = 'Execute Questions and Answers Program in an interactive mode';

    protected $run = true;

    public function handle()
    {
        $emailUser = $this->argument('user');
        $this->createUser($emailUser);
        $user = $this->getUser($emailUser);

        $this->info("*** Welcome " . $user->name . " ***");
        $this->menu($user);
    }

    protected function menu($user){
        $choice = $this->choice(
            'Choose an option between 0-5',
            [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
                'Exit',
            ],
            0
        );

        switch ($choice) {
            case "Create a question":
                $this->createQuestion($user);
                break;
            case "List all questions":
                $this->listAllQuestions($user);
                break;
            case "Practice":
                $this->practice($user);
                break;
            case "Exit":
                $this->stop($user);
                break;
            default:
                $this->menu($user);
        }
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
//        $questions = Question::where('user_id', $user->id)->get();
        $allQuestions = Question::select('id','description', 'status')->where('user_id', $user->id)->get();
        $answeredQuestions= Question::where('status','!=','NOT ANSWERED')->get();
        $correctQuestions= Question::where('status','CORRECT')->get();
        $separator = new TableSeparator();
        /*$this->table(
             ['Question', 'Answer', 'Status'],
             Question::select('description', 'answer', 'status')->where('user_id', $user->id)->get(),
         );*/
//        dd($questions);
//        $this->withProgressBar($allQuestions, function ($question) {
////            $this->performTask($question);
////            sleep(1);
//        });
//        dd($correctQuestions->toArray());
        $bar = $this->output->createProgressBar($allQuestions->count());
        $bar->start();
        foreach ($correctQuestions->toArray() as $question) {
//            $this->performTask($question);
//            sleep(1);
            $bar->advance();
        }
        $bar->finish();

        $this->newLine();
        $table = new Table($this->output);
        $table->setHeaders(['ID', 'Question', 'Status']);

        $table->setRows(
            $allQuestions->toArray()
        );
//        $table->setFooterTitle('Progress: '.count($correctQuestions)."/".count($allQuestions));
        $table->setRow( count($allQuestions->toArray()), [$separator, $separator, $separator]);
        $table->setRow( count($allQuestions->toArray())+1, [new TableCell( count($correctQuestions)."/".count($allQuestions), ['colspan'=>3])]);
        $table->render();

        $this->info("bye Practicing");


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

    protected function stop($user){
        $this->info("Bye bye ".$user->name."...");
        $this-> run = false;
        exit();
    }
}
