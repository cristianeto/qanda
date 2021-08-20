<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationData;
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

        $this->menu($user);

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
            case "Exit":
                $this->stop($user);
                break;
//            default:
//                $this->menu($user);
//                break;
        }
//        return $this->menu($user);
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

        $allQuestions = Question::getAllByUser($user);
        $correctQuestions= Question::getCorrectByUser($user);

        $this->newLine();

        $this->printTable($allQuestions, $correctQuestions);

        $this->answerQuestion($user);

        $this->info("bye Practicing");


    }

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

    protected function percentageCompletion($allQuestions, $correctQuestions)
    {
        if(count($allQuestions)===0) return 0.0;
        return round(($correctQuestions->count()/$allQuestions->count())*100,1);
    }

    protected function stop($user){
        $this->info("Bye bye ".$user->name."...");
        $this-> run = false;
        exit();
    }

    private function answerQuestion($user)
    {
        $idQuestion = $this->ask('Choose a question ID or write stop');

        if($idQuestion=== 'stop') return $this->menu($user);

        $validator = Validator::make(['id'=>$idQuestion], ['id'=>['required','exists:questions']]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return $this->answerQuestion($user);
        }
//        $validator->errors()->all();

        $question = Question::findOrFail($idQuestion);
        if ($question->status === 'CORRECT') {
            $this->error('Choose other question ID!');
             return $this->answerQuestion($user);
        };

        $answer = $this->ask($question->description);


        if($answer === $question->answer){
            $this->line('Correct!');
            $question->update(['status' => 'CORRECT']);
        }else{
            $this->line('Incorrect!');
            $question->update(['status' => 'INCORRECT']);
        }
    }

    private function stats($user)
    {
        $allQuestions= Question::getAnsweredByUser($user);
        $answeredQuestions= Question::getAnsweredByUser($user);
        $correctQuestions= Question::getCorrectByUser($user);

        $this->line('Total amount of questions: '.$allQuestions->count());
        $this->line('% of questions that have an answer: '. $this->percentageCompletion($allQuestions, $answeredQuestions));
        $this->line('% of questions that have a correct answer: '. $this->percentageCompletion($allQuestions, $correctQuestions));


    }
}
