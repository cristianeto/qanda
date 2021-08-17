<?php

namespace Tests\Feature\Commands;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListQuestionsTest extends TestCase
{
    use  RefreshDatabase;

    /** @test */
    public function guests_can_fetch_all_his_questions_with_correct_answer()
    {
        //Arrange
        $user = User::factory()->create(['name'=>'Guest', 'email'=>'guest@test.com', 'password'=>'password']);
        $questions = Question::factory()->count(3)->create(['user_id'=>$user->id]);

        //Act
        $this->artisan('qanda:interactive')

            //Assert
            ->expectsOutput('*** Welcome Guest ***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program', 'List all questions' , [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ])
            ->expectsOutput('Fetching all my questions...')
            ->expectsTable([
                'Question',
                'Answer',
            ], [
                [$questions[0]->description, $questions[0]->answer],
                [$questions[1]->description, $questions[1]->answer],
                [$questions[2]->description, $questions[2]->answer],
            ])->assertExitCode(0);
    }

    /** @test */
    public function a_specific_user_can_fetch_all_his_questions_with_correct_answer()
    {
        //Arrange
        $user = User::factory()->create();
        $questions = Question::factory()->count(3)->create(['user_id'=>$user->id]);

        //Act
        $this->artisan('qanda:interactive '.$user->email)

            //Assert
            ->expectsOutput('*** Welcome '.$user->name.' ***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program', 'List all questions' , [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ])
            ->expectsOutput('Fetching all my questions...')
            ->expectsTable([
                'Question',
                'Answer',
            ], [
                [$questions[0]->description, $questions[0]->answer],
                [$questions[1]->description, $questions[1]->answer],
                [$questions[2]->description, $questions[2]->answer],
            ])->assertExitCode(0);
    }

    /** @test */
    public function guests_can_list_his_questions_in_order_for_practicing()
    {
        //Arrange
        $user = User::factory()->create(['name'=>'Guest', 'email'=>'guest@test.com', 'password'=>'password']);
        $NotAnsweredQuestions = Question::factory()->count(3)->create(['user_id'=>$user->id]);
        $correctQuestions = Question::factory()->count(2)->create(['user_id'=>$user->id, 'status'=>'CORRECT']);
        $incorrectQuestion = Question::factory()->create(['user_id'=>$user->id, 'status'=>'INCORRECT']);

        //Act
        $this->artisan('qanda:interactive')

            //Assert
            ->expectsOutput('*** Welcome Guest ***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program', 'Practice' , [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ])
            ->expectsOutput('Practicing...')
            ->expectsTable([
                'Question',
                'Answer',
                'Status',
            ], [
                [$NotAnsweredQuestions[0]->description, $NotAnsweredQuestions[0]->answer, $NotAnsweredQuestions[0]->status],
                [$NotAnsweredQuestions[1]->description, $NotAnsweredQuestions[1]->answer, $NotAnsweredQuestions[1]->status],
                [$NotAnsweredQuestions[2]->description, $NotAnsweredQuestions[2]->answer, $NotAnsweredQuestions[2]->status],
                [$correctQuestions[0]->description, $correctQuestions[0]->answer, $correctQuestions[0]->status],
                [$correctQuestions[1]->description, $correctQuestions[1]->answer, $correctQuestions[1]->status],
                [$incorrectQuestion->description, $incorrectQuestion->answer, $incorrectQuestion->status],
            ])->assertExitCode(0);
    }

    /** @test */
    public function a_specific_user_can_list_his_questions_for_practicing()
    {
        //Arrange
        $user = User::factory()->create();
        $questions = Question::factory()->count(3)->create(['user_id'=>$user->id]);

        //Act
        $this->artisan('qanda:interactive '.$user->email)

            //Assert
            ->expectsOutput('*** Welcome '.$user->name.' ***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program', 'Practice' , [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ])
            ->expectsOutput('Practicing...')
            ->expectsTable([
                'Question',
                'Answer',
                'Status',
            ], [
                [$questions[0]->description, $questions[0]->answer, $questions[0]->status],
                [$questions[1]->description, $questions[1]->answer, $questions[1]->status],
                [$questions[2]->description, $questions[2]->answer, $questions[2]->status],
            ])->assertExitCode(0);
    }
}
