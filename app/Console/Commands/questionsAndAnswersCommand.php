<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;

class questionsAndAnswersCommand extends Command
{

    protected $signature = 'qanda:interactive {user=guest@test.com}';

    protected $description = 'Execute Questions and Answers Program in an interactive mode';

    protected $run = true;

    public function handle(): int
    {
        $user = User::createByEmail($this->argument('user'));

        $this->info("*** Welcome " . $user->name . " ***");

//        while ($this->run) {
            $this->menu($user);
//        }

        $this->info('Bye bye '.$user->name);

        return 0;
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
            ]
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
            case "Stats":
                $this->stats($user);
                break;
            case "Reset":
                $this->reset($user);
                break;
            case "Exit":
                $this->stop();
                break;

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
            ['ID', 'Question', 'Answer'],
            Question::select('id', 'description', 'answer')->where('user_id', $user->id)->get()
        );

    }

    protected function practice($user){

        $this->info( "Practicing...");

        $allQuestions = Question::getAllByUser($user);
        $correctQuestions= Question::getCorrectByUser($user);

        $this->newLine();

        $this->printTable($allQuestions, $correctQuestions);

        $this->answerQuestion($user);

        $this->info("bye Practicing");

    }

    private function stats($user)
    {
        $allQuestions= Question::getAllByUser($user);
        $answeredQuestions= Question::getAnsweredByUser($user);
        $correctQuestions= Question::getCorrectByUser($user);

        $this->info($user->name. ', these are your stats');

        $this->line('Total amount of questions: '.$allQuestions->count());
        $this->line('% of questions that have an answer: '. $this->percentageCompletion($allQuestions, $answeredQuestions).'%');
        $this->line('% of questions that have a correct answer: '. $this->percentageCompletion($allQuestions, $correctQuestions).'%');

    }

    private function reset($user)
    {
        $this->info("Erasing all practice progress...");

        $answeredQuestions = Question::getAnsweredByUser($user);

        foreach ($answeredQuestions as $question){
            $question->update(['status'=> 'NOT ANSWERED']);
        }
    }

    /** Auxiliary methods*/

    protected function printTable($allQuestions, $correctQuestions)
    {
        $table = new Table($this->output);
        $separator = new TableSeparator();
        $percentageCompletion = $this->percentageCompletion($allQuestions, $correctQuestions);

        $table->setHeaders(['ID', 'Question', 'Status']);

        $table->setRows($allQuestions->toArray());
        $table->setRow( count($allQuestions->toArray()), [$separator, $separator, $separator]);
        $table->setRow( count($allQuestions->toArray())+1,
                        [new TableCell($percentageCompletion.'%', ['colspan'=>3])]
        );

        $table->render();
    }

    protected function answerQuestion($user)
    {
        $idQuestion = $this->ask('Choose a question ID or write stop');

        if($idQuestion=== 'stop') return $this->menu($user);

        $validator = Validator::make([
            'id'=>$idQuestion,
            'user_id'=> $user->id
        ],
            [
                'id'=>['required','exists:questions'],
                'user_id' => ['required']
            ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return $this->answerQuestion($user);
        }

        $question = Question::where('id', $idQuestion)->where('user_id', $user->id)->first();

        if (!$question) {
            $this->error('Question with ID '. $idQuestion.' does not belong to you!');
            return $this->answerQuestion($user);
        }

        if ($question->status === 'CORRECT') {
            $this->error('Choose other question ID!');
             return $this->answerQuestion($user);
        };

        $answer = $this->ask($question->description);


        if(strtolower($answer) === strtolower($question->answer)){
            $this->line('Correct!');
            $question->update(['status' => 'CORRECT']);
        }else{
            $this->line('Incorrect!');
            $question->update(['status' => 'INCORRECT']);
        }
        return 0;
    }

    protected function percentageCompletion($allQuestions, $someQuestions)
    {
        if(count($allQuestions)===0) return 0.0;
        return round(($someQuestions->count()/$allQuestions->count())*100,1);
    }

    protected function stop(){
        $this-> run = false;
    }
}
